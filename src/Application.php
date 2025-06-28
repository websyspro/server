<?php

namespace Websyspro\Server;

use Exception;
use Websyspro\Logger\Log;
use Websyspro\Commons\DataList;
use Websyspro\Commons\Statics;
use Websyspro\Commons\Util;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Server\Exceptions\Error;
use Websyspro\Server\Shareds\ItemController;
use Websyspro\Server\Shareds\StructureModule;
use Websyspro\Server\Shareds\StructureModuleControllers;
use Websyspro\Server\Shareds\StructureModuleEntitys;
use Websyspro\Server\Shareds\StructureModuleServices;
use Websyspro\Server\Shareds\StructureRoute;

class Application
{
  public readonly Request $request;
  public DataList $structureModuleControllers;
  public DataList $structureModuleEntitys;
  public DataList $structureModuleServices;
  
  public function __construct(
    public DataList $modules
  ){
    $this->RunStatics();
    $this->RunClient();
    $this->RunServer();
  }

  public function RunStatics(
  ): void {
    if(defined("modules") === false){
      $structureModule = (
        new StructureModule(
          $this->modules
        )
      );

      Statics::$modules = (
        $structureModule->entitysFromModule
      );
    }
  }

  public function RunClient(
  ): void {
    if($this->hasClient() === true){
      $this->InitialControllers();
      $this->InitialControllersLogs();
      $this->InitialUpdatedsEntitys();
      $this->InitialUpdatedsServices();
    }
  }

  private function InitialUpdatedsEntitys(
  ): void {
    $this->structureModuleEntitys = $this->modules->Copy()->Mapper(
      fn(string $module) => new StructureModuleEntitys($module)
    );
  } 

  private function InitialUpdatedsServices(
  ): void {
    $this->structureModuleServices = $this->modules->Copy()->Mapper(
      fn(string $module) => new StructureModuleServices($module)
    );
    
    $this->structureModuleServices->ForEach(
      fn(StructureModuleServices $structureModuleServices) => (
        $structureModuleServices->structureServices->ForEach(
          fn(string $service) => $structureModuleServices->getInstance($service)
        )
      )
    );
  }

  public function InitialControllersLogs(
  ): void {
    $this->structureModuleControllers->ForEach(
      function(StructureModuleControllers $stuctureModule){
        Log::Message(LogType::Module, "Map module [{$this->className($stuctureModule->module)}]");

        if($stuctureModule->structureControllers->controllers->Exist()){
          Log::Message(LogType::Controller, "Map controllers from module [{$this->className($stuctureModule->module)}]");

          $stuctureModule->structureControllers->controllers->ForEach(
            function(ItemController $itemController){
              Log::Message(LogType::Controller, "Map controller [{$this->className($itemController->controller)}]");

              $itemController->routes->ForEach(
                function(StructureRoute $structureRoute){
                  Log::Message(LogType::Controller, sprintf("Map route {%s, %s}", ...[
                    strtoupper($structureRoute->endpoint->First()->methodType->name), (
                      empty($structureRoute->endpoint->First()->endpoint) === false 
                        ? $structureRoute->endpoint->First()->endpoint 
                        : "/"
                    )
                  ]));
                }
              );
            }
          );
        }
      }
    );
  }

  public function RunServer(
  ): void {
    if($this->hasClient() === false){
      $this->Initial();
      $this->InitialControllers();
      $this->InitialServer();
    }
  }

  public function Initial(
  ): void {
    $this->request = new Request();
  }

  private function InitialControllers(
  ): void {
    $this->structureModuleControllers = $this->modules->Copy()->Mapper(
      fn(string $module) => new StructureModuleControllers($module)
    );
  }   

  private function className(
    string $class
  ): string {
    return Util::className($class);
  }

  private function hasModules(
  ): void {
    $this->structureModuleControllers->Where(
      function(StructureModuleControllers $stuctureModule){
        if($this->request->module === null){
          return false;
        }

        return strtolower($this->className($stuctureModule->module)) 
           === strtolower($this->request->module);
      }
    );

    if($this->structureModuleControllers->Exist() === false){
      Error::NotFound("Module {$this->request->module} not found");
    }
  }

  private function hasController(
  ): void {
    $this->structureModuleControllers->Where(
      fn(StructureModuleControllers $structureModule) => $structureModule->structureControllers->controllers->Where(
        fn(ItemController $itemController) => $itemController->name->First()->name === $this->request->controller
      )->Exist()
    );

    if($this->structureModuleControllers->Exist() === false){
      Error::NotFound("Controller {$this->request->module}/{$this->request->controller} not found");
    }
  }

  private function hasEndpointInController(
  ): void {
    $this->structureModuleControllers->Where(
      fn(StructureModuleControllers $structureModule) => $structureModule->structureControllers->controllers->Where(
        fn(ItemController $itemController) => $itemController->routes->Where(
          fn(StructureRoute $structureRoute) => $structureRoute->isEndpoint(
            DataList::Create($this->request->endpoint), $this->request->method
          )
        )
      )
    );

    if($this->structureModuleControllers->First()->structureControllers->controllers->First()->routes->Exist() === false){
      Error::NotFound(sprintf("Route {$this->request->controller}/%s not found", implode("/", $this->request->endpoint)));
    }

    $this->structureModuleControllers
      ->First()->structureControllers->controllers
      ->First()->routes
      ->First()->Execute(
        $this->request,
        $this->structureModuleControllers
          ->First()->structureControllers->controllers
          ->First()->middlewares
      );
  }

  private function hasClient(
  ): bool {
    return (
      php_sapi_name() 
        === "cli"
    );
  }

  private function InitialServer(
  ): void {
    try {
      $this->hasModules();
      $this->hasController();
      $this->hasEndpointInController();
    } catch (Exception $error){
      $this->setError($error);
    }
  }

  private function setError(
    Exception $error
  ): void {
    Response::json(
      $error->getMessage(), 
      $error->getCode()
    )->send();    
  }

  public static function Modules(
    array $modules
  ): Application {
    return new static(
      DataList::Create(
        $modules
      )
    );
  }
}