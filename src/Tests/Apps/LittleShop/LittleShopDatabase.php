<?php

namespace Websyspro\Server\Tests\Apps\LittleShop
{
  use Websyspro\Server\Decorators\EntityList;
  use Websyspro\Server\Tests\Apps\LittleShop\Entitys\BoxEntity;
    use Websyspro\Server\Tests\Apps\LittleShop\Entitys\OperatorEntity;

  #[EntityList([
    BoxEntity::class,
    OperatorEntity::class
  ])]
  class LittleShopDatabase {}
}