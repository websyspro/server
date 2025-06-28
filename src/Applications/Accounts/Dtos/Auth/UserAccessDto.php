<?php

namespace Websyspro\Server\Applications\Accounts\Dtos\Auth;

class UserAccessDto
{
  public function __construct(
    public int $userId,
    public string $name,
    public string $email,
    public array $profiles
  ){}
}