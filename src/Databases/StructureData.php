<?php

namespace Websyspro\Server\Databases
{
  use ReflectionAttribute;
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Databases\Commands\AutoIncrementCreate;
  use Websyspro\Server\Databases\Commands\ColumnsCreate;
  use Websyspro\Server\Databases\Commands\EntityCreate;
  use Websyspro\Server\Databases\Commands\ForeignKeyCreate;
  use Websyspro\Server\Databases\Commands\IndexCreate;
  use Websyspro\Server\Databases\Commands\PrimaryKeyCreate;
  use Websyspro\Server\Databases\Commands\UniqueCreate;
  use Websyspro\Server\Entitys\Commons\EntityUtil;
  use Websyspro\Server\Entitys\StructureDesign;
  use Websyspro\Server\Entitys\StructurePersistedResult;
  use Websyspro\Server\Entitys\StructurePersisteds;
  use Websyspro\Server\Entitys\StructureDesignResult;
  use Websyspro\Server\Reflections\ReflectUtils;

  class StructureData
  {
    public array $databaseList = [];

    public function __construct(
      private readonly array $databases
    ){
      $this->init();
      $this->updateEntitys();
      $this->savingEntitys();
    }

    public function init(
    ): void {
      Util::Mapper( $this->databases, fn( string $databaseClass ) => (
        $this->databaseList[ $databaseClass ][ "entitys" ] = (
          $this->appendStructureDesign( $databaseClass )
        )
      ));
    }

    public function appendStructureDesign(
      string $databaseClass
    ): array {
      $getAttributes = (
        ReflectUtils::getReflectClass(
          $databaseClass
        )->getAttributes()
      );

      [ $entityList ] = Util::Mapper(
        $getAttributes, fn( ReflectionAttribute $reflectionAttribute ) => (
          Util::Mapper(
            $reflectionAttribute->newInstance()->get(), fn(string $entityClasse ) => (
              [ $entityClasse => (
                [ "persisteds" => StructurePersisteds::set( $entityClasse, $databaseClass ),
                  "designs" => StructureDesign::set( $entityClasse, $databaseClass )]
              )]
            )
          )
        )
      );

      return $entityList;
    }

    public function updateEntitys(
    ): void {
      Util::Mapper( $this->databases, fn( string $databaseClass ) => (
        $this->updateStructure( 
          $this->databaseList[ $databaseClass ][ "entitys" ]
        )
      ));
    }

    public function updateStructure(
      array $entityList = [],
    ): void {
      Util::Mapper( $entityList, (
        fn( array $structure ) => (
          $this->updateStructureList(
            array_values( $structure )
          )
        )
      ));
    }

    public function updateStructureList(
      array $structureList = []
    ): void {
      [ $persisteds, $designs ] = array_values(
        array_shift( $structureList )
      );

      $this->updateStructureListEntity( $persisteds, $designs );
      $this->updateStructureListEntityColumns( $persisteds, $designs );
      $this->updateStructureListEntityColumnsPrimaryKey( $persisteds, $designs );
      $this->updateStructureListEntityColumnsAutoIncrement( $persisteds, $designs );
      $this->updateStructureListEntityColumnsUniques( $persisteds, $designs );
      $this->updateStructureListEntityColumnsIndexes( $persisteds, $designs );
      $this->updateStructureListEntityForeignKey( $persisteds, $designs );
    }

    public function updateStructureListEntity(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      if( $persisteds->hasColumns() === false ){
        $designs->scripts[] = new EntityCreate(
          $designs
        );
      }
    }

    public function updateStructureListEntityColumnsPrimaryKey(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      $designs->scripts[] = new PrimaryKeyCreate(
        $persisteds, $designs
      );
    }

    public function updateStructureListEntityColumns(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      if( $persisteds->hasColumns() === true ){
        $designs->scripts[] = new ColumnsCreate(
          $persisteds, $designs
        );
      }
    }

    public function updateStructureListEntityColumnsAutoIncrement(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      $designs->scripts[] = new AutoIncrementCreate(
        $persisteds, $designs
      );
    }    
    
    public function updateStructureListEntityColumnsUniques(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      $designs->scripts[] = new UniqueCreate(
        $persisteds, $designs
      );
    }
    
    public function updateStructureListEntityColumnsIndexes(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      $designs->scripts[] = new IndexCreate(
        $persisteds, $designs
      );
    }

    public function updateStructureListEntityForeignKey(
      StructurePersistedResult $persisteds, 
      StructureDesignResult $designs
    ): void {
      $designs->scripts[] = new ForeignKeyCreate(
        $persisteds, $designs
      );
    }
    
    private function savingEntitys(
    ): void {
      Util::Mapper( $this->databaseList, (
        function( array $entitysList ){
          $this->savingEntitysList( 1, $entitysList );
          $this->savingEntitysList( 2, $entitysList );
        }
      ));
    }

    private function savingEntitysList(
      int $type,
      array $entitysList = [],
    ): void {
      [ $entitys ] = (
        array_values(
          $entitysList
        )
      );

      Util::Mapper( $entitys, fn( array $entity ) => (
        Util::Mapper( $entity, fn( array $structureList ) => (
          $this->savingEntityDesigns(  $type, $structureList )
        ))
      ));
    }

    private function savingEntityDesigns(
      int $type,
      array $structureList = []
    ): void {
      [ $_, $designs ] = (
        array_values(
          $structureList
        )
      );

      $database = EntityUtil::DatabaseParse(
        $designs->database
      );

      $scripts = Util::Filter(
        $designs->scripts, (
          fn( object $script ) => $script->type === $type
        ) 
      );

      Util::Mapper( $scripts, fn( object $script ) => (
        Util::Mapper( $script->command, fn( string $command, int $commandOrder ) => (
          $this->connectExecuteCommand( 
            $database,
            $command,
            $script->message[ $commandOrder ]
          )
        ))
      ));
    }

    private function connectExecuteCommand(
      string $database,
      string $command,
      string $mensage
    ): void {
      Connect::on( 
        $database
      )->query( $command );
    }
  }
}