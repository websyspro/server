<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Commons\Log;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Structure\Drivers\MySqlDriver;
use Websyspro\Server\Decorations\Databases\EntityList;
use Websyspro\Server\Enums\LogType;

class StructureDatabase
{
  private array $entitys;
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
            $this->entitys[$entity] = new StructureEntity(
              new StructurePersistedTable( $entity, $this->database ),
              new StructureDesignTable( $entity, $this->database )
            )
          ))
        )
      )
    );
  }

  private function entitysMapperDriver(
  ): void {
    $this->mysql = new MySqlDriver(
      $this->entitys, $this->database
    );
  }
}