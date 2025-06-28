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
    $this->InitialProps();
  }

  private function InitialProps(
  ): void {
    Reflect::InstancesFromAttributes(
      $this->module
    )->Mapper(
      function(Module $module){
        $this->structureControllers = new StructureControllers(
          DataList::Create( $module->Controllers )
        );
      }
    );
  }
}