<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;

class CashMovementRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(
      CashMovementEntity::class
    )
  ){}
  
  public function getResumeByBox(
    int $boxId
  ): mixed {
    return $this->repo
      ->include(
        fn(CashMovementEntity $i) => $i->box->include(
          fn( BoxEntity $b ) => $b->operator
        )
      )
      ->where( 
        fn(CashMovementEntity $i) => (
          $i->type == "E" 
          && $i->boxId == $boxId
          && $i->paymentMethod == "CC"
        )   
      )
      ->select(
        fn(CashMovementEntity $i) => [
          $i->boxId,
          $i->sum( $i->value )->as( "value" )
        ]
      )
      ->groupBy(
        fn(CashMovementEntity $i) => $i->boxId
      )
      ->rows();
  }
}