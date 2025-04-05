<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Controllers
{
  use Websyspro\Server\Databases\Connect\DB;
  use Websyspro\Server\Decorators\Controller;
  use Websyspro\Server\Decorators\HttpGet;
  use Websyspro\Server\Http\Response;

  #[Controller( "box" )]
  class BoxController
  {
    #[HttpGet()]
    public function getToken(
    ): Response {
      return Response::json(
        DB::set()->query(
          "select * from Customer"
        )->all()
      );
    }
  }
}