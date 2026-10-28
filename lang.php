<?php
/**
 * 多语言：界面文字 + 主题名 + 工具名
 */

// 主题名
$themeNames = [
    'default'     => ['zh' => '默认暗黑',   'en' => 'Default Dark',   'ja' => 'デフォルトダーク',    'ru' => 'Тёмная по умолчанию'],
    'violet'      => ['zh' => '紫罗兰',     'en' => 'Violet',         'ja' => 'バイオレット',        'ru' => 'Фиолетовый'],
    'white'       => ['zh' => '极简白',     'en' => 'Minimal White',  'ja' => 'ミニマルホワイト',    'ru' => 'Минимальный белый'],
    'darkpurple'  => ['zh' => '暗夜紫',     'en' => 'Night Purple',   'ja' => 'ナイトパープル',      'ru' => 'Ночной фиолетовый'],
    'pink'        => ['zh' => '樱花粉',     'en' => 'Sakura Pink',    'ja' => 'さくらピンク',        'ru' => 'Сакура розовый'],
    'ocean'       => ['zh' => '深海蓝',     'en' => 'Deep Ocean',     'ja' => 'ディープオーシャン',  'ru' => 'Глубокий океан'],
    'forest'      => ['zh' => '森林绿',     'en' => 'Forest Green',   'ja' => 'フォレストグリーン',  'ru' => 'Лесной зелёный'],
    'orange'      => ['zh' => '夕阳橙',     'en' => 'Sunset Orange',  'ja' => 'サンセットオレンジ',  'ru' => 'Закатный оранжевый'],
    'cyan'        => ['zh' => '霓虹青',     'en' => 'Neon Cyan',      'ja' => 'ネオンシアン',        'ru' => 'Неоновый циан'],
    'rose'        => ['zh' => '玫瑰红',     'en' => 'Rose Red',       'ja' => 'ローズレッド',        'ru' => 'Розово-красный'],
    'lavender'    => ['zh' => '薰衣草',     'en' => 'Lavender',       'ja' => 'ラベンダー',          'ru' => 'Лаванда'],
    'mint'        => ['zh' => '薄荷绿',     'en' => 'Mint Green',     'ja' => 'ミントグリーン',      'ru' => 'Мятный зелёный'],
    'sapphire'    => ['zh' => '宝石蓝',     'en' => 'Sapphire Blue',  'ja' => 'サファイアブルー',    'ru' => 'Сапфировый синий'],
    'coral'       => ['zh' => '珊瑚橙',     'en' => 'Coral Orange',   'ja' => 'コーラルオレンジ',    'ru' => 'Коралловый оранжевый'],
    'nightpurple' => ['zh' => '夜空紫',     'en' => 'Night Sky Purple','ja' => '夜空パープル',       'ru' => 'Фиолетовое небо'],
    'freshblue'   => ['zh' => '清新蓝',     'en' => 'Fresh Blue',     'ja' => 'フレッシュブルー',    'ru' => 'Свежий синий'],
    'warmyellow'  => ['zh' => '温暖黄',     'en' => 'Warm Yellow',    'ja' => 'ウォームイエロー',    'ru' => 'Тёплый жёлтый'],
    'iceblue'     => ['zh' => '冰晶蓝',     'en' => 'Ice Blue',       'ja' => 'アイスブルー',        'ru' => 'Ледяной синий'],
    'gray'        => ['zh' => '极简灰',     'en' => 'Minimal Gray',   'ja' => 'ミニマルグレー',      'ru' => 'Минимальный серый'],
    'black'       => ['zh' => '极简黑',     'en' => 'Minimal Black',  'ja' => 'ミニマルブラック',    'ru' => 'Минимальный чёрный'],
    'simpleblue'  => ['zh' => '极简蓝',     'en' => 'Simple Blue',    'ja' => 'シンプルブルー',      'ru' => 'Простой синий'],
    'simplegreen' => ['zh' => '极简绿',     'en' => 'Simple Green',   'ja' => 'シンプルグリーン',    'ru' => 'Простой зелёный'],
    'simplepink'  => ['zh' => '极简粉',     'en' => 'Simple Pink',    'ja' => 'シンプルピンク',      'ru' => 'Простой розовый'],
    'simplepurple'=> ['zh' => '极简紫',     'en' => 'Simple Purple',  'ja' => 'シンプルパープル',    'ru' => 'Простой фиолетовый'],
    'simpleorange'=> ['zh' => '极简橙',     'en' => 'Simple Orange',  'ja' => 'シンプルオレンジ',    'ru' => 'Простой оранжевый'],
    'simplered'   => ['zh' => '极简红',     'en' => 'Simple Red',     'ja' => 'シンプルレッド',      'ru' => 'Простой красный'],
    'simplecyan'  => ['zh' => '极简青',     'en' => 'Simple Cyan',    'ja' => 'シンプルシアン',      'ru' => 'Простой циан'],
    'deeppurple'  => ['zh' => '深空紫',     'en' => 'Deep Purple',    'ja' => 'ディープパープル',    'ru' => 'Глубокий фиолетовый'],
    'darkblue'    => ['zh' => '暗夜蓝',     'en' => 'Dark Blue',      'ja' => 'ダークブルー',        'ru' => 'Тёмно-синий'],
    'darkgreen'   => ['zh' => '暗夜绿',     'en' => 'Dark Green',     'ja' => 'ダークグリーン',      'ru' => 'Тёмно-зелёный'],
    'darkred'     => ['zh' => '暗夜红',     'en' => 'Dark Red',       'ja' => 'ダークレッド',        'ru' => 'Тёмно-красный'],
    'darkgold'    => ['zh' => '暗夜金',     'en' => 'Dark Gold',      'ja' => 'ダークゴールド',      'ru' => 'Тёмно-золотой'],
    'darksilver'  => ['zh' => '暗夜银',     'en' => 'Dark Silver',    'ja' => 'ダークシルバー',      'ru' => 'Тёмно-серебряный'],
];

// 工具名（导航栏显示）
$toolNames = [
    'json'      => ['zh' => '📋 JSON',    'en' => '📋 JSON',       'ja' => '📋 JSON',           'ru' => '📋 JSON'],
    'timestamp' => ['zh' => '🕐 时间戳',  'en' => '🕐 Timestamp',  'ja' => '🕐 タイムスタンプ', 'ru' => '🕐 Время'],
    'url'       => ['zh' => '🔗 URL',     'en' => '🔗 URL',        'ja' => '🔗 URL',            'ru' => '🔗 URL'],
    'base64'    => ['zh' => '📦 Base64',  'en' => '📦 Base64',     'ja' => '📦 Base64',         'ru' => '📦 Base64'],
    'hash'      => ['zh' => '🔐 哈希',    'en' => '🔐 Hash',       'ja' => '🔐 ハッシュ',       'ru' => '🔐 Хеш'],
    'beautify'  => ['zh' => '✨ 美化',    'en' => '✨ Beautify',   'ja' => '✨ 整形',           'ru' => '✨ Формат'],
    'regex'     => ['zh' => '🧪 正则',    'en' => '🧪 Regex',      'ja' => '🧪 正規表現',       'ru' => '🧪 Регулярка'],
    'color'     => ['zh' => '🎨 颜色',    'en' => '🎨 Color',      'ja' => '🎨 カラー',         'ru' => '🎨 Цвет'],
    'wordcount' => ['zh' => '📊 字数',    'en' => '📊 Word Count', 'ja' => '📊 文字数',         'ru' => '📊 Слова'],
    'diff'      => ['zh' => '📝 对比',    'en' => '📝 Diff',       'ja' => '📝 比較',           'ru' => '📝 Сравнение'],
    'convert'   => ['zh' => '📏 换算',    'en' => '📏 Convert',    'ja' => '📏 換算',           'ru' => '📏 Единицы'],
];

// 界面文字
$text = [
    // 头部
    'tool_name'       => ['zh' => '在线工具箱',    'en' => 'Online Toolbox',     'ja' => 'オンラインツールボックス', 'ru' => 'Онлайн-инструменты'],
    'subtitle'        => ['zh' => '开发者必备 · 11 个实用工具 · 纯本地运行 · 数据不上云', 'en' => '11 handy tools for devs · 100% local · nothing uploaded', 'ja' => '開発者向け11ツール · 完全ローカル · アップロードなし', 'ru' => '11 инструментов для разработчиков · всё локально · без загрузки'],
    'badge'           => ['zh' => '⚠️ 仅供学习参考 · 请勿用于非法用途', 'en' => '⚠️ For learning only · No illegal use', 'ja' => '⚠️ 学習用途のみ · 違法利用禁止', 'ru' => '⚠️ Только для обучения · Не для незаконного использования'],
    'theme_settings'  => ['zh' => '⚙️ 主题设置',   'en' => '⚙️ Theme',           'ja' => '⚙️ テーマ',               'ru' => '⚙️ Тема'],
    'current_theme'   => ['zh' => '当前主题',      'en' => 'Current theme',      'ja' => '現在のテーマ',            'ru' => 'Текущая тема'],
    'themes_total'    => ['zh' => '种主题',        'en' => 'themes',             'ja' => 'テーマ',                  'ru' => 'тем'],
    'theme_switch_hint'=> ['zh' => '点击任意主题立即切换，圆点是对应主题的主色', 'en' => 'Click any theme to switch instantly; the dot is its primary color', 'ja' => 'テーマをクリックで即時切替。丸はそのテーマのメイン色', 'ru' => 'Нажмите на тему, чтобы переключить. Точка — основной цвет темы'],

    // 免责
    'disclaimer_note_title' => ['zh' => '⚠️ 使用条款与免责声明', 'en' => '⚠️ Terms & Disclaimer', 'ja' => '⚠️ 利用規約と免責事項', 'ru' => '⚠️ Условия и отказ от ответственности'],
    'disclaimer_note'       => ['zh' => '本工具箱所有工具仅供学习、开发测试使用，所有计算均在本地完成，数据不上传服务器。作者不对使用本工具产生的任何数据丢失、泄露负责。请勿用于非法用途，使用本工具即表示您已知悉并同意上述条款。', 'en' => 'All tools in this toolbox are for learning and development testing only. All calculations run locally; nothing is uploaded. The author is not responsible for any data loss or leak. Do not use for illegal purposes. By using this toolbox you acknowledge and agree to these terms.', 'ja' => '本ツールボックスのすべてのツールは学習・開発テスト用途のみです。計算はすべてローカルで行われ、アップロードはありません。データの損失・漏洩について作者は責任を負いません。違法用途に使用しないでください。使用することで本規約に同意したものとみなします。', 'ru' => 'Все инструменты предназначены только для обучения и тестирования. Все вычисления выполняются локально, данные не загружаются. Автор не несёт ответственности за потерю или утечку данных. Не используйте в незаконных целях. Используя инструменты, вы соглашаетесь с этими условиями.'],

    // JSON
    'json_title'        => ['zh' => 'JSON 格式化',     'en' => 'JSON Formatter',       'ja' => 'JSON フォーマット',  'ru' => 'Форматирование JSON'],
    'json_desc'         => ['zh' => '格式化、验证、压缩 JSON 数据', 'en' => 'Format, validate, minify JSON', 'ja' => 'JSONの整形・検証・圧縮', 'ru' => 'Формат, проверка, сжатие JSON'],
    'json_input_label'  => ['zh' => '输入 JSON',        'en' => 'JSON input',           'ja' => 'JSON を入力',        'ru' => 'Введите JSON'],
    'json_btn'          => ['zh' => '🚀 格式化',        'en' => '🚀 Format',            'ja' => '🚀 整形',            'ru' => '🚀 Формат'],
    'json_placeholder'  => ['zh' => '{"name":"工具箱","version":"1.0","tools":["JSON","时间戳","URL"]}', 'en' => '{"name":"toolbox","version":"1.0","tools":["JSON","Timestamp","URL"]}', 'ja' => '{"name":"ツールボックス","version":"1.0","tools":["JSON","タイムスタンプ","URL"]}', 'ru' => '{"name":"toolbox","version":"1.0","tools":["JSON","Время","URL"]}'],

    // 时间戳
    'timestamp_title'       => ['zh' => '时间戳转换',       'en' => 'Timestamp Converter',   'ja' => 'タイムスタンプ変換',    'ru' => 'Конвертер времени'],
    'timestamp_desc'        => ['zh' => '时间戳 ↔ 日期时间 互转', 'en' => 'Timestamp ↔ Datetime', 'ja' => 'タイムスタンプ ↔ 日時', 'ru' => 'Время ↔ Дата'],
    'timestamp_current'     => ['zh' => '当前时间戳',       'en' => 'Current timestamp',     'ja' => '現在のタイムスタンプ',  'ru' => 'Текущее время'],
    'timestamp_to_date'     => ['zh' => '时间戳 → 日期',    'en' => 'Timestamp → Date',      'ja' => 'タイムスタンプ → 日時', 'ru' => 'Время → Дата'],
    'timestamp_to_ts'       => ['zh' => '日期 → 时间戳',    'en' => 'Date → Timestamp',      'ja' => '日時 → タイムスタンプ', 'ru' => 'Дата → Время'],
    'timestamp_placeholder' => ['zh' => '输入时间戳（整数）', 'en' => 'Enter timestamp (integer)', 'ja' => 'タイムスタンプを入力（整数）', 'ru' => 'Введите время (целое)'],
    'date_placeholder'      => ['zh' => '如 2026-08-30 12:00:00', 'en' => 'e.g. 2026-08-30 12:00:00', 'ja' => '例 2026-08-30 12:00:00', 'ru' => 'напр. 2026-08-30 12:00:00'],
    'timestamp_btn'         => ['zh' => '🔄 转换',          'en' => '🔄 Convert',            'ja' => '🔄 変換',              'ru' => '🔄 Конверт'],

    // URL
    'url_title'       => ['zh' => 'URL 编解码',  'en' => 'URL Encode/Decode', 'ja' => 'URL エンコード/デコード', 'ru' => 'Кодирование URL'],
    'url_desc'        => ['zh' => 'URL 编码 / 解码', 'en' => 'Encode or decode URL', 'ja' => 'URLのエンコード/デコード', 'ru' => 'Кодировать / декодировать URL'],
    'url_input_label' => ['zh' => '输入 URL',    'en' => 'URL',                'ja' => 'URL を入力',              'ru' => 'Введите URL'],
    'url_btn'         => ['zh' => '🚀 执行',     'en' => '🚀 Run',             'ja' => '🚀 実行',                 'ru' => '🚀 Выполнить'],
    'url_encode'      => ['zh' => '编码',        'en' => 'Encode',             'ja' => 'エンコード',              'ru' => 'Кодировать'],
    'url_decode'      => ['zh' => '解码',        'en' => 'Decode',             'ja' => 'デコード',                'ru' => 'Декодировать'],

    // Base64
    'base64_title'       => ['zh' => 'Base64 编解码',  'en' => 'Base64 Encode/Decode', 'ja' => 'Base64 エンコード/デコード', 'ru' => 'Кодирование Base64'],
    'base64_desc'        => ['zh' => 'Base64 编码 / 解码（严格模式）', 'en' => 'Base64 encode / decode (strict)', 'ja' => 'Base64 エンコード/デコード（厳格）', 'ru' => 'Base64 кодирование / декодирование (строгое)'],
    'base64_input_label' => ['zh' => '输入内容',       'en' => 'Input',                 'ja' => '内容を入力',                  'ru' => 'Введите содержимое'],

    // Hash
    'hash_title'       => ['zh' => '哈希计算',   'en' => 'Hash Calculator', 'ja' => 'ハッシュ計算',   'ru' => 'Хеш'],
    'hash_desc'        => ['zh' => 'MD5 · SHA-1 · SHA-256 · SHA-512', 'en' => 'MD5 · SHA-1 · SHA-256 · SHA-512', 'ja' => 'MD5 · SHA-1 · SHA-256 · SHA-512', 'ru' => 'MD5 · SHA-1 · SHA-256 · SHA-512'],
    'hash_input_label' => ['zh' => '输入文本',   'en' => 'Text',             'ja' => 'テキストを入力', 'ru' => 'Введите текст'],
    'hash_btn'         => ['zh' => '🚀 计算',    'en' => '🚀 Calculate',     'ja' => '🚀 計算',        'ru' => '🚀 Вычислить'],

    // Beautify
    'beautify_title'        => ['zh' => '代码美化 / 压缩', 'en' => 'Code Beautify / Minify', 'ja' => 'コード整形 / 圧縮',  'ru' => 'Формат / Сжатие кода'],
    'beautify_desc'         => ['zh' => 'HTML · CSS · JavaScript · PHP 格式化与压缩（压缩模式对含 // 的字符串可能不友好，请备份）', 'en' => 'HTML · CSS · JavaScript · PHP (minify may damage strings containing //, back up first)', 'ja' => 'HTML · CSS · JavaScript · PHP（圧縮は // を含む文字列を壊す可能性あり）', 'ru' => 'HTML · CSS · JavaScript · PHP (сжатие может испортить строки с //)'],
    'beautify_input_label'  => ['zh' => '输入代码',        'en' => 'Code',                    'ja' => 'コードを入力',       'ru' => 'Введите код'],
    'beautify_btn'          => ['zh' => '🚀 执行',         'en' => '🚀 Run',                  'ja' => '🚀 実行',            'ru' => '🚀 Выполнить'],
    'beautify_lang_auto'    => ['zh' => '自动识别',        'en' => 'Auto',                    'ja' => '自動判別',           'ru' => 'Авто'],
    'beautify_mode_beautify'=> ['zh' => '美化',            'en' => 'Beautify',                'ja' => '整形',               'ru' => 'Форматировать'],
    'beautify_mode_minify'  => ['zh' => '压缩',            'en' => 'Minify',                  'ja' => '圧縮',               'ru' => 'Сжать'],
    'beautify_original_size'=> ['zh' => '原始大小',        'en' => 'Original size',           'ja' => '元のサイズ',         'ru' => 'Исходный размер'],
    'beautify_minified_size'=> ['zh' => '压缩后大小',      'en' => 'Minified size',           'ja' => '圧縮後サイズ',       'ru' => 'Размер после сжатия'],
    'beautify_saved'        => ['zh' => '节省',            'en' => 'Saved',                   'ja' => '節約',               'ru' => 'Сэкономлено'],
    'beautify_bytes'        => ['zh' => '字节',            'en' => 'bytes',                   'ja' => 'バイト',             'ru' => 'байт'],

    // Regex
    'regex_title'         => ['zh' => '正则表达式测试',   'en' => 'Regex Tester',           'ja' => '正規表現テスト',      'ru' => 'Тест регулярки'],
    'regex_desc'          => ['zh' => '在线测试正则表达式匹配', 'en' => 'Test regex patterns in browser', 'ja' => '正規表現のマッチをオンラインテスト', 'ru' => 'Тестирование регулярных выражений онлайн'],
    'regex_pattern_label' => ['zh' => '正则表达式',       'en' => 'Pattern',                'ja' => '正規表現パターン',    'ru' => 'Шаблон'],
    'regex_subject_label' => ['zh' => '测试文本',         'en' => 'Test text',              'ja' => 'テストテキスト',      'ru' => 'Тестовый текст'],
    'regex_flags_label'   => ['zh' => '修饰符（仅 i,m,s,x,u,U,D）', 'en' => 'Flags (i,m,s,x,u,U,D)', 'ja' => 'フラグ（i,m,s,x,u,U,D）', 'ru' => 'Флаги (i,m,s,x,u,U,D)'],
    'regex_btn'           => ['zh' => '🚀 测试',          'en' => '🚀 Test',                'ja' => '🚀 テスト',            'ru' => '🚀 Тест'],
    'regex_match_count'   => ['zh' => '匹配数量',         'en' => 'Matches',                'ja' => '一致数',              'ru' => 'Совпадений'],

    // Color
    'color_title'       => ['zh' => '颜色转换',   'en' => 'Color Converter', 'ja' => 'カラー変換',  'ru' => 'Конвертер цветов'],
    'color_desc'        => ['zh' => 'HEX ↔ RGB ↔ HSL ↔ HSV 互转', 'en' => 'HEX ↔ RGB ↔ HSL ↔ HSV', 'ja' => 'HEX ↔ RGB ↔ HSL ↔ HSV', 'ru' => 'HEX ↔ RGB ↔ HSL ↔ HSV'],
    'color_input_label' => ['zh' => '输入颜色（HEX）', 'en' => 'Color (HEX)', 'ja' => 'カラーを入力（HEX）', 'ru' => 'Цвет (HEX)'],
    'color_btn'         => ['zh' => '🚀 转换',    'en' => '🚀 Convert',      'ja' => '🚀 変換',    'ru' => '🚀 Конверт'],

    // Wordcount
    'wordcount_title'       => ['zh' => '字数统计',       'en' => 'Word Count',          'ja' => '文字数カウント',  'ru' => 'Подсчёт слов'],
    'wordcount_desc'        => ['zh' => '字符数 · 单词数 · 行数 · 空格数（单词数：中文按字计，英文按词计）', 'en' => 'Characters · Words · Lines · Spaces (CJK counted per char, Latin per word)', 'ja' => '文字数 · 単語数 · 行数 · スペース（CJKは1文字=1語、英単語は1語）', 'ru' => 'Символы · Слова · Строки · Пробелы (CJK по символам, латиница по словам)'],
    'wordcount_input_label' => ['zh' => '输入文本',       'en' => 'Text',                'ja' => 'テキストを入力', 'ru' => 'Введите текст'],
    'wordcount_btn'         => ['zh' => '🚀 统计',        'en' => '🚀 Count',            'ja' => '🚀 カウント',    'ru' => '🚀 Подсчёт'],
    'wordcount_chars'       => ['zh' => '字符数',         'en' => 'Characters',          'ja' => '文字数',          'ru' => 'Символы'],
    'wordcount_bytes'       => ['zh' => '字节数',         'en' => 'Bytes',               'ja' => 'バイト数',        'ru' => 'Байты'],
    'wordcount_words'       => ['zh' => '单词数',         'en' => 'Words',               'ja' => '単語数',          'ru' => 'Слова'],
    'wordcount_lines'       => ['zh' => '行数',           'en' => 'Lines',               'ja' => '行数',            'ru' => 'Строки'],
    'wordcount_spaces'      => ['zh' => '空格数',         'en' => 'Spaces',              'ja' => 'スペース数',      'ru' => 'Пробелы'],

    // Diff
    'diff_title'     => ['zh' => '文本对比',   'en' => 'Text Diff',        'ja' => 'テキスト比較',    'ru' => 'Сравнение текста'],
    'diff_desc'      => ['zh' => '逐行对比两份文本的差异', 'en' => 'Line-by-line comparison of two texts', 'ja' => '2つのテキストを行単位で比較', 'ru' => 'Построчное сравнение двух текстов'],
    'diff_a_label'   => ['zh' => '文本 A',     'en' => 'Text A',           'ja' => 'テキスト A',      'ru' => 'Текст A'],
    'diff_b_label'   => ['zh' => '文本 B',     'en' => 'Text B',           'ja' => 'テキスト B',      'ru' => 'Текст B'],
    'diff_btn'       => ['zh' => '🚀 对比',    'en' => '🚀 Diff',          'ja' => '🚀 比較',         'ru' => '🚀 Сравнить'],
    'diff_identical' => ['zh' => '✅ 两份文本完全相同', 'en' => '✅ Both texts are identical', 'ja' => '✅ 両方のテキストは同一です', 'ru' => '✅ Тексты идентичны'],
    'diff_line'      => ['zh' => '行',         'en' => 'Line',             'ja' => '行',              'ru' => 'Строка'],

    // Convert
    'convert_title'       => ['zh' => '单位换算',      'en' => 'Unit Converter',    'ja' => '単位換算',      'ru' => 'Конвертер единиц'],
    'convert_desc'        => ['zh' => '长度单位：mm · cm · m · km · in · ft · yd · mi', 'en' => 'Length: mm · cm · m · km · in · ft · yd · mi', 'ja' => '長さ：mm · cm · m · km · in · ft · yd · mi', 'ru' => 'Длина: mm · cm · m · km · in · ft · yd · mi'],
    'convert_value_label' => ['zh' => '数值',          'en' => 'Value',             'ja' => '値',            'ru' => 'Значение'],
    'convert_from_label'  => ['zh' => '从',            'en' => 'From',              'ja' => '変換元',        'ru' => 'Из'],
    'convert_to_label'    => ['zh' => '到',            'en' => 'To',                'ja' => '変換先',        'ru' => 'В'],
    'convert_btn'         => ['zh' => '🔄 换算',       'en' => '🔄 Convert',        'ja' => '🔄 換算',       'ru' => '🔄 Конверт'],

    // 单位
    'unit_mm' => ['zh' => '毫米 (mm)', 'en' => 'Millimeter (mm)', 'ja' => 'ミリメートル (mm)', 'ru' => 'Миллиметр (mm)'],
    'unit_cm' => ['zh' => '厘米 (cm)', 'en' => 'Centimeter (cm)', 'ja' => 'センチメートル (cm)', 'ru' => 'Сантиметр (cm)'],
    'unit_m'  => ['zh' => '米 (m)',    'en' => 'Meter (m)',       'ja' => 'メートル (m)',       'ru' => 'Метр (m)'],
    'unit_km' => ['zh' => '千米 (km)', 'en' => 'Kilometer (km)',  'ja' => 'キロメートル (km)',  'ru' => 'Километр (km)'],
    'unit_in' => ['zh' => '英寸 (in)', 'en' => 'Inch (in)',       'ja' => 'インチ (in)',        'ru' => 'Дюйм (in)'],
    'unit_ft' => ['zh' => '英尺 (ft)', 'en' => 'Foot (ft)',       'ja' => 'フィート (ft)',      'ru' => 'Фут (ft)'],
    'unit_yd' => ['zh' => '码 (yd)',   'en' => 'Yard (yd)',       'ja' => 'ヤード (yd)',        'ru' => 'Ярд (yd)'],
    'unit_mi' => ['zh' => '英里 (mi)', 'en' => 'Mile (mi)',       'ja' => 'マイル (mi)',        'ru' => 'Миля (mi)'],

    // 通用
    'copy_btn' => ['zh' => '📋 复制', 'en' => '📋 Copy', 'ja' => '📋 コピー', 'ru' => '📋 Копировать'],
    'copied'   => ['zh' => '已复制', 'en' => 'Copied', 'ja' => 'コピー済み', 'ru' => 'Скопировано'],

    // 错误 / 限制
    'error_csrf'            => ['zh' => 'CSRF 校验失败，请刷新重试', 'en' => 'CSRF check failed, please refresh', 'ja' => 'CSRF検証失敗、再読み込みしてください', 'ru' => 'Ошибка CSRF, обновите страницу'],
    'err_input_too_large'   => ['zh' => '输入过大（上限 %s）', 'en' => 'Input too large (max %s)', 'ja' => '入力が大きすぎます（上限 %s）', 'ru' => 'Слишком большой ввод (макс. %s)'],
    'err_json_parse'        => ['zh' => 'JSON 解析错误', 'en' => 'JSON parse error', 'ja' => 'JSON 解析エラー', 'ru' => 'Ошибка разбора JSON'],
    'err_base64_decode'     => ['zh' => 'Base64 解码失败', 'en' => 'Base64 decode failed', 'ja' => 'Base64 デコード失敗', 'ru' => 'Ошибка декодирования Base64'],
    'err_date_invalid'      => ['zh' => '日期格式无效', 'en' => 'Invalid date format', 'ja' => '日付形式が無効です', 'ru' => 'Неверный формат даты'],
    'err_timestamp_invalid' => ['zh' => '时间戳必须是整数', 'en' => 'Timestamp must be an integer', 'ja' => 'タイムスタンプは整数で入力してください', 'ru' => 'Время должно быть целым числом'],
    'err_color_invalid'     => ['zh' => '无效的颜色格式，请使用 #RRGGBB 或 #RGB', 'en' => 'Invalid color, use #RRGGBB or #RGB', 'ja' => '無効な色形式です。#RRGGBB または #RGB を使用してください', 'ru' => 'Неверный цвет, используйте #RRGGBB или #RGB'],
    'err_unit_unsupported'  => ['zh' => '不支持的单位', 'en' => 'Unsupported unit', 'ja' => '未対応の単位', 'ru' => 'Неподдерживаемая единица'],
    'err_regex_too_long'    => ['zh' => '正则表达式长度不能超过 1000 字符', 'en' => 'Regex pattern cannot exceed 1000 characters', 'ja' => '正規表現は1000文字以内にしてください', 'ru' => 'Шаблон не должен превышать 1000 символов'],
    'err_regex_complex'     => ['zh' => '正则过于复杂，已中止匹配。请简化正则表达式。', 'en' => 'Regex too complex, aborted. Please simplify.', 'ja' => '正規表現が複雑すぎるため中止しました。簡略化してください。', 'ru' => 'Регулярка слишком сложна, прервано. Упростите.'],
    'err_regex_bad_utf8'    => ['zh' => '测试文本不是合法 UTF-8，或去掉 u 修饰符', 'en' => 'Test text is not valid UTF-8, or remove the u flag', 'ja' => 'テストテキストが正しい UTF-8 ではありません。u フラグを外してください', 'ru' => 'Текст не является корректным UTF-8, или уберите флаг u'],
    'err_regex_error'       => ['zh' => '正则表达式错误', 'en' => 'Regex error', 'ja' => '正規表現エラー', 'ru' => 'Ошибка регулярки'],
    'err_beautify_fail'     => ['zh' => '操作失败，请重试。', 'en' => 'Operation failed, please retry.', 'ja' => '操作に失敗しました。再試行してください。', 'ru' => 'Ошибка операции, повторите.'],
    'err_beautify_timeout'  => ['zh' => '操作超时，请简化代码后重试。', 'en' => 'Operation timed out, simplify the code.', 'ja' => '操作がタイムアウトしました。コードを簡略化してください。', 'ru' => 'Тайм-аут операции, упростите код.'],
    'err_beautify_unavailable' => ['zh' => '该工具当前不可用。', 'en' => 'This tool is currently unavailable.', 'ja' => 'このツールは現在利用できません。', 'ru' => 'Инструмент недоступен.'],

    // 页脚
    'footer_learn_more' => ['zh' => '⚠️ 仅供学习参考 · 所有工具均在本地运行 · 数据不上云', 'en' => '⚠️ For learning only · All tools run locally · No upload', 'ja' => '⚠️ 学習用のみ · すべてローカル実行 · アップロードなし', 'ru' => '⚠️ Только для обучения · Всё работает локально · Без загрузки'],
    'footer_copyright'  => ['zh' => '数据不上云 · 纯本地运行', 'en' => 'No cloud · 100% local', 'ja' => 'クラウド不使用 · 完全ローカル', 'ru' => 'Без облака · Полностью локально'],
    'footer_based_on'   => ['zh' => 'highlight.js (BSD-3-Clause) · 本项目 MIT', 'en' => 'highlight.js (BSD-3-Clause) · This project MIT', 'ja' => 'highlight.js (BSD-3-Clause) · 本プロジェクト MIT', 'ru' => 'highlight.js (BSD-3-Clause) · Проект MIT'],
    'footer_version_fmt'=> ['zh' => '🧰 在线工具箱 v1.0 · %d 种主题 · %d 个工具', 'en' => '🧰 Online Toolbox v1.0 · %d themes · %d tools', 'ja' => '🧰 オンラインツールボックス v1.0 · %dテーマ · %dツール', 'ru' => '🧰 Онлайн-инструменты v1.0 · %d тем · %d инструментов'],
];

function t($key) {
    global $text, $current_lang;
    if (!isset($text[$key])) {
        return $key;
    }
    return $text[$key][$current_lang]
        ?? $text[$key]['zh']
        ?? $key;
}

function themeName($key) {
    global $themeNames, $current_lang;
    if (!isset($themeNames[$key])) {
        return $key;
    }
    return $themeNames[$key][$current_lang]
        ?? $themeNames[$key]['zh']
        ?? $key;
}

function toolName($key) {
    global $toolNames, $current_lang;
    if (!isset($toolNames[$key])) {
        return $key;
    }
    return $toolNames[$key][$current_lang]
        ?? $toolNames[$key]['zh']
        ?? $key;
}