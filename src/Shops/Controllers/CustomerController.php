<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;
use Websyspro\Server\Shops\Services\CustomerService;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\Utils\Collection;


#[Controller("customer")]
#[Authenticate()]
class CustomerController
{
  public function __construct(
    private CustomerService $customerService
  ){}

  #[Get()]
  #[AllowAnonymous()]
  public function getList(
  ): Collection {
    return $this->customerService->getList();
  }
}