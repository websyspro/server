<?php

namespace Websyspro\Server\Tests\Controllers\Accounts
{
  use Websyspro\Server\Decorators\Controller;
  use Websyspro\Server\Decorators\HttpGet;
  use Websyspro\Server\Http\Response;

  #[Controller("token")]
  class TokenController
  {
    #[HttpGet( "valid" )]
    public function getToken(
    ): Response {
      return Response::json( "Hello Wordpress!!!" );
    }
  }
}