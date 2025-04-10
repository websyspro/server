<?php

namespace Websyspro\Server\Interfaces\Entitys;

use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\Structure\StructureTable;
use Websyspro\Server\Interfaces\Reflections\IProperty;

class IForeignKey
{
  public string $entity;
  public string $entityKey;
  public object $reference;

  public function __construct(
    private readonly Reflect $reflect,
    private readonly IProperty $property,
    private object $attribute
  ){
    $this->setEntity();
    $this->setEntityKey();
    $this->setReference();
  }

  private function setEntity(
  ): void {
    $this->entity = (
      $this->reflect->getClass()
    );
  }

  private function setEntityKey(
  ): void {
    $this->entityKey = $this->property->name;
  }

  private function setReference(
  ): void {
    $this->reference = ( new StructureTable(
      $this->attribute->referenceClass
    ))->getForeingKey();
  }
}