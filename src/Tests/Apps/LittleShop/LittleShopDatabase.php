<?php

namespace Websyspro\Server\Tests\Apps\LittleShop;

use Websyspro\Server\Decorators\EntityList;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\BoxEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\CashMovementEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\ConfigEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\CustomerEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\DocumentEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\DocumentItemEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\OperatorEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\ProductEntity;
use Websyspro\Server\Tests\Apps\LittleShop\Entitys\ProductGroupEntity;

#[EntityList([
  BoxEntity::class,
  CashMovementEntity::class,
  ConfigEntity::class,
  CustomerEntity::class,
  DocumentEntity::class,
  DocumentItemEntity::class,
  OperatorEntity::class,
  ProductEntity::class,
  ProductGroupEntity::class
])]
class LittleShopDatabase {}