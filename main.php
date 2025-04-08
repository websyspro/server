<?php

use Websyspro\Server\Decorations\Conntrollers\Body;
use Websyspro\Server\Decorations\Conntrollers\Controller;
use Websyspro\Server\Decorations\Conntrollers\ControllerList;
use Websyspro\Server\Decorations\Conntrollers\Get;
use Websyspro\Server\Decorations\Conntrollers\Post;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Decorations\Middlewares\FileValidator;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Server\Application;
use Websyspro\Server\Server\Response;

#[Controller( "user" )]
#[Authenticate()]
class UserController
{
  public function __construct()
  {}

  #[Post( "list" )]
  #[FileValidator()]
  public function getList(
    #[Body()] array $body
  ): Response {
    return Response::json( $body );
  }

  #[Get( "list" )]
  #[AllowAnonymous()]
  public function list(
  ): Response {
    return Response::json(
      [ "Test" => "Thiago" ]
    );
  }
}

#[Controller( "roles" )]
class RolesController {}

#[ControllerList([
  UserController::class,
  RolesController::class
])]
class AccountsControllers {}

#[Controller( "product" )]
class ProductController {}

#[ControllerList([
  ProductController::class
])]
class PedidosControllers {}

Application::server(
  controllers: [
    AccountsControllers::class,
    PedidosControllers::class
  ]
);