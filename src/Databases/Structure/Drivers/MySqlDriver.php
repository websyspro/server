<?php

namespace Websyspro\Server\Databases\Structure\Drivers;

use Websyspro\Server\Commons\Log;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\LogType;
use Websyspro\Server\Enums\Entitys\CommandType;
use Websyspro\Server\Interfaces\Entitys\IForeignKey;
use Websyspro\Server\Databases\Connect\DB;
use Websyspro\Server\Databases\Structure\Drivers\Scripts\MySQLScript;
use Websyspro\Server\Databases\Structure\StructureCommand;
use Websyspro\Server\Databases\Structure\StructureEntity;
use Websyspro\Server\Databases\Structure\StructureDesignTable;
use Websyspro\Server\Databases\Structure\StructurePersistedTable;
use Websyspro\Server\Enums\Entitys\ColumnType;

class MySqlDriver
{
  private MySQLScript | null $mysqlScript = null;
  private array $properties = [];

  public array $commands = [];
  public array $uniques = [];
  public array $statistics = [];
  public array $foreignKeys = [];


  public function __construct(
    public array $entitys,
    public string $database,
  ){
    $this->setMapperColumns();
    $this->setMapperStart();
  }

  public function getData(
  ): string | null {
    [ $structureEntity ] = (
      $this->entitys
    );

    if( $structureEntity instanceof StructureEntity ){
      return util::parseDatabase(
        $structureEntity->design->database
      );
    }

    return null;
  }  

  public function setColumnParseType(
    array $items = []  
  ): array {
    return Util::Mapper(
      $items, fn( object $object ) => (
        match( $object->type ){
          ColumnType::Decimal->value => "decimal({$object->args})",
          ColumnType::Text->value => "varchar({$object->args})",
          ColumnType::Datetime->value => "datetime",
          ColumnType::Number->value => "bigint",
          ColumnType::Flag->value => "smallint",
          ColumnType::Time->value => "time",
          ColumnType::Date->value => "date",
        }
      )
    );
  }

  public function setMapperColumns(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $entity ) => (
        $entity->design->columns->items = $this->setColumnParseType(
          $entity->design->columns->items
        )
      )
    );
  }  

  public function setMapperStart(
  ): void {
    $this->setMapperEntityPersisteds();
    $this->setMapperEntityUpdateds();
    $this->setMapperEntityCommands();
  }

  private function setMapperEntityPersisteds(
  ): void {
    $this->setMapperEntityPersistedsData();
    $this->setMapperEntityPersistedsColumns();
    $this->setMapperEntityPersistedsRequireds();
    $this->setMapperEntityPersistedsPrimaryKeys();
    $this->setMapperEntityPersistedsGenerations();
    $this->setMapperEntityPersistedsUniques();
    $this->setMapperEntityPersistedsStatistics();
    $this->setMapperEntityPersistedsForeignKeys();
  }

  private function getQuery(
    string $sql
  ): array {
    return DB::set(
      $this->getData()
    )->query( $sql )->all();
  }

  private function setMapperEntityPersistedsData(
  ): void {
    $this->mysqlScript = (
      new MySQLScript(
        $this->getData()
      )
    );

    [ $this->properties, 
      $this->uniques, 
      $this->statistics, 
      $this->foreignKeys ] = [
      $this->getQuery( $this->mysqlScript->getProperties()),
      $this->getQuery( $this->mysqlScript->getUniques()),
      $this->getQuery( $this->mysqlScript->getStatistics()),
      $this->getQuery( $this->mysqlScript->getForeignKeys())
    ];
  }

  private function setMapperEntityPersistedsColumns(
  ): void {
    Util::Mapper(
      $this->entitys, fn(StructureEntity $structureEntity) => (
        Util::Mapper(
          Util::Filter($this->properties, fn(object $property) => (
            $property->entity === $structureEntity->design->getEntity()
          )), fn( object $property ) => (
            $structureEntity->persisted->columns->add(
              $property->name, $property->type
            )
          )
        )
      )
    );    
  }

  private function setMapperEntityPersistedsDefault(
    string $propertyName,
    string $persistedPropertyName
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $structureEntity ) => (
        Util::Mapper(
          Util::Filter( $this->properties, fn( object $property ) => (
            $property->{$propertyName} === 1 && (
              $property->entity === $structureEntity->design->getEntity()
            )
          )), fn( object $property ) => (
            $structureEntity->persisted->{
              $persistedPropertyName
            }->add( $property->name )
          )
        )
      )
    );    
  }

  private function setMapperEntityPersistedsRequireds(
  ): void {
    $this->setMapperEntityPersistedsDefault(
      "required", "requireds"
    );
  }

  private function setMapperEntityPersistedsPrimaryKeys(
  ): void {
    $this->setMapperEntityPersistedsDefault(
      "primaryKey", "primaryKeys"
    );
  }  

  private function setMapperEntityPersistedsGenerations(
  ): void {
    $this->setMapperEntityPersistedsDefault(
      "generation", "generations"
    );
  }
  
  private function setMapperEntityPersistedsUniques(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $structureEntity ) => (
        Util::Mapper(
          Util::Filter( $this->uniques, fn( object $unique ) => (
            $unique->entity === $structureEntity->design->getEntity()
          )), fn( object $unique ) => (
            $structureEntity->persisted->uniques->add( $unique->name )
          )
        )
      )
    ); 
  }

  private function setMapperEntityPersistedsStatistics(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $structureEntity ) => (
        Util::Mapper(
          Util::Filter( $this->statistics, fn( object $statistic ) => (
            $statistic->entity === $structureEntity->design->getEntity()
          )), fn( object $statistic ) => (
            $structureEntity->persisted->statistics->add( $statistic->name )
          )
        )
      )
    ); 
  }
  
  private function setMapperEntityPersistedsForeignKeys(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $structureEntity ) => (
        Util::Mapper(
          Util::Filter( $this->foreignKeys, fn( object $foreignKey ) => (
            $foreignKey->entity === $structureEntity->design->getEntity()
          )), fn( object $foreignKey ) => (
            $structureEntity->persisted->foreignKeys->add( $foreignKey->name )
          )
        )
      )
    );
  }

  private function setMapperEntityUpdateds(
  ): void {
    Util::Mapper(
      $this->entitys, fn( StructureEntity $structureEntity ) => (
        $this->setMapperEntityUpdatedsSteps( 
          $structureEntity->persisted,
          $structureEntity->design
        )
      )
    );
  }

  private function setMapperEntityUpdatedsSteps(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    $this->setMapperEntityDriver( $persisted );
    $this->setMapperEntityUpdatedsColumns( $persisted, $design );
    $this->setMapperEntityUpdatedsPrimaryKeys( $persisted, $design );
    $this->setMapperEntityUpdatedsGenerations( $persisted, $design );
    $this->setMapperEntityUpdatedsUniques( $persisted, $design );
    $this->setMapperEntityUpdatedsStatistics( $persisted, $design );
    $this->setMapperEntityUpdatedsForeignKeys( $persisted, $design );
  }

  private function setMapperEntityDriver(
    StructurePersistedTable $persisted
  ): void {
    $this->mysqlScript->setEntity(
      $persisted->getEntity()
    );
  }

  private function setMapperEntityUpdatedsColumns(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    if( $persisted->columns->exists() === false ){
      $this->setMapperEntityUpdatedsColumnsCreateds( $design );
    } else {
      $this->setMapperEntityUpdatedsColumnsAdds( $persisted,  $design );
      $this->setMapperEntityUpdatedsColumnsModifys( $persisted,  $design );
      $this->setMapperEntityUpdatedsColumnsDrops( $persisted,  $design );
    }
  }

  private function setMapperEntityUpdatedsColumnsCreateds(
    StructureDesignTable $design
  ): void {
    $this->commands[] = $this->mysqlScript->entityAdd(
      Util::Mapper( $design->columns->items, fn(string $type, string $name) => (
        "{$name} {$type} {$design->requireds->getRequired( $name )}"
      ))
    );
  }

  private function setMapperEntityUpdatedsColumnsAdds(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    Util::Mapper(
      Util::FilterByKey(
        $design->columns->items, fn( string $name ) => (
          $persisted->columns->hasColumn($name) === false
        )
      ), fn(string $type, string $name) => (
        $this->commands[] = $this->mysqlScript->columnAdd(
          $name, $type, $design->requireds->getRequired($name), $design->columns->before($name)
        )
      ) 
    );
  }

  private function setMapperEntityUpdatedsColumnsModifys(
    StructurePersistedTable $persisted,
    StructureDesignTable $design 
  ): void {
    Util::Mapper(
      Util::Filter(
        $design->columns->items, fn(string $type, string $name) => (
          $persisted->columns->hasColumn( $name ) && ( $persisted->columns->items[ $name ] !== $type ||
            $persisted->requireds->isRequired( $name ) !== $design->requireds->isRequired( $name )
          )
        )
      ), fn(string $type, string $name) => (
        $this->commands[] = $this->mysqlScript->columnModifys(
          $name, $type, $design->requireds->getRequired($name)
        )
      )
    );    
  }

  private function setMapperEntityUpdatedsColumnsDrops(
    StructurePersistedTable $persisted,
    StructureDesignTable $design     
  ): void {
    Util::FilterByKey(
      Util::FilterByKey(
        $persisted->columns->items, fn(string $name) => (
          $design->columns->hasColumn( $name ) === false
        )
      ), fn(string $name) => (
        $this->commands[] = $this->mysqlScript->columnDrop( $name )
      )
    );
  }

  private function setMapperEntityUpdatedsPrimaryKeys(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    $this->setMapperEntityUpdatedsPrimaryKeysUpdate( $persisted, $design );
    $this->setMapperEntityUpdatedsPrimaryKeysDrop( $persisted, $design );
  }

  private function setMapperEntityUpdatedsPrimaryKeysUpdate(
    StructurePersistedTable $persisted,
    StructureDesignTable $design     
  ): void {
    if($design->primaryKeys->exists() === true){
      $equalsPrimaryKeys = Util::arrayEquais(
        $persisted->primaryKeys->items,
        $design->primaryKeys->items
      );

      if( $equalsPrimaryKeys === false ){
        if( $persisted->primaryKeys->exists() ){
          $this->commands[] = $this->mysqlScript->primaryKeyDrop();
        }

        $this->commands[] = $this->mysqlScript->primaryKeyAdd(
          Util::joinColumns( $design->primaryKeys->items )
        );
      }
    }    
  }

  private function setMapperEntityUpdatedsPrimaryKeysDrop(
    StructurePersistedTable $persisted,
    StructureDesignTable $design     
  ): void {
    if( $persisted->primaryKeys->exists() === true ){
      if( $design->primaryKeys->exists() === false ){
        $this->commands[] = $this->mysqlScript->primaryKeyDrop();
      }
    }
  }

  private function setMapperEntityUpdatedsGenerations(
    StructurePersistedTable $persisted,
    StructureDesignTable $design     
  ): void {
    $this->setMapperEntityUpdatedsGenerationsAdd( $persisted, $design );
    $this->setMapperEntityUpdatedsGenerationsUpdate( $persisted, $design );
    $this->setMapperEntityUpdatedsGenerationsDrop( $persisted, $design );
  }

  private function setMapperEntityUpdatedsGenerationsAdd(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if($persisted->generations->exists() === false){
      if($design->generations->exists() === true){
        Util::Mapper(
          Util::FilterByKey(
            $design->columns->items, fn( string $name ) => (
              in_array($name, $design->generations->items)
            )
          ), fn( string $type, string $name ) => (
            $this->commands[] = $this->mysqlScript->generationsAdd(
              $name, $type, $design->requireds->getRequired($name)
            )
          )
        );
      }
    }
  }

  private function setMapperEntityUpdatedsGenerationsUpdate(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if( $persisted->generations->exists() === true ){
      if( $design->generations->exists() === true ){
        if( Util::arrayEquais( 
          $persisted->generations->items,
          $design->generations->items
          ) === false
        ){
          $this->commands[] = (
            $this->mysqlScript->generationsUpdate(
              array_merge(
                Util::Mapper(
                  Util::FilterByKey(
                    $persisted->columns->items, fn(string $name) => (
                      $persisted->generations->hasGeneration( $name )
                    )
                  ), fn( string $type, string $name ) => (
                    $this->mysqlScript->generationsTextModify(
                      $name, $type, $persisted->requireds->getRequired( $name )
                    )
                  ) 
                ),
                Util::Mapper(
                  Util::FilterByKey(
                    $design->columns->items, fn(string $name) => (
                      $design->generations->hasGeneration( $name )
                    )
                  ), fn( string $type, string $name ) => (
                    $this->mysqlScript->generationsTextAdd(
                      $name, $type, $design->requireds->getRequired( $name )
                    )
                  ) 
                )
              )
            )
          );
        }
      }
    }    
  }

  private function setMapperEntityUpdatedsGenerationsDrop(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if( $persisted->generations->exists() === true ){
      if( $design->generations->exists() === false ){
        Util::Mapper(
          Util::FilterByKey(
            $design->columns->items, fn( string $name ) => (
              $persisted->generations->hasGeneration( $name )
            )
          ), fn( string $type, string $name ) => (
            $this->commands[] = $this->mysqlScript->generationsDrop(
              $name, $type, $design->requireds->getRequired( $name )
            )
          )  
        );
      }
    }
  }

  private function setMapperEntityUpdatedsUniques(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    $this->setMapperEntityUpdatedsUniquesAdd( $persisted, $design );
    $this->setMapperEntityUpdatedsUniquesUpdate( $persisted, $design );
    $this->setMapperEntityUpdatedsUniquesDrop( $persisted, $design );
  }

  private function setMapperEntityUpdatedsUniquesAdd(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if($persisted->uniques->exists() === false){
      if($design->uniques->exists() === true){
        Util::Mapper( $design->uniques->items, fn( string $columns, string $name ) => (
          $this->commands[] = $this->mysqlScript->uniqueAddOrUpdate( $name, $columns )
        ));
      }
    }
  }

  private function setMapperEntityUpdatedsUniquesUpdate(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if($persisted->uniques->exists() === true){
      if($design->uniques->exists() === true){
        Util::Mapper( $design->uniques->items, fn( string $columns, string $name ) => (
          in_array( $name, $persisted->uniques->items )  ? [] : (
            $this->commands[] = $this->mysqlScript->uniqueAddOrUpdate( $name, $columns )
          )
        ));
      }
    }    
  }
  
  private function setMapperEntityUpdatedsUniquesDrop(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if($persisted->uniques->exists() === true){
      Util::Mapper( $persisted->uniques->items, fn( string $name ) => (
        $design->uniques->hasUnique( $name ) ? [] : (
          $this->commands[] = $this->mysqlScript->uniqueDrop( $name )
        )
      ));
    }
  }

  private function setMapperEntityUpdatedsStatistics(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    $this->setMapperEntityUpdatedsStatisticsAdd( $persisted, $design );
    $this->setMapperEntityUpdatedsStatisticsUpdate( $persisted, $design );
    $this->setMapperEntityUpdatedsStatisticsDrop( $persisted, $design );
  }

  private function setMapperEntityUpdatedsStatisticsAdd(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    if($persisted->statistics->exists() === false){
      if($design->statistics->exists() === true){
        Util::Mapper( $design->statistics->items, fn( string $columns, string $name ) => (
          $this->commands[] = $this->mysqlScript->statisticsAddOrUpdate( $name, $columns )
        ));
      }
    }
  }

  private function setMapperEntityUpdatedsStatisticsUpdate(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    if($persisted->statistics->exists() === true){
      if($design->statistics->exists() === true){
        Util::Mapper( $design->statistics->items, fn( string $columns, string $name ) => (
          in_array( $name, $persisted->statistics->items ) ? [] : (
            $this->commands[] = $this->mysqlScript->statisticsAddOrUpdate( $name, $columns )
          )
        ));
      }
    }
  }
  
  private function setMapperEntityUpdatedsStatisticsDrop(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    if( $persisted->statistics->exists() === true ){
      Util::Mapper( $persisted->statistics->items, fn( string $name ) => (
        $design->statistics->hasStatistic( $name ) ? [] : (
          $this->commands[] = $this->mysqlScript->statisticsDrop( $name )
        )
      ));      
    }
  }

  private function setMapperEntityUpdatedsForeignKeys(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    $this->setMapperEntityUpdatedsForeignKeysAdd( $persisted, $design );
    $this->setMapperEntityUpdatedsForeignKeysUpdate( $persisted, $design );
    $this->setMapperEntityUpdatedsForeignKeysDrop( $persisted, $design );
  }

  private function setMapperEntityUpdatedsForeignKeysAdd(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if( $persisted->foreignKeys->exists() === false ){
      if( $design->foreignKeys->exists() === true ){
        Util::Mapper($design->foreignKeys->items, fn( IForeignKey $iforeign ) => (
          $this->commands[] = $this->mysqlScript->foreignKeyAdd( $iforeign )
        ));
      }
    }
  }

  private function setMapperEntityUpdatedsForeignKeysUpdate(
    StructurePersistedTable $persisted,
    StructureDesignTable $design
  ): void {
    if( $persisted->foreignKeys->exists() === true ){
      if( $design->foreignKeys->exists() === true ){
        Util::Mapper( $design->foreignKeys->items, fn( IForeignKey $iforeign ) => (
          in_array( $iforeign->name, $persisted->foreignKeys->names()) ? [] : (
            $this->commands[] = $this->mysqlScript->foreignKeyAdd( $iforeign )
          )
        ));
      }
    }
  }

  private function setMapperEntityUpdatedsForeignKeysDropItems(
    string $name
  ): void {
    $this->commands[] = $this->mysqlScript->foreignKeyDrop( $name );
    $this->commands[] = $this->mysqlScript->foreignKeyDropIndex( $name );
  }

  private function setMapperEntityUpdatedsForeignKeysDrop(
    StructurePersistedTable $persisted,
    StructureDesignTable $design    
  ): void {
    if( $persisted->foreignKeys->exists() === true ){
      Util::Mapper( $persisted->foreignKeys->items, fn( string $name ) => (
        in_array( $name, $design->foreignKeys->names()) ? [] : (
          $this->setMapperEntityUpdatedsForeignKeysDropItems( $name )
        ) 
      ));
    }
  }

  private function setMapperCommandType(
    CommandType $commandType
  ): array {
    return (
      Util::Filter( $this->commands, (
        fn( StructureCommand $sc ) => (
          $sc->commandType === $commandType
        ) 
      ))
    );
  }

  private function setMapperEntityCommands(
  ): void {
    if( sizeof( $this->commands ) !== 0 ){
      Log::Message( LogType::Database, (
        sprintf( "Mapper Database [%s]", (
          Util::getData( $this->database )
        ))
      ));
    }

    Util::Mapper(
      array_merge(
        $this->setMapperCommandType( CommandType::Entitys ),
        $this->setMapperCommandType( CommandType::Columns ),
        $this->setMapperCommandType( CommandType::PrimaryKeys ),
        $this->setMapperCommandType( CommandType::Generationns ),
        $this->setMapperCommandType( CommandType::Uniques ),
        $this->setMapperCommandType( CommandType::Statistics ),
        $this->setMapperCommandType( CommandType::ForeignKeys )
      ), (
      fn( StructureCommand $sc ) => (
        $this->setMapperEntityCommandList( $sc )
      )
    ));
  }

  private function setMapperEntityCommandList(
    StructureCommand $command
  ): void {
    [ $hasSuccess ] = (
      Util::Mapper( $command->scripts, (
        fn( string $script ) => (
          DB::set( $this->getData())->execute( $script )
        )
      ))
    );

    if( $hasSuccess === true ){
      Log::Message( LogType::Database, $command->message );
    }
  }
}