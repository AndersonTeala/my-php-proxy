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
