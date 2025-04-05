<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys
{
  use Websyspro\Server\Databases\Entity\BaseEntity;
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Columns\Decimal;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;
  use Websyspro\Server\Decorators\Entity\Constraints\Index;

  class CashMovementEntity
  extends BaseEntity
  {
    #[Text(1)]
    public string $type;

    #[Text(3)]
    public string $paymentMethod;

    #[BigNumber()]
    #[Index()]
    #[ForeignKey(DocumentEntity::class)]
    public int $idDocument;

    #[BigNumber()]
    #[Index()]
    #[ForeignKey(BoxEntity::class)]
    public int $dBox;
  
    #[Decimal(10,2)]
    public int $number;
  
    #[Text(255)]
    public int $observations;
  }
}