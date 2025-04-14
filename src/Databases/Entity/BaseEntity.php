<?php

namespace Websyspro\Server\Databases\Entity;

use Websyspro\Server\Decorations\Entitys\Columns\Datetime;
use Websyspro\Server\Decorations\Entitys\Columns\Flag;
use Websyspro\Server\Decorations\Entitys\Columns\Number;
use Websyspro\Server\Decorations\Entitys\Constraints\PrimaryKey;
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