<?php

namespace Websyspro\Server\Applications;

use Websyspro\Server\Decorations\Module;
use Websyspro\Server\Applications\Accounts\Controllers\AuthController;
use Websyspro\Server\Applications\Accounts\Entitys\CredentialEntity;
use Websyspro\Server\Applications\Accounts\Entitys\ProfileEntity;
use Websyspro\Server\Applications\Accounts\Entitys\UserEntity;
use Websyspro\Server\Applications\Accounts\Entitys\UserProfileEntity;
use Websyspro\Server\Applications\Accounts\Services\InitService;

#[Module(
  Entitys: [
    UserEntity::class,
    ProfileEntity::class,
    CredentialEntity::class,
    UserProfileEntity::class
  ],
  Controllers: [
    AuthController::class
  ],
  Services: [
    InitService::class
  ]
)]
class AccountsModule {}