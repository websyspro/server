<?php

namespace Websyspro\Server\Databases\Structure\Table;

class ForeignKeys
{
  public function __construct(
    private readonly string $entityClass
  ){} 
}