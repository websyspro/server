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
    $this->InitialProps();
  }

  private function InitialProps(
  ): void {
    Reflect::InstancesFromAttributes(
      $this->module
    )->Mapper(
      function(Module $module){
        $this->structureEntitys = new StructureEntitys(
          DataList::Create($module->Entitys), $this->module
        );
      }
    );
  }
}