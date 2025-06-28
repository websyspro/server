<?php

namespace Websyspro\Server\Applications\Shops\Services;

use Websyspro\Server\Applications\Shops\Dtos\CustomerDetailsDto;
use Websyspro\Server\Applications\Shops\Entitys\DocumentEntity;
use Websyspro\Server\Applications\Shops\Helpers\Format;
use Websyspro\Server\Applications\Shops\Repositorys\CustomerRepository;

class CustomerService
{
  public function __construct(
    private CustomerRepository $customerRepository,
    private DocumentService $documentService,
    private ConfigService $configService
  ){}

  public function GetByCpf(
    string $cpf  
  ): CustomerDetailsDto {
    $custumer = (
      $this->customerRepository->GetByCpf(
        Format::Cpf($cpf)
      )
    );

    $totalPurchased = $this->documentService->ByCustomer($custumer->Id)
      ->Sum(fn(DocumentEntity $i) => $i->Value);

    $purchaseLimitPerCustomer = $this->configService->Get()
      ->PurchaseLimitPerCustomer;

    return new CustomerDetailsDto(
      custumer: $custumer,
      totalPurchased: $totalPurchased,
      limitPurchase: $purchaseLimitPerCustomer - $totalPurchased
    );
  }
}