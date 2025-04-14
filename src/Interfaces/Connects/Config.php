<?php

namespace Websyspro\Server\Interfaces\Connects;

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