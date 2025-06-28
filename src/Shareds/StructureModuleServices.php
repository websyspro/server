<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Server\Decorations\Module;

class StructureModuleServices
{
  public DataList $structureServices;

  public function __construct(
    string $module
  ){
    Reflect::InstancesFromAttributes($module)->Mapper(
      function(Module $module){
        $this->structureServices = DataList::Create(
          $module->Services
        );
      }
    );
  }

  public function getInstance(
    string $class
  ): object {
    $hasMethodConstruct = method_exists(
      $class, "__construct"
    );

    if($hasMethodConstruct === true){
      return InstanceDependences::gets($class);
    } else return new $class;
  }  
}