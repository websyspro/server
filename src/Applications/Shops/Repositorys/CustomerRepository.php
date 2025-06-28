<?php

namespace Websyspro\Server\Applications\Shops\Repositorys;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Shops\Entitys\CustomerEntity;

class CustomerRepository
{
  public function __construct(
    private Repository $repo = new Repository(
      CustomerEntity::class
    )
  ){}

  public function GetByCpf(
    string $cpf
  ): CustomerEntity {
    return $this->repo
      ->Where(fn(CustomerEntity $c) => $c->Cpf == $cpf)
      ->Select(fn(CustomerEntity $c) => [
        $c->Id, $c->Cpf, $c->Name, $c->LastPurchaseAt
      ])
      ->One();
  }
}