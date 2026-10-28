<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Server\Shops\Services\BoxService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;
use Websyspro\WorkerServer\Decorators\Requests\Param;


#[Controller("box")]
#[Authenticate()]
class BoxController
{
  public function __construct(
    private readonly BoxService $boxService
  ){}

  #[Get("/:boxId")]
  #[AllowAnonymous()]
  public function getList(
    #[Param("boxId")] int $boxId
  ): mixed {
    return $this->boxService->list( $boxId );
  }  
}