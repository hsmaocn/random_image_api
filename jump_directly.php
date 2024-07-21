<?php
// 定义文件路径
$file = 'images.txt';

// 自定义错误处理函数
function handle_error($code, $message) {
    http_response_code($code);
    echo $message;
    exit;
}

// 检查文件是否存在并可读
if (!file_exists($file) || !is_readable($file)) {
    handle_error(500, '文件不存在或不可读');
}

// 读取文件内容到数组
$lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    handle_error(500, '读取文件失败');
}

// 检查文件是否为空
if (empty($lines)) {
    handle_error(500, '文件为空');
}

// 随机选择一行
$random_line = $lines[array_rand($lines)];

// 验证URL格式
if (filter_var($random_line, FILTER_VALIDATE_URL) === false) {
    handle_error(500, '无效的URL格式');
}

// 重定向到随机选择的URL
header("Location: $random_line");
exit;
?>
