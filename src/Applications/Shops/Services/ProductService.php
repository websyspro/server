<?php

namespace Websyspro\Server\Applications\Shops\Services;

use Websyspro\Commons\DataList;
use Websyspro\Server\Applications\Shops\Dtos\SearchProductDto;
use Websyspro\Server\Applications\Shops\Entitys\ProductEntity;
use Websyspro\Server\Applications\Shops\Repositorys\ProductRepository;

class ProductService
{
  public function __construct(
    private ProductRepository $productRepository
  ){}

  public function GetByName(
    string $Name
  ): DataList {
    return $this->productRepository
      ->GetByName($Name)
      ->Mapper(fn(ProductEntity $i) => (
        new SearchProductDto(
          $i->Id,
          $i->Name,
          $i->Value,
          $i->Amount,
          $i->TotalStock
        )
      ));
  }
}