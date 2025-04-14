<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Enums\Entitys\CommandType;

class StructureCommand
{
  public function __construct(
    public readonly array $scripts,
    public readonly string $message,
    public readonly CommandType $commandType = CommandType::Basic
  ){}
}