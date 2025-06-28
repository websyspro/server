<?php

namespace Websyspro\Server\Applications\Accounts\Repositorys;

use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Accounts\Entitys\ProfileEntity;

class ProfileRepository
{
  public function __construct(
    private Repository $repo = new Repository(
      ProfileEntity::class
    )
  ){}

  public function CreateProfile(
    string $Name
  ): ProfileEntity {
    return $this->repo->Insert(
      fn(ProfileEntity $i) => [
        $i->Name == $Name
      ]
    );
  }

  public function GetByName(
    string $Name
  ): ProfileEntity {
    return $this->repo->Where(
      fn(ProfileEntity $i) => $i->Name == $Name
    )->One();
  }

  public function GetById(
    int $Id
  ): ProfileEntity {
    return $this->repo
      ->Where(
        fn(ProfileEntity $i) => (
          $i->Id == $Id &&
          $i->Actived == true &&
          $i->Deleted == false
        )
      )
      ->Select(fn(ProfileEntity $s) => [
        $s->Id, $s->Name
      ])
      ->One();
  }
}