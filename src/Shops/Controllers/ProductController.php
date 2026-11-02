<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Server\Shops\Services\ProductService;
use Websyspro\Utils\Collection;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\Server\Shops\Services\BoxService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Requests\Param;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;


#[Controller("product")]
#[Authenticate()]
class ProductController
{
  public function __construct(
    private readonly ProductService $service
  ){}

  #[Get()]
  #[AllowAnonymous()]
  public function getList(
  ): Collection {
    return $this->service->getList();
  }
}