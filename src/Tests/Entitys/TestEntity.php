<?php

namespace Websyspro\Server\Tests\Entitys
{
  use Websyspro\Server\Decorators\Entity\Columns\BigNumber;
  use Websyspro\Server\Decorators\Entity\Columns\Datetime;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\ForeignKey;
  use Websyspro\Server\Decorators\Entity\Constraints\Index;
  use Websyspro\Server\Decorators\Entity\Constraints\PrimaryKey;
  use Websyspro\Server\Decorators\Entity\Constraints\Unique;
  use Websyspro\Server\Decorators\Entity\Generations\AutoIncrement;
  use Websyspro\Server\Decorators\Entity\Requireds\NotNull;
  use Websyspro\Server\Decorators\Entity\Triggers\BeforeCreate;
  use Websyspro\Server\Decorators\Entity\Util\Now;

  class TestEntity
  {
    #[NotNull()]
    #[BigNumber()]
    #[PrimaryKey()]
    #[AutoIncrement()]
    public int $id;

    #[NotNull()]
    #[BigNumber()]
    #[PrimaryKey()]
    #[ForeignKey(TestForeignKeyEntity::class)]
    public int $testForeignKeyId;

    #[Text(64)]
    #[Index()]
    public string $name;

    #[Datetime()]
    #[BeforeCreate(Now::class)]
    public string $createdAt;

    #[Unique()]
    #[NotNull()]
    #[Datetime()]
    #[BeforeCreate(Now::class)]    
    public string $updatedAt;
  }
}