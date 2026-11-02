<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Repositories\BoxRepository;
use Websyspro\Server\Shops\Repositories\CashMovementRepository;

class CashMovementService
{
  public function __construct(
    private readonly CashMovementRepository $cashMovementRepository
  ){}

  public function getResumeByBox(
    int $boxId
  ): mixed {
    return $this->cashMovementRepository->getResumeByBox( $boxId );
  } 
}