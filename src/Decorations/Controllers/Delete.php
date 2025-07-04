<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\MethodType;
use Websyspro\Server\Enums\AttributeType;

#[Attribute( Attribute::TARGET_METHOD )]
class Delete
{
  public AttributeType $attributeType = AttributeType::endpoint;
  public MethodType $methodType = MethodType::delete;

  public function __construct(
    public readonly string $endpoint = ""
  ){}
}