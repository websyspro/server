<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\Commons\EntityUtil;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Entitys\StructurePersistedResult;

  class UniqueCreate 
  {
    public int $type = 1;
    public array $command = [];
    public array $message = [];

    private array $uniqueGroups = [];

    public function __construct(
      private readonly StructurePersistedResult $persisteds,
      private readonly StructureDesignResult $designs
    ){ 
      $this->setCommand();
    }

    private function setCommand(
    ): void {
      $this->setCommandUniqueGroups();
      $this->setCommandCreateds();
      $this->setCommandUpdates();
      $this->setCommandDrops();
    }

    private function setCommandUniqueGroups(
    ): void {
      $this->uniqueGroups = Util::Mapper(
        Util::Reduce($this->designs->uniques, [], 
          function(array $curr, object $item){
            $curr[$item->args][] = $item->name;
            return $curr;
          }
        ), fn(array $groups) => (object)(
          [ "columns" => EntityUtil::joinColumns($groups),
            "name" => sprintf( "UNIQUE_%s", Util::join( "", 
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
      if( $this->persisteds->hasUniques() === false ){
        if( $this->designs->hasUniques() === true ){
          Util::Mapper( $this->uniqueGroups, function(object $unique){
            $this->command[] = "alter table {$this->designs->getEntity()} add constraint {$unique->name} unique ({$unique->columns});";
            $this->message[] = "Constraint unique {$unique->name} added with successfully to {$this->designs->getEntity()}";      
          });
        }
      }
    }

    private function setCommandUpdates(
    ): void {
      if( $this->persisteds->hasUniques() === true ){
        if( $this->designs->hasUniques() === true ){
          Util::Mapper( $this->uniqueGroups, function(object $unique){
            if( in_array( $unique->name, $this->persisteds->uniques ) === false) {
              $this->command[] = "alter table {$this->designs->getEntity()} add constraint {$unique->name} unique ({$unique->columns});";
              $this->message[] = "Constraint unique {$unique->name} added with successfully to {$this->designs->getEntity()}";      
            }
          });
        }
      }
    }

    private function setCommandDrops(
    ): void {
      if( $this->persisteds->hasUniques() === true ){
        Util::Mapper( $this->persisteds->uniques, function( string $unique ){
          if ( in_array( $unique, Util::Mapper( $this->uniqueGroups, fn( object $unique) => $unique->name )) === false ){
            $this->command[] = "alter table {$this->designs->getEntity()} drop constraint {$unique};";
            $this->message[] = "Constraint unique {$unique} drop with successfully to {$this->designs->getEntity()}";      
          }
        });
      }      
    }
  }
}