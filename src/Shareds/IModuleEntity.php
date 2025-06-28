<?php

namespace Websyspro\Server\Shareds;

class IModuleEntity
{
  public function __construct(
    public string $module,
    public string $entity
  ){}
}