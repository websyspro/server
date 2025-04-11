<?php

use Websyspro\Server\Databases\Structure\StructureDatabase;
use Websyspro\Server\Decorations\Conntrollers\Controller;
use Websyspro\Server\Decorations\Conntrollers\ControllerList;
use Websyspro\Server\Decorations\Conntrollers\Get;
use Websyspro\Server\Decorations\Databases\EntityList;
use Websyspro\Server\Decorations\Entitys\Columns\Datetime;
use Websyspro\Server\Decorations\Entitys\Columns\Decimal;
use Websyspro\Server\Decorations\Entitys\Columns\Flag;
use Websyspro\Server\Decorations\Entitys\Columns\Number;
use Websyspro\Server\Decorations\Entitys\Columns\Text;
use Websyspro\Server\Decorations\Entitys\Constraints\ForeignKey;
use Websyspro\Server\Decorations\Entitys\Constraints\PrimaryKey;
use Websyspro\Server\Decorations\Entitys\Constraints\Unique;
use Websyspro\Server\Decorations\Entitys\Generations\AutoIncrement;
use Websyspro\Server\Decorations\Entitys\Requireds\NotNull;
use Websyspro\Server\Decorations\Entitys\Statistics\Index;
use Websyspro\Server\Server\Application;
use Websyspro\Server\Server\Response;

class BaseEntity
{
  #[NotNull()]
  #[Number()]
  #[PrimaryKey()]
  #[AutoIncrement()]    
  public int $Id;

  #[Flag()]
  #[NotNull()]
  public bool $Actived;

  #[NotNull()]
  #[Number()]
  public int $ActivedBy;

  #[NotNull()]
  #[Datetime()]
  public string $ActivedAt;

  #[NotNull()]
  #[Number()]    
  public int $CreatedBy;

  #[NotNull()]
  #[Datetime()]    
  public string $CreatedAt;

  #[Number()]
  public int $UpdatedBy;

  #[Datetime()]
  public string $UpdatedAt;

  #[Flag()]
  public bool $Deleted;

  #[Number()]
  public int $DeletedBy;

  #[Datetime()]
  public string $DeletedAt;
}

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
  public string $name;

  #[Text(14)]
  #[Unique()]
  public string $cpf;

  #[Datetime()]
  public string $lastPurchaseAt;
}

class DocumentEntity
extends BaseEntity
{
  #[Text(1)]
  #[Index(1)]
  public string $type;

  #[Text(1)]
  public string $state;

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
  public int $dBox;

  #[Decimal(10,2)]
  public int $number;

  #[Text(255)]
  public int $observations;
}

class ConfigEntity
extends BaseEntity
{
  #[Text(32)]
  public string $passwordReleaseDiscount;

  #[Decimal(10,2)]
  public string $purchaseLimitPerCustomer;

  #[Number()]
  #[ForeignKey(BoxEntity::class)]
  public string $MainBox;
}

class ProductGroupEntity
extends BaseEntity
{
  #[Text(64)]
  public string $name;
}

class ProductEntity
extends BaseEntity
{
  #[Text(255)]
  #[Index()]
  public string $name;

  #[Decimal(10,2)]
  public float $value;

  #[Number()]
  #[ForeignKey(ProductGroupEntity::class)]
  public int $idProductGroup;

  #[Text(1)]
  public string $state;

  #[Decimal(10,2)]
  public float $amount;

  #[Decimal(10,2)]
  public float $totalStock;
}

class DocumentItemEntity
extends BaseEntity
{
  #[Number()]
  #[ForeignKey(DocumentEntity::class)]
  public string $idDocument;

  #[Number()]
  #[ForeignKey(ProductEntity::class)]
  public string $idProduct;

  #[Decimal(10,2)]
  public float $value;

  #[Decimal(10,2)]
  public float $amount;

  #[Decimal(10,2)]
  public float $discount;

  #[Decimal(10,2)]
  public float $totalValue;
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

#[Controller( "designs" )]
class DesignsController
{
  #[Get()]
  public function get(
  ): Response {
    return Response::json(
      new StructureDatabase(
        AccountsDatabase::class
      )
    );
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
  databases: []
);