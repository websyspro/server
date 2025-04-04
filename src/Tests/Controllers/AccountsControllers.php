<?php

namespace Websyspro\Server\Tests\Controllers
{
  use Websyspro\Server\Decorators\ControllerList;
  use Websyspro\Server\Tests\Controllers\Accounts\PerfilController;
  use Websyspro\Server\Tests\Controllers\Accounts\TokenController;
  use Websyspro\Server\Tests\Controllers\Accounts\UserController;

  #[ControllerList([
    UserController::class,
    PerfilController::class,
    TokenController::class
  ])]
  class AccountsControllers {}
}