<?php

use Websyspro\Server\Apps\Accounts\AccountDatabase;
use Websyspro\Server\Apps\Shops\ShopBoots;
use Websyspro\Server\Apps\Shops\ShopControllers;
use Websyspro\Server\Apps\Shops\ShopDatabase;
use Websyspro\Server\Server\Application;

Application::server(
  controllers: [
    ShopControllers::class
  ],
  databases: [
    ShopDatabase::class,
    AccountDatabase::class
  ],
  boots: [
    ShopBoots::class
  ]
);