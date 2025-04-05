<?php

use Websyspro\Server\Application;
use Websyspro\Server\Commons\Log;
use Websyspro\Server\Enums\LogType;
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