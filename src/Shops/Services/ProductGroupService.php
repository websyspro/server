<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\ProductGroupRepository;
use Websyspro\Utils\Collection;

class ProductGroupService
{
  public function __construct(
    private readonly ProductGroupRepository $repo
  ){}

  public function getList(
  ): Collection {
    return $this->repo->getList();
  } 
}