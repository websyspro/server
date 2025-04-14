<?php

namespace Websyspro\Server\Databases\Structure\Drivers;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Structure\StructureEntity;
use Websyspro\Server\Enums\Entitys\ColumnType;

class AbstractDriver
{
  public array $commands = [];

  public function __construct(
    public array $entitys,
    public string $database,
  ){
    $this->setMapperColumns();
    $this->setMapperStart();
  }

  public function getData(
  ): string {
    return Util::getData(
      $this->database
    );
  }

  public function setColumnParseType(
    array $items = []  
  ): array {
    return Util::Mapper(
      $items, fn( object $object ) => (
        match( $object->type ){
          ColumnType::Decimal->value => "decimal({$object->args})",
          ColumnType::Text->value => "varchar({$object->args})",
          ColumnType::Datetime->value => "datetime",
          ColumnType::Number->value => "bigint",
          ColumnType::Flag->value => "smallint",
          ColumnType::Time->value => "time",
          ColumnType::Date->value => "date",
        }
      )
    );
  }

  public function setMapperColumns(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $entity ) => (
        $entity->design->columns->items = $this->setColumnParseType(
          $entity->design->columns->items
        )
      )
    );
  }

  public function setMapperStart(
  ): void {}
}