<?php

namespace Websyspro\Server\Applications\Shops\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Text;

class ProductGroupEntity
extends BaseEntity
{
  #[Text(64)]
  public string $Name;
}