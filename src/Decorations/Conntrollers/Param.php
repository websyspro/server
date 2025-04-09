<?php

namespace Websyspro\Server\Decorations\Conntrollers;

use Attribute;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Enums\Request\RequestType;
use Websyspro\Server\Server\Request;
use Websyspro\Server\Server\RequestData;

#[Attribute( Attribute::TARGET_PARAMETER )]
class Param
{
  public AttributeType $attributeType = AttributeType::Parameter;

  public function __construct(
    public readonly string | null $key = null
  ){}

  public function execute(
    array $controllerEndpoint = [],
    array $requestEndpoint = []
  ): array | object | string | null {
    return Request::data(
      $this->key, RequestType::PARAMS, $controllerEndpoint , $requestEndpoint
    );
  }
}