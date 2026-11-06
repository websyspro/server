<?php

namespace Websyspro\Server\Accounts\Controllers;

use Websyspro\WorkerServer\Container;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Post;
use Websyspro\WorkerServer\JwtManager;
use Websyspro\WorkerServer\Response;

#[Controller("auth")]
class AuthController
{
  #[Post()]
  public function getToken(
  ): mixed {
    $jwtManager = Container::make(
      JwtManager::class
    );

    $token = $jwtManager->sign([
      "sub" => null,
      "name" => "",
    ]);

    return Response::json([
      "accessToken" => $token,
      "expiresIn" => 3600,
    ]);
  }
}
