<?php

require 'vendor/autoload.php';

use Proxy\Proxy;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (isset($_GET['url'])) {
  $url = $_GET['url'];
} else {
  http_response_code(400);
  echo json_encode(['error' => '"url" parameter not provided']);
}

$logArquivoCaminho = 'my-php-proxy-' . date('Y-m-d') . '.log';

$log = new Logger('index.php');
$log->pushHandler(new StreamHandler('storage/logs/' . $logArquivoCaminho, Logger::DEBUG));

$proxy = new Proxy($url);

$proxy->setLogger($log);

$response = $proxy->fetchResponse();

echo $response;
