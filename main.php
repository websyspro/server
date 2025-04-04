<?php

use Websyspro\Server\Application;
use Websyspro\Server\Tests\Controllers\AccountsControllers;
use Websyspro\Server\Tests\Database\TestDatabase;

Application::Init(
  controllers: [
    AccountsControllers::class
  ],
  databases: [
    TestDatabase::class
  ],
  entitys: []
);
