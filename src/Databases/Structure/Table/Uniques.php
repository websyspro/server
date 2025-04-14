<?php

namespace Websyspro\Server\Databases\Structure\Table;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class Uniques
{
  public array $items = [];
  private array $itemsGroups = [];

  public function __construct(
    private readonly Reflect $reflect
  ){
    $this->columnsMapper();
    $this->columnsMapperJoins();
  } 

  public function exists(
  ): bool {
    return sizeof(
      $this->items
    ) !== 0;
  }
  
  public function hasUnique(
    string $name
  ): bool {
    return in_array(
      $name, array_keys(
        $this->items
      )
    ) === true;
  }  
  
  private function columnsMapper(
  ): void {
    Util::Mapper( 
      $this->reflect->getAttributesByProperties(),  (
        fn( IProperty $property ) => (
          Util::Mapper( $property->atributes, fn( object $attribute ) => (
            $attribute->attributeType !== AttributeType::Uniques ? [] : (
              $this->itemsGroups[] = (object)[
                "name" => $property->name,
                "ords" => $attribute->uniqueGroup
              ]
            )
          ))
        )
      )
    );
  }

  private function getEntity(
  ): string {
    return Util::parseEntity(
      $this->reflect->getClass()
    );
  }

  private function columnsMapperJoins(
  ): void {
    Util::Mapper(
      Util::Reduce(
        $this->itemsGroups, [], function( array $groups, object $property ){
          $groups[$property->ords][] = $property->name;
          return $groups;
        }
      ), fn( array $groups ) => (
        $this->items[
          sprintf( "UNIQUE_%s_%s", $this->getEntity(), Util::Join( "_", $groups ))
        ] = implode( ",", $groups )
      )
    );
  }
}