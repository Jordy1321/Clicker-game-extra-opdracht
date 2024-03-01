<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';
$publicDir = '/public/';
$api = '/api/v1';

if (strpos($request, $api . '/stats') === 0) {
    require __DIR__ . $viewDir . 'stats.php';
    return;
}

switch ($request) {
    case '':

    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case $api . '/daily':
        require __DIR__ . $viewDir . 'daily.php';
        break;

    case $api . '/click':
        require __DIR__ . $viewDir . 'click.php';
        break;

    case $api . '/activate':
        require __DIR__ . $viewDir . 'activate.php';
        break;

    case $api . '/register':
        require __DIR__ . $viewDir . 'register.php';
        break;
}