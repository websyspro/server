<?php

namespace Websyspro\Server\Applications\Shops\Repositorys;

use Websyspro\Entity\Repository;
use Websyspro\Server\Applications\Shops\Entitys\ConfigEntity;

class ConfigRepository
{
  public function __construct(
    public Repository $repo = new Repository(ConfigEntity::class)
  ){}

  public function Get(
  ): ConfigEntity {
    return $this->repo->Where(
      fn(ConfigEntity $i) => $i->Id == 1
    )->One();
  }
}