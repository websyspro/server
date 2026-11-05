<?php

namespace Websyspro\Server\Shops\Controllers;

use ReflectionClass;
use Websyspro\Entity\Schemas\SqLiteEntityStructure;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\OperatorEntity;
use Websyspro\Server\Shops\Services\OperatorService;
use Websyspro\Utils\Collection;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Middlewares\AllowAnonymous;
use Websyspro\WorkerServer\Decorators\Middlewares\Authenticate;


#[Controller("operator")]
#[Authenticate()]
class OperatorController
{
  public function __construct(
    private readonly OperatorService $service
  ){}

  #[Get("/")]
  #[AllowAnonymous()]
  public function getList(
  ): Collection {
    return $this->service->getList();
  }
}