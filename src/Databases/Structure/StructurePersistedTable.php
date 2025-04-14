<?php

namespace Websyspro\Server\Databases\Structure;

use Websyspro\Server\Databases\Structure\Table\ColumnsPersisteds;
use Websyspro\Server\Databases\Structure\Table\ForeignKeys;
use Websyspro\Server\Databases\Structure\Table\ForeignKeysPersisteds;
use Websyspro\Server\Databases\Structure\Table\Generations;
use Websyspro\Server\Databases\Structure\Table\GenerationsPersisteds;
use Websyspro\Server\Databases\Structure\Table\PrimaryKeysPersisteds;
use Websyspro\Server\Databases\Structure\Table\RequiredsPersisteds;
use Websyspro\Server\Databases\Structure\Table\Statistics;
use Websyspro\Server\Databases\Structure\Table\StatisticsPersisteds;
use Websyspro\Server\Databases\Structure\Table\Uniques;
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

  public function __construct(
    public readonly string $entity,
    public readonly string $databae
  ){
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
    return preg_replace(
      "/Entity$/", "", $this->entity
    );
  }

  private function setColumns(
  ): void {
    $this->columns = new ColumnsPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setRequireds(
  ): void {
    $this->requireds = new RequiredsPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setPrimaryKeys(
  ): void {
    $this->primaryKeys = new PrimaryKeysPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setGenerations(
  ): void {
    $this->generations = new GenerationsPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setUniques(
  ): void {
    $this->uniques = new UniquesPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setStatistics(
  ): void {
    $this->statistics = new StatisticsPersisteds(
      $this->entity, $this->databae
    );
  }

  private function setForeignKeys(
  ): void {
    $this->foreignKeys = new ForeignKeysPersisteds(
      $this->entity, $this->databae
    );
  }
}