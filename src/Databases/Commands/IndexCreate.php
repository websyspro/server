<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\Commons\EntityUtil;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Entitys\StructurePersistedResult;

  class IndexCreate 
  {
    public int $type = 1;
    public array $command = [];
    public array $message = [];

    private array $indexGroups = [];

    public function __construct(
      private readonly StructurePersistedResult $persisteds,
      private readonly StructureDesignResult $designs
    ){ 
      $this->setCommand();
    }

    private function setCommand(
    ): void {
      $this->setCommandIndexGroups();
      $this->setCommandCreateds();
      $this->setCommandUpdates();
      $this->setCommandDrops();
    }

    private function setCommandIndexGroups(
    ): void {
      $this->indexGroups = Util::Mapper(
        Util::Reduce($this->designs->indexes, [], 
          function(array $curr, object $item){
            $curr[$item->args][] = $item->name;
            return $curr;
          }
        ), fn(array $groups) => (object)(
          [ "columns" => EntityUtil::joinColumns($groups),
            "name" => sprintf( "INDEX_%s", Util::join( "", 
              Util::Mapper( $groups, fn( string $key ) => (
                ucfirst( $key )
              ))
            ))
          ]
        )
      );
    }

    private function setCommandCreateds(
    ): void {
      if( $this->persisteds->hasIndexes() === false ){
        if( $this->designs->hasIndexes() === true ){
          Util::Mapper( $this->indexGroups, function( object $index ){
            $this->command[] = "create index {$index->name} on {$this->designs->getEntity()} ({$index->columns});";
            $this->message[] = "Index {$index->name} added with successfully to {$this->designs->getEntity()}";      
          });
        }
      }
    }

    private function setCommandUpdates(
    ): void {
      if( $this->persisteds->hasIndexes() === true ){
        if( $this->designs->hasIndexes() === true ){
          Util::Mapper( $this->indexGroups, function(object $index){
            if( in_array( $index->name, $this->persisteds->indexes ) === false) {
              $this->command[] = "create index {$index->name} on {$this->designs->getEntity()} ({$index->columns});";
              $this->message[] = "Index {$index->name} added with successfully to {$this->designs->getEntity()}";      
            }
          });
        }
      }
    }

    private function setCommandDrops(
    ): void {
      if( $this->persisteds->hasIndexes() === true ){
        Util::Mapper( $this->persisteds->indexes, function( string $index ){
          if ( in_array( $index, Util::Mapper( $this->indexGroups, fn( object $index) => $index->name )) === false ){
            $this->command[] = "alter table {$this->designs->getEntity()} drop index {$index};";
            $this->message[] = "Index {$index} drop with successfully to {$this->designs->getEntity()}";      
          }
        });
      }      
    }
  }
}