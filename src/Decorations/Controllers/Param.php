<?php

namespace Websyspro\Server\Decorations\Controllers;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Enums\RequestType;
use Websyspro\Server\Request;

#[Attribute( Attribute::TARGET_PARAMETER )]
class Param
{
  public AttributeType $attributeType = AttributeType::Parameter;

  public function __construct(
    public readonly string | null $key = null
  ){}

  public function Execute(
    array $controllerEndpoint = [],
    array $requestEndpoint = []
  ): array | object | string | null {
    return Request::data(
      $this->key, RequestType::PARAMS, $controllerEndpoint , $requestEndpoint
    );
  }
}