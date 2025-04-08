<?php

namespace Websyspro\Server\Decorations\Middlewares;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;

#[Attribute(Attribute::TARGET_METHOD)]
class AllowAnonymous
{
  public AttributeType $attributeType = AttributeType::Middleware;

  public function execute(
  ): void {}
}