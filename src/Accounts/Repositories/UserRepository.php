<?php

namespace Websyspro\Server\Accounts\Repositories;

use Websyspro\Server\Accounts\Entities\UserEntity;
use Websyspro\ArrowToSql\EntityStructure;
use Websyspro\ArrowToSql\Repository;


class UserRepository
{
  public function __construct(
    private Repository $repository = new Repository(UserEntity::class)
  ){}

  public function findAll(
    string $email
  ): mixed {
    // $repository = new Repository(UserEntity::class);
    $this->repository->where( fn( UserEntity $u ) => (
      $email == $u->email
      && $u->name == null
      && !$u->name != "TEST%" 
      // && !$u->name
      // && !$u->isDeleted
      // && $u->deletedAt >= "01/03/2026"
      // && !$u->deletedAt->toDate()->between( "01/01/2026", "31/01/2026" )
      // && !$u->name->contains( "TEST 1", "TEST 2" )
      // && !$u->name->notIn( "TEST 1", "TEST 2" )
      // && !$u->name == [ "TEST 1", "TEST 2" ]
      // && "31/03/2026" >= $u->deletedAt
      // && !$u->isActive && ( $u->name == "TEST" )
      // && !$u->access->any( fn(AccessEntity $a ) => $a->isActive )
    ));

    $this->repository->select( fn( UserEntity $u ) => [
      $u->sum(( $u->Id / $u->balance ) * $u->balance ), $u->name->trim()
    ]);

    return $this->repository;
  }

  public function findById(
  ): mixed {
    return new EntityStructure(UserEntity::class);
  }
}
