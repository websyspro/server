<?php

namespace Websyspro\Server\Applications\Accounts\Services;

use Websyspro\Logger\Enums\LogType;
use Websyspro\Logger\Message;
use Websyspro\Server\Applications\Accounts\Entitys\ProfileEntity;
use Websyspro\Server\Applications\Accounts\Repositorys\ProfileRepository;

class ProfileService
{
  public function __construct(
    private ProfileRepository $profileRepository
  ){} 

  public function InitProfile(
  ): void {
    $profile = $this->profileRepository
      ->GetByName("super-admin");

    if($profile->Exist() === false){
      $profile = $this->profileRepository
        ->CreateProfile("super-admin");

      if($profile->Exist() == true){
        Message::Infors(LogType::Service, "Profile SUPER ADMIN criado com sucesso");  
      }  
    }
  }

  public function GetById(
    int $Id
  ): ProfileEntity {
    return $this->profileRepository->GetById($Id);
  }
}