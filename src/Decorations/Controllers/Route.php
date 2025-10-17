<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Enums\RequestType;
use Websyspro\Server\Request;

#[Attribute( Attribute::TARGET_METHOD )]
class Route
{
  public AttributeType $attributeType = AttributeType::endpoint;

  public function __construct(
    public readonly string|null $endpoint = null
  ){}
}