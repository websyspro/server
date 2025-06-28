<?php

namespace Websyspro\Server\Applications\Accounts\Services;

use Websyspro\Jwt\Encode;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Logger\Message;
use Websyspro\Server\Applications\Accounts\Dtos\Auth\AccessTokenDto;
use Websyspro\Server\Applications\Accounts\Dtos\Auth\UserAccessDto;
use Websyspro\Server\Exceptions\Error;

class AuthService
{
  public function __construct(
    private UserService $userService,
    private CredentialService $credentialService,
    private UserProfileService $userProfileService
  ){}

  public function IsAuth(
    string $username,
    string $password
  ): AccessTokenDto {
    $user = (
      $this->userService
        ->GetByEmail($username)
    );

    if($user->Exist() === false){
      Error::Unauthorized(
        "Invalid username or password"
      );
    }

    $credential = (
      $this->credentialService
        ->GetCredentialByUser($user->Id)
    );

    if($credential->Exist() === false){
      Error::Unauthorized(
        "Invalid username or password"
      );
    }

    $hashVerify = password_verify(
      $password, $credential->Hash
    );

    if($hashVerify === false){
      Error::Unauthorized(
        "Invalid username or password"
      );
    }
     
    $hashNeeds = password_needs_rehash(
      $credential->Hash, PASSWORD_BCRYPT
    );

    if($hashNeeds === true){
      $this->credentialService
        ->UpdateCredentialByUser(
          $credential->Id, $user->Id, $password
        );
    }

    $userProfiles = (
      $this->userProfileService
        ->GetByUser($user->Id)
    );
    return new AccessTokenDto(
      accessToken: (
        new Encode([
          "Id" => $user->Id,
          "Name" => $user->Name,
          "Email" => $user->Email 
        ], privatekey)
      )->Get(),
      expiresIn: 3600,
      user: new UserAccessDto(
        $user->Id,
        $user->Name,
        $user->Email,
        $userProfiles
      )
    );
  }
}