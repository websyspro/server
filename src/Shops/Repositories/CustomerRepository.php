<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;
use Websyspro\Server\Shops\Entities\CustomerEntity;

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
      ->orderByDesc( fn( CustomerEntity $i ) => $i->id )
      ->paged(1, 12)
      ->rows();
  }
}