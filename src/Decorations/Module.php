<?php

namespace Websyspro\Server\Decorations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
  public function __construct(
    public readonly array $Controllers,
    public readonly array $Entitys,
    public readonly array $Services,
    public readonly array $Scheduleres
  ){}  
}