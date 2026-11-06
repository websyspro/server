<?php

namespace Websyspro\Server\Accounts;

use Websyspro\Server\Accounts\Controllers\AuthController;
use Websyspro\Server\Accounts\Controllers\EntityController;
use Websyspro\Server\Accounts\Controllers\UserController;
use Websyspro\Server\Accounts\Entities\AccessEntity;
use Websyspro\Server\Accounts\Entities\UserEntity;
use Websyspro\WorkerServer\Decorators\Module;

#[Module(
  controllers: [
    AuthController::class
  ],
  entities: [
    UserEntity::class,
    AccessEntity::class
  ]
)]
class AccountsModule {}
