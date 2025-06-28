<?php

namespace Websyspro\Server\Applications\Accounts\Services;

class InitService
{
  public function __construct(
    private UserService $userService,
    private ProfileService $profileService
  ){
    $this->profileService->InitProfile();
    $this->userService->InitUser();
  }
}