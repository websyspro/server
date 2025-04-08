<?php

namespace Websyspro\Server\Server;

use Websyspro\Server\Commons\Util;

class Request
{
  public string $requestUri;
  public string $requestMethod;

  public string $base;
  public string $ver;
  public string $module;
  public string $controller;

  public array $endpoint;

  public function __construct(
  ){
    $this->setEnvironment();
    $this->setEnvironmentPaths();
  }

  private function setEnvironment(
  ): void {
    [ "REQUEST_URI" => $this->requestUri, 
      "REQUEST_METHOD" => $this->requestMethod
    ] = $_SERVER; 
  
    $this->requestUri = (
      Util::ParseRequestUri(
        $this->requestUri
      )
    );
  }

  private function setEnvironmentPaths(
  ): void {
    [ $this->base, $this->ver, $this->module, $this->controller 
    ] = explode( "/", $this->requestUri );

    $this->endpoint = Util::Filter(
      array_slice( explode( 
        "/", Util::DropParamsFromUri( $this->requestUri )
      ), 4 ), fn( string $path ) => $path !== ""
    );
  }
}