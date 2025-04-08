<?php

namespace Websyspro\Server\Interfaces\Reflections;

use ReflectionAttribute;
use ReflectionParameter;
use Websyspro\Server\Commons\Util;

class IParameter
{
  public string $name;
  public array $attributes;

  public function __construct(
    private ReflectionParameter $reflectionParameter
  ){
    $this->name = $this->reflectionParameter->name;
    $this->attributes = Util::Mapper(
      $this->reflectionParameter->getAttributes(), (
        fn( ReflectionAttribute $reflectionAttribute ) => (
          new IAttributesByProperty(
            $reflectionAttribute
          )
        )
      )
    );

    unset( $this->reflectionParameter );
  }
}