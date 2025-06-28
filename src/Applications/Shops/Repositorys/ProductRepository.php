<?php

namespace Websyspro\Server\Applications\Shops\Repositorys;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Shops\Entitys\ProductEntity;

class ProductRepository
{
  public function __construct(
    public Repository $repo = new Repository(
      ProductEntity::class
    )
  ){}

  public function GetByName(
    string $Name
  ): DataList {
    return (
      $this->repo->Where(
        fn(ProductEntity $i) => (
          $i->Name == "%{$Name}%" &&
          $i->Actived == true && 
          $i->Deleted == false
        )
      )->All()
    );
  }
}