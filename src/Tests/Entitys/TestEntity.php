<?php

namespace Websyspro\Server\Tests\Entitys
{
  use Websyspro\Server\Databases\Entity\EntityBase;
  use Websyspro\Server\Decorators\Entity\Columns\Text;
  use Websyspro\Server\Decorators\Entity\Constraints\Index;

  class TestEntity 
  extends EntityBase
  {
    #[Text(64)]
    #[Index()]
    public string $name;
  }
}