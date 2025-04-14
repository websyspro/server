<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Entitys\IForeignKey;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class ForeignKeys
{
  public array $items = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
  }

  public function exists(
  ): bool {
    return sizeof(
      $this->items
    ) !== 0;
  }  

  public function names(
  ): array {
    return Util::Mapper(
      $this->items, fn( IForeignKey $iForeignKey ) => (
        $iForeignKey->name
      )
    );
  }

  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),  (
        fn( IProperty $property ) => (
          Util::Mapper( $property->atributes, fn( object $attribute ) => (
            $attribute->attributeType !== AttributeType::Foreigns ? [] : (
              $this->items[] = new IForeignKey(
                $this->reflect, $property, $attribute
              )
            )
          ))
        )
      )
    );
  }  
}