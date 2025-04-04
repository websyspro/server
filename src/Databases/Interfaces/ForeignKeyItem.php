<?php

namespace Websyspro\Server\Databases\Interfaces
{
  class ForeignKeyItem
  {
    public string $name;

    public function __construct(
      public readonly string $entity,
      public readonly string $key,
      public readonly string $reference,
      public readonly string $referenceKey
    ){
      if (empty( $this->referenceKey ) === false) {
        $this->name = (
          "FK_{$this->entity}_{$this->key}_in_{$this->reference}_{$this->referenceKey}"
        );
      }
    }
  }
}