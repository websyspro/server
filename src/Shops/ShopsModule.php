<?php

namespace Websyspro\Server\Shops;

use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;
use Websyspro\Server\Shops\Entities\ConfigEntity;
use Websyspro\Server\Shops\Entities\CustomerEntity;
use Websyspro\Server\Shops\Entities\DocumentEntity;
use Websyspro\Server\Shops\Entities\DocumentItemEntity;
use Websyspro\Server\Shops\Entities\OperatorEntity;
use Websyspro\Server\Shops\Entities\ProductEntity;
use Websyspro\Server\Shops\Entities\ProductGroupEntity;
use Websyspro\WorkerServer\Decorators\Module;


#[Module(
  name: 'shops',
  controllers: [],
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
  ]
)]
class ShopsModule {}
