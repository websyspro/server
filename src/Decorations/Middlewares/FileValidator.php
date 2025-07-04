<?php

namespace Websyspro\Server\Decorations\Middlewares;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Request;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class FileValidator
{
  public AttributeType $attributeType = AttributeType::middleware;

  public function Execute(
    Request $request
  ): void {}
}