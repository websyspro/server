<?php

namespace Websyspro\Server\Decorations\Conntrollers;

use Attribute;
use Websyspro\Server\Enums\Controllers\MethodType;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute( Attribute::TARGET_METHOD )]
class Delete
{
  public AttributeType $attributeType = AttributeType::Endpoint;
  public MethodType $methodType = MethodType::Delete;

  public function __construct(
    public readonly string $endpoint = ""
  ){}
}