<?php

namespace Websyspro\Server\Applications\Accounts\Services;

use Websyspro\Logger\Message;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Server\Applications\Accounts\Entitys\UserEntity;
use Websyspro\Server\Applications\Accounts\Repositorys\CredentialRepository;
use Websyspro\Server\Applications\Accounts\Repositorys\ProfileRepository;
use Websyspro\Server\Applications\Accounts\Repositorys\UserProfileRepository;
use Websyspro\Server\Applications\Accounts\Repositorys\UserRepository;

class UserService
{
  public function __construct(
    private UserRepository $userRepository,
    private ProfileRepository $profileRepository,
    private CredentialRepository $credentialRepository,
    private UserProfileRepository $userProfileRepository
  ){}

  public function InitUser(
  ): void {
    $this->CreateUserAdmin();
  }

  public function CreateUserAdmin(
  ): void {
    $user = $this->userRepository
      ->GetByName("ADMIN");
      
    if($user->Exist() === false){
      $user = $this->userRepository->CreateUser(
        "ADMIN", "admin@localhost"
      );
        
      $this->credentialRepository->CreateCredential(
        $user->Id, password_hash("@Qazwsx190483", PASSWORD_BCRYPT)
      );

      $profileSuperAdmin = $this->profileRepository
        ->GetByName("super-admin");

      if($profileSuperAdmin->Exist() === true){
        $this->userProfileRepository->CreateUserProfile(
          $user->Id, $profileSuperAdmin->Id
        );
      }

      Message::Infors(LogType::Service, "User ADMIN created successfully"); 
      Message::Infors(LogType::Service, "Credential for User ADMIN created successfully"); 
    }
  }

  public function GetByEmail(
    string $Email    
  ): UserEntity {
    return $this->userRepository->GetByEmail($Email);
  }
}