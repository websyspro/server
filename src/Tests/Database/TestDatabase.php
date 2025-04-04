<?php

namespace Websyspro\Server\Tests\Database
{
  use Websyspro\Server\Decorators\EntityList;
  use Websyspro\Server\Tests\Entitys\TestEntity;
  use Websyspro\Server\Tests\Entitys\TestForeignKeyEntity;

  #[EntityList([
    TestEntity::class,
    TestForeignKeyEntity::class
  ])]
  class TestDatabase {}
}