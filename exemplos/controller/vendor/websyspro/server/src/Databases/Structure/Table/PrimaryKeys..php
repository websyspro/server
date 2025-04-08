<?php

namespace Websyspro\Server\Databases\Structure\Table;

class PrimaryKeys
{
  public function __construct(
    private readonly string $entityClass
  ){}  
}