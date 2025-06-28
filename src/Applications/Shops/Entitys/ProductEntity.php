<?php

namespace Websyspro\Server\Applications\Shops\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Decimal;
use Websyspro\Entity\Decorations\Columns\Number;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\ForeignKey;
use Websyspro\Entity\Decorations\Statistics\Index;

class ProductEntity
extends BaseEntity
{
  #[Text(255)]
  #[Index()]
  public string $Name;

  #[Decimal(10,2)]
  public float $Value;

  #[Number()]
  #[ForeignKey(ProductGroupEntity::class)]
  public int $ProductGroupId;

  #[Text(1)]
  public string $State;

  #[Decimal(10,2)]
  public float $Amount;

  #[Decimal(10,2)]
  public float $TotalStock;
}