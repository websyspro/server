<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys
{

  use Websyspro\Server\Databases\Entity\BaseEntity;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\Unique;

  class OperatorEntity
  extends BaseEntity
  {
    #[Text(64)]
    #[Unique(1)]
    public string $name;
  }
}