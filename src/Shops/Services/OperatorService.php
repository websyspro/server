<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\OperatorRepository;
use Websyspro\Server\Shops\Repositories\ProductGroupRepository;
use Websyspro\Utils\Collection;

class OperatorService
{
  public function __construct(
    private readonly OperatorRepository $repo
  ){}

  public function getList(
  ): Collection {
    return $this->repo->getList();
  } 
}