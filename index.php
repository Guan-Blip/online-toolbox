<?php
/**
 * 在线工具箱 · 主入口
 * v1.0 · 纯本地运行
 */

// 辅助函数：keepOnly 保留白名单键
if (!function_exists('keepOnly')) {
    function keepOnly($data, $keys) {
        $out = [];
        foreach ($keys as $k) {
            if (isset($data[$k])) $out[$k] = $data[$k];
        }
        return $out;
    }
}

// 功能开关表
$config = [
    'enable_toolbox'        => 1,
    'enable_session'        => 1,
    'enable_csrf'           => 1,
    'enable_rate_limit'     => 0,
    'enable_error_log'      => 0,
    'max_concurrent_users'  => 0,
    'concurrent_timeout'    => 300,
    'enable_theme_switch'   => 1,
    'enable_lang_switch'    => 1,
    'enable_disclaimer'     => 1,
    'enable_footer'         => 1,
    'enable_tool_json'      => 1,
    'enable_tool_timestamp' => 1,
    'enable_tool_url'       => 1,
    'enable_tool_base64'    => 1,
    'enable_tool_hash'      => 1,
    'enable_tool_beautify'  => 1,
    'enable_tool_regex'     => 1,
    'enable_tool_color'     => 1,
    'enable_tool_wordcount' => 1,
    'enable_tool_diff'      => 1,
    'enable_tool_convert'   => 1,
    'enable_cache'          => 1,
    'enable_debug_mode'     => 0,
];

// CSP nonce
$csp_nonce = base64_encode(random_bytes(16));

// 安全响应头
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header(
    "Content-Security-Policy: " .
    "default-src 'self'; " .
    "style-src 'self' 'nonce-{$csp_nonce}'; " .
    "script-src 'self' 'nonce-{$csp_nonce}'; " .
    "img-src 'self' data:; " .
    "connect-src 'self'; " .
    "object-src 'none'; " .
    "base-uri 'self'; " .
    "form-action 'self'; " .
    "frame-ancestors 'none'"
);

error_reporting(0);
$GLOBALS['__cfg'] = $config;

if ($config['enable_debug_mode'] != 1) {
    set_error_handler(function($no, $str, $file, $line) {
        $logOn = (($GLOBALS['__cfg']['enable_error_log'] ?? 0) == 1);
        $important = in_array($no, [E_WARNING, E_NOTICE, E_USER_WARNING, E_USER_NOTICE], true);
        if ($logOn || $important) {
            error_log(sprintf('[PHP] level=%d %s in %s:%d', $no, $str, $file, $line));
        }
        return true;
    });
    set_exception_handler(function($e) {
        if (!headers_sent()) http_response_code(500);
        echo '<div style="padding:20px;color:#f87171;text-align:center;font-family:sans-serif;">操作失败，请重试。</div>';
        exit;
    });
    register_shutdown_function(function() {
        $err = error_get_last();
        if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            if (!headers_sent()) http_response_code(500);
            echo '<div style="padding:20px;color:#f87171;text-align:center;font-family:sans-serif;">操作失败，请重试。</div>';
        }
    });
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
        $config['enable_debug_mode'] = 0;
        $GLOBALS['__cfg']['enable_debug_mode'] = 0;
    }
}

if ($config['enable_toolbox'] != 1) {
    die('🛠️ 工具箱维护中，请稍后访问。');
}

define('DATA_DIR', __DIR__ . '/data/');
if (!is_dir(DATA_DIR)) @mkdir(DATA_DIR, 0700, true);
@chmod(DATA_DIR, 0700);

$ht = DATA_DIR . '.htaccess';
if (!file_exists($ht)) {
    @file_put_contents($ht, "Require all denied\nDeny from all\n", LOCK_EX);
    @chmod($ht, 0600);
}

$idx = DATA_DIR . 'index.html';
if (!file_exists($idx)) {
    @file_put_contents($idx, '', LOCK_EX);
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lang.php';
require_once __DIR__ . '/functions.php';

// 并发限制
if ($config['max_concurrent_users'] > 0) {
    $cdir = DATA_DIR . 'concurrent/';
    if (!is_dir($cdir)) @mkdir($cdir, 0700, true);
    @chmod($cdir, 0700);

    if (is_writable($cdir)) {
        if (session_status() === PHP_SESSION_NONE && $config['enable_session'] == 1) session_start();
        $sid = session_id();
        if (empty($sid)) {
            if (!isset($_COOKIE['TOOLBOX_FP'])) {
                $fp = bin2hex(random_bytes(16));
                setcookie('TOOLBOX_FP', $fp, time() + 86400, '/');
                $_COOKIE['TOOLBOX_FP'] = $fp;
            }
            $sid = $_COOKIE['TOOLBOX_FP'];
        }
        $sfile = $cdir . 'user_' . hash('sha256', $sid) . '.tmp';
        $now = time();
        $timeout = (int)($config['concurrent_timeout'] ?? 300);

        $files = glob($cdir . 'user_*.tmp');
        if (is_array($files)) {
            foreach ($files as $f) {
                if (is_file($f) && ($now - filemtime($f)) > $timeout) @unlink($f);
            }
        }

        $count = count(glob($cdir . 'user_*.tmp') ?: []);
        if ($count >= $config['max_concurrent_users']) {
            if (!file_exists($sfile)) {
                http_response_code(503);
                die('🚫 当前访问人数已达上限，请稍后再试。');
            }
        }
        @file_put_contents($sfile, (string)$now, LOCK_EX);
        @chmod($sfile, 0600);
    }
}

// 频率限制
if ($config['enable_rate_limit'] == 1) {
    $rdir = DATA_DIR . 'rate_limit/';
    if (!is_dir($rdir)) @mkdir($rdir, 0700, true);
    @chmod($rdir, 0700);

    if (is_writable($rdir)) {
        $ip_hash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $rfile = $rdir . 'rate_' . $ip_hash . '.tmp';
        $now = time();

        $data = null;
        if (is_file($rfile)) {
            $raw = @file_get_contents($rfile);
            if ($raw !== false) $data = @json_decode($raw, true);
        }

        if (is_array($data) && isset($data['time'], $data['count'])) {
            if ($now - (int)$data['time'] < 30) {
                $data['count'] = (int)$data['count'] + 1;
                if ($data['count'] > 10) {
                    http_response_code(429);
                    die('⏳ 访问过于频繁，请 30 秒后重试。');
                }
            } else {
                $data = ['time' => $now, 'count' => 1];
            }
        } else {
            $data = ['time' => $now, 'count' => 1];
        }
        @file_put_contents($rfile, json_encode($data), LOCK_EX);
        @chmod($rfile, 0600);
    }
}

// Session
if ($config['enable_session'] == 1) {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.gc_maxlifetime', '3600');
        session_name('TOOLKIT_SESSION');
        session_start();
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
    }
}

// 输入白名单
$_GET  = keepOnly($_GET,  ['tool', 'set_lang']);
$_POST = keepOnly($_POST, [
    'csrf_token', 'json_input', 'timestamp', 'date', 'url_input', 'url_action',
    'base64_input', 'base64_action', 'hash_input', 'code_input', 'code_mode',
    'code_lang', 'regex_pattern', 'regex_subject', 'regex_flags',
    'color_input', 'text_input', 'text_a', 'text_b',
    'convert_value', 'convert_from', 'convert_to', 'set_theme'
]);

// 语言切换与检测（修复逻辑：本次请求立即生效，并永久写 Cookie）
$current_lang = null;
if ($config['enable_lang_switch'] == 1 && isset($_GET['set_lang'])) {
    $newLang = validateInput($_GET['set_lang'], 'string');
    if (isset($langs[$newLang])) {
        $_SESSION['lang'] = $newLang;
        // 写入 1 年有效期的 Cookie
        setcookie('COOKIE_LANG', $newLang, time() + 31536000, '/');
        $current_lang = $newLang; // 当前页面立刻生效
    }
}

if ($current_lang === null) {
    $current_lang = $_COOKIE['COOKIE_LANG'] ?? $_SESSION['lang'] ?? detectBrowserLanguage();
}
if (!isset($langs[$current_lang])) {
    $current_lang = 'zh';
}

// 主题切换
$current_theme = $_COOKIE['COOKIE_THEME'] ?? $_SESSION['theme'] ?? 'default';
if (!in_array($current_theme, $themes, true)) {
    $current_theme = 'default';
}

if ($config['enable_theme_switch'] == 1 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_theme'])) {
    if ($config['enable_csrf'] == 0 || validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $newTheme = validateInput($_POST['set_theme'], 'string');
        if (in_array($newTheme, $themes, true)) {
            $_SESSION['theme'] = $newTheme;
            setcookie('COOKIE_THEME', $newTheme, [
                'expires'  => time() + 31536000,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            if ($config['enable_csrf'] == 1) regenerateCsrfToken();
            header('Location: ?tool=' . rawurlencode((string)($_GET['tool'] ?? 'json')) . '&_t=' . time());
            exit;
        }
    }
}

// 路由
$tool = isset($_GET['tool']) ? validateInput($_GET['tool'], 'string') : 'json';

$enabledTools = [];
foreach ($allowedTools as $t) {
    if (isset($config['enable_tool_' . $t]) && $config['enable_tool_' . $t] == 1) {
        $enabledTools[] = $t;
    }
}

if (!in_array($tool, $enabledTools, true)) {
    $tool = !empty($enabledTools) ? $enabledTools[0] : 'json';
}

$result = null;
$error = null;
$csrf_token = ($config['enable_csrf'] == 1) ? generateCsrfToken() : '';
$beautify_safe = function_exists('beautifyCode') && function_exists('minifyCode');

// POST 请求处理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_valid = ($config['enable_csrf'] == 0) || validateCsrfToken($_POST['csrf_token'] ?? '');
    if (!$csrf_valid && !isset($_POST['set_theme'])) {
        $error = t('error_csrf');
    }

    if ($csrf_valid) {
        // 1. JSON
        if ($config['enable_tool_json'] == 1 && $tool === 'json' && isset($_POST['json_input'])) {
            $input = trim($_POST['json_input']);
            if ($input !== '') {
                if (strlen($input) > 1024 * 1024) $error = sprintf(t('err_input_too_large'), '1MB');
                else {
                    $data = json_decode($input, true);
                    if ($data === null && json_last_error() !== JSON_ERROR_NONE) $error = t('err_json_parse');
                    else $result = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
        }

        // 2. 时间戳
        if ($config['enable_tool_timestamp'] == 1 && $tool === 'timestamp') {
            $tsRaw = trim((string)($_POST['timestamp'] ?? ''));
            $date = trim((string)($_POST['date'] ?? ''));
            if ($tsRaw !== '') {
                $ts = validateStrictInt($tsRaw);
                if ($ts === null) {
                    $error = t('err_timestamp_invalid');
                } else {
                    $result = date('Y-m-d H:i:s', $ts);
                }
            } elseif ($date !== '') {
                $ts = strtotime($date);
                if ($ts === false) $error = t('err_date_invalid');
                else $result = $ts;
            } else {
                $result = time();
            }
        }

        // 3. URL
        if ($config['enable_tool_url'] == 1 && $tool === 'url' && isset($_POST['url_input'])) {
            $input = trim($_POST['url_input']);
            $action = $_POST['url_action'] ?? 'encode';
            if ($input !== '') $result = ($action === 'encode') ? urlencode($input) : urldecode($input);
        }

        // 4. Base64
        if ($config['enable_tool_base64'] == 1 && $tool === 'base64' && isset($_POST['base64_input'])) {
            $input = trim($_POST['base64_input']);
            $action = $_POST['base64_action'] ?? 'encode';
            if ($input !== '') {
                if ($action === 'encode') $result = base64_encode($input);
                else {
                    $decoded = base64_decode($input, true);
                    if ($decoded === false) $error = t('err_base64_decode');
                    else $result = $decoded;
                }
            }
        }

        // 5. Hash
        if ($config['enable_tool_hash'] == 1 && $tool === 'hash' && isset($_POST['hash_input'])) {
            $input = trim($_POST['hash_input']);
            if ($input !== '') {
                $result = [
                    'MD5' => md5($input), 'SHA-1' => sha1($input),
                    'SHA-256' => hash('sha256', $input), 'SHA-512' => hash('sha512', $input),
                ];
            }
        }

        // 6. Beautify
        if ($config['enable_tool_beautify'] == 1 && $tool === 'beautify' && isset($_POST['code_input'])) {
            if (!$beautify_safe) $error = t('err_beautify_unavailable');
            else {
                $input = trim($_POST['code_input']);
                $mode = $_POST['code_mode'] ?? 'beautify';
                $lang = $_POST['code_lang'] ?? 'auto';
                if ($input !== '') {
                    if (strlen($input) > 512 * 1024) $error = sprintf(t('err_input_too_large'), '512KB');
                    else {
                        if ($lang === 'auto') {
                            if (strpos($input, '<?php') !== false) $lang = 'php';
                            elseif (preg_match('/<[a-z][\s\S]*>/i', $input)) $lang = 'html';
                            else $lang = 'javascript';
                        }
                        $old_limit = ini_get('max_execution_time');
                        set_time_limit(5);
                        try {
                            if ($mode === 'minify') {
                                $minified = minifyCode($input, $lang);
                                if ($minified === null) $error = t('err_beautify_fail');
                                else {
                                    $os = strlen($input); $ms = strlen($minified);
                                    $result = ['code' => $minified, 'original_size' => $os, 'minified_size' => $ms, 'saved' => $os - $ms, 'percent' => $os > 0 ? round((($os - $ms) / $os) * 100, 2) : 0];
                                }
                            } else {
                                $beautified = beautifyCode($input, $lang);
                                if ($beautified === null) $error = t('err_beautify_fail');
                                else $result = $beautified;
                            }
                        } catch (Throwable $e) {
                            $error = t('err_beautify_timeout');
                        } finally {
                            if ($old_limit !== false && (int)$old_limit > 0) set_time_limit((int)$old_limit);
                            else set_time_limit(30);
                        }
                    }
                }
            }
        }

        // 7. Regex
        if ($config['enable_tool_regex'] == 1 && $tool === 'regex' && isset($_POST['regex_pattern'])) {
            $pattern = trim($_POST['regex_pattern']);
            $subject = trim($_POST['regex_subject'] ?? '');
            $flags   = (string)($_POST['regex_flags'] ?? '');
            if ($pattern !== '' && $subject !== '') {
                if (mb_strlen($pattern) > 1000) $error = t('err_regex_too_long');
                elseif (strlen($subject) > 512 * 1024) $error = sprintf(t('err_input_too_large'), '512KB');
                else {
                    ini_set('pcre.backtrack_limit', '1000000');
                    ini_set('pcre.recursion_limit', '100000');
                    $allowed_flags = ['i', 'm', 's', 'x', 'u', 'U', 'D'];
                    $valid = implode('', array_intersect(str_split($flags), $allowed_flags));
                    $quoted = str_replace('/', '\\/', $pattern);
                    $full = '/' . $quoted . '/' . $valid;
                    $count = @preg_match_all($full, $subject, $matches);
                    if ($count === false) {
                        $pe = preg_last_error();
                        $error = ($pe === PREG_BAD_UTF8_ERROR) ? t('err_regex_bad_utf8') : t('err_regex_error');
                    } else {
                        $pe = preg_last_error();
                        if ($pe === PREG_BACKTRACK_LIMIT_ERROR || $pe === PREG_RECURSION_LIMIT_ERROR) $error = t('err_regex_complex');
                        else $result = ['count' => $count, 'matches' => $matches];
                    }
                }
            }
        }

        // 8. Color
        if ($config['enable_tool_color'] == 1 && $tool === 'color' && isset($_POST['color_input'])) {
            $input = trim($_POST['color_input']);
            if ($input !== '') {
                $c = convertColor($input);
                if ($c) $result = $c;
                else $error = t('err_color_invalid');
            }
        }

        // 9. Wordcount
        if ($config['enable_tool_wordcount'] == 1 && $tool === 'wordcount' && isset($_POST['text_input'])) {
            $inputText = (string)$_POST['text_input']; // 修复变量覆盖：将原 $text 改为 $inputText
            if ($inputText !== '') {
                $result = [
                    t('wordcount_chars')  => mb_strlen($inputText),
                    t('wordcount_bytes')  => strlen($inputText),
                    t('wordcount_words')  => countWords($inputText),
                    t('wordcount_lines')  => substr_count($inputText, "\n") + 1,
                    t('wordcount_spaces') => substr_count($inputText, ' '),
                ];
            }
        }

        // 10. Diff
        if ($config['enable_tool_diff'] == 1 && $tool === 'diff' && isset($_POST['text_a'], $_POST['text_b'])) {
            $a = (string)$_POST['text_a']; $b = (string)$_POST['text_b'];
            if ($a !== '' || $b !== '') {
                if (strlen($a) > 512 * 1024 || strlen($b) > 512 * 1024) $error = sprintf(t('err_input_too_large'), '512KB');
                else {
                    $la = explode("\n", $a); $lb = explode("\n", $b);
                    $max = max(count($la), count($lb));
                    $diff = [];
                    for ($i = 0; $i < $max; $i++) {
                        $x = $la[$i] ?? ''; $y = $lb[$i] ?? '';
                        if ($x !== $y) $diff[] = t('diff_line') . ' ' . ($i + 1) . ":\n  A: $x\n  B: $y";
                    }
                    $result = empty($diff) ? t('diff_identical') : implode("\n\n", $diff);
                }
            }
        }

        // 11. Convert
        if ($config['enable_tool_convert'] == 1 && $tool === 'convert' && isset($_POST['convert_value'])) {
            $v = (float)$_POST['convert_value'];
            $from = $_POST['convert_from'] ?? 'cm'; $to = $_POST['convert_to'] ?? 'm';
            if (isset($units[$from], $units[$to])) {
                $result = number_format($v * $units[$from] / $units[$to], 6, '.', '') . ' ' . $to;
            } else {
                $error = t('err_unit_unsupported');
            }
        }

        if ($config['enable_csrf'] == 1) {
            regenerateCsrfToken();
            $csrf_token = generateCsrfToken();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo h($current_lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h(t('tool_name')); ?></title>
    <link rel="stylesheet" href="style.css">
    <?php if ($config['enable_tool_beautify'] == 1 && $tool === 'beautify' && $beautify_safe): ?>
    <link rel="stylesheet" href="assets/highlight/atom-one-dark.min.css">
    <script nonce="<?php echo h($csp_nonce); ?>" src="assets/highlight/highlight.min.js"></script>
    <script nonce="<?php echo h($csp_nonce); ?>" src="assets/highlight/languages/php.min.js"></script>
    <?php endif; ?>
    <?php if ($config['enable_theme_switch'] == 1): ?>
    <style nonce="<?php echo h($csp_nonce); ?>">
    <?php foreach ($themes as $tk): ?>
    .swatch-<?php echo h($tk); ?>{background:<?php echo h($color_map[$tk] ?? '#a78bfa'); ?>}
    <?php endforeach; ?>
    </style>
    <?php endif; ?>
</head>
<body class="theme-<?php echo h($current_theme); ?>">

<div class="container">
    <div class="header">
        <span class="icon">🧰</span>
        <h1><?php echo h(t('tool_name')); ?></h1>
        <div class="sub"><?php echo h(t('subtitle')); ?></div>
        <div class="badge"><?php echo h(t('badge')); ?></div>
        <div class="header-actions">
            <?php if ($config['enable_theme_switch'] == 1): ?>
            <button type="button" id="openSettingsBtn" class="theme-settings-btn">
                <?php echo h(t('theme_settings')); ?>
            </button>
            <?php endif; ?>

           <?php if ($config['enable_lang_switch'] == 1): ?>
            <!-- 去掉了 onchange，改由底部 JS 监听，以符合 CSP 规范 -->
            <div class="lang-group">
                <span class="lang-label">Language:</span>
                <select name="lang_select" id="langSelect" class="lang-select">
                <?php foreach ($langs as $code => $name): ?>
                <option value="<?php echo h($code); ?>" <?php echo $current_lang === $code ? 'selected' : ''; ?>>
                    <?php echo h($name); ?>
                </option>
                <?php endforeach; ?>
            </select>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($config['enable_theme_switch'] == 1): ?>
    <div id="settingsModal" class="settings-modal-hidden">
        <div class="settings-modal-body">
            <div class="settings-modal-header">
                <h3>🎨 <?php echo h(t('theme_settings')); ?></h3>
                <button type="button" id="closeSettingsBtn" class="settings-close">✕</button>
            </div>
            <p class="settings-hint"><?php echo h(t('theme_switch_hint')); ?></p>
            <div class="theme-grid">
                <?php foreach ($themes as $key): ?>
                <form method="post" class="theme-form">
                    <?php if ($config['enable_csrf'] == 1): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="set_theme" value="<?php echo h($key); ?>">
                    <button type="submit" class="theme-item <?php echo $current_theme === $key ? 'theme-item-active' : ''; ?>">
                        <span class="theme-swatch swatch-<?php echo h($key); ?>"></span>
                        <span class="theme-name"><?php echo h(themeName($key)); ?></span>
                        <?php if ($current_theme === $key): ?>
                        <span class="theme-check">✓</span>
                        <?php endif; ?>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
            <div class="settings-modal-footer">
                <?php echo h(t('current_theme')); ?>：<strong><?php echo h(themeName($current_theme)); ?></strong>
                <span class="settings-modal-count"><?php echo count($themes); ?> <?php echo h(t('themes_total')); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="tabs">
        <?php foreach ($toolKeys as $key):
            if (isset($config['enable_tool_' . $key]) && $config['enable_tool_' . $key] == 1):
        ?>
        <a href="?tool=<?php echo h($key); ?>" class="<?php echo $tool === $key ? 'active' : ''; ?>">
            <?php echo h(toolName($key)); ?>
        </a>
        <?php endif; endforeach; ?>
    </div>

    <div class="card">
        <?php if ($config['enable_tool_json'] == 1 && $tool === 'json'): ?>
        <div class="tool-header"><span class="icon">📋</span><h2><?php echo h(t('json_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('json_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('json_input_label')); ?></label><textarea name="json_input" rows="6" placeholder="<?php echo h(t('json_placeholder')); ?>"><?php echo h($_POST['json_input'] ?? ''); ?></textarea></div>
            <button type="submit" class="btn"><?php echo h(t('json_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box"><pre><?php echo h($result); ?></pre></div>
        <button type="button" class="copy-btn" data-copy="<?php echo h($result); ?>"><?php echo h(t('copy_btn')); ?></button>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_timestamp'] == 1 && $tool === 'timestamp'): ?>
        <div class="tool-header"><span class="icon">🕐</span><h2><?php echo h(t('timestamp_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('timestamp_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('timestamp_current')); ?>：<strong class="timestamp-current"><?php echo time(); ?></strong></label></div>
            <div class="field">
                <div class="row">
                    <div><label><?php echo h(t('timestamp_to_date')); ?></label><input type="text" name="timestamp" placeholder="<?php echo h(t('timestamp_placeholder')); ?>" value="<?php echo h($_POST['timestamp'] ?? ''); ?>"></div>
                    <div><label><?php echo h(t('timestamp_to_ts')); ?></label><input type="text" name="date" placeholder="<?php echo h(t('date_placeholder')); ?>" value="<?php echo h($_POST['date'] ?? ''); ?>"></div>
                </div>
            </div>
            <button type="submit" class="btn"><?php echo h(t('timestamp_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box"><?php echo h($result); ?></div>
        <button type="button" class="copy-btn" data-copy="<?php echo h($result); ?>"><?php echo h(t('copy_btn')); ?></button>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_url'] == 1 && $tool === 'url'): ?>
        <div class="tool-header"><span class="icon">🔗</span><h2><?php echo h(t('url_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('url_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('url_input_label')); ?></label><input type="text" name="url_input" value="<?php echo h($_POST['url_input'] ?? ''); ?>" placeholder="<?php echo h(t('url_input_label')); ?>"></div>
            <div class="field">
                <select name="url_action">
                    <option value="encode" <?php echo ($_POST['url_action'] ?? '') === 'encode' ? 'selected' : ''; ?>><?php echo h(t('url_encode')); ?></option>
                    <option value="decode" <?php echo ($_POST['url_action'] ?? '') === 'decode' ? 'selected' : ''; ?>><?php echo h(t('url_decode')); ?></option>
                </select>
            </div>
            <button type="submit" class="btn"><?php echo h(t('url_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box"><?php echo h($result); ?></div>
        <button type="button" class="copy-btn" data-copy="<?php echo h($result); ?>"><?php echo h(t('copy_btn')); ?></button>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_base64'] == 1 && $tool === 'base64'): ?>
        <div class="tool-header"><span class="icon">📦</span><h2><?php echo h(t('base64_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('base64_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('base64_input_label')); ?></label><textarea name="base64_input" rows="4"><?php echo h($_POST['base64_input'] ?? ''); ?></textarea></div>
            <div class="field">
                <select name="base64_action">
                    <option value="encode" <?php echo ($_POST['base64_action'] ?? '') === 'encode' ? 'selected' : ''; ?>><?php echo h(t('url_encode')); ?></option>
                    <option value="decode" <?php echo ($_POST['base64_action'] ?? '') === 'decode' ? 'selected' : ''; ?>><?php echo h(t('url_decode')); ?></option>
                </select>
            </div>
            <button type="submit" class="btn"><?php echo h(t('url_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box"><?php echo h($result); ?></div>
        <button type="button" class="copy-btn" data-copy="<?php echo h($result); ?>"><?php echo h(t('copy_btn')); ?></button>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_hash'] == 1 && $tool === 'hash'): ?>
        <div class="tool-header"><span class="icon">🔐</span><h2><?php echo h(t('hash_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('hash_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('hash_input_label')); ?></label><input type="text" name="hash_input" value="<?php echo h($_POST['hash_input'] ?? ''); ?>" placeholder="<?php echo h(t('hash_input_label')); ?>"></div>
            <button type="submit" class="btn"><?php echo h(t('hash_btn')); ?></button>
        </form>
        <?php if (is_array($result)): ?>
        <div class="result-box">
            <?php foreach ($result as $algo => $val): ?>
            <div class="hash-item"><span class="algo"><?php echo h($algo); ?></span><span class="val"><?php echo h($val); ?></span></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_beautify'] == 1 && $tool === 'beautify'): ?>
        <?php if (!$beautify_safe): ?>
        <div class="error-msg">⚠️ <?php echo h(t('err_beautify_unavailable')); ?></div>
        <?php else: ?>
        <div class="tool-header"><span class="icon">✨</span><h2><?php echo h(t('beautify_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('beautify_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('beautify_input_label')); ?></label><textarea name="code_input" rows="8"><?php echo h($_POST['code_input'] ?? ''); ?></textarea></div>
            <div class="field">
                <div class="row">
                    <select name="code_lang">
                        <option value="auto" <?php echo ($_POST['code_lang'] ?? '') === 'auto' ? 'selected' : ''; ?>><?php echo h(t('beautify_lang_auto')); ?></option>
                        <option value="javascript" <?php echo ($_POST['code_lang'] ?? '') === 'javascript' ? 'selected' : ''; ?>>JavaScript</option>
                        <option value="html" <?php echo ($_POST['code_lang'] ?? '') === 'html' ? 'selected' : ''; ?>>HTML</option>
                        <option value="css" <?php echo ($_POST['code_lang'] ?? '') === 'css' ? 'selected' : ''; ?>>CSS</option>
                        <option value="php" <?php echo ($_POST['code_lang'] ?? '') === 'php' ? 'selected' : ''; ?>>PHP</option>
                    </select>
                    <select name="code_mode">
                        <option value="beautify" <?php echo ($_POST['code_mode'] ?? '') === 'beautify' ? 'selected' : ''; ?>><?php echo h(t('beautify_mode_beautify')); ?></option>
                        <option value="minify" <?php echo ($_POST['code_mode'] ?? '') === 'minify' ? 'selected' : ''; ?>><?php echo h(t('beautify_mode_minify')); ?></option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn"><?php echo h(t('beautify_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
            <?php if (is_array($result) && isset($result['code'])): ?>
                <div class="result-box">
                    <p><?php echo h(t('beautify_original_size')); ?>：<?php echo (int)$result['original_size']; ?> <?php echo h(t('beautify_bytes')); ?></p>
                    <p><?php echo h(t('beautify_minified_size')); ?>：<?php echo (int)$result['minified_size']; ?> <?php echo h(t('beautify_bytes')); ?></p>
                    <p><?php echo h(t('beautify_saved')); ?>：<?php echo (int)$result['saved']; ?> <?php echo h(t('beautify_bytes')); ?> (<?php echo h($result['percent']); ?>%)</p>
                    <pre><code><?php echo h($result['code']); ?></code></pre>
                </div>
                <button type="button" class="copy-btn" data-copy="<?php echo h($result['code']); ?>"><?php echo h(t('copy_btn')); ?></button>
            <?php else: ?>
                <div class="result-box"><pre><code><?php echo h($result); ?></code></pre></div>
                <button type="button" class="copy-btn" data-copy="<?php echo h($result); ?>"><?php echo h(t('copy_btn')); ?></button>
            <?php endif; ?>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_regex'] == 1 && $tool === 'regex'): ?>
        <div class="tool-header"><span class="icon">🧪</span><h2><?php echo h(t('regex_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('regex_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('regex_pattern_label')); ?></label><input type="text" name="regex_pattern" value="<?php echo h($_POST['regex_pattern'] ?? ''); ?>" placeholder="\d+"></div>
            <div class="field"><label><?php echo h(t('regex_subject_label')); ?></label><textarea name="regex_subject" rows="4"><?php echo h($_POST['regex_subject'] ?? ''); ?></textarea></div>
            <div class="field"><label><?php echo h(t('regex_flags_label')); ?></label><input type="text" name="regex_flags" value="<?php echo h($_POST['regex_flags'] ?? ''); ?>" placeholder="i, m, s"></div>
            <button type="submit" class="btn"><?php echo h(t('regex_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box">
            <p><?php echo h(t('regex_match_count')); ?>：<?php echo (int)$result['count']; ?></p>
            <pre><?php echo h(print_r($result['matches'], true)); ?></pre>
        </div>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_color'] == 1 && $tool === 'color'): ?>
        <div class="tool-header"><span class="icon">🎨</span><h2><?php echo h(t('color_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('color_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('color_input_label')); ?></label><input type="text" name="color_input" value="<?php echo h($_POST['color_input'] ?? '#a78bfa'); ?>" placeholder="#RRGGBB"></div>
            <button type="submit" class="btn"><?php echo h(t('color_btn')); ?></button>
        </form>
        <?php if (is_array($result)): ?>
        <div class="result-box">
            <div class="color-result-row">
                <span class="color-preview" data-color="<?php echo h($result['HEX']); ?>"></span>
                <span><?php echo h($result['HEX']); ?></span>
            </div>
            <div><?php echo h($result['RGB']); ?></div>
            <div><?php echo h($result['HSL']); ?></div>
            <div><?php echo h($result['HSV']); ?></div>
        </div>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_wordcount'] == 1 && $tool === 'wordcount'): ?>
        <div class="tool-header"><span class="icon">📊</span><h2><?php echo h(t('wordcount_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('wordcount_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('wordcount_input_label')); ?></label><textarea name="text_input" rows="6" placeholder="<?php echo h(t('wordcount_input_label')); ?>"><?php echo h($_POST['text_input'] ?? "Hello 世界！\nThis is line 2.\n这是第三行。"); ?></textarea></div>
            <button type="submit" class="btn"><?php echo h(t('wordcount_btn')); ?></button>
        </form>
        <?php if (is_array($result)): ?>
        <div class="result-box">
            <?php foreach ($result as $k => $v): ?>
            <div class="hash-item"><span class="algo"><?php echo h($k); ?></span><span class="val"><?php echo h($v); ?></span></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_diff'] == 1 && $tool === 'diff'): ?>
        <div class="tool-header"><span class="icon">📝</span><h2><?php echo h(t('diff_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('diff_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field"><label><?php echo h(t('diff_a_label')); ?></label><textarea name="text_a" rows="4"><?php echo h($_POST['text_a'] ?? ''); ?></textarea></div>
            <div class="field"><label><?php echo h(t('diff_b_label')); ?></label><textarea name="text_b" rows="4"><?php echo h($_POST['text_b'] ?? ''); ?></textarea></div>
            <button type="submit" class="btn"><?php echo h(t('diff_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box"><?php echo h($result); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ($config['enable_tool_convert'] == 1 && $tool === 'convert'): ?>
        <div class="tool-header"><span class="icon">📏</span><h2><?php echo h(t('convert_title')); ?></h2></div>
        <div class="desc"><?php echo h(t('convert_desc')); ?></div>
        <form method="post">
            <?php if ($config['enable_csrf'] == 1): ?>
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            <?php endif; ?>
            <div class="field">
                <div class="row">
                    <div><label><?php echo h(t('convert_value_label')); ?></label><input type="number" name="convert_value" value="<?php echo h($_POST['convert_value'] ?? 1); ?>" step="any"></div>
                    <div><label><?php echo h(t('convert_from_label')); ?></label>
                        <select name="convert_from">
                            <?php foreach (['mm','cm','m','km','in','ft','yd','mi'] as $uv): ?>
                            <option value="<?php echo h($uv); ?>" <?php echo ($_POST['convert_from'] ?? '') === $uv ? 'selected' : ''; ?>><?php echo h(t('unit_' . $uv)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div><label><?php echo h(t('convert_to_label')); ?></label>
                        <select name="convert_to">
                            <?php foreach (['mm','cm','m','km','in','ft','yd','mi'] as $uv): ?>
                            <option value="<?php echo h($uv); ?>" <?php echo ($_POST['convert_to'] ?? '') === $uv ? 'selected' : ''; ?>><?php echo h(t('unit_' . $uv)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn"><?php echo h(t('convert_btn')); ?></button>
        </form>
        <?php if ($result !== null): ?>
        <div class="result-box result-convert"><?php echo h($result); ?></div>
        <?php elseif ($error): ?>
        <div class="error-msg"><?php echo h($error); ?></div>
        <?php endif; ?>
        <?php endif; ?>

        <div class="learn-more">
            <?php echo h(t('footer_learn_more')); ?>
        </div>
    </div>

    <?php if ($config['enable_disclaimer'] == 1): ?>
    <div class="disclaimer-note">
        <strong><?php echo h(t('disclaimer_note_title')); ?></strong>
        <p><?php echo h(t('disclaimer_note')); ?></p>
    </div>
    <?php endif; ?>

    <?php if ($config['enable_footer'] == 1): ?>
    <div class="footer">
        <?php echo h(t('footer_copyright')); ?>
        <br><?php echo h(t('footer_based_on')); ?>
        <br><?php echo sprintf(h(t('footer_version_fmt')), count($themes), count($toolKeys)); ?>
    </div>
    <?php endif; ?>
</div>

<div class="toast" id="toast"></div>

<script nonce="<?php echo h($csp_nonce); ?>">
(function() {
    // 自动清理地址栏的 set_lang 参数，保持 URL 整洁
    if (window.history.replaceState) {
        var url = new URL(window.location.href);
        if (url.searchParams.has('set_lang')) {
            url.searchParams.delete('set_lang');
            window.history.replaceState({}, '', url.toString());
        }
    }

    // 监听语言下拉框切换（解决 CSP 拦截 onchange 的问题）
    var langSelect = document.getElementById('langSelect');
    if (langSelect) {
        langSelect.addEventListener('change', function() {
            var tool = '<?php echo h($tool); ?>';
            window.location.href = '?tool=' + encodeURIComponent(tool) + '&set_lang=' + encodeURIComponent(this.value);
        });
    }

    var T = {
        copied: <?php echo json_encode(t('copied'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
        copyBtn: <?php echo json_encode(t('copy_btn'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
    };

    function toast(msg) {
        var el = document.getElementById('toast');
        if (!el) return;
        el.textContent = msg;
        el.classList.add('show');
        clearTimeout(el._t);
        el._t = setTimeout(function() { el.classList.remove('show'); }, 2000);
    }

    function fallbackCopy(text, cb) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            toast('✅ ' + T.copied);
            if (cb) cb();
        } catch (e) {
            toast('❌ ' + T.copied);
        }
        document.body.removeChild(ta);
    }

    function copy(text, cb) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                toast('✅ ' + T.copied);
                if (cb) cb();
            }).catch(function() {
                fallbackCopy(text, cb);
            });
        } else {
            fallbackCopy(text, cb);
        }
    }

    var modal = document.getElementById('settingsModal');
    var openBtn = document.getElementById('openSettingsBtn');
    var closeBtn = document.getElementById('closeSettingsBtn');
    if (openBtn && modal) {
        openBtn.addEventListener('click', function() { modal.style.display = 'flex'; });
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function() { modal.style.display = 'none'; });
    }
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });
    }

    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.copy-btn');
        if (!btn) return;
        var text = btn.getAttribute('data-copy');
        if (text == null) return;
        copy(text, function() {
            btn.textContent = '✅ ' + T.copied;
            btn.classList.add('copied');
            setTimeout(function() {
                btn.textContent = T.copyBtn;
                btn.classList.remove('copied');
            }, 1500);
        });
    });

    document.querySelectorAll('.color-preview[data-color]').forEach(function(el) {
        el.style.backgroundColor = el.getAttribute('data-color');
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach(function(el) {
                hljs.highlightElement(el);
            });
        }
    });
})();
</script>
</body>
</html>
