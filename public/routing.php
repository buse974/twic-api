<?php
// www/routing.php
if (preg_match('/\.(?:jpg|jpeg|gif|css|js|ico|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    include __DIR__ . '/index.php';
}
