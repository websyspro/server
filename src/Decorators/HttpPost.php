<?php

namespace Websyspro\Server\Decorators;

use Attribute;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Enums\RequestMethod;
use Websyspro\Server\Commons\Util;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpPost {
  public function __construct(
    private readonly string $name = ""
  ){}

  public function getEndpoint(
  ): array {
    if( empty( $this->name )){
      return [];
    }

    return explode(
      "/", Util::ParseRequestUri($this->name)
    );
  }

  public function getRequestMethod(
  ): RequestMethod {
    return RequestMethod::POST;
  }

  public function AttributeType(
  ): AttributeType {
    return AttributeType::Endpoint;
  }
}