<?php

namespace Websyspro\Server\Databases\Structure\Table;

class RequiredsPersisteds
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

  public function isRequired(
    string $name
  ): bool {
    return in_array(
      $name,$this->items
    );
  }

  public function getRequired(
    string $name
  ): string {
    return $this->isRequired( $name )
      ? "not null" : "null";
  }  

  public function exists(
  ): bool {
    return sizeof( $this->items ) !== 0;
  }
}