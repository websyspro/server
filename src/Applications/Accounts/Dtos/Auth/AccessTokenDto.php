<?php

namespace Websyspro\Server\Applications\Accounts\Dtos\Auth;

use Websyspro\Server\Applications\Accounts\Entitys\UserEntity;

class AccessTokenDto
{
  public function __construct(
    public string $accessToken,
    public int $expiresIn,
    public UserAccessDto $user
  ){}
}