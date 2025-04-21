<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Structure\Drivers\MySqlDriver;
use Websyspro\Server\Decorations\Databases\EntityList;

class StructureDatabase
{
  private array $entitys = [];
  public MySqlDriver $mysql;

  public function __construct(
    private readonly string $database
  ){
    $this->entitysMapper();
    $this->entitysMapperDriver();
  }

  private function entitysMapper(
  ): void {
    Util::Mapper(
      ( new Reflect( $this->database ))->getAttriutes(), (
        fn( EntityList $entityList ) => (
          Util::Mapper( $entityList->items, fn( string $entity ) => (
            $this->entitys[] = new StructureEntity(
              new StructurePersistedTable( $entity ),
              new StructureDesignTable( $entity )
            )
          ))
        )
      )
    );
  }

  private function entitysMapperDriver(
  ): void {
    if( sizeof( $this->entitys ) !== 0 ){
      $this->mysql = (
        new MySqlDriver(
          $this->entitys
        )
      );
    }
  }
}