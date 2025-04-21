<?php

namespace Websyspro\Server\Databases\Entity\Core;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Connect\DB;
use Websyspro\Server\Databases\Connect\InsertBulkList;
use Websyspro\Server\Databases\Connect\UpdateBulkList;
use Websyspro\Server\Databases\Structure\StructureDesignTable;
use Websyspro\Server\Enums\Entitys\ColumnType;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Exceptions\Error;

class Repository
{
  public StructureDesignTable $structureDesignTable;
  
  public function __construct(
    private readonly string $entityClass
  ){
    $this->structureDesignTable = (
      new StructureDesignTable(
        $this->entityClass
      )
    );
  }

  public function getDatabase(
  ): string {
    return $this->structureDesignTable->getDatabase();
  }

  public function getEntity(
  ): string {
    return $this->structureDesignTable->getEntity();
  }  

  public function columnsParseType(
    string $type,
    mixed $value
  ): mixed {
    if( in_array( $type, [ ColumnType::Number->value ])){
      return $value;
    } else 
    if( in_array( $type, [ ColumnType::Date->value, ColumnType::Datetime->value ])){
      $value = (
        Util::quote( ColumnType::Datetime->value 
          ? Util::datetimeParse( $value ) 
          : Util::dateParse( $value )
        )
      );
    } else
    if( in_array( $type, [ ColumnType::Decimal->value ])){
      $value = Util::decimalParse( $value );
    } else
    if( in_array( $type, [ ColumnType::Text->value ])){
      $value = Util::quote(
        addslashes( $value )
      );
    } else
    if( in_array( $type, [ ColumnType::Flag->value ])){
      $value = Util::flagParse( $value );
    }

    return $value;
  }

  public function columnsParse(
    array $data = []
  ): array {
    return Util::Mapper( $data, (
      fn( mixed $value, string $column ) => $this->columnsParseType(
        $this->structureDesignTable->columns->items[ $column ]->type, $value
      )
    ));
  }

  private function foreingKeyColumn(
  ): string {
    return $this->structureDesignTable->getForeingKeyColumn();
  }

  private function events(
  ): array {
    return $this->structureDesignTable->events->items;
  }

  private function requireds(
  ): array {
    return $this->structureDesignTable->requireds->items;
  }

  private function notRequireds(
    array $dataValues = []
  ): array {
    return (
      Util::Mapper(
        $dataValues, (
          fn( mixed $val, string $key ) => (
            in_array($key, $this->requireds()) ? $val : (
              is_null($val) || empty($val) ? "NULL" : $val
            )
          )
        )
      )
    );
  }

  private function createDefaults(
    array $dataValues,
    AttributeType $attributeType,
  ): array {
    $dataValues = (
      array_merge(
        Util::Mapper(
          Util::Filter(
            Util::Mapper(
              $this->events(), fn(array $events) => (
                Util::Filter($events, fn(object $event) => (
                  $event->attributeType === $attributeType
                ))
              )
            ), fn(array $events) => sizeof($events) !== 0 
          ), fn(array $event) => end($event)->get()
        ),
        $this->notRequireds(
          $dataValues
        )
      )
    );

    Util::Filter(
      $dataValues, fn( mixed $value, string $key ) => (
        $value === null && $key !== $this->foreingKeyColumn() && in_array( 
          $key, $this->requireds()
        ) === true ? Error::BadRequest( "Required fields" ) : []
      )
    );

    return $dataValues;
  }

  private function hasPrimaryKeys(
    array $dataArr = []
  ): void {
    Util::Mapper(
      $dataArr, fn( array $dataValues ) => (
        Util::Mapper( $this->structureDesignTable->primaryKeys->items, (
          fn( string $key ) => (
            in_array( $key, array_keys( $dataValues )) === false
              ? Error::BadRequest( "Mandatory primary keys [{$key}]" ) : []
          ) 
        ))
      )
    );
  }

  public function create(
    array $dataArr = []
  ): bool {
    return (
      new InsertBulkList(
        Util::Mapper( $dataArr, (
          fn( array $dataValues ) => (
            $this->columnsParse(
              $this->createDefaults(
                $dataValues, AttributeType::EventBeforeInsert
              )
            )
          ))
        ),
        $this->structureDesignTable->getEntity(),
        $this->structureDesignTable->getDatabase()
      )
    )->execute();
  }

  public function update(
    array $dataArr = []
  ): bool {
    $this->hasPrimaryKeys(
      $dataArr
    );

    return (
      new UpdateBulkList(
        Util::Mapper( $dataArr, (
          fn( array $dataValues ) => (
            $this->columnsParse(
              $this->createDefaults(
                $dataValues, AttributeType::EventBeforeUpdate
              )
            )
          ))
        ),
        $this->structureDesignTable->getPrimaryKeys(),
        $this->structureDesignTable->getEntity(),
        $this->structureDesignTable->getDatabase()
      )
    )->execute();
  }

  public function delete(
    array $dataArr = []
  ): bool {
    $this->hasPrimaryKeys(
      $dataArr
    );

    return (
      new UpdateBulkList(
        Util::Mapper( $dataArr, (
          fn( array $dataValues ) => (
            $this->columnsParse(
              $this->createDefaults(
                $dataValues, AttributeType::EventBeforeDelete
              )
            )
          ))
        ),
        $this->structureDesignTable->getPrimaryKeys(),
        $this->structureDesignTable->getEntity(),
        $this->structureDesignTable->getDatabase()
      )
    )->execute();
  }

  public function count(
  ): int {
    return DB::set($this->getDatabase())->query(
      "select * from {$this->getEntity()}"
    )->count();
  }

  public function all(
  ): array {
    return DB::set($this->getDatabase())->query(
      "select * from {$this->getEntity()}"
    )->all();    
  }
}