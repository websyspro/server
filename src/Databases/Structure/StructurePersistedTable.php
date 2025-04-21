<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Connect\DB;
use Websyspro\Server\Databases\Structure\Table\ColumnsPersisteds;
use Websyspro\Server\Databases\Structure\Table\ForeignKeysPersisteds;
use Websyspro\Server\Databases\Structure\Table\GenerationsPersisteds;
use Websyspro\Server\Databases\Structure\Table\PrimaryKeysPersisteds;
use Websyspro\Server\Databases\Structure\Table\RequiredsPersisteds;
use Websyspro\Server\Databases\Structure\Table\StatisticsPersisteds;
use Websyspro\Server\Databases\Structure\Table\UniquesPersisteds;

class StructurePersistedTable
{
  public ColumnsPersisteds $columns;
  public RequiredsPersisteds $requireds;
  public PrimaryKeysPersisteds $primaryKeys;
  public GenerationsPersisteds $generations;
  public UniquesPersisteds $uniques;
  public StatisticsPersisteds $statistics;
  public ForeignKeysPersisteds $foreignKeys;

  public string $database;
  public string $table;

  public function __construct(
    public string $entity
  ){ 
    $this->setDatabase();
    $this->setTable();
    $this->setColumns();
    $this->setRequireds();
    $this->setPrimaryKeys();
    $this->setGenerations();
    $this->setUniques();
    $this->setStatistics();
    $this->setForeignKeys();
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

  private function setColumns(
  ): void {
    $this->columns = new ColumnsPersisteds(
      $this->entity, $this->database
    );
  }

  private function setRequireds(
  ): void {
    $this->requireds = new RequiredsPersisteds(
      $this->entity, $this->database
    );
  }

  private function setPrimaryKeys(
  ): void {
    $this->primaryKeys = new PrimaryKeysPersisteds(
      $this->entity, $this->database
    );
  }

  private function setGenerations(
  ): void {
    $this->generations = new GenerationsPersisteds(
      $this->entity, $this->database
    );
  }

  private function setUniques(
  ): void {
    $this->uniques = new UniquesPersisteds(
      $this->entity, $this->database
    );
  }

  private function setStatistics(
  ): void {
    $this->statistics = new StatisticsPersisteds(
      $this->entity, $this->database
    );
  }

  private function setForeignKeys(
  ): void {
    $this->foreignKeys = new ForeignKeysPersisteds(
      $this->entity, $this->database
    );
  }
}