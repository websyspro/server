<?php 

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys
{
  use Websyspro\Server\Databases\Entity\BaseEntity;
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Columns\Datetime;
  use Websyspro\Server\Decorators\Entity\Columns\Decimal;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;
  use Websyspro\Server\Decorators\Entity\Constraints\Index;
  use Websyspro\Server\Decorators\Entity\Constraints\Unique;

  class BoxEntity 
  extends BaseEntity
  {
    #[Text(32)]
    #[Index()]
    #[Unique()]
    public string $name;

    #[Text(1)]
    public string $state;

    #[BigNumber()]
    #[ForeignKey(OperatorEntity::class)]
    public string $operator;

    #[Text(255)]
    public string $printer;

    #[Datetime(10,2)]
    public string $openingAt;

    #[Decimal(10,2)]
    public string $openingBalance;
  }
}