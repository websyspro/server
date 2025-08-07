<?php

namespace Websyspro\Server\Decorations\Middlewares;

use Attribute;
use Websyspro\Jwt\Decode;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Exceptions\Error;
use Websyspro\Server\Request;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Authenticate
{
  public AttributeType $attributeType = AttributeType::middleware;

  public function execute(
    Request $request
  ): void {
    $accessToken = (
      $request->accessToken()
    );

    $notPermissionMessage = (
      "You do not have permission to access this resource."
    );

    if($accessToken === null){
      Error::unauthorized(
        $notPermissionMessage
      );
    }

    if($accessToken instanceof Decode === false){
      Error::unauthorized(
        $notPermissionMessage
      );
    }

    if($accessToken->verified === false){
      Error::Unauthorized(
        $notPermissionMessage
      );
    }
  }
}