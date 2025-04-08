<?php

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Decorations\Conntrollers\Body;
use Websyspro\Server\Decorations\Conntrollers\Controller;
use Websyspro\Server\Decorations\Conntrollers\Get;
use Websyspro\Server\Decorations\Conntrollers\Post;
use Websyspro\Server\Decorations\Conntrollers\Query;
use Websyspro\Server\Decorations\Entitys\Columns\Number;
use Websyspro\Server\Decorations\Entitys\Columns\Varchar;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Decorations\Middlewares\FileValidator;
use Websyspro\Server\Interfaces\Conntrollers\IMethod;
use Websyspro\Server\Server\ControllerStructure;

#[Controller( "controller" )]
#[Authenticate()]
class TestEntity
{
  #[Number()]
  public int $id;

  #[Varchar()]
  public string $name;

  public function __construct(
  ){}

  #[Post( "test/post" )]
  public function getList(
    #[Body( "testBody" )] array $bodyRows,
    #[Query( "testQuery" )] array $queryRows
  ): array {
    return [ $bodyRows, $queryRows ];
  }

  #[Get( "test/user" )]
  #[FileValidator()]
  public function getUser(
    #[Body( "testBody" )] array $bodyRows,
  ): array {
    return [ $bodyRows ];
  }
}

$controllerStructure = new ControllerStructure(
  TestEntity::class
);

Util::Mapper(
  $controllerStructure->endpoints, fn( IMethod $imethod ) => (
    $imethod->setExecute()
  )
);