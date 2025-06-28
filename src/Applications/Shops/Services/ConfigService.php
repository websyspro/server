<?php

namespace Websyspro\Server\Applications\Shops\Services;

use Websyspro\Server\Applications\Shops\Entitys\ConfigEntity;
use Websyspro\Server\Applications\Shops\Repositorys\ConfigRepository;

class ConfigService
{
  public function __construct(
    public ConfigRepository $configRepository
  ){}

  public function Get(
  ): ConfigEntity {
    return $this->configRepository->Get();
  }
}