<?php

namespace Websyspro\Server\Accounts\Controllers;

use Websyspro\WorkerServer\Container;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Post;
use Websyspro\WorkerServer\Decorators\Requests\Body;
use Websyspro\WorkerServer\JwtManager;
use Websyspro\WorkerServer\Response;

#[Controller("auth")]
class AuthController
{
  #[Post("/token")]
  public function token(
    #[Body("sub")]  mixed  $sub  = null,
    #[Body("name")] string $name = ""
  ): Response {
    /** @var JwtManager $jwtManager */
    $jwtManager = Container::make( JwtManager::class );

    $token = $jwtManager->sign([
      "sub"  => $sub,
      "name" => $name,
    ]);

    return Response::json([
      "accessToken" => $token,
      "expiresIn" => 3600,
    ]);
  }
}
