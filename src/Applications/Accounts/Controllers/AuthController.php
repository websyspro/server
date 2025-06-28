<?php

namespace Websyspro\Server\Applications\Accounts\Controllers;

use Websyspro\Server\Applications\Accounts\Services\AuthService;
use Websyspro\Server\Decorations\Controllers\Body;
use Websyspro\Server\Decorations\Controllers\Controller;
use Websyspro\Server\Decorations\Controllers\Post;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Response;

#[Controller("auth")]
#[Authenticate()]
class AuthController
{
  public function __construct(
    private AuthService $authService
  ){}

  #[Post()]
  #[AllowAnonymous()]
  public function Token(
    #[Body("username")] string $username,
    #[Body("password")] string $password    
  ): Response {
    return Response::json(
      $this->authService->IsAuth(
        $username, $password
      )
    );    
  }
}