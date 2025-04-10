<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Decorations\Databases\EntityList;

class StructureDatabase
{
  public array $entitys = [];

  public function __construct(
    private readonly string $database
  ){
    $this->entitysMapper();
  }

  private function entitysMapper(
  ): void {
    Util::Mapper(
      ( new Reflect( $this->database ))->getAttriutes(), (
        fn( EntityList $entityList ) => (
          Util::Mapper( $entityList->items, fn( string $entity ) => (
            $this->entitys[] = new StructureTable( 
              $entity, $this->database
            )
          ))
        )
      ));
  }
}