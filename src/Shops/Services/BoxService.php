<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\BoxRepository;

class BoxService
{
  public function __construct(
    private readonly BoxRepository $boxRepository
  ){}

  public function list(
    int $boxId
  ): array {
    return $this->boxRepository->list( $boxId );
  }
}