<?php

use Websyspro\Server\Application;
use Websyspro\Server\Tests\Apps\LittleShop\LittleShopControllers;
use Websyspro\Server\Tests\Apps\LittleShop\LittleShopDatabase;

Application::Init(
  controllers: [
    LittleShopControllers::class
  ],
  databases: [
    LittleShopDatabase::class,
  ],
  entitys: []
);
