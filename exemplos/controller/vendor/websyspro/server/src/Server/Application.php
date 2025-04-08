<?php

namespace Websyspro\Server\Server;

class Application
{
  public function __construct(
    private readonly array $controllers
  ){}
}