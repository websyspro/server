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
      ->include( fn( ProductEntity $i ) => $i->productGroup )
      ->where( fn( ProductEntity $i ) => $i->productGroupId == 3 )
      ->select( 
        fn( ProductEntity $i ) => [
          $i->id,
          $i->name,
          $i->value,
          $i->state,
          $i->amount,
          $i->productGroupId
        ]
      )
      ->rows();
  }
}