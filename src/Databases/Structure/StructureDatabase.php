<?php

namespace Websyspro\Server\Databases\Structure;

use stdClass;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Structure\Drivers\MySql;
use Websyspro\Server\Decorations\Databases\EntityList;
use Websyspro\Server\Interfaces\Drivers\IPersisted;

class StructureDatabase
{
  private array $entitys;
  public MySql $mysql;

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
            $this->entitys[$entity] = (object)[
              "persisted" => new StructurePersistedTable( $entity, $this->database ),
              "design" => new StructureTable( $entity, $this->database )
            ]
          ))
        )
      )
    );
  }

  private function entitysMapperDriver(
  ): void {
    $this->mysql = new MySql(
      $this->entitys, $this->database
    );
  }
}