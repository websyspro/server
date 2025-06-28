<?php

namespace Websyspro\Server\Applications\Accounts\Services;

use Websyspro\Server\Applications\Accounts\Entitys\CredentialEntity;
use Websyspro\Server\Applications\Accounts\Repositorys\CredentialRepository;

class CredentialService
{
  public function __construct(
    private CredentialRepository $credentialRepository
  ){}

  public function GetCredentialByUser(
    int $UserId
  ): CredentialEntity {
    return (
      $this->credentialRepository
        ->GetCredentialByUser($UserId)
    );
  }

  public function UpdateCredentialByUser(
    int $Id,
    int $UserId,
    string $Hash
  ): CredentialEntity {
    return (
      $this->credentialRepository
        ->UpdateCredentialByUser(
          $Id, $UserId, password_hash(
            $Hash, PASSWORD_DEFAULT, [ "const" => 12 ]
          )
        )
    );
  }
}