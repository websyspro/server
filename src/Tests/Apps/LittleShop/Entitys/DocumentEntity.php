<?php

namespace Websyspro\Server\Tests\Apps\LittleShop\Entitys
{
  use Websyspro\Server\Databases\Entity\BaseEntity;
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Columns\Decimal;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;
  use Websyspro\Server\Decorators\Entity\Constraints\Index;

  class DocumentEntity
  extends BaseEntity
  {
    #[Text(1)]
    #[Index(1)]
    public string $type;

    #[Text(1)]
    public string $state;

    #[BigNumber()]
    #[Index(2)]
    #[Index(3)]
    #[ForeignKey(BoxEntity::class)]
    public int $idBox;

    #[BigNumber()]
    #[Index(2)]
    #[Index(3)]
    #[ForeignKey(OperatorEntity::class)]
    public int $idOperator;

    #[BigNumber()]
    #[ForeignKey(CustomerEntity::class)]
    public int $idCustomer;

    #[Decimal(10,2)]
    public float $value;

    #[Decimal(10,2)]
    public float $valueInPix;

    #[Decimal(10,2)]
    public float $valueInDebitCard;

    #[Decimal(10,2)]
    public float $valueInCreditCard;

    #[Decimal(10,2)]
    public float $installmentsFromCreditCard;

    #[Decimal(10,2)]
    public float $valueInCash;

    #[Decimal(10,2)]
    public float $amountReceived;

    #[Decimal(10,2)]
    public float $valueChange;

    #[Text(255)]
    public string $observations;
  }
}