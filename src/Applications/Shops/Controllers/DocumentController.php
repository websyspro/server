<?php

namespace Websyspro\Server\Applications\Shops\Controllers;

use Websyspro\Server\Applications\Shops\Services\DocumentService;
use Websyspro\Server\Decorations\Controllers\Controller;
use Websyspro\Server\Decorations\Controllers\Get;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Response;

#[Controller("document")]
class DocumentController
{
  public function __construct(
    private DocumentService $documentService
  ){}

  #[Get("customer/:costumerId")]
  public function GetCustomer(
    #[Param("costumerId")] int $costumerId   
  ): Response {
    return Response::json(
      $this->documentService->ByCustomer($costumerId)
    );
  }
}