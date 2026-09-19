<?php

namespace Websyspro\Server\Accounts\Controllers;

use ReflectionClass;
use Websyspro\Entity\Schemas\MySqlEntityStructure;
use Websyspro\Entity\Schemas\MySqlEntityStructurePersisteds;
use Websyspro\Entity\Schemas\MySqlSchemaManager;
use Websyspro\Server\Accounts\Entities\TestEntity;
use Websyspro\WorkerServer\Decorators\Body;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;



#[Controller("entity")]
class EntityController
{
  #[Get("/")]
  public function index(
    #[Body()] object $body
  ): mixed {
    // $structureEntity = new StructureEntity(
    //   new ReflectionClass(
    //     TestEntity::class
    //   )
    // );

    // $structureEntity->asyncEntity();
    // return $structureEntity;
    return [
      "success" => true,
      "content" => $body
    ];
  }
  
  #[Get("/async")]
  public function async(
  ): mixed {
    $schemaManager = new MySqlSchemaManager(
      new MySqlEntityStructure(
        new ReflectionClass(
          TestEntity::class
        )
      ),
      new MySqlEntityStructurePersisteds(
        new ReflectionClass(
          TestEntity::class
        )
      ) 
    );

    $schemaManager->asyncEntity();
    return $schemaManager;
  }  
}