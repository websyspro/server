<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Interfaces\Entitys\IForeignKey;

class ForeignKeysPersisteds
{
  public array $items = [];

  public function __construct(
    private readonly string $entity,
    private readonly string $database
  ){}

  public function add(
    string $name
  ): void {
    $this->items[] = $name;
  }

  public function exists(
  ): bool {
    return sizeof(
      $this->items
    ) !== 0;
  }

  public function names(
  ): array {
    return $this->items;
  }
}