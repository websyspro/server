<?php

use Websyspro\Server\Databases\Entity\BaseEntity;
use Websyspro\Server\Databases\Structure\StructureDesignTable;
use Websyspro\Server\Decorations\Conntrollers\Controller;
use Websyspro\Server\Decorations\Conntrollers\ControllerList;
use Websyspro\Server\Decorations\Conntrollers\Get;
use Websyspro\Server\Decorations\Conntrollers\Param;
use Websyspro\Server\Decorations\Conntrollers\Post;
use Websyspro\Server\Decorations\Databases\EntityList;
use Websyspro\Server\Decorations\Entitys\Columns\Datetime;
use Websyspro\Server\Decorations\Entitys\Columns\Decimal;
use Websyspro\Server\Decorations\Entitys\Columns\Number;
use Websyspro\Server\Decorations\Entitys\Columns\Text;
use Websyspro\Server\Decorations\Entitys\Constraints\ForeignKey;
use Websyspro\Server\Decorations\Entitys\Constraints\Unique;
use Websyspro\Server\Decorations\Entitys\Statistics\Index;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Server\Application;
use Websyspro\Server\Server\Response;

class OperatorEntity
extends BaseEntity
{
  #[Text(64)]
  #[Unique(1)]
  public string $Name;
}

class BoxEntity 
extends BaseEntity
{
  #[Text(32)]
  #[Index()]
  #[Unique()]
  public string $Name;

  #[Text(1)]
  public string $State;

  #[Number()]
  #[ForeignKey(OperatorEntity::class)]
  public string $OperatorId;

  #[Text(255)]
  public string $Printer;

  #[Decimal()]
  public string $OpeningAt;

  #[Decimal(10,2)]
  public string $OpeningBalance;
}

class CustomerEntity
extends BaseEntity
{
  #[Text(255)]
  public string $Name;

  #[Text(14)]
  #[Unique()]
  public string $Cpf;

  #[Datetime()]
  public string $LastPurchaseAt;
}

class DocumentEntity
extends BaseEntity
{
  #[Text(1)]
  #[Index(1)]
  public string $Type;

  #[Text(1)]
  public string $State;

  #[Number()]
  #[Index(2)]
  #[ForeignKey(BoxEntity::class)]
  public int $BoxId;

  #[Number()]
  #[Index(2)]
  #[ForeignKey(OperatorEntity::class)]
  public int $OperatorId;

  #[Number()]
  #[ForeignKey(CustomerEntity::class)]
  public int $CustomerId;

  #[Decimal(10,2)]
  public float $Value;

  #[Decimal(10,2)]
  public float $ValueInPix;

  #[Decimal(10,2)]
  public float $ValueInDebitCard;

  #[Decimal(10,2)]
  public float $ValueInCreditCard;

  #[Decimal(10,2)]
  public float $InstallmentsFromCreditCard;

  #[Decimal(10,2)]
  public float $ValueInCash;

  #[Decimal(10,2)]
  public float $AmountReceived;

  #[Decimal(10,2)]
  public float $ValueChange;

  #[Text(255)]
  public string $Observations;
}

class CashMovementEntity
extends BaseEntity
{
  #[Text(1)]
  public string $Type;

  #[Text(3)]
  public string $PaymentMethod;

  #[Number()]
  #[Index()]
  #[ForeignKey(DocumentEntity::class)]
  public int $DocumentId;

  #[Number()]
  #[Index()]
  #[ForeignKey(BoxEntity::class)]
  public int $BoxId;

  #[Decimal(10,2)]
  public int $Number;

  #[Text(255)]
  public int $observations;
}

class ConfigEntity
extends BaseEntity
{
  #[Text(32)]
  public string $PasswordReleaseDiscount;

  #[Decimal(10,2)]
  public string $PurchaseLimitPerCustomer;

  #[Number()]
  #[ForeignKey(BoxEntity::class)]
  public string $MainBox;
}

class ProductGroupEntity
extends BaseEntity
{
  #[Text(64)]
  public string $Name;
}

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

class DocumentItemEntity
extends BaseEntity
{
  #[Number()]
  #[ForeignKey(DocumentEntity::class)]
  public string $DocumentId;

  #[Number()]
  #[ForeignKey(ProductEntity::class)]
  public string $ProductId;

  #[Decimal(10,2)]
  public float $Value;

  #[Decimal(10,2)]
  public float $Amount;

  #[Decimal(10,2)]
  public float $Discount;

  #[Decimal(10,2)]
  public float $TotalValue;
}


#[EntityList([
  BoxEntity::class,
  ConfigEntity::class,
  CustomerEntity::class,
  OperatorEntity::class,
  DocumentEntity::class,
  DocumentItemEntity::class,
  CashMovementEntity::class,
  ProductGroupEntity::class,
  ProductEntity::class,
])]
class AccountsDatabase {}

#[Authenticate()]
#[Controller( "designs" )]
class DesignsController
{
  #[Get()]
  #[AllowAnonymous()]
  public function get(
  ): Response {
    return Response::json(
      new StructureDesignTable(
        BoxEntity::class
      )
    );
  }

  #[Post( "accounts/:email" )]
  #[AllowAnonymous()]
  public function getAccoutsUserEmail(
    #[Param()] array $param
  ): Response {
    return Response::json( $param );
  }
}

#[ControllerList([
  DesignsController::class
])]
class DevControllers {}

Application::server(
  controllers: [
    DevControllers::class 
  ],
  databases: [
    AccountsDatabase::class
  ]
);