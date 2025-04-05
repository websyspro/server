<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys;

use Websyspro\Server\Databases\Entity\BaseEntity;
use Websyspro\Server\Decorators\Entity\Columns\Text;

class ProductGroupEntity
extends BaseEntity
{
  #[Text(64)]
  public string $name;
}