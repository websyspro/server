<?php

namespace Websyspro\Server\Databases
{
  class StructureScript
  {
    public function __construct(
      public readonly string $command,
      public readonly string $message
    ){}
  }
}