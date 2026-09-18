<?php

namespace Websyspro\Server\Accounts\Controllers;

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
    // $schemaManager = new MySqlSchemaManager_(
    //   new MySqlEntityStructure_(
    //     new ReflectionClass(
    //       TestEntity::class
    //     )
    //   ),
    //   new MySqlEntityStructurePersisteds(
    //     new ReflectionClass(
    //       TestEntity::class
    //     )
    //   ) 
    // );

    // $schemaManager->asyncEntity();
    // return $schemaManager;
    return "";
  }  
}