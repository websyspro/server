<?php

namespace Websyspro\Server\Server;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Exceptions\Error;

class Application
{
  public Request $request;
  public Response $response;

  public ControllerStructure $controllerStructure;

  public function __construct(
    private array $controllers
  ){
    Util::hasCli()
      ? $this->setClient()
      : $this->setServer();
  }

  private function setClient(
  ): void {}

  private function setServer(
  ): void {
    $this->request = (
      new Request()
    );
    
    if( $this->request ){
      try {
        $this->setModule();
        $this->setController();
        $this->setEndpoint();
      } catch ( Exception $error ){
        $this->setResponseError( $error );
      }
    }
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

  private function setModule(
  ): void {
    $this->controllers = Util::Filter(
      $this->controllers, fn( string $controller ) => (
        Util::getModuleFromController($controller) === (
          $this->request->module
        )
      )
    );

    if( sizeof( $this->controllers ) === 0 ){
      Error::NotFound(
        "Module not found"
      );
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
      Error::NotFound( "Controller not found" );
    }
    
    $this->controllerStructure = (
      new ControllerStructure(
        $controller
      )
    );
  }

  private function setEndpoint(
  ): void {
    [ $mt ] = $this->controllerStructure->getMethodo(
      $this->request
    );

    if( $mt === null ){
      Error::NotFound(
        "Route not found"
      );
    }

    $this->setResponse(
      $mt->setExecute()
    );
  }

  private function setResponse(
    Response $response
  ): void {
    if( $response instanceof Response ){
      exit( $response->context());
    }   
  }

  public static function server(
    array $controllers
  ): Application {
    return new static(
      controllers: $controllers
    );
  }
}