<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Server\Shops\Services\BoxService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Param;

#[Controller("box")]
class BoxController
{
  public function __construct(
    private readonly BoxService $boxService
  ){}

  #[Get("/:boxId")]
  public function getList(
    #[Param("boxId")] int $boxId
  ): mixed {
    return [ $boxId ];
    // return $this->boxService->list( $boxId );
  }  
}