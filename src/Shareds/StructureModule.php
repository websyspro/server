<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Commons\Util;
use Websyspro\Server\Applications\Shops\Entitys\BoxEntity;
use Websyspro\Server\Decorations\Module;

class StructureModule
{
  public DataList $entitysFromModule;

  public function __construct(
    private DataList $modules
  ){
    $this->entitysFromModule = (
      $this->modules->Copy()->Mapper(
        fn(string $module) => (
          Reflect::InstancesFromAttributes($module)->Mapper(
            fn(Module $item) => DataList::Create($item->Entitys)->Mapper(
              fn(string $entity) => new IModuleEntity(
                entity: $entity, module: $module
              )
            )
          )
        )
      )
    );

    $this->entitysFromModule->Reduce(
      [], function(array $curr, DataList $entitysFromModule){
        $curr = array_merge($curr, $entitysFromModule->First()->All());
        return $curr;
      }
    );
  } 
}