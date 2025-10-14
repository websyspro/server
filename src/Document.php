<?php

namespace Websyspro\Server;

use Exception;
use Websyspro\Commons\DataList;
use Websyspro\Commons\Util;
use Websyspro\Server\Exceptions\Error;
use Websyspro\Server\Shareds\ItemController;
use Websyspro\Server\Shareds\StructureModuleControllers;
use Websyspro\Server\Shareds\StructureRoute;

class Document
{
  public readonly Request $request;
  public DataList $structureModuleControllers;

  public function __construct(
    public DataList $modules
  ){
    $this->runServer();
  }

  public function runServer(
  ): void {
    $this->initial();
    $this->initialControllers();
    $this->initialServer();
  }
  
  public function initial(
  ): void {
    $this->request = new Request();
  }  
  
  private function initialServer(
  ): void {
    try {
      $this->hasModules();
      $this->hasController();
      $this->hasEndpointInController();
    } catch (Exception $error){
      $this->setError($error);
    }
  }
  
  private function hasModules(
  ): void {
    $this->structureModuleControllers->where(
      function(StructureModuleControllers $stuctureModule){
        if($this->request->module === null){
          return false;
        }

        return strtolower(Util::className($stuctureModule->module)) 
           === strtolower($this->request->module);
      }
    );
    
    if($this->structureModuleControllers->exist() === false){
      Error::notFound("Module {$this->request->module} not found....");
    }
  }

  private function hasController(
  ): void {
    $this->structureModuleControllers->where(
      fn(StructureModuleControllers $structureModule) => $structureModule->structureControllers->controllers->where(
        fn(ItemController $itemController) => $itemController->name->first()->name === $this->request->controller
      )->exist()
    );

    if($this->structureModuleControllers->exist() === false){
      Error::notFound("Controller {$this->request->module}/{$this->request->controller} not found");
    }
  }
  
  private function hasEndpointInController(
  ): void {
    $this->structureModuleControllers->where(
      fn(StructureModuleControllers $structureModule) => $structureModule->structureControllers->controllers->where(
        fn(ItemController $itemController) => $itemController->routes->where(
          fn(StructureRoute $structureRoute) => $structureRoute->isEndpoint(
            DataList::create($this->request->endpoint), $this->request->method
          )
        )
      )
    );

    if($this->structureModuleControllers->first()->structureControllers->controllers->first()->routes->exist() === false){
      Error::notFound(sprintf("Route {$this->request->controller}/%s not found", implode("/", $this->request->endpoint)));
    }

    $this->structureModuleControllers
      ->first()->structureControllers->controllers
      ->first()->routes
      ->first()->execute(
        $this->request,
        $this->structureModuleControllers
          ->first()->structureControllers->controllers
          ->first()->middlewares
      );
  }  

  private function initialControllers(
  ): void {
    $this->structureModuleControllers = (
      $this->modules->copy()->mapper(
        function(string $module){
          return new StructureModuleControllers($module);
        }
      )
    );
  }
  
  private function setError(
    Exception $error
  ): void {
    Response::json(
      $error->getMessage(), 
      $error->getCode()
    )->send();    
  }  

  public static function render(
    array $modules
  ): Document {
    return new static(
      DataList::create(
        $modules
      )
    );
  }  
}