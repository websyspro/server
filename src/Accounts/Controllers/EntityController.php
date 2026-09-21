<?php

namespace Websyspro\Server\Accounts\Controllers;

use ReflectionClass;
use Websyspro\Entity\Schemas\SqlServerEntityStructure;
use Websyspro\Entity\Schemas\SqlServerEntityStructurePersisteds;
use Websyspro\Entity\Schemas\SqlServerSchemaManager;
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
    $structureEntity = new SqlServerEntityStructure(
      new ReflectionClass(
        TestEntity::class
      )
    );

    return $structureEntity;
  }
  
  #[Get("/async")]
  public function async(
  ): mixed {
    // $schemaManager = new MySqlSchemaManager(
    //   new MySqlEntityStructure(
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

    $schemaManager = new SqlServerSchemaManager(
      new SqlServerEntityStructure(
        new ReflectionClass(
          TestEntity::class
        )
      ),
      new SqlServerEntityStructurePersisteds(
        new ReflectionClass(
          TestEntity::class
        )
      ) 
    );    

    $schemaManager->asyncEntity();
    return $schemaManager;
  }  
}