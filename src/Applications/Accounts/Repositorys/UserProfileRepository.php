<?php

namespace Websyspro\Server\Applications\Accounts\Repositorys;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Accounts\Entitys\ProfileEntity;
use Websyspro\Server\Applications\Accounts\Entitys\UserProfileEntity;

class UserProfileRepository
{
  public function __construct(
    private Repository $repo = new Repository(
      UserProfileEntity::class
    )
  ){}

  public function CreateUserProfile(
    int $UserId,
    int $ProfileId
  ): UserProfileEntity {
    return $this->repo->Insert(
      fn(UserProfileEntity $i) => [
        $i->UserId = $UserId,
        $i->ProfileId = $ProfileId
      ]
    );
  }

  public function ExistsUserProfile(
    int $UserId,
    int $ProfileId
  ): UserProfileEntity {
    return $this->repo->Where(
      fn(UserProfileEntity $i) => $i->UserId == $UserId && $i->ProfileId == $ProfileId
    )->One();
  }

  public function GetByUser(
    int $UserId
  ): DataList {
    return (
      $this->repo->Where(
        fn(UserProfileEntity $i) => (
          $i->UserId == $UserId && 
          $i->Actived == true && 
          $i->Deleted == false
        )
      )->All()
    );
  }  
}