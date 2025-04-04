<?php

use Websyspro\Server\Application;
use Websyspro\Server\Tests\Controllers\PerfilController;
use Websyspro\Server\Tests\Controllers\UserController;
use Websyspro\Server\Tests\Database\TestDatabase;

Application::Init(
  controllers: [
    UserController::class,
    PerfilController::class
  ],
  databases: [
    TestDatabase::class
  ],
  entitys: []
);
