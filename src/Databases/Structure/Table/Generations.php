<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Generations
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
            $attribute->attributeType !== AttributeType::Generations ? [] : (
              $this->items[] = $iProperty->name
            )
          ))
        )
      )
    );
  } 
}