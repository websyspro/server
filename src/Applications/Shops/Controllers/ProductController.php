<?php

namespace Websyspro\Server\Applications\Shops\Controllers;

use Websyspro\Server\Applications\Shops\Services\ProductService;
use Websyspro\Server\Decorations\Controllers\Controller;
use Websyspro\Server\Decorations\Controllers\Get;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Response;

#[Controller("product")]
#[Authenticate]
class ProductController
{
  public function __construct(
    private ProductService $productService
  ){}

  #[Get("search/:name")]
  public function search(
    #[Param("name")] string $name
  ): Response {
    return Response::json(
      $this->productService->GetByName($name)
    );
  }
}