<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\MethodType;
use Websyspro\Server\Enums\AttributeType;

#[Attribute( Attribute::TARGET_METHOD )]
class Delete
{
  public AttributeType $attributeType = AttributeType::Endpoint;
  public MethodType $methodType = MethodType::Delete;

  public function __construct(
    public readonly string $endpoint = ""
  ){}
}