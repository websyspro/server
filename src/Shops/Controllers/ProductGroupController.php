<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;
use Websyspro\Server\Shops\Services\ProductGroupService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\Utils\Collection;


#[Controller("product-group")]
#[Authenticate()]
class ProductGroupController
{
  public function __construct(
    private readonly ProductGroupService $service
  ){}

  #[Get()]
  #[AllowAnonymous()]
  public function getList(
  ): Collection {
    return $this->service->getList();
  }
}