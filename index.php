<?php

require 'vendor/autoload.php';

$configLogging = require('src/Config/Logging.php');

use Proxy\Proxy;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header("Content-Type: 'json");

if (isset($_GET['url'])) {
  $url = $_GET['url'];
} else {
  http_response_code(400);
  echo json_encode(['error' => 'url parameter not provided']);
  return false;
}

if(empty($url)){
  http_response_code(400);
  echo json_encode(['error' => 'url parameter empty']);
  return false;
}

$logArquivoCaminho = 'my-php-proxy-' . date('Y-m-d') . '.log';

$log = new Logger('index.php');
$log->pushHandler(new StreamHandler($configLogging['path_default'] . $logArquivoCaminho, Logger::DEBUG));

$proxy = new Proxy($url);

$proxy->setLogger($log);

$response = $proxy->fetchResponse();

echo $response;
