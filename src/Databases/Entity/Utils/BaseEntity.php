<?php

namespace Websyspro\Server\Databases\Entity\Utils;

use Websyspro\Server\Databases\Entity\Utils\Now;
use Websyspro\Server\Decorations\Entitys\Columns\Datetime;
use Websyspro\Server\Decorations\Entitys\Columns\Flag;
use Websyspro\Server\Decorations\Entitys\Columns\Number;
use Websyspro\Server\Decorations\Entitys\Constraints\PrimaryKey;
use Websyspro\Server\Decorations\Entitys\Events\BeforeDelete;
use Websyspro\Server\Decorations\Entitys\Events\BeforeInsert;
use Websyspro\Server\Decorations\Entitys\Events\BeforeUpdate;
use Websyspro\Server\Decorations\Entitys\Generations\AutoIncrement;
use Websyspro\Server\Decorations\Entitys\Requireds\NotNull;

class BaseEntity
{
  #[NotNull()]
  #[Number()]
  #[PrimaryKey()]
  #[AutoIncrement()]    
  public int $Id;

  #[Flag()]
  #[NotNull()]
  #[BeforeInsert(1)]
  public bool $Actived;

  #[NotNull()]
  #[Number()]
  #[BeforeInsert(1)]
  public int $ActivedBy;

  #[NotNull()]
  #[Datetime()]
  #[BeforeInsert(Now::class)]
  public string $ActivedAt;

  #[NotNull()]
  #[Number()]
  #[BeforeInsert(1)] 
  public int $CreatedBy;

  #[NotNull()]
  #[Datetime()]
  #[BeforeInsert(Now::class)]
  public string $CreatedAt;

  #[Number()]
  #[BeforeUpdate(1)]
  public int $UpdatedBy;

  #[Datetime()]
  #[BeforeUpdate(Now::class)]
  public string $UpdatedAt;

  #[Flag()]
  #[BeforeDelete(1)]
  #[BeforeInsert(0)]
  public bool $Deleted;

  #[Number()]
  #[BeforeDelete(1)]
  public int $DeletedBy;

  #[Datetime()]
  #[BeforeDelete(Now::class)]
  public string $DeletedAt;
}