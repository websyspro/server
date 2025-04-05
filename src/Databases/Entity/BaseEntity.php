<?php

namespace Websyspro\Server\Databases\Entity
{
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Columns\Datetime;
  use Websyspro\Server\Decorators\Entity\Columns\TimyInt;
  use Websyspro\Server\Decorators\Entity\Constraints\PrimaryKey;
  use Websyspro\Server\Decorators\Entity\Generations\AutoIncrement;
  use Websyspro\Server\Decorators\Entity\Requireds\NotNull;

  class BaseEntity
  {
    #[NotNull()]
    #[BigNumber()]
    #[PrimaryKey()]
    #[AutoIncrement()]    
    public int $id;

    #[TimyInt()]
    #[NotNull()]
    public bool $actived;

    #[NotNull()]
    #[BigNumber()]
    public int $activedBy;

    #[NotNull()]
    #[Datetime()]
    public string $activedAt;

    #[NotNull()]
    #[BigNumber()]    
    public int $createdBy;

    #[NotNull()]
    #[Datetime()]    
    public string $createdAt;

    #[BigNumber()]
    public int $updatedBy;

    #[Datetime()]
    public string $updatedAt;

    #[TimyInt()]
    public bool $deleted;

    #[BigNumber()]
    public int $deletedBy;

    #[Datetime()]
    public string $deletedAt;
  }
}