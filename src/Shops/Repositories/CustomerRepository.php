<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;
use Websyspro\Server\Shops\Entities\CustomerEntity;
use Websyspro\Server\Shops\Entities\DocumentEntity;
use Websyspro\Server\Shops\Entities\DocumentItemEntity;
use Websyspro\Server\Shops\Entities\ProductEntity;
use Websyspro\Server\Shops\Entities\ProductGroupEntity;

class CustomerRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(
      CustomerEntity::class
    )
  ){}
  
  public function getList(
  ): mixed {
    return $this->repo
      ->include( 
        fn( CustomerEntity $i ) => $i->documents
          ->include(
            fn( DocumentEntity $i ) => $i->box,
            fn( DocumentEntity $i ) => $i->operator,
            fn( DocumentEntity $i ) => $i->items
              ->include(
                fn( DocumentItemEntity $i ) => $i->product
                  ->include( 
                    fn( ProductEntity $i ) => $i->productGroup
                  )
              )
          )
      )
      ->where( fn( CustomerEntity $i ) => (
        $i->cpf == "063.140.123-76"
      ))
      ->paged(1, 4)
      ->rows();
  }
}