<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Enums\RequestType;
use Websyspro\Server\Request;

#[Attribute( Attribute::TARGET_PARAMETER )]
class Body
{
  public AttributeType $attributeType = AttributeType::parameter;

  public function __construct(
    public readonly string | null $key = null
  ){}

  public function execute(
    string $instanceType
  ): array | object | string | null {
    return Request::data(
      $this->key, $instanceType, RequestType::body
    );
  }
}