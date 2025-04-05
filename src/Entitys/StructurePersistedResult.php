<?php

namespace Websyspro\Server\Entitys
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Databases\Connect;
    use Websyspro\Server\Databases\Connect\DB;
    use Websyspro\Server\Decorators\Entity\Enums\AttributeType;

  class StructurePersistedResult 
  extends StructureResult
  {
    public array $columns = [];
    public array $uniques = [];
    public array $indexes = [];
    public array $foreigns = [];
    public array $requireds = [];
    public array $primaryKeys = [];
    public array $autoIncrements = [];
    public array $scripts = [];

    private array $properties = [];
    
    public function __construct(
      public readonly string $entity,
      public readonly string $database
    ){
      $this->init();
    }

    private function init(
    ): void {
      $this->setProperties();
      $this->setEntityColumns();
      $this->setEntityUniques();
      $this->setEntityIndexes();
      $this->setEntityForeignKeys();
      $this->setEntityColumnsTypes();
    }

    private function setProperties(
    ): void {
      $this->properties = DB::set( $this->getDatabase())->query(
        "select information_schema.columns.column_name as name
	             ,information_schema.columns.column_type as type
            ,if(information_schema.columns.is_nullable = 'NO', 'S', 'N') as required
            ,if(information_schema.columns.column_key = 'PRI', 'S', 'N') as primaryKey
            ,if(information_schema.columns.extra = 'auto_increment', 'S', 'N') as autoIncrement
           from information_schema.columns
          where information_schema.columns.table_schema = '{$this->getDatabase()}'
            and information_schema.columns.table_name = '{$this->getEntity()}'"
      )->all();
    }

    private function setEntityUniques(
    ): void {
      $this->uniques = Util::Mapper(
        DB::set( $this->getDatabase())->query(
          "select information_schema.table_constraints.constraint_name as name
             from information_schema.table_constraints
            where information_schema.table_constraints.table_schema = '{$this->getDatabase()}'
              and information_schema.table_constraints.table_name = '{$this->getEntity()}'
              and information_schema.table_constraints.constraint_type = 'UNIQUE'"
        )->all(), fn( object $constraint ) => $constraint->name
      );
    }

    private function setEntityIndexes(
    ): void {
      $this->indexes = Util::Mapper(
        DB::set( $this->getDatabase())->query(
          "select information_schema.statistics.index_name as name
             from information_schema.statistics
            where information_schema.statistics.table_schema = '{$this->getDatabase()}'
              and information_schema.statistics.table_name = '{$this->getEntity()}'
              and information_schema.statistics.index_name like 'INDEX_%'
              and information_schema.statistics.non_unique = 1
         group by information_schema.statistics.index_name"
        )->all(), fn( object $index ) => $index->name
      );      
    }

    private function setEntityForeignKeys(
    ): void {
      $this->foreigns = Util::Mapper(
        DB::set( $this->getDatabase())->query(
          "select information_schema.key_column_usage.constraint_name as name
             from information_schema.key_column_usage 
            where information_schema.key_column_usage.table_schema = '{$this->getDatabase()}'
              and information_schema.key_column_usage.table_name = '{$this->getEntity()}' 
              and information_schema.key_column_usage.referenced_table_name is not null"
        )->all(), fn( object $foreign ) => $foreign->name
      ); 
    }

    private function setEntityColumns(
    ): void {
      Util::Mapper(
        $this->properties, fn( object $property ) => (
          $this->columns[] = new StructureAttribute(
            $property->name, AttributeType::Columns->value, 
            $property->type
          )
        )
      );
    }

    private function setColumnsPropertisType(
      string $type,
      string $typeList
    ): void {
      Util::Mapper(
        Util::Filter(
          $this->properties, fn( object $property ) => (
            $property->{$type} === "S"
          )
        ), fn( object $property ) => (
          $this->{$typeList}[] = new StructureAttribute(
            $property->name, AttributeType::Columns->value, 
            $property->type
          )
        )
      );
    }

    private function setEntityColumnsTypes(
    ): void {
      $this->setColumnsPropertisType( "required", "requireds" );
      $this->setColumnsPropertisType( "primaryKey", "primaryKeys" );
      $this->setColumnsPropertisType( "autoIncrement", "autoIncrements" );
    }
  }
}