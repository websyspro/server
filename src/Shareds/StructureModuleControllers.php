<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Server\Decorations\Module;

class StructureModuleControllers
{
  public StructureControllers $structureControllers;

  public function __construct(
    public string $module
  ){
    $this->startProps();
  }

  private function startProps(
  ): void {
    Reflect::InstancesFromAttributes(
      $this->module
    )->mapper(
      function(Module $module){
        $this->structureControllers = new StructureControllers(
          DataList::create( $module->Controllers )
        );
      }
    );
  }
}