<?php

namespace Websyspro\Server\Databases\Structure\Table;

class PrimaryKeysPersisteds
{
  public array $items = [];
  
  public function __construct(
    private readonly string $entity,
    private readonly string $database
  ){}
}