<?php
/**
 * 函数库：安全 + 工具算法
 *
 * CSRF/XSS 在前半部分，工具算法在后面
 * 改这里之前先确认 index.php 里调用的函数名没变
 */

// ---------- CSRF ----------

function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    // hash_equals 防时序攻击，别改
    return hash_equals($_SESSION['csrf_token'], $token);
}

function regenerateCsrfToken(): void
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------- 输出转义 ----------

/**
 * HTML 转义
 * 数组会递归处理，key 不动，只处理 value
 */
function safeOutput($data)
{
    if (is_array($data)) {
        $out = [];
        foreach ($data as $k => $v) {
            $out[$k] = safeOutput($v);
        }
        return $out;
    }
    return htmlspecialchars((string)$data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * 短别名，index.php 里大量使用
 * 统一走 safeOutput，避免两套转义逻辑日后分叉
 */
function h($data)
{
    return safeOutput($data);
}

// ---------- 输入验证 ----------

/**
 * 按类型过滤输入
 * string 类型不在这里转义，输出时用 safeOutput
 * 注意 filter_var(FILTER_VALIDATE_INT) 遇到 "0" 会返回 0（假值），
 * 这里用 !== false 判断，否则时间戳 0 会被吞掉
 */
function validateInput($value, string $type = 'string', array $options = [])
{
    $value = trim((string)$value);
    switch ($type) {
        case 'int':
            $r = filter_var($value, FILTER_VALIDATE_INT, $options);
            return ($r === false) ? 0 : $r;

        case 'email':
            $r = filter_var($value, FILTER_VALIDATE_EMAIL);
            return ($r === false) ? '' : $r;

        case 'url':
            $r = filter_var($value, FILTER_VALIDATE_URL);
            return ($r === false) ? '' : $r;

        case 'string':
            return $value;

        case 'pattern':
            if (empty($options['pattern'])) {
                return '';
            }
            $r = preg_match($options['pattern'], $value);
            if ($r === false) {
                error_log('[VALIDATE_PATTERN] 非法正则: ' . $options['pattern']);
                return '';
            }
            return $r ? $value : '';

        default:
            return $value;
    }
}

/**
 * 严格整数校验：只有"合法整数"才返回 int，非法返回 null
 * 用于时间戳这类"0 是合法值、非法输入要报错"的场景
 */
function validateStrictInt($value): ?int
{
    $value = trim((string)$value);
    if ($value === '' || !preg_match('/^-?\d+$/', $value)) {
        return null;
    }
    $r = filter_var($value, FILTER_VALIDATE_INT);
    return ($r === false) ? null : $r;
}

/**
 * 从 Accept-Language 猜语言
 * 只认 zh/en/ja/ru，其他一律回 zh
 * q=0 表示明确拒绝该语言，直接跳过
 */
function detectBrowserLanguage(): string
{
    $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if ($header === '') {
        return 'zh';
    }

    $weights = [];
    // 格式：zh-CN,zh;q=0.9,en;q=0.8
    preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=([0-9.]+))?/i', $header, $m, PREG_SET_ORDER);
    foreach ($m as $one) {
        $code = strtolower($one[1]);
        $q = isset($one[2]) ? (float)$one[2] : 1.0;
        if ($q <= 0) {
            continue; // 明确拒绝
        }
        // 同一个语言出现多次，取最大权重
        if (!isset($weights[$code]) || $weights[$code] < $q) {
            $weights[$code] = $q;
        }
    }

    arsort($weights);
    $supported = ['zh', 'en', 'ja', 'ru'];
    foreach (array_keys($weights) as $code) {
        if (in_array($code, $supported, true)) {
            return $code;
        }
    }
    return 'zh';
}

// ---------- 颜色转换 ----------

/**
 * HEX 转 RGB / HSL / HSV
 * 只接受 #RGB 或 #RRGGBB，其他一律 null
 */
function convertColor(string $input): ?array
{
    $hex = ltrim(trim($input), '#');

    if (preg_match('/^[a-f0-9]{3}$/i', $hex)) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (!preg_match('/^[a-f0-9]{6}$/i', $hex)) {
        return null;
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $rn = $r / 255;
    $gn = $g / 255;
    $bn = $b / 255;
    $max = max($rn, $gn, $bn);
    $min = min($rn, $gn, $bn);
    $l = ($max + $min) / 2;

    if ($max == $min) {
        $h = 0;
        $s = 0;
    } else {
        $d = $max - $min;
        $s = ($l > 0.5) ? $d / (2 - $max - $min) : $d / ($max + $min);
        if ($max == $rn) {
            $h = ($gn - $bn) / $d + ($gn < $bn ? 6 : 0);
        } elseif ($max == $gn) {
            $h = ($bn - $rn) / $d + 2;
        } else {
            $h = ($rn - $gn) / $d + 4;
        }
        $h *= 60;
    }

    $h_r = (int)round($h);
    $s_p = (int)round($s * 100);
    $l_p = (int)round($l * 100);

    if ($max == 0) {
        $v_p = 0;
        $sv_p = 0;
    } else {
        $v_p = (int)round($max * 100);
        $sv_p = (int)round((($max - $min) / $max) * 100);
    }

    return [
        'HEX' => '#' . strtoupper($hex),
        'RGB' => "rgb($r, $g, $b)",
        'HSL' => "hsl($h_r, {$s_p}%, {$l_p}%)",
        'HSV' => "hsv($h_r, {$sv_p}%, {$v_p}%)",
    ];
}

// ---------- 代码美化 ----------

/**
 * 入口：按语言分发
 * PHP 用 token_get_all，其他走通用缩进
 */
function beautifyCode(string $code, string $lang): ?string
{
    if ($code === '') {
        return '';
    }
    if ($lang === 'php') {
        return beautifyPhpCode($code);
    }
    return beautifyGenericCode($code);
}

/**
 * PHP 美化
 * token_get_all 遇到语法错误会抛 ParseError（PHP 7+）
 * 所以要 catch Throwable，不能只 catch Exception
 */
function beautifyPhpCode(string $code): ?string
{
    try {
        $tokens = @token_get_all($code);
    } catch (Throwable $e) {
        return null;
    }
    if ($tokens === false) {
        return null;
    }

    $out = '';
    foreach ($tokens as $tk) {
        if (is_array($tk)) {
            $out .= $tk[1];
        } else {
            $out .= $tk;
        }
    }

    // 到这里只是把 token 拼回去，真正的格式化交给通用缩进
    return beautifyGenericCode($out);
}

/**
 * 通用缩进美化
 * 遇到 { 缩进+1，遇到 } 缩进-1
 * 只处理大括号和方括号，圆括号不动
 */
function beautifyGenericCode(string $code): ?string
{
    $lines = explode("\n", $code);
    $indent = 0;
    $out = [];

    foreach ($lines as $line) {
        $t = trim($line);
        if ($t === '') {
            continue;
        }

        // 先看开头是不是收尾符号
        if ($t[0] === '}' || $t[0] === ']') {
            $indent = max(0, $indent - 1);
        }

        $out[] = str_repeat('    ', $indent) . $t;

        // 再看结尾是不是开头符号
        $last = substr($t, -1);
        if ($last === '{' || $last === '[') {
            $indent++;
        }
    }

    return implode("\n", $out);
}

// ---------- 代码压缩 ----------

/**
 * 压缩代码：去注释、去多余空白
 *
 * 正则做不到完美，字符串里的 // 和 /* 仍可能被吃掉。
 * 这里做了保守化处理：
 *   - 行注释只在行首或空白后匹配，且排除 :// 协议、// 开头的相对 URL
 *   - 块注释保留 /*! 开头的（license 保留惯例）
 * 对含大量字符串的代码建议用户先备份。
 */
function minifyCode(string $code, string $lang): ?string
{
    if ($code === '') {
        return '';
    }

    // 块注释（保留 /*! 开头的 license 注释）
    $code = preg_replace('/\/\*(?!\!).*?\*\//s', '', $code);
    if ($code === null) return null;

    // 行注释：前面是行首或空白，且后面不是 /（避免吃掉 //example.com）
    // 排除 :// 协议（前面是冒号时不匹配）
    $code = preg_replace('/(?<![:\w])\/\/[^\n]*/m', '', $code);
    if ($code === null) return null;

    // HTML 注释
    $code = preg_replace('/<!--(?!\[if).*?-->/s', '', $code);
    if ($code === null) return null;

    // 连续空白压成一个空格
    $code = preg_replace('/\s+/', ' ', $code);
    if ($code === null) return null;

    // 去掉符号两侧空格
    $code = str_replace(
        [' {', ' }', ' ;', ' ,', ' (', ' )', ' :'],
        ['{', '}', ';', ',', '(', ')', ':'],
        $code
    );

    // CSS 十六进制简写
    if ($lang === 'css') {
        $code = preg_replace_callback(
            '/#([a-f0-9])\1([a-f0-9])\2([a-f0-9])\3/i',
            fn($m) => '#' . $m[1] . $m[2] . $m[3],
            $code
        );
        if ($code === null) return null;
    }

    return trim($code);
}

// ---------- 文本统计 ----------

/**
 * 中文/英文混合词数统计
 * - 连续的中文字符按"每字一词"计（与常见中文统计口径一致）
 * - 连续的非中文单词（字母/数字/下划线）按一个词计
 * - 标点和空白不计
 */
function countWords(string $text): int
{
    if ($text === '') {
        return 0;
    }
    // 先统计中文字符数
    $han = preg_match_all('/\p{Han}/u', $text, $m) ?: 0;
    // 去掉中文后再统计非中文单词
    $rest = preg_replace('/\p{Han}/u', ' ', $text) ?? '';
    $words = preg_match_all('/[A-Za-z0-9_]+(?:[\'’-][A-Za-z0-9_]+)*/u', $rest, $m2) ?: 0;
    return $han + $words;
}