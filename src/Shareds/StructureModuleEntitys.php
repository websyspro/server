<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Server\Decorations\Module;

class StructureModuleEntitys
{
  public StructureEntitys $structureEntitys;

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
        $this->structureEntitys = new StructureEntitys(
          DataList::create($module->Entitys), $this->module
        );
      }
    );
  }
}