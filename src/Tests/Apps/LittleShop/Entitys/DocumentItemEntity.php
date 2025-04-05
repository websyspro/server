<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys;

use Websyspro\Server\Databases\Entity\BaseEntity;
use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
use Websyspro\Server\Decorators\Entity\Columns\Decimal;
use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;

class DocumentItemEntity
extends BaseEntity
{
  #[BigNumber()]
  #[ForeignKey(DocumentEntity::class)]
  public string $idDocument;

  #[BigNumber()]
  #[ForeignKey(ProductEntity::class)]
  public string $idProduct;

  #[Decimal(10,2)]
  public float $value;

  #[Decimal(10,2)]
  public float $amount;

  #[Decimal(10,2)]
  public float $discount;

  #[Decimal(10,2)]
  public float $totalValue;
}