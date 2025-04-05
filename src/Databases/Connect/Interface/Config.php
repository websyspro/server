<?php

namespace Websyspro\Server\Databases\Connect\Interface
{
  class Config
  {
    public function __construct(
      public string $prefix,
      public string $database,
      public string $type,
      public string $hostname,
      public string $username,
      public string $password,
      public int $port
    ){}
  }
}