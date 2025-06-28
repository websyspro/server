<?php

namespace Websyspro\Server\Applications\Accounts\Repositorys;

use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Accounts\Entitys\CredentialEntity;

class CredentialRepository
{
  public function __construct(
    private Repository $repo = new Repository(
      CredentialEntity::class
    )
  ){}

  public function CreateCredential(
    int $UserId,
    string $Hash
  ): CredentialEntity {
    return $this->repo->Insert(
      fn(CredentialEntity $i) => [
        $i->UserId = $UserId,
        $i->Hash = $Hash
      ]
    );
  }

  public function GetCredentialByUser(
    int $UserId
  ): CredentialEntity {
    return $this->repo->Where(
      fn(CredentialEntity $i) => (
        $i->UserId == $UserId && 
        $i->Actived == true && 
        $i->Deleted == false
      )
    )->One();
  }

  public function UpdateCredentialByUser(
    int $Id,
    int $UserId,
    string $Hash
  ): CredentialEntity {
      $this->repo
        ->Update(fn(CredentialEntity $i) => [
          $i->UserId == $UserId,
          $i->Hash == $Hash,
          $i->Id == $Id,
        ]);

    return new $this->repo->table;
  }
}