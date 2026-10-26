<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Server\Shops\Services\BoxService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;

#[Controller("box")]
class BoxController
{
  public function __construct(
    private readonly BoxService $boxService
  ){}

  #[Get()]
  public function getList(
  ): mixed {
    return $this->boxService->list();
  }  
}