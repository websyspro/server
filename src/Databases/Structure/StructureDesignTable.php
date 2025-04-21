<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Connect\DB;
use Websyspro\Server\Databases\Structure\Table\Columns;
use Websyspro\Server\Databases\Structure\Table\Events;
use Websyspro\Server\Databases\Structure\Table\ForeignKeys;
use Websyspro\Server\Databases\Structure\Table\Generations;
use Websyspro\Server\Databases\Structure\Table\PrimaryKeys;
use Websyspro\Server\Databases\Structure\Table\Requireds;
use Websyspro\Server\Databases\Structure\Table\Statistics;
use Websyspro\Server\Databases\Structure\Table\Uniques;

class StructureDesignTable
{
  public Reflect $reflect;
  public Columns $columns;
  public Requireds $requireds;
  public PrimaryKeys $primaryKeys;
  public Generations $generations;
  public Uniques $uniques;
  public Statistics $statistics;
  public ForeignKeys $foreignKeys;
  public Events $events;

  public string $database;
  public string $table;

  public function __construct(
    public string | null $entity = null
  ){
    $this->setDatabase();
    $this->setTable();
    $this->setReflect();
    $this->setColumns();
    $this->setRequireds();
    $this->setPrimaryKeys();
    $this->setGenerations();
    $this->setUniques();
    $this->setStatistics();
    $this->setForeignKeys();
    $this->setEvents();
    $this->setClear();
  }

  public function getEntity(
  ): string {
    return $this->entity;
  }

  public function getTable(
  ): string {
    return $this->table;
  }  

  public function getDatabase(
  ): string {
    return $this->database;
  }
  
  public function getPrimaryKeys(
  ): array {
    return $this->primaryKeys->items;
  }
  
  public function getForeingKeyColumn(
  ): string {
    [ $foreignKey ] = array_keys(
      Util::FilterByKey(
        $this->columns->items, fn( string $key ) => (
          in_array( $key, $this->primaryKeys->items ) &&
          in_array( $key, $this->generations->items )
        )
      )
    );

    return $foreignKey;
  }

  public function getForeingKey(
  ): object | null {
    $foreignKey = $this->getForeingKeyColumn();

    return (object)[
      "entity" => Util::parseEntity( $this->entity ), 
      "entityKey" => $foreignKey
    ];
  }

  private function setDatabase(
  ): void {
    $this->database = sprintf( "%s%s", ...[ 
      DB::set()->getPrefix(), Util::parseDatabase(
        Util::getData( $this->entity )
      )
    ]);
  }

  private function setTable(
  ): void {
    $this->table = Util::parseEntity(
      $this->entity
    );
  }

  private function setReflect(
  ): void {
    $this->reflect = (
      new Reflect(
        $this->entity
      )
    );
  }

  private function setColumns(
  ): void {
    $this->columns = new Columns(
      $this->reflect
    );
  }

  private function setRequireds(
  ): void {
    $this->requireds = new Requireds(
      $this->reflect
    );
  }

  private function setPrimaryKeys(
  ): void {
    $this->primaryKeys = new PrimaryKeys(
      $this->reflect
    );
  }

  private function setGenerations(
  ): void {
    $this->generations = new Generations(
      $this->reflect
    );
  }

  private function setUniques(
  ): void {
    $this->uniques = new Uniques(
      $this->reflect
    );
  }

  private function setStatistics(
  ): void {
    $this->statistics = new Statistics(
      $this->reflect
    );
  }

  private function setForeignKeys(
  ): void {
    $this->foreignKeys = new ForeignKeys(
      $this->reflect
    );
  }

  private function setEvents(
  ): void {
    $this->events = new Events(
      $this->reflect
    );
  }

  private function setClear(
  ): void {
    unset($this->reflect);
  }
}