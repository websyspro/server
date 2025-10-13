<?php

namespace Websyspro\Server\Shareds\Swaggers;

use Websyspro\Server\Decorations\Controllers\Body;
use Websyspro\Server\Decorations\Controllers\Get;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Decorations\Controllers\Post;
use Websyspro\Server\Shareds\StructureRoute;

class SwaggerModulesControllerRoute
{
  public string $methodType;
  public string $endpoint;

  public function __construct(
    StructureRoute $structureRoute
  ){
    $structureRoute->endpoint->mapper(
      function(Get|Post|Body|Param $endpoint){
        $this->methodType = $endpoint->methodType->name;
        $this->endpoint = $endpoint->endpoint;
      });
  }
}