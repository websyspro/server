<?php

namespace Websyspro\Server\Applications\Shops\Repositorys;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Shops\Entitys\DocumentEntity;
use Websyspro\Server\Applications\Shops\Enums\Document\EState;

class DocumentRepository
{
  public function __construct(
    public Repository $repo = (
      new Repository(
        DocumentEntity::class
      )
    )
  ){}

  public function ByCustomer(
    int $CustomerId,
    string $State  
  ): DataList {
    return $this->repo->Where(
      fn(DocumentEntity $d) => (
        $d->CustomerId == $CustomerId && 
        $d->Deleted == false &&
        $d->Actived == true && 
        $d->State == $State
      )
    )->All();
  }
}