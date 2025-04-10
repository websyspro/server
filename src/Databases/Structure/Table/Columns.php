<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Enums\Entitys\ColumnOrdem;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Columns
{
  public array $items = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
    $this->columnsOrders();
  }

  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),  (
        fn( IProperty $iProperty ) => (
          $this->items[ $iProperty->name ] = Util::ValueOfArray(
            Util::Filter( $iProperty->atributes, fn( object $attribute ) => (
              $attribute->attributeType === AttributeType::Column
            ))
          )->type()
        )
      )
    );
  }

  private function columnsStart(
  ): array {
    return explode( "|", (
      ColumnOrdem::ColumnStart->value
    ));
  }

  private function columnsEnd(
  ): array {
    return explode( "|", (
      ColumnOrdem::ColumnEnd->value
    ));
  }  

  private function columnsOrders(
  ): void {
    $columnsStart = $this->columnsStart();
    $columnsEnd = $this->columnsEnd();

    $this->items = array_merge(
      Util::FilterByKey( $this->items, (
        fn( string $name ) => in_array( $name, $columnsStart )
      )),
      Util::FilterByKey( $this->items, (
        fn( string $name ) => in_array( $name, array_merge(
          $columnsStart, $columnsEnd
        )) === false
      )),
      Util::FilterByKey( $this->items, (
        fn( string $name ) => in_array( $name, $columnsEnd )
      ))
    );
  }
}