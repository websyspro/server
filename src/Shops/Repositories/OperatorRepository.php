<?php

namespace Websyspro\Server\Shops\Repositories;

use Websyspro\Server\Shops\Entities\OperatorEntity;
use Websyspro\ArrowToSql\Repository;
use Websyspro\Utils\Collection;

class OperatorRepository
{
  public function __construct(
    private readonly Repository $repo = new Repository(
      OperatorEntity::class
    )
  ){}

  public function getList(
  ): Collection {
    return $this->repo
      ->include( fn( OperatorEntity $i ) => $i->box )
      ->select( fn( OperatorEntity $i ) => 
        [
          $i->id,
          $i->name,
          $i->isActived,
          $i->createdAt
        ]
      )
      ->rows();
  }  
}