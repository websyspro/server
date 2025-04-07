<?php

namespace Websyspro\Server\Interfaces\Reflections;

use ReflectionAttribute;

class IAttributesByProperty
{
  public string $classInstance;
  public object $instance;

  public function __construct(
    public ReflectionAttribute $reflectionAttribute
  ){}
}