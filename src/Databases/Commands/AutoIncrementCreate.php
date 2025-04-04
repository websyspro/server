<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\StructureAttribute;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Entitys\StructurePersistedResult;

  class AutoIncrementCreate 
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
      $this->setCommandCreateds();
      $this->setCommandUpdates();
      $this->setCommandDrops();
    }

    public function setCommandCreateds(
    ): void {
      if( $this->persisteds->hasAutoIncrement() === false ){
        if( $this->designs->hasAutoIncrement() === true ){
          Util::Mapper($this->designs->autoIncrements, (
            function( StructureAttribute $structureAttribute ){
              $column = $this->designs->getColumnName(
                $structureAttribute->name
              );
              
              $this->command[] = "alter table {$this->designs->getEntity()} modify column {$column->name} {$column->args} {$this->designs->getNotNull($column->name)} auto_increment";
              $this->message[] = "Column {$column->name} added AutoIncrement with successfully to {$this->designs->getEntity()}";
            }
          ));

        }
      }
    }

    public function setCommandUpdates(
    ): void {
      if( $this->persisteds->hasAutoIncrement() === true ){
        if( $this->designs->hasAutoIncrement() === true ){
          [ $persisteds ] = $this->persisteds->autoIncrements;
          [ $designs ] = $this->designs->autoIncrements;
          
          if( $persisteds->name !== $designs->name ){
            $persistedsColumn = $this->designs->getColumnName( $persisteds->name );
            $designsColumn = $this->designs->getColumnName( $designs->name );            

            $this->command[] = "alter table {$this->designs->getEntity()} modify column {$persistedsColumn->name} {$persistedsColumn->args} {$this->designs->getNotNull($persistedsColumn->name)}";
            $this->command[] = "alter table {$this->designs->getEntity()} modify column {$designsColumn->name} {$designsColumn->args} {$this->designs->getNotNull($designsColumn->name)} auto_increment";
            $this->message[] = "Update AutoIncrement {$persistedsColumn->name} to {$designsColumn->name} with successfully to {$this->designs->getEntity()}";
          }
        }
      }      
    }

    public function setCommandDrops(
    ): void {
      if( $this->persisteds->hasAutoIncrement() === true ){
        if( $this->designs->hasAutoIncrement() === false ){
          [ $persisteds ] = $this->persisteds->autoIncrements;
          
          $persistedsColumn = $this->designs->getColumnName( $persisteds->name );     

          $this->command[] = "alter table {$this->designs->getEntity()} modify column {$persistedsColumn->name} {$persistedsColumn->args} {$this->designs->getNotNull($persistedsColumn->name)}";
          $this->message[] = "Drop AutoIncrement {$persistedsColumn->name} with successfully to {$this->designs->getEntity()}";
        }
      }      
    }
  }
}