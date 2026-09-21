<?php

namespace Websyspro\Server\Accounts\Controllers;

use ReflectionClass;
use Websyspro\Entity\Schemas\MySqlEntityStructure;
use Websyspro\Entity\Schemas\MySqlEntityStructurePersisteds;
use Websyspro\Entity\Schemas\MySqlSchemaManager;
use Websyspro\Entity\Schemas\PostgresEntityStructure;
use Websyspro\Entity\Schemas\PostgresEntityStructurePersisteds;
use Websyspro\Entity\Schemas\PostgresSchemaManager;
use Websyspro\Entity\Schemas\SqlServerEntityStructure;
use Websyspro\Entity\Schemas\SqlServerEntityStructurePersisteds;
use Websyspro\Entity\Schemas\SqlServerSchemaManager;
use Websyspro\Server\Accounts\Entities\TestEntity;
use Websyspro\WorkerServer\Decorators\Body;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Param;



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
  
  #[Get("/async/:fonte")]
  public function async(
    #[Param("fonte")] string $fonte
  ): mixed {
     $schemaManager = match( $fonte ){
      "mysql" => new MySqlSchemaManager(
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
      ),
      "postgres" => new PostgresSchemaManager(
        new PostgresEntityStructure(
          new ReflectionClass(
            TestEntity::class
          )
        ),
        new PostgresEntityStructurePersisteds(
          new ReflectionClass(
            TestEntity::class
          )
        ) 
      ),
      "sqlserver" => new SqlServerSchemaManager(
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
      )
    };   

    $schemaManager->asyncEntity();
    return $schemaManager;
  }  
}