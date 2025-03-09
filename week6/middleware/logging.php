<?php
function applyLoggingMiddleware() {
    $log_message = sprintf(
        "[%s] %s %s\n",
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['REQUEST_URI']
    );

    file_put_contents('logs/api.log', $log_message, FILE_APPEND);
    return true;
}