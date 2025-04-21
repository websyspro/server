<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Events
{
  public array $items = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
    $this->columnsClear();
  }

  private function isEventList(
  ): array {
    return [
      AttributeType::EventBeforeInsert,
      AttributeType::EventBeforeUpdate,
      AttributeType::EventBeforeDelete,
      AttributeType::EventAfterInsert,
      AttributeType::EventAfterUpdate,
      AttributeType::EventAfterDelete
    ];
  }

  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),
        fn( IProperty $iProperty ) => (
          $this->items[ $iProperty->name ] = (
            Util::Filter( $iProperty->atributes, (
              fn( object $attribute ) => (
                in_array(
                  $attribute->attributeType, 
                  $this->isEventList()
                )
              )
            ))
          )
        )
    );
  }

  private function columnsClear(
  ): void {
    $this->items = Util::Filter(
      $this->items, fn( array  $item ) => (
        $item !== []
      )
    );
  }
}