<?php

namespace Websyspro\Server\Entitys
{
  use Websyspro\Server\Commons\Util;
    use Websyspro\Server\Databases\Interfaces\ForeignKeyItem;
    use Websyspro\Server\Entitys\Commons\EntityUtil;

  class StructureResult
  {
    public array $columns = [];
    public array $uniques = [];
    public array $indexes = [];
    public array $foreigns = [];
    public array $requireds = [];
    public array $primaryKeys = [];
    public array $autoIncrements = [];
    public array $scripts = [];
  
    public function __construct(
      public readonly string $entity,
      public readonly string $database
    ){}

    public function getDatabase(
    ): string {
      return sprintf(
        "%s%s", connect->prefix, (
          EntityUtil::DatabaseParse(
            $this->database
          )
        )
      );
    }

    public function getEntity(
    ): string {
      return EntityUtil::EntityParse(
        $this->entity
      );
    }
    
    public function hasColumns(
    ): bool {
      return sizeof($this->columns) !== 0;
    } 
    
    public function hasPrimaryKey(
    ): bool {
      return sizeof($this->primaryKeys) !== 0;
    }

    public function hasAutoIncrement(
    ): bool {
      return sizeof($this->autoIncrements) !== 0;
    }

    public function hasUniques(
    ): bool {
      return sizeof($this->uniques) !== 0;
    }

    public function hasIndexes(
    ): bool {
      return sizeof($this->indexes) !== 0;
    }
    
    public function hasForeigns(
    ): bool {
      return sizeof($this->foreigns) !== 0;
    }    
    
    public function getPrimaryKeyColumns(
    ): array {
      return (
        Util::Mapper( $this->primaryKeys, (
          fn( StructureAttribute $sa ) => (
            $sa->name
          )
        ))
      );
    }

    public function getColumnName(
      string $name
    ): StructureAttribute {
      [ $structureAttribute ] = Util::Filter( $this->columns, (
        fn( StructureAttribute $structureAttribute ) => (
          $structureAttribute->name === $name
        )
      ));

      return $structureAttribute;
    }    
    
    public function getColumns(
    ): array {
      return (
        Util::Mapper( $this->columns, (
          fn( StructureAttribute $structureAttribute ) => (
            $structureAttribute->name
          )
        ))
      );
    }
    
    public function hasColumnName(
      string $name
    ): bool {
      return in_array(
        $name, $this->getColumns()
      );
    }

    public function getPropertyForName(
      string $name, array $properties = []
    ): string | null {
      [ $property ] = Util::Filter( $properties, (
        fn( StructureAttribute $structureAttribute ) => (
          $structureAttribute->name === $name
        )) 
      );

      return is_null($property) === false 
        ? $property->args
        : $property;       
    }
    
    public function isRequired(
      string $name
    ): bool {
      return sizeof( Util::filter( $this->requireds, (
        fn( object $required ) => $required->name === $name
      ))) !== 0;
    }
    
    public function getNotNull(
      string $name
    ): string | null {
      return $this->getPropertyForName(
        $name, $this->requireds
      );
    }

    public function getAfter(
      string $name
    ): string {
      $arrayIndexOf = array_search(
        $name, $this->getColumns()
      );    

      return $arrayIndexOf !== 0 
        ? $this->getColumns()[ $arrayIndexOf - 1 ]
        : $this->getColumns()[ sizeof($this->getColumns()) - 1 ];
    }

    public function getReferenceKey(
      string $reference
    ): string {
      $reference = (
        new StructureDesignResult(
          $reference
        )
      );

      [ $referenceKey ] = Util::Filter(
        $reference->primaryKeys, fn( StructureAttribute $primaryKey ) => (
          in_array( $primaryKey->name, Util::Mapper($reference->autoIncrements, (
            fn( StructureAttribute $autoIncrement ) => $autoIncrement->name
          )))
        )
      );

      if ( $referenceKey instanceof StructureAttribute ){
        return $referenceKey->name;
      }

      return "";
    }

    public function getForeignsName(
    ): array {
      return Util::Mapper($this->foreigns, (
        fn( ForeignKeyItem $foreignKeyItem ) => $foreignKeyItem->name
      ));
    }
  }
}