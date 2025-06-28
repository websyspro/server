<?php

namespace Websyspro\Server\Applications\Shops\Dtos;

class SearchProductDto
{
  public function __construct(
    public int $Id,
    public string $Name,
    public float $Value,
    public float $Amount,
    public float $TotalStock
  ){}
}