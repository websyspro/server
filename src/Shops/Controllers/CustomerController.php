<?php

namespace Websyspro\Server\Shops\Controllers;

use Websyspro\Server\Shops\Services\CustomerService;
use Websyspro\Utils\Collection;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;
use Websyspro\Entity\Schemas\SqLiteEntityStructure;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\WorkerServer\Decorators\Get;
use ReflectionClass;


#[Controller("customer")]
#[Authenticate()]
class CustomerController
{
  public function __construct(
    private CustomerService $customerService
  ){}

  #[Get("/")]
  #[AllowAnonymous()]
  public function getList(
  ): Collection {
    return $this->customerService->getList();
  }
}