<?php

namespace Websyspro\Server\Shops;

use Websyspro\Server\Shops\Controllers\BoxController;
use Websyspro\Server\Shops\Controllers\CashMovementController;
use Websyspro\Server\Shops\Controllers\CustomerController;
use Websyspro\Server\Shops\Controllers\OperatorController;
use Websyspro\Server\Shops\Controllers\ProductController;
use Websyspro\Server\Shops\Controllers\ProductGroupController;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;
use Websyspro\Server\Shops\Entities\ConfigEntity;
use Websyspro\Server\Shops\Entities\CustomerEntity;
use Websyspro\Server\Shops\Entities\DocumentEntity;
use Websyspro\Server\Shops\Entities\DocumentItemEntity;
use Websyspro\Server\Shops\Entities\OperatorEntity;
use Websyspro\Server\Shops\Entities\ProductEntity;
use Websyspro\Server\Shops\Entities\ProductGroupEntity;
use Websyspro\Server\Shops\Services\InitalService;
use Websyspro\WorkerServer\Decorators\Module;


#[Module(
  controllers: [
    BoxController::class,
    ProductController::class,
    OperatorController::class,
    CustomerController::class,
    CashMovementController::class,
    ProductGroupController::class
  ],
  entities: [
    BoxEntity::class,
    CashMovementEntity::class,
    ConfigEntity::class,
    CustomerEntity::class,
    DocumentEntity::class,
    DocumentItemEntity::class,
    OperatorEntity::class,
    ProductEntity::class,
    ProductGroupEntity::class
  ],
  services: [
    InitalService::class
  ]
)]
class ShopsModule {}
