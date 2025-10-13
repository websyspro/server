<?php

namespace Websyspro\Server\Shareds\Swaggers;

use Websyspro\Commons\DataList;
use Websyspro\Server\Shareds\ItemController;
use Websyspro\Server\Shareds\StructureRoute;

class SwaggerModulesController
{
  public string $name;
  public DataList $routes;

  public function __construct(
    ItemController $itemController
  ){
    $this->setNameController($itemController);
    $this->setNameControllerEndpoints($itemController);
  }

  public function setNameController(
    ItemController $itemController
  ): void {
    $this->name = $itemController->name->first()->name;
  }

  public function setNameControllerEndpoints(
    ItemController $itemController
  ): void {
    $this->routes = $itemController->routes->mapper(
      fn(StructureRoute $structureRoute) => (
        new SwaggerModulesControllerRoute($structureRoute)
      )
    );
  }
}