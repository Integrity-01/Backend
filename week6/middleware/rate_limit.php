<?php
use Doctrine\Common\Cache\FilesystemCache;

function applyRateLimitMiddleware() {
    $cache = new FilesystemCache(sys_get_temp_dir());
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_$ip";

    $requests = $cache->fetch($key) ?? 0;

    if ($requests >= 5) {
        http_response_code(429); // Too Many Requests
        echo json_encode(['error' => 'Rate limit exceeded. Try again later.']);
        return false;
    }

    $cache->save($key, $requests + 1, 60); // Allow 5 requests per minute
    return true;
}