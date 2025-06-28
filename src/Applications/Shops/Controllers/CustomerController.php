<?php

namespace Websyspro\Server\Applications\Shops\Controllers;

use Websyspro\Server\Response;
use Websyspro\Server\Decorations\Controllers\Get;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Decorations\Controllers\Controller;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Applications\Shops\Services\CustomerService;

#[Controller("customer")]
#[Authenticate()]
class CustomerController
{
  public function __construct(
    private CustomerService $customerService
  ){}

  #[Get("cpf/:cpf")]
  public function GetByCpf(
    #[Param("cpf")] string $cpf
  ): Response {
    return Response::json(
      $this->customerService->GetByCpf($cpf)
    );
  }
}