<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Util;

class ColumnsPersisteds
{
  public array $items = [];

  public function __construct(
    private readonly string $entity,
    private readonly string $database
  ){}
  
  public function add(
    string $name,
    string $type
  ): void {
    $this->items[ $name ] = $type;
  }

  public function exists(
  ): bool {
    return sizeof( $this->items ) !== 0;
  }

  public function hasColumn(
    string $name
  ): bool {
    return in_array(
      $name, array_keys(
        $this->items
      )
    ) === true;
  }

  public function getColumn(
    string $name
  ): array {
    return Util::FilterByKey(
      $this->items, fn( string $name ) => (
        $this->hasColumn( $name)
      )
    );
  }
}