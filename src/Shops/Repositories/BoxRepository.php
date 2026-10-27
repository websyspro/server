<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\ArrowToSql\Repository;

class BoxRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(BoxEntity::class)
  ){}

  public function list(
    int $boxId
  ): array {
    return $this->repo
      //->include( fn( BoxEntity $i ) => $i->operator )
      ->where(
        fn( BoxEntity $i ) => (
          $i->isActived == true
          && $i->isDeleted == false 
          && $i->id == $boxId
        )
      )
      ->select(
        fn( BoxEntity $i ) => [
          $i->id, 
          $i->name, 
          $i->openingAt,
          $i->printer,
          $i->isActived,
          $i->operatorId
        ]
      )->rows();
  }
}