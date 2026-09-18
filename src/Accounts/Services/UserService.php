<?php

namespace Websyspro\Server\Accounts\Services;

use Websyspro\Server\Accounts\Repositories\UserRepository;

class UserService
{
  public function __construct(
    private UserRepository $userRepository
  ){}

  public function findAll( 
    string $email
  ): mixed {
    return $this->userRepository->findAll($email);
  }

  public function findById(
  ): mixed {
    return $this->userRepository->findById();
  }
}
