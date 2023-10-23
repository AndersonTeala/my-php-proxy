<?php

namespace Proxy;

use Monolog\Logger;

class Proxy implements ProxyLoggerInterface
{
  private $url;

  public function __construct($url)
  {
    $this->url = $url;
  }

  public function setLogger(Logger $logger)
  {
    $this->logger = $logger;
  }

  public function fetchResponse()
  {
    if ($this->logger) {
      $this->logger->info('Iniciando requisição para: ' . $this->url);
    }

    $ch = curl_init($this->url);

    $this->logger->info('REQUEST_METHOD', [$_SERVER['REQUEST_METHOD']]);

    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
      curl_setopt($ch, CURLOPT_POST, true);
      $post_data = file_get_contents('php://input');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      $request_headers = getallheaders();
      $curl_headers = array();

      foreach ($request_headers as $key => $value) {
        $curl_headers[] = $key . ': ' . $value;
      }

      curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $response = curl_exec($ch);

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    curl_close($ch);

    header("Content-Type: $contentType");

    if ($this->logger) {
      $this->logger->info('Requisição concluída com sucesso.');
    }

    return $response;
  }
}
