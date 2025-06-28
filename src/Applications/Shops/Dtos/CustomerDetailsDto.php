<?php

namespace Websyspro\Server\Applications\Shops\Dtos;

use Websyspro\Server\Applications\Shops\Entitys\DocumentEntity;

class CustomerDetailsDto
{
  public function __construct(
    public object $custumer,
    public float $totalPurchased,
    public float $limitPurchase
  ){}
}