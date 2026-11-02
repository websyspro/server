<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\BoxRepository;
use Websyspro\Server\Shops\Repositories\ProductRepository;
use Websyspro\Utils\Collection;

class ProductService
{
  public function __construct(
    private readonly ProductRepository $repo
  ){}

  public function getList(
  ): Collection {
    return $this->repo->getList();
  } 
}