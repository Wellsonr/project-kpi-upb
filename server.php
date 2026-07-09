<?php

$uri = urldecode(
    parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

$uri = substr($uri, 1);

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PATH_INFO'] = '/' . $uri;

require_once __DIR__ . '/index.php';
