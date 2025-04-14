<?php

namespace Websyspro\Server\Databases\Structure;

class StructureEntity
{
  public function __construct(
    public readonly StructurePersistedTable $persisted,
    public readonly StructureDesignTable $design
  ){}
}