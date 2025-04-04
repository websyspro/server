<?php

namespace Websyspro\Server\Entitys\Commons
{
  class EntityUtil
  {
    public static function DatabaseParse(
      string $entityClass
    ): string {
      $entityName = explode(
        "\\", $entityClass
      );

      return preg_replace(
        "/Database$/", "", end(
          $entityName
        )
      );
    }

    public static function EntityParse(
      string $entityClass
    ): string {
      $entityName = explode(
        "\\", $entityClass
      );

      return preg_replace(
        "/Entity$/", "", end(
          $entityName
        )
      );
    }

    public static function joinColumns(
      array $joinList
    ): string {
      return implode(
        ", ", $joinList
      );
    }
    
    public static function joinWithSpace(
      array $joinList
    ): string {
      return implode(
        " ", $joinList
      );
    }
  }
}