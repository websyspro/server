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
use Websyspro\Server\Shareds\SchedulerRunner;
use Websyspro\Server\Shareds\StructureModule;
use Websyspro\Server\Shareds\StructureModuleControllers;
use Websyspro\Server\Shareds\StructureModuleEntitys;
use Websyspro\Server\Shareds\StructureModuleServices;
use Websyspro\Server\Shareds\StructureRoute;
use Websyspro\Server\Shareds\Swaggers\SwaggerModules;

class Application
{
  public readonly Request $request;
  public SchedulerRunner $schedulerRunner;
  public DataList $structureModuleControllers;
  public DataList $structureModuleEntitys;
  public DataList $structureModuleServices;
  
  public function __construct(
    public DataList $modules
  ){
    $this->runPutEnvs();
    $this->runStatics();
    $this->runClient();
    $this->runServer();
    $this->runSchedule();
  }

  public function runPutEnvs(
  ): void {
    if(defined("rootdir")){
      $envfile = DataList::create(
        file(rootdir . DIRECTORY_SEPARATOR . ".env")
      );

      $envfile
        ->where(fn(string $line) => preg_match("#^(\#|;)#", $line) === 0)
        ->where(fn(string $line) => empty(trim($line)) === false)
        ->mapper(fn(string $line) => explode("=", $line))
        ->mapper(
          function(array $line){
            [ $key, $val ] = $line;

            putenv(sprintf(
              "%s=%s", trim($key), trim($val, " \t\n\r\0\x0B\"'")
            ));
          });
    }
  }  

  public function runStatics(
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

  public function runClient(
  ): void {
    if($this->hasSchedule() === false){
      if($this->hasClient() === true){
        $this->initialSwaggers();
        $this->initialControllers();
        $this->initialControllersLogs();
        $this->initialUpdatedsEntitys();
        $this->initialUpdatedsServices();
        $this->initialSchedulers();
      }
    }

  }

  private function initialUpdatedsEntitys(
  ): void {
    $this->structureModuleEntitys = $this->modules->copy()->mapper(
      fn(string $module) => new StructureModuleEntitys($module)
    );
  } 

  private function initialUpdatedsServices(
  ): void {
    $this->structureModuleServices = $this->modules->copy()->mapper(
      fn(string $module) => new StructureModuleServices($module)
    );
    
    $this->structureModuleServices->forEach(
      fn(StructureModuleServices $structureModuleServices) => (
        $structureModuleServices->structureServices->forEach(
          fn(string $service) => $structureModuleServices->getInstance($service)
        )
      )
    );
  }

  public function runSchedule(
  ): void {
    if($this->hasSchedule() === true){
      $this->schedulerRunner = (
        new SchedulerRunner(
          $this->modules->copy()
        )
      );

      if($this->schedulerRunner instanceof SchedulerRunner){
        $this->schedulerRunner->startAllTask();
      }
    }
  }

  private function initialSchedulers(
  ): void {
    $this->schedulerRunner = (
      new SchedulerRunner(
        $this->modules->copy()
      )
    );

    if($this->schedulerRunner instanceof SchedulerRunner){
      if($this->schedulerRunner->isRunning() === true){
        $this->schedulerRunner->stop();
      }

      $this->schedulerRunner->start();
    }
  }

  public function initialControllersLogs(
  ): void {
    $this->structureModuleControllers->forEach(
      function(StructureModuleControllers $stuctureModule){
        Log::message(LogType::module, "Map module [{$this->className($stuctureModule->module)}]");

        if($stuctureModule->structureControllers->controllers->exist()){
          Log::message(LogType::controller, "Map controllers from module [{$this->className($stuctureModule->module)}]");

          $stuctureModule->structureControllers->controllers->forEach(
            function(ItemController $itemController){
              Log::message(LogType::controller, "Map controller [{$this->className($itemController->controller)}]");

              $itemController->routes->forEach(
                function(StructureRoute $structureRoute){
                  Log::message(LogType::controller, sprintf("Map route {%s, %s}", ...[
                    strtoupper($structureRoute->endpoint->first()->methodType->name), (
                      empty($structureRoute->endpoint->first()->endpoint) === false 
                        ? $structureRoute->endpoint->first()->endpoint 
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

  public function runServer(
  ): void {
    if($this->hasClient() === false){
      $this->initial();      
      if($this->hasServerApi()){
        $this->initialControllers();
        $this->initialServer();
      } else {
        $this->initialPublic();
      }
    }
  }

  public function initial(
  ): void {
    $this->request = new Request();
  }

  private function initialSwaggers(
  ): void {
    file_put_contents((
        rootdir . DIRECTORY_SEPARATOR . "swaggers.json"
      ), json_encode(
        Util::convertKeysToCamelCase(
          $this->modules->copy()
            ->mapper(fn(string $module) => new StructureModuleControllers($module))
            ->mapper(fn(StructureModuleControllers $struture) => new SwaggerModules($struture))
        )
      )
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

  private function className(
    string $class
  ): string {
    return Util::className($class);
  }

  private function baseAPIDefault(
  ): string {
    return sprintf("%s/%s", ...[
      getenv("API"), getenv("VERSION")
    ]);
  }

  private function hasModules(
  ): void {
    $this->structureModuleControllers->where(
      function(StructureModuleControllers $stuctureModule){
        if($this->request->module === null){
          return false;
        }

        return strtolower($this->className($stuctureModule->module)) 
           === strtolower($this->request->module);
      }
    );
    
    if($this->structureModuleControllers->exist() === false){
      if($this->request->uri !== $this->baseAPIDefault()){
        Error::notFound("Module {$this->request->module} not found....");
      } else {
        Response::json( "Service is running smoothly", Response::HTTP_OK)->send(); 
      }
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

  private function hasClient(
  ): bool {
    return (
      php_sapi_name() 
        === "cli"
    );
  }

  private function hasSchedule(
  ): bool {
    if(isset($_SERVER["argv"]) === false){
      return false;
    }

    ["argv" => $argv] = $_SERVER;

    if($argv === null){
      return false;
    }

    if(sizeof($argv) === 2){
      $argName = DataList::create($argv)->last();

      if($argName === "--schedule"){
        return true;
      }
    } 

    return false;
  }  

  private function hasServerApi(
  ): bool {
    return isset(
      $this->request->base
    ) && $this->request->base === getenv("API");
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

  public function initialPublic(
  ): void {
    if(file_exists(rootdir . "/src/Public/index.php")){
      require_once rootdir . "/src/Public/index.php";
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

  public static function modules(
    array $modules
  ): Application {
    return new static(
      DataList::create(
        $modules
      )
    );
  }
}