<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Statistics
{
  public array $items = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
    $this->columnsMapperJoins();
  } 
  
  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),  (
        fn( IProperty $property ) => (
          Util::Mapper( $property->atributes, fn( object $attribute ) => (
            $attribute->attributeType !== AttributeType::Indexes ? [] : (
              $this->items[] = (object)[
                "name" => $property->name,
                "ords" => $attribute->indexGroup
              ]
            )
          ))
        )
      )
    );
  }

  private function columnsMapperJoins(
  ): void {
    $this->items = Util::Mapper(
      Util::Reduce(
        $this->items, [], function( array $groups, object $property ){
          $groups[$property->ords][] = $property->name;
          return $groups;
        }
      ), fn( array $groups ) => sprintf(
        "INDEX_%s", Util::Join( "_", $groups )
      )
    );
  }
}