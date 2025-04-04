<?php

namespace Websyspro\Server\Entitys
{
  use ReflectionProperty;
  use Websyspro\Server\Commons\Util;
    use Websyspro\Server\Databases\Interfaces\ForeignKeyItem;
    use Websyspro\Server\Decorators\Entity\Enums\AttributeType;
    use Websyspro\Server\Entitys\Commons\EntityUtil;
    use Websyspro\Server\Reflections\ReflectUtils;

  class StructureDesignResult
  extends StructureResult
  {
    public array $columns = [];
    public array $uniques = [];
    public array $indexes = [];
    public array $foreigns = [];
    public array $requireds = [];
    public array $primaryKeys = [];
    public array $autoIncrements = [];
    public array $triggersBeforeCreate = [];
    public array $triggersBeforeUpdate = [];
    public array $triggersBeforeDelete = [];
    public array $triggersAfterCreate = [];
    public array $triggersAfterUpdate = [];
    public array $triggersAfterDelete = [];
    public array $scripts = [];
    
    private array $properties = [];

    public function __construct(
      public readonly string $entity,
      public readonly string $database = ""
    ){
      $this->setProperties();
      $this->setColumns();
      $this->setUniques();
      $this->setIndexes();
      $this->setForeigns();
      $this->setRequireds();
      $this->setPrimaryKeys();
      $this->setAutoIncrement();
      $this->setTriggers();
    }

    private function setProperties(
    ): void {
      Util::Mapper(
        ReflectUtils::getProperties( $this->entity ), 
          fn( ReflectionProperty $reflectionProperty ) => (
            $this->properties[
              $reflectionProperty->getName()
            ] = (
              ( new StructureAttributeList(
                $reflectionProperty 
              ))->getAttributes()
            )
          )
      );
    }

    private function getAttributeType(
      string $type,
      array $attributeList = []
    ): array {
      foreach( $this->properties as $property ){
        [ $propertys ] = Util::Filter(
          $property, fn(StructureAttribute $structureAttribute) => (
            $structureAttribute->type === $type
          )
        );

        if( $propertys !== null ){
          $attributeList[] = $propertys;
        }
      }
      
      return $attributeList;
    }

    private function setColumns(
    ): void {
      $this->columns = $this->getAttributeType(
        AttributeType::Columns->name
      );
    }

    private function setUniques(
    ): void {
      $this->uniques = $this->getAttributeType(
        AttributeType::Uniques->name
      );
    }

    private function setIndexes(
    ): void {
      $this->indexes = $this->getAttributeType(
        AttributeType::Indexes->name
      );
    }

    private function setForeigns(
    ): void {
      $this->foreigns = Util::Mapper(
        $this->getAttributeType(
          AttributeType::Foreigns->name
        ), fn( StructureAttribute $foreign ) => (
          new ForeignKeyItem(
            key: $foreign->name,
            entity: EntityUtil::EntityParse( $this->entity ),
            reference: EntityUtil::EntityParse( $foreign->args ),
            referenceKey: $this->getReferenceKey( $foreign->args )
          )
        )
      ); 

      // $this->foreigns = $this->getAttributeType(
      //   AttributeType::Foreigns->name
      // );      
    }

    private function setRequireds(
    ): void {
      $this->requireds = $this->getAttributeType(
        AttributeType::Requireds->name
      );
    }

    private function setPrimaryKeys(
    ): void {
      $this->primaryKeys = $this->getAttributeType(
        AttributeType::PrimaryKey->name
      );
    }

    private function setAutoIncrement(
    ): void {
      $this->autoIncrements = $this->getAttributeType(
        AttributeType::AutoIncrement->name
      );
    }    

    private function setTriggers(
    ): void {
      $this->triggersBeforeCreate = $this->getAttributeType( AttributeType::TriggersBeforeCreate->name );
      $this->triggersBeforeUpdate = $this->getAttributeType( AttributeType::TriggersBeforeUpdate->name );
      $this->triggersBeforeDelete = $this->getAttributeType( AttributeType::TriggersBeforeDelete->name );
      $this->triggersAfterCreate = $this->getAttributeType( AttributeType::TriggersAfterCreate->name );
      $this->triggersAfterUpdate = $this->getAttributeType( AttributeType::TriggersAfterUpdate->name );
      $this->triggersAfterDelete = $this->getAttributeType( AttributeType::TriggersAfterDelete->name );
    }
  }
}