<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Utils\Collection;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\Server\Shops\Services\BoxService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Requests\Param;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;


#[Controller("box")]
#[Authenticate()]
class BoxController
{
  public function __construct(
    private readonly BoxService $boxService
  ){}

  #[Get("/:boxId")]
  public function getList(
    #[Param("boxId")] int $boxId
  ): Collection {
    return $this->boxService->list( $boxId );
  }
}