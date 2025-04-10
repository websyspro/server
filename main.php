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
use Websyspro\Server\Decorations\Entitys\Columns\Varchar;
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
  #[Varchar(64)]
  #[Unique(1)]
  public string $Name;
}

class BoxEntity 
extends BaseEntity
{
  #[Varchar(32)]
  #[Index()]
  #[Unique()]
  public string $Name;

  #[Varchar(1)]
  public string $State;

  #[Number()]
  #[ForeignKey(OperatorEntity::class)]
  public string $OperatorId;

  #[Varchar(255)]
  public string $Printer;

  #[Decimal()]
  public string $OpeningAt;

  #[Decimal(10,2)]
  public string $OpeningBalance;
}

#[EntityList([
  BoxEntity::class,
  OperatorEntity::class
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