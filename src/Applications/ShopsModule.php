<?php

namespace Websyspro\Server\Applications;

use Websyspro\Server\Applications\Shops\Controllers\CustomerController;
use Websyspro\Server\Applications\Shops\Controllers\DocumentController;
use Websyspro\Server\Applications\Shops\Controllers\ProductController;
use Websyspro\Server\Applications\Shops\Entitys\BoxEntity;
use Websyspro\Server\Applications\Shops\Entitys\CashMovementEntity;
use Websyspro\Server\Applications\Shops\Entitys\ConfigEntity;
use Websyspro\Server\Applications\Shops\Entitys\CustomerEntity;
use Websyspro\Server\Applications\Shops\Entitys\DocumentEntity;
use Websyspro\Server\Applications\Shops\Entitys\DocumentItemEntity;
use Websyspro\Server\Applications\Shops\Entitys\OperatorEntity;
use Websyspro\Server\Applications\Shops\Entitys\ProductEntity;
use Websyspro\Server\Applications\Shops\Entitys\ProductGroupEntity;
use Websyspro\Server\Applications\Shops\Services\InitService;
use Websyspro\Server\Decorations\Module;

#[Module(
  Entitys: [
    OperatorEntity::class,
    CustomerEntity::class,
    ProductGroupEntity::class,
    ProductEntity::class,
    BoxEntity::class,
    ConfigEntity::class,
    DocumentEntity::class,
    DocumentItemEntity::class,
    CashMovementEntity::class
  ],
  Controllers: [
    CustomerController::class,
    DocumentController::class,
    ProductController::class
  ],
  Services: [
    InitService::class
  ]
)]
class ShopsModule {}