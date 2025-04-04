<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Entitys\Commons\EntityUtil;
  use Websyspro\Server\Entitys\StructurePersistedResult;

  class PrimaryKeyCreate 
  {
    public int $type = 1;
    public array $command = [];
    public array $message = [];

    public function __construct(
      private readonly StructurePersistedResult $persisteds,
      private readonly StructureDesignResult $designs
    ){
      $this->setCommand();
    }

    public function setCommand(
    ): void {
      $this->designs->hasPrimaryKey()
        ? $this->setCommandCreate()
        : $this->setCommandDrop();
    }

    public function setCommandCreate(
    ): void {
      if ( $this->designs->hasPrimaryKey() === true ) {
        $arrayNotEquais = Util::arrayEquais(
          $this->persisteds->getPrimaryKeyColumns(),
          $this->designs->getPrimaryKeyColumns()
        ) === false;
        
        if( $arrayNotEquais === true ){
          if( $this->persisteds->hasPrimaryKey() === true ){
            $this->command[] = "alter table {$this->designs->getEntity()} drop primary key";
            $this->message[] = "Primary key successfully deleted from {$this->designs->getEntity()} table";
          }

          $primaryKeyList = EntityUtil::joinColumns(
            $this->designs->getPrimaryKeyColumns()
          );

          $this->command[] = "alter table {$this->designs->getEntity()} add primary key ({$primaryKeyList})";
          $this->message[] = "Primary key ({$primaryKeyList}) create for {$this->designs->getEntity()} table successfully";
        }
      }
    }

    public function setCommandDrop(
    ): void {
      if( $this->persisteds->hasPrimaryKey() === true ){
        if( $this->designs->hasPrimaryKey() === false ){
          $this->command[] = "alter table {$this->designs->getEntity()} drop primary key";
          $this->message[] = "Primary Key successfully deleted from {$this->designs->getEntity()} table";
        }
      }
    }
  }
}