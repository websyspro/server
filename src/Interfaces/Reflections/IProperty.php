<?php

namespace Websyspro\Server\Interfaces\Reflections;

use ReflectionAttribute;
use Websyspro\Server\Commons\Util;

class IProperty
{
  public function __construct(
    public string $name,
    public array $atributes
  ){
    $this->atributes = Util::Mapper( $this->atributes, (
      fn( ReflectionAttribute $reflectionAttribute ) => (
        $reflectionAttribute->newInstance()
      )
    ));
  }
}