<?php

namespace Websyspro\Server\Databases\Structure\Drivers;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Enums\Entitys\CommandType;
use Websyspro\Server\Databases\Structure\StructureCommand;
use Websyspro\Server\Interfaces\Entitys\IForeignKey;

class MySqlScripts
{
  public string $entity;

  public function __construct(
    public readonly string $database
  ){}

  public function setEntity(
    string $entity
  ){
    $this->entity = (
      Util::parseEntity(
        $entity
      )
    );
  }  

  public function getProperties(
  ): string {
    return (
      "select information_schema.columns.table_name as entity
             ,information_schema.columns.column_name as name
             ,information_schema.columns.column_type as type
          ,if(information_schema.columns.is_nullable = 'NO', 1, 0) as required
          ,if(information_schema.columns.column_key = 'PRI', 1, 0) as primaryKey
          ,if(information_schema.columns.extra = 'auto_increment', 1, 0) as generation
         from information_schema.columns
        where information_schema.columns.table_schema = '{$this->database}'
     order by information_schema.columns.table_name asc
             ,information_schema.columns.ordinal_position asc"
    );
  }

  public function getUniques(
  ): string {
    return (
      "select information_schema.table_constraints.table_name as entity
             ,information_schema.table_constraints.constraint_name as name
         from information_schema.table_constraints
        where information_schema.table_constraints.table_schema = '{$this->database}'
          and information_schema.table_constraints.constraint_type = 'UNIQUE'"
    );
  }

  public function getStatistics(
  ): string {
    return (
      "select information_schema.statistics.table_name as entity 
             ,information_schema.statistics.index_name as name
         from information_schema.statistics
        where information_schema.statistics.table_schema = '{$this->database}'
          and information_schema.statistics.index_name like 'INDEX_%'
          and information_schema.statistics.non_unique = 1
     group by information_schema.statistics.table_name
             ,information_schema.statistics.index_name"
    );
  }

  public function getForeignKeys(
  ): string {
    return (
      "select information_schema.key_column_usage.table_name as entity
 	           ,information_schema.key_column_usage.constraint_name as name
         from information_schema.key_column_usage 
        where information_schema.key_column_usage.table_schema = '{$this->database}' 
          and information_schema.key_column_usage.referenced_table_name is not null"
    );
  }  

  public function entityAdd(
    array $colums = []
  ): StructureCommand {
    return new StructureCommand([
      sprintf( "create table {$this->entity} (%s) engine=innodb;", Util::JoinColumns( $colums ))
    ], "Table {$this->entity} created with successfully", CommandType::Entitys );
  }

  public function columnAdd(
    string $name,
    string $type,
    string $required,
    string $after
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} add column {$name} {$type} {$required} after {$after}"
    ], "Column {$name} added with successfully to {$this->entity}", CommandType::Columns );
  }

  public function columnModifys(
    string $name,
    string $type,
    string $required
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} modify column {$name} {$type} {$required}"
    ], "Column {$name} modify with successfully to {$this->entity}", CommandType::Columns );
  }

  public function columnDrop(
    string $name
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop {$name}"
    ], "Column {$name} drop with successfully to {$this->entity}", CommandType::Columns );
  }

  public function primaryKeyDrop(
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop primary key"
    ], "Primary key successfully deleted from {$this->entity} table", CommandType::PrimaryKeys );
  }

  public function primaryKeyAdd(
    string $primaryKeyList
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} add primary key ({$primaryKeyList})"
    ], "Primary key ({$primaryKeyList}) create for {$this->entity} table successfully", CommandType::PrimaryKeys );
  }

  public function generationsTextModify(
    string $name,
    string $type,
    string $required
  ): string {
    return "alter table {$this->entity} modify column {$name} {$type} {$required}";
  }

  public function generationsTextAdd(
    string $name,
    string $type,
    string $required
  ): string {
    return "alter table {$this->entity} modify column {$name} {$type} {$required} auto_increment";
  }

  public function generationsAdd(
    string $name,
    string $type,
    string $required
  ): StructureCommand {
    return new StructureCommand([
      $this->generationsTextAdd( $name, $type, $required )
    ], "Column {$name} added AutoIncrement with successfully to {$this->entity}", CommandType::Generationns );
  }  

  public function generationsUpdate(
    array $generationsScripts = []
  ): StructureCommand {
    return new StructureCommand(
      $generationsScripts, "Added AutoIncrement with successfully to {$this->entity}", CommandType::Generationns
    );
  }

  public function generationsDrop(
    string $name,
    string $type,
    string $required
  ): StructureCommand {
    return new StructureCommand([
      $this->generationsTextModify( $name, $type, $required )
    ], "Column {$name} added AutoIncrement with successfully to {$this->entity}", CommandType::Generationns );
  }   

  public function uniqueAddOrUpdate(
    string $name,
    string $columns
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} add constraint {$name} unique ({$columns})"
    ], "Constraint unique {$name} added with successfully to {$this->entity}", CommandType::Uniques );
  }

  public function uniqueDrop(
    string $name
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop constraint {$name}"
    ], "Constraint unique {$name} drop with successfully to {$this->entity}", CommandType::Uniques );
  }

  public function statisticsAddOrUpdate(
    string $name,
    string $columns
  ): StructureCommand {
    return new StructureCommand([
      "create index {$name} on {$this->entity} ({$columns})"
    ], "Index {$name} added with successfully to {$this->entity}", CommandType::Statistics );
  }

  public function statisticsDrop(
    string $name
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop index {$name}"
    ], "Index {$name} drop with successfully to {$this->entity}", CommandType::Statistics );
  }

  public function foreignKeyAdd(
    IForeignKey $fk
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} add constraint {$fk->name} foreign key ({$fk->entityKey}) references {$fk->reference->entity}({$fk->reference->entityKey})"
    ], "Foreign key constraint {$fk->name} added with successfully to {$this->entity}", CommandType::ForeignKeys );
  }

  public function foreignKeyDrop(
    string $name
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop foreign key {$name}"
    ], "Foreign key constraint {$name} drop with successfully to {$this->entity}", CommandType::ForeignKeys );
  }

  public function foreignKeyDropIndex(
    string $name
  ): StructureCommand {
    return new StructureCommand([
      "alter table {$this->entity} drop index {$name}"
    ], "Index {$name} drop with successfully to {$this->entity}", CommandType::ForeignKeys );
  }
}