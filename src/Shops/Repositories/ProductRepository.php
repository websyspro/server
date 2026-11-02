<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\ProductEntity;

class ProductRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(
      ProductEntity::class
    )
  ){}
  
  public function getList(
  ): mixed {
    return $this->repo
      ->paged(1, 12)
      ->rows();
  }
}