<?php
/**
 * 配置：主题、工具白名单、语言、颜色映射、单位
 *
 * 改这里之前先确认 index.php 里没写死相关键名
 */

// 主题列表，键名是标识符，中文名在 lang.php 里对应
$themes = [
    'default',
    'violet',
    'white',
    'darkpurple',
    'pink',
    'ocean',
    'forest',
    'orange',
    'cyan',
    'rose',
    'lavender',
    'mint',
    'sapphire',
    'coral',
    'nightpurple',
    'freshblue',
    'warmyellow',
    'iceblue',
    'gray',
    'black',
    'simpleblue',
    'simplegreen',
    'simplepink',
    'simplepurple',
    'simpleorange',
    'simplered',
    'simplecyan',
    'deeppurple',
    'darkblue',
    'darkgreen',
    'darkred',
    'darkgold',
    'darksilver',
];

// 工具白名单
// 决定 $_GET['tool'] 能路由到哪，增删都要同步 $toolKeys 和 lang.php
$allowedTools = [
    'json',
    'timestamp',
    'url',
    'base64',
    'hash',
    'beautify',
    'regex',
    'color',
    'wordcount',
    'diff',
    'convert',
];

// 导航栏顺序，必须和 $allowedTools 一致
$toolKeys = [
    'json', 'timestamp', 'url', 'base64', 'hash',
    'beautify', 'regex', 'color',
    'wordcount', 'diff', 'convert',
];

// 支持的语言
// 加语言时：这里加一行，lang.php 里加对应翻译，否则切过去会显示 key
$langs = [
    'zh' => '中文',
    'en' => 'English',
    'ja' => '日本語',
    'ru' => 'Русский',
];

// 主题预览圆点颜色
// 键名必须和 $themes 里对得上，少一个会 fallback 到 #a78bfa
$color_map = [
    'default'      => '#a78bfa',
    'violet'       => '#b38bff',
    'white'        => '#5a4bd1',
    'darkpurple'   => '#9b6bff',
    'pink'         => '#ff8aae',
    'ocean'        => '#4a8aff',
    'forest'       => '#4ade80',
    'orange'       => '#ff9944',
    'cyan'         => '#00e5b0',
    'rose'         => '#ff4466',
    'lavender'     => '#a88bff',
    'mint'         => '#33e0a8',
    'sapphire'     => '#4466ff',
    'coral'        => '#ff7755',
    'nightpurple'  => '#8855dd',
    'freshblue'    => '#4488ff',
    'warmyellow'   => '#ffcc44',
    'iceblue'      => '#44bbff',
    'gray'         => '#666680',
    'black'        => '#888888',
    'simpleblue'   => '#3b7bdd',
    'simplegreen'  => '#38b86a',
    'simplepink'   => '#d46080',
    'simplepurple' => '#8855cc',
    'simpleorange' => '#d48844',
    'simplered'    => '#cc4444',
    'simplecyan'   => '#38b8b8',
    'deeppurple'   => '#7744cc',
    'darkblue'     => '#4466cc',
    'darkgreen'    => '#38b060',
    'darkred'      => '#cc4444',
    'darkgold'     => '#ccaa44',
    'darksilver'   => '#8888aa',
];

// 单位换算，基准是毫米
// 新增单位直接加，key 和 convert 工具下拉框里的 value 对应
$units = [
    'mm' => 1,
    'cm' => 10,
    'm'  => 1000,
    'km' => 1000000,
    'in' => 25.4,
    'ft' => 304.8,
    'yd' => 914.4,
    'mi' => 1609344,
];