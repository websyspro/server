<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\BoxRepository;
use Websyspro\Utils\Collection;

class BoxService
{
  public function __construct(
    private readonly BoxRepository $boxRepository
  ){}

  public function list(
    int $boxId
  ): Collection {
    return $this->boxRepository->list( $boxId );
  } 
}