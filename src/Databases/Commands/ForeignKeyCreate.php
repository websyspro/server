<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Databases\Interfaces\ForeignKeyItem;
  use Websyspro\Server\Entitys\StructurePersistedResult;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Commons\Util;

  class ForeignKeyCreate 
  {
    public int $type = 2;
    public array $command = [];
    public array $message = [];

    public function __construct(
      private readonly StructurePersistedResult $persisteds,
      private readonly StructureDesignResult $designs
    ){ 
      $this->setCommand();
    }

    private function setCommand(
    ): void {
      $this->setCommandCreateds();
      $this->setCommandUpdates();
      $this->setCommandDrops();
    }

    private function setCommandCreateds(
    ): void {
      if( $this->persisteds->hasForeigns() === false ){
        if( $this->designs->hasForeigns() === true ){
          Util::Mapper( $this->designs->foreigns, function( ForeignKeyItem $fk ){
            $this->command[] = "alter table {$fk->entity} add constraint {$fk->name} foreign key ({$fk->key}) references {$fk->reference}({$fk->referenceKey});";
            $this->message[] = "Foreign key constraint {$fk->name} added with successfully to {$fk->entity}";
          });
        }
      }
    }

    private function setCommandUpdates(
    ): void {
      if( $this->persisteds->hasForeigns() === true ){
        if( $this->designs->hasForeigns() === true ){
          Util::Mapper( $this->designs->foreigns, function( ForeignKeyItem $fk ){
            if( in_array( $fk->name, $this->persisteds->foreigns ) === false ){
              $this->command[] = "alter table {$fk->entity} add constraint {$fk->name} foreign key ({$fk->key}) references {$fk->reference}({$fk->referenceKey});";
              $this->message[] = "Foreign key constraint {$fk->name} added with successfully to {$fk->entity}";
            }
          });
        }
      }
    }

    private function setCommandDrops(
    ): void {
      if( $this->persisteds->hasForeigns() === true ){
        Util::Mapper( $this->persisteds->foreigns, function( string $name ){
          if( in_array( $name, $this->designs->getForeignsName()) === false ) {
            $this->command[] = "alter table {$this->persisteds->getEntity()} drop foreign key {$name};";
            $this->message[] = "Foreign key constraint {$name} drop with successfully to {$this->persisteds->getEntity()}";
            $this->command[] = "alter table {$this->persisteds->getEntity()} drop index {$name};";
            $this->message[] = "Index {$name} drop with successfully to {$this->persisteds->getEntity()}";
          }
        });
      }      
    }
  }
}