<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\BoxEntity;

class BoxRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(BoxEntity::class)
  ){}

  public function list(
  ): array {
    return $this->repo
      ->where(
        fn( BoxEntity $i ) => $i->isActived == true && $i->isDeleted == false
      )
      ->select(
        fn( BoxEntity $i ) => [
          $i->id, $i->name, $i->openingAt, $i->printer, $i->isActived, $i->operatorId
        ]
      )->rows();
  }
}