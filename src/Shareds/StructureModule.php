<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Server\Decorations\Module;

class StructureModule
{
  public DataList $entitysFromModule;

  public function __construct(
    private DataList $modules
  ){
    $this->entitysFromModule = (
      $this->modules->copy()->mapper(
        fn(string $module) => (
          Reflect::InstancesFromAttributes($module)->mapper(
            function(Module $item) use ($module) {
              return DataList::create($item->Entitys)->mapper(
                fn(string $entity) => new IModuleEntity(
                  entity: $entity, module: $module
                )
              );
            }
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