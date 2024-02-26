<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';
$api = '/api/v1';

switch ($request) {
    case '':

    case $api . '/':
        require __DIR__ . $viewDir . 'home.php';
        break;
    
    case $api . '/stats':
        require __DIR__ . $viewDir . 'stats.php';
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
}