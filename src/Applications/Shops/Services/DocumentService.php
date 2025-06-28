<?php

namespace Websyspro\Server\Applications\Shops\Services;

use Websyspro\Commons\DataList;
use Websyspro\Server\Applications\Shops\Enums\Document\EState;
use Websyspro\Server\Applications\Shops\Repositorys\DocumentRepository;

class DocumentService
{
  public function __construct(
    private DocumentRepository $documentRepository
  ){}

  public function ByCustomer(
    int $CustomerId
  ): DataList {
    return $this->documentRepository->ByCustomer(
      $CustomerId, EState::Finalizado->value
    );
  }
}