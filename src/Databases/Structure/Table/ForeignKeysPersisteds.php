<?php

namespace Websyspro\Server\Databases\Structure\Table;

class ForeignKeysPersisteds
{
  public array $items = [];

  public function __construct(
    private readonly string $entity,
    private readonly string $database
  ){}
}