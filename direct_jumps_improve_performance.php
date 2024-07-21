<?php
// 定义文件路径和缓存文件路径
$file = 'images.txt';
$cache_file = 'cache.txt';
$cache_time = 3600; // 缓存时间，单位为秒

// 自定义错误处理函数
function handle_error($code, $message) {
    http_response_code($code);
    echo $message;
    exit;
}

// 生成缓存文件
function generate_cache($file, $cache_file) {
    $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false || empty($lines)) {
        handle_error(500, '文件读取失败或文件为空。');
    }
    file_put_contents($cache_file, json_encode($lines));
    return $lines;
}

// 检查缓存文件是否存在且未过期
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    $lines = json_decode(file_get_contents($cache_file), true);
} else {
    // 生成新的缓存文件
    $lines = generate_cache($file, $cache_file);
}

// 随机选择一行
$random_line = $lines[array_rand($lines)];

// 验证URL格式
if (filter_var($random_line, FILTER_VALIDATE_URL) === false) {
    handle_error(500, '无效的URL格式。');
}

// 重定向到随机选择的URL
header("Location: $random_line");
exit;
?>
