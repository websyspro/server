<?php

namespace Websyspro\Server\Applications\Accounts\Services;

use Websyspro\Commons\DataList;
use Websyspro\Server\Applications\Accounts\Entitys\UserProfileEntity;
use Websyspro\Server\Applications\Accounts\Repositorys\ProfileRepository;
use Websyspro\Server\Applications\Accounts\Repositorys\UserProfileRepository;

class UserProfileService
{
  public function __construct(
    public ProfileRepository $profileRepository,
    public UserProfileRepository $userProfileRepository
  ){}

  public function ExistsUserProfile(
    int $UserId,
    int $ProfileId
  ): UserProfileEntity {
    return $this->userProfileRepository
      ->ExistsUserProfile($UserId, $ProfileId);
  }

  public function GetByUser(
    int $UserId
  ): array {
    return (
      $this->userProfileRepository
        ->GetByUser($UserId)
        ->Mapper(fn(UserProfileEntity $i) => (
          $this->profileRepository->GetById(
            $i->ProfileId
          )
        ))
        ->All()
    );
  }
}