<?php

namespace Websyspro\Server\Tests\Controllers;

use Exception;
use Websyspro\Server\Databases\StructureData;
use Websyspro\Server\Tests\Services\UserService;
use Websyspro\Server\Decorators\AllowAnonymous;
use Websyspro\Server\Decorators\FileValidate;
use Websyspro\Server\Decorators\Authenticate;
use Websyspro\Server\Decorators\Controller;
use Websyspro\Server\Decorators\HttpPost;
use Websyspro\Server\Decorators\HttpGet;
use Websyspro\Server\Decorators\HttpPut;
use Websyspro\Server\Decorators\Query;
use Websyspro\Server\Decorators\Param;
use Websyspro\Server\Decorators\Body;
use Websyspro\Server\Decorators\File;
use Websyspro\Server\Http\Response;
use Websyspro\Server\Tests\Database\TestDatabase;

#[Authenticate()]
#[Controller("user")]
class UserController
{
  public function __construct(
    private readonly UserService $userService
  ){}

  #[HttpGet( "create/perfil" )]
  public function createPerfil(
  ): void {}

  #[AllowAnonymous()]
  #[FileValidate()]
  #[HttpPost( "create/access/:email/:username" )]
  public function createAccessoPost(
    #[Body()] array $body,
    #[File()] array $file,
    #[Param()] array $param
  ): Response {
    return Response::json($param);
  }

  #[AllowAnonymous()]
  #[FileValidate()]
  #[HttpGet( "create/access/:email/:username" )]
  public function createAccessoGet(
    #[Body()] mixed $body,
    #[File()] mixed $file
  ): Response {
    return Response::json($body);
  }

  #[AllowAnonymous()]
  #[FileValidate()]
  #[HttpPut( "create/access/:email/:username" )]
  public function createAccessoPut(
    #[Body()] array $body,
    #[Query()] array $query,
    #[File()] array $file,
    #[Param()] array $param
  ): Response {
    return Response::json($this->userService->getUser());
  }

  #[AllowAnonymous()]
  #[HttpGet( "entity" )]
  public function entity(
  ): Response {
    return (
      Response::json(
        new StructureData([
          TestDatabase::class
        ])
      )
    );
  }
}