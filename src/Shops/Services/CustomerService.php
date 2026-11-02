<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\Server\Shops\Repositories\CustomerRepository;
use Websyspro\Utils\Collection;

class CustomerService
{
  public function __construct(
    private readonly CustomerRepository $customerRepository
  ){}

  public function getList(
    string $cpf
  ): Collection {
    return $this->customerRepository->getList( $cpf );
  } 
}