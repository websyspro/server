<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\ProductGroupEntity;

class ProductGroupRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(
      ProductGroupEntity::class
    )
  ){}
  
  public function getList(
  ): mixed {
    return $this->repo
      ->select( 
        fn( ProductGroupEntity $i ) => [
          $i->id,
          $i->name
        ]
      )
      ->rows();
  }
}