<?php 

namespace Websyspro\Server\Interfaces\Decorations\Entitys\Columns;

use Websyspro\Server\Enums\Entitys\ColumnType;

class IColumn
{
  public function __construct(
    public readonly string $name,
    public readonly string $args,
    public readonly ColumnType $columnType
  ){}
}