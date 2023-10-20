<?php

namespace Proxy;

use Monolog\Logger;

interface ProxyLoggerInterface
{
  public function setLogger(Logger $logger);
}
