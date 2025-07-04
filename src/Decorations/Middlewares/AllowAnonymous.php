<?php

namespace Websyspro\Server\Decorations\Middlewares;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Request;

#[Attribute(Attribute::TARGET_METHOD)]
class AllowAnonymous
{
  public AttributeType $attributeType = AttributeType::middleware;

  public function Execute(
    Request $request
  ): void {}
}