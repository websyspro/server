<?php

namespace Websyspro\Server\Decorators;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Enums\RequestMethod;
use Websyspro\Server\Commons\Util;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpGet {
  public function __construct(
    private readonly string $name
  ){}

  public function getEndpoint(
  ): array {
    return explode(
      "/", Util::ParseRequestUri($this->name)
    );
  }

  public function getRequestMethod(
  ): RequestMethod {
    return RequestMethod::GET;
  }

  public function AttributeType(
  ): AttributeType {
    return AttributeType::Endpoint;
  }
}