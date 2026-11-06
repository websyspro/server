<?php

namespace Websyspro\Server\Accounts\Controllers;

use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Post;
use Websyspro\WorkerServer\JwtManager;
use Websyspro\WorkerServer\Response;

#[Controller("auth")]
class AuthController
{
  public function __construct(
    private readonly JwtManager $jwtManager
  ){}

  #[Post()]
  public function getToken(
  ): mixed {
    $token = $this->jwtManager->sign([
      "sub" => null,
      "name" => "",
    ]);

    return Response::json([
      "accessToken" => $token,
      "expiresIn" => 3600,
    ]);
  }
}
