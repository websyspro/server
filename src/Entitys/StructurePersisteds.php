<?php

namespace Websyspro\Server\Entitys
{
  class StructurePersisteds
  {
    public static function set(
      string $entity,
      string $database
    ): StructurePersistedResult {
      return new StructurePersistedResult(
        entity: $entity,
        database: $database
      );
    }
  }
}