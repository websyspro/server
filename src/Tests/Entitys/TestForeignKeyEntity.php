<?php

namespace Websyspro\Server\Tests\Entitys
{
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Constraints\PrimaryKey;
  use Websyspro\Server\Decorators\Entity\Generations\AutoIncrement;
  use Websyspro\Server\Decorators\Entity\Requireds\NotNull;

  class TestForeignKeyEntity
  {
    #[NotNull()]
    #[BigNumber()]
    #[PrimaryKey()]
    #[AutoIncrement()]
    public string $id;
  }
}