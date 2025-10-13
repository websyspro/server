<?php

namespace Websyspro\Server\Shareds\Swaggers;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Util;
use Websyspro\Server\Shareds\ItemController;
use Websyspro\Server\Shareds\StructureModuleControllers;

class SwaggerModules
{
  public string $module;
  public DataList $controllers;

  public function __construct(
    StructureModuleControllers $structureModuleControllers
  ){
    $this->setClassName($structureModuleControllers);
    $this->setControllers($structureModuleControllers);
  }
  
  private function setClassName(
    StructureModuleControllers $structureModuleControllers
  ): void {
    $this->module = Util::className(
      $structureModuleControllers->module
    );
  }

  private function setControllers(
    StructureModuleControllers $structureModuleControllers
  ): void {
    $this->controllers = $structureModuleControllers->structureControllers->controllers
      ->mapper(fn(ItemController $itemController) => (
        new SwaggerModulesController($itemController)
      ));
  }
}