<?php
// 网站基本配置
// Automatically detect environment and set appropriate base URL
$server_name = $_SERVER['SERVER_NAME'];

// Check if we're on localhost or production
if ($server_name == 'localhost' || $server_name == '127.0.0.1') {
    // Local environment
    define('BASE_URL', '/WebDevelopment2/golden_rule_calendar_project/');
} else {
    // Production environment
    define('BASE_URL', '/');
}

// 获取当前页面的相对深度，用于正确引用资源
function getBasePath() {
    // Simply return the BASE_URL constant for consistent path resolution
    return BASE_URL;
}
?>