<?php

namespace Websyspro\Server\Entitys
{
  class StructureDesign
  {
    public static function set(
      string $entity,
      string $database
    ): StructureDesignResult {
      return new StructureDesignResult(
        entity: $entity,
        database: $database
      );
    }
  }
}