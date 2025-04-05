<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\StructureAttribute;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Entitys\StructurePersistedResult;

  class ColumnsCreate 
  {
    public int $type = 1;
    public array $command = [];
    public array $message = [];

    private array $structureAttributeCreateds = [];
    private array $structureAttributeUpdates = [];
    private array $structureAttributeDrops = [];

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
      Util::Mapper( $this->designs->columns, (
        fn( StructureAttribute $structureAttribute ) => (
          $this->persisteds->hasColumnName( $structureAttribute->name ) === false
            ? $this->structureAttributeCreateds[] = ( $structureAttribute ) : []
        )
      ));

      Util::Mapper( $this->structureAttributeCreateds, (
        function( StructureAttribute $structureAttribute ){
          $after = $this->designs->getAfter(
            $structureAttribute->name
          );

          $this->command[] = "alter table {$this->designs->getEntity()} add column {$structureAttribute->name} {$structureAttribute->args} {$this->designs->getNotNull($structureAttribute->name)} after {$after}";
          $this->message[] = "Column {$structureAttribute->name} added with successfully to {$this->designs->getEntity()}";
        }
      ));
    }

    public function setCommandUpdates(
    ): void {
      Util::Mapper( $this->designs->columns, (
        function( StructureAttribute $structureAttribute ){
          $this->persisteds->hasColumnName( $structureAttribute->name ) && (
          $this->persisteds->getColumnName( $structureAttribute->name )->args !== $structureAttribute->args ||
          $this->persisteds->isRequired( $structureAttribute->name ) !== $this->designs->isRequired( $structureAttribute->name ))
            ? $this->structureAttributeUpdates[] = ( $structureAttribute ) : [];
        }
      ));

      Util::Mapper( $this->structureAttributeUpdates, (
        function( StructureAttribute $structureAttribute ){
          $this->command[] = "alter table {$this->designs->getEntity()} modify column {$structureAttribute->name} {$structureAttribute->args} {$this->designs->getNotNull($structureAttribute->name)}";
          $this->message[] = "Column {$structureAttribute->name} modify with successfully to {$this->designs->getEntity()}";
        }
      ));      
    }    

    public function setCommandDrops(
    ): void {
      Util::Mapper( $this->persisteds->columns, (
        fn( StructureAttribute $structureAttribute ) => (
          $this->designs->hasColumnName( $structureAttribute->name ) === false
            ? $this->structureAttributeDrops[] = ( $structureAttribute ) : []          
        )
      ));

      Util::Mapper( $this->structureAttributeDrops, (
        function( StructureAttribute $structureAttribute ){
          $this->command[] = "alter table {$this->designs->getEntity()} drop column {$structureAttribute->name}";
          $this->message[] = "Column {$structureAttribute->name} drop with successfully to {$this->designs->getEntity()}";
        }
      ));      
    }
  }
}