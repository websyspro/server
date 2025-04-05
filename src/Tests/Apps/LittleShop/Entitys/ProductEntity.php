<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys;

use Websyspro\Server\Databases\Entity\BaseEntity;
use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
use Websyspro\Server\Decorators\Entity\Columns\Decimal;
use Websyspro\Server\Decorators\Entity\Columns\Text;
use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;
use Websyspro\Server\Decorators\Entity\Constraints\Index;

class ProductEntity
extends BaseEntity
{
  #[Text(255)]
  #[Index()]
  public string $name;

  #[Decimal(10,2)]
  public float $value;

  #[BigNumber()]
  #[ForeignKey(ProductGroupEntity::class)]
  public int $idProductGroup;

  #[Text(1)]
  public string $state;

  #[Decimal(10,2)]
  public float $amount;

  #[Decimal(10,2)]
  public float $totalStock;
}