<?php

namespace Websyspro\Server\Applications\Accounts\Repositorys;

use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Accounts\Entitys\UserEntity;

class UserRepository
{
  public function __construct(
    private Repository $repo = new Repository(
      UserEntity::class
    )
  ){}

  public function CreateUser(
    string $Name,
    string $Email
  ): UserEntity {
    return $this->repo->Insert(
      fn(UserEntity $i) => [
        $i->Name = $Name,
        $i->Email = $Email
      ]
    ); 
  }

  public function GetByName(
    string $Name
  ): UserEntity {
    return $this->repo->Where(
      fn(UserEntity $i) => $i->Name == $Name
    )->One();
  }

  public function GetByEmail(
    string $Email    
  ): UserEntity {
    return $this->repo->Where(
      fn(UserEntity $i) => $i->Email == $Email && $i->Actived == true && $i->Deleted == false
    )->One();
  }
}