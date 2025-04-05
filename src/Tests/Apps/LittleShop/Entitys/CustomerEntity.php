<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys
{
  use Websyspro\Server\Databases\Entity\BaseEntity;
  use Websyspro\Server\Decorators\Entity\Columns\Datetime;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\Unique;

  class CustomerEntity
  extends BaseEntity
  {
    #[Text(255)]
    public string $name;

    #[Text(14)]
    #[Unique()]
    public string $cpf;

    #[Datetime()]
    public string $lastPurchaseAt;
  }
}