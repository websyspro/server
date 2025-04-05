<?php

namespace Websyspro\Server\Tests\Apps\LittleShop
{
  use Websyspro\Server\Decorators\ControllerList;
  use Websyspro\Server\Tests\Apps\LittleShop\Controllers\BoxController;

  #[ControllerList([
    BoxController::class
  ])]
  class LittleShopControllers {}
}