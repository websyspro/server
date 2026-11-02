<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\ArrowToSql\Repository;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Requests\Param;
use Websyspro\Server\Shops\Services\CashMovementService;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;


#[Controller("cash")]
#[Authenticate()]
class CashMovementController
{
  public function __construct(
    private readonly CashMovementService $cashMovementService
  ){}

  #[Get("/:boxId")]
  #[AllowAnonymous()]
  public function getResumeByBox(
    #[Param("boxId")] int $boxId
  ): mixed {
    return $this->cashMovementService->getResumeByBox( $boxId );
  }
}