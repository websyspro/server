<?php

namespace Websyspro\Server\Server;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use Websyspro\Server\Commons\Log;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Consts\Controllers;
use Websyspro\Server\Databases\Structure\StructureDatabase;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Enums\LogType;
use Websyspro\Server\Exceptions\Error;

class Application
{
  public Request $request;
  public Response $response;
  public ControllerStructure | null $controllerStructure;
  public ControllerStructureMethod | null $controllerStructureMethod;

  public static array $entitys = [];

  public function __construct(
    private array $controllers,
    private array $databases
  ){
    Util::hasCli()
      ? $this->setClient()
      : $this->setServer();
  }

  private function setClient(
  ): void {
    Log::setStartTimer();
    $this->setClientEntityMapper();
    $this->setClientDatabaseMapper();
    $this->setClientModuleMapper();
  }

  private function setClientEntityMapper(
  ): void {
    Util::Mapper( 
      $this->databases, 
      fn( string $database ) => (
        Util::Mapper( 
          Util::ValueOfArray(
            (new Reflect($database)
          )->getAttriutes())->items,
        fn( string $entity ) => (
          Application::$entitys[ $entity ] = $database
        ))
      )
    );
  }

  private function setClientDatabaseMapper(
  ): void {
    if( sizeof( $this->databases ) !== 0 ){
      Util::Mapper( $this->databases, (
        fn( string $database ) => (
          new StructureDatabase(
            $database
          )
        )
      ));
    }    
  }

  private function setClientModuleMapper(
  ): void {
    if( sizeof( $this->controllers ) !== 0 ){
      Util::Mapper( $this->controllers, (
        fn( string $controllers ) => (
          $this->setClientModuleControllerMapper(
            $controllers
          )
        )
      ));
    } 
  }

  private function setClientModuleControllerMapper(
    string $controllers
  ): void {
    Util::Mapper(
      Util::ValueOfArray(
        Util::Mapper(
          ( new ReflectionClass( $controllers ))->getAttributes(), 
            fn( ReflectionAttribute $controller ) => (
              $controller->newInstance()->controllers
            )
        )
      ), fn( string $controller ) => (
      $this->setClientModuleControllerEndpointsMapper(
        ucfirst( Util::getModuleFromController( $controllers )), new ControllerStructure( $controller )
      )
    ));
  }

  private function setClientModuleControllerEndpointsMapper(
    string $module,
    ControllerStructure $controller
  ): void {
    Log::Message( LogType::Controller, "Mapper Module [{$module}]");

    if(sizeof( $controller->endpoints ) !== 0){
      Log::Message( LogType::Controller, (
        "Mapper Controller [{$controller->name}]"
      ));

      Util::Mapper( $controller->endpoints, (
        fn( ControllerStructureMethod $csm ) => (
          Log::Message( LogType::Controller, (
            sprintf( "Mapper route { %s, %s }", (
              sizeof( $csm->endpoint ) === 0 ? "/" : Util::Join( "/", $csm->endpoint )
            ), $csm->method )
          ))
        ) 
      ));
    }
  }

  private function setServer(
  ): void {
    [ $this->request ] = [
      new Request()
    ];
    
    if( $this->request ){
      try {
        $this->setEntitys();
        $this->setModule();
        $this->setController();
        $this->setEndpoint();
      } catch ( Exception $error ){
        $this->setResponseError( $error );
      }
    }
  }

  private function setEntitys(
  ): void {
    $this->setClientEntityMapper();
  }

  private function setModule(
  ): void {
    $this->controllers = Util::Filter(
      $this->controllers, fn( string $controller ) => (
        Util::getModuleFromController( $controller ) === (
          $this->request->module
        )
      )
    );

    if( sizeof( $this->controllers ) === 0 ){
      Error::NotFound( Controllers::ModuleNotFound );
    }
  }

  private function setController(
  ): void {
    [ $controllersList ] = Util::Mapper(
      ( new ReflectionClass( Util::ValueOfArray( $this->controllers ))
      )->getAttributes(), fn( ReflectionAttribute $reflectionAttribute ) => (
        $reflectionAttribute->newInstance()->controllers
      )
    );

    [ $controller ] = Util::Filter(
      $controllersList, fn( string $controller ) => (
        Util::getController( $controller ) === (
          $this->request->controller
        )
      )
    );

    if( $controller === null ){
      Error::NotFound( Controllers::ControllerNotFound );
    }
    
    $this->controllerStructure = (
      new ControllerStructure(
        $controller
      )
    );
  }

  private function setRunMiddlewares(
  ): void {
    Util::Mapper(
      array_merge(
        Util::Filter( $this->controllerStructure->middlewares, (
          fn( object $middleware ) => (
            $middleware instanceof Authenticate && Util::ValueOfArray(
              Util::Filter( $this->controllerStructureMethod->middlewares, (
                fn( object $middleware ) => $middleware instanceof AllowAnonymous
              ))
            ) === false
          )
        )), $this->controllerStructureMethod->middlewares
      ), fn( object $middleware ) => $middleware->execute()
    );
  }

  private function setEndpoint(
  ): void {
    [ $this->controllerStructureMethod ] = (
      $this->controllerStructure->findEndpoint(
        $this->request
      )
    );

    if( is_null( $this->controllerStructureMethod )){
      Error::NotFound( Controllers::RouteNotFound );
    } else {
      $this->setRunMiddlewares();
      $this->setEndpointExecute();
    }
  }

  private function setEndpointExecute(
  ): void {
    $this->controllerStructureMethod->setExecute(
      $this->request
    );
  }

  public static function server(
    array $controllers,
    array $databases
  ): Application {
    return new static(
      controllers: $controllers,
      databases: $databases
    );
  }

  private function setResponseError(
    Exception $error
  ): void {
    exit(
      Response::json(
        $error->getMessage(),
        $error->getCode()
      )->context()
    );
  }
}