<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Requireds
{
  public array $items = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
  } 
  
  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),  (
        fn( IProperty $iProperty ) => (
          Util::Mapper( $iProperty->atributes, fn( object $attribute ) => (
            $attribute->attributeType !== AttributeType::Requireds ? [] : (
              $this->items[] = $iProperty->name
            )
          ))
        )
      )
    );
  }

  public function isRequired(
    string $name
  ): string {
    return in_array( $name, $this->items );
  }
}