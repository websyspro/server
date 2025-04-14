<?php

namespace Websyspro\Server\Databases\Structure\Table;

class StatisticsPersisteds
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
}