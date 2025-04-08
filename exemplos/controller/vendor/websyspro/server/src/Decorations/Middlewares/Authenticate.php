<?php

namespace Websyspro\Server\Decorations\Middlewares;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Authenticate
{
  public AttributeType $attributeType = AttributeType::Middleware;

  public function execute(
  ): void {}
}