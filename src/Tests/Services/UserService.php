<?php

namespace Websyspro\Server\Tests\Services;

use Websyspro\Server\Tests\Repositorys\UserRepository;

class UserService
{
  public function __construct(
		private readonly UserRepository $userRepository
	){}

	public function getUser(): string {
		return "emerson.thiago";
	}
}