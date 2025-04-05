<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys;

use Websyspro\Server\Databases\Entity\BaseEntity;
use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
use Websyspro\Server\Decorators\Entity\Columns\Decimal;
use Websyspro\Server\Decorators\Entity\Columns\Text;
use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;

class ConfigEntity
extends BaseEntity
{
  #[Text(32)]
  public string $passwordReleaseDiscount;

  #[Decimal(10,2)]
  public string $purchaseLimitPerCustomer;

  #[BigNumber()]
  #[ForeignKey(BoxEntity::class)]
  public string $MainBox;
}