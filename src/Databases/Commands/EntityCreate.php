<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\Commons\EntityUtil;
  use Websyspro\Server\Entitys\StructureAttribute;

  class EntityCreate extends CommandsUtil
  {
    private function getEntityColumns(
    ): string {
      return (
        EntityUtil::joinColumns(
          Util::Mapper( $this->getStructureDesignResult()->columns,
            fn( StructureAttribute $structureAttribute ) => (
              $this->getNotNull( $structureAttribute->name )
                ? EntityUtil::joinWithSpace([
                    $structureAttribute->name,
                    $structureAttribute->args, (
                      $this->getNotNull(
                        $structureAttribute->name
                      )
                    )
                  ]) 
                : EntityUtil::joinWithSpace([
                  $structureAttribute->name,
                  $structureAttribute->args
                ])
            )
          )
        )
      );
    }

    public function setCommand(
    ): void {
      $this->command[] = "create table {$this->getEntity()} ({$this->getEntityColumns()}) engine=innodb;";
      $this->message[] = "Table {$this->getEntity()} created with successfully";
    }
  }
}