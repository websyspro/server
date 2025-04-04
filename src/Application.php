<?php

namespace Websyspro\Server;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Websyspro\Server\Decorators\AllowAnonymous;
use Websyspro\Server\Decorators\Authenticate;
use Websyspro\Server\Decorators\Controller;
use Websyspro\Server\Decorators\Param;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Http\Response;
use Websyspro\Server\Reflections\ReflectUtils;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Databases\StructureData;

class Application
{
  public string $requestUri;
  public string $base;
  public string $version;
  public string $controller;
  public array $endpoint;
  public string $requestMethod;

  public string | null $currentController = null;
  public string | null $currentEndpoint = null;

  public function __construct(
    private readonly array $controllers = [],
    private readonly array $entitys = [],
    private readonly array $databases = []
  ){
    $this->isUpdateDatabase()
      ? $this->startUpdateDatabase()
      : $this->startEnvironment();
  }

  private function hasEndpoint(
  ): bool {
    return sizeof(
      explode( "/", $this->requestUri )
    ) >= 4;
  }

  private function isUpdateDatabase(
  ): bool {
    $getopt = getopt( "", [ "update-database::" ]);
    return isset( $getopt[ "update-database" ])
        && sizeof( $getopt ) !== 0;
  }

  private function startUpdateDatabase(
  ): void {
    new StructureData(
      $this->databases
    );
  }

  private function startEnvironment(
  ): void {
    $this->getEnvironment();
    $this->setEnvironment();
    $this->setEnvironmentLoad();
  } 

  private function getEnvironment(
  ): void {
    [ "REQUEST_URI" => $this->requestUri, 
      "REQUEST_METHOD" => $this->requestMethod
    ] = $_SERVER;

    $this->requestUri = Util::ParseRequestUri(
      $this->requestUri
    );
  }

  private function setEnvironment(
  ): void {
    [ $this->base, 
      $this->version, 
      $this->controller
    ] = explode( "/", $this->requestUri );

    if($this->hasEndpoint()){
      $this->endpoint = array_slice(
        explode("/", $this->requestUri), 3
      );
    }
  }

  public static function Init(
    array $controllers = [],
    array $databases = [],
    array $entitys = [] 
  ): Application {
    return new static(
      controllers: $controllers,
      databases: $databases,
      entitys: $entitys
    );
  }

  private function controllerValidate(
    string | object $objectOrClass
  ): bool {
    $reflectClass = ReflectUtils::getReflectClass($objectOrClass);
    [ $controler ] = Util::Mapper(
      $reflectClass->getAttributes(Controller::class), fn(ReflectionAttribute $reflectionAttribute) => (
        $reflectionAttribute->newInstance()->getName()
      )
    );

    return $this->controller === $controler;
  }

  private function endpointValidateAttrs(
    ReflectionAttribute $reflectionAttribute
  ): bool {
    return is_null($reflectionAttribute) === false;
  }

  private function endpointValidateMethod(
    ReflectionAttribute $reflectionAttribute
  ): bool {
    return $reflectionAttribute->newInstance()->getRequestMethod()->value
       === $this->requestMethod;
  }

  private function endpointValidatePathsCount(
    ReflectionAttribute $reflectionAttribute
  ): bool {
    return sizeof($reflectionAttribute->newInstance()->getEndpoint())
       === sizeof($this->endpoint);
  }

  private function endpointValidatePathsItems(
    ReflectionAttribute $reflectionAttribute
  ): bool {
    $pathItems = Util::Mapper(
      $reflectionAttribute->newInstance()->getEndpoint(), fn(string $path, int $pathIndex) => (
        preg_match( "/^:/", $path ) || $path === $this->endpoint[$pathIndex]
      )
    );

    return in_array(false, $pathItems)
       !== true;
  }

  private function getReflectionAttributeIsEndpoint(
    ReflectionMethod $reflectionMethod
  ): ReflectionAttribute {
    [ $reflectionEndpoint ] = Util::Filter(
      $reflectionMethod->getAttributes(), fn(ReflectionAttribute $reflectionAttribute) => (
        $reflectionAttribute->newInstance()->AttributeType() === AttributeType::Endpoint 
      )
    );

    return $reflectionEndpoint;
  }  

  private function endpointValidate(
    string | object $objectOrClass,
    string $method,
    array $endpointValidates = []
  ): bool {
    $reflectionAttribute = $this->getReflectionAttributeIsEndpoint(
      ReflectUtils::getReflectMethod( $objectOrClass, $method )
    );

    $endpointValidates[] = $this->endpointValidateAttrs($reflectionAttribute);
    $endpointValidates[] = $this->endpointValidateMethod($reflectionAttribute);
    $endpointValidates[] = $this->endpointValidatePathsCount($reflectionAttribute);
    $endpointValidates[] = $this->endpointValidatePathsItems($reflectionAttribute);

    return in_array(false, $endpointValidates) !== true;
  }

  private function middlewareList(
    array $middlewareList,
    ReflectionClass | ReflectionMethod $reflectionClassOrMethod
  ): array {
    $middlewareListNew = Util::Filter(
      $reflectionClassOrMethod->getAttributes(), fn(ReflectionAttribute $reflectionAttribute) => (
        method_exists( $reflectionAttribute->newInstance(), "AttributeType" )
          ? $reflectionAttribute->newInstance()->AttributeType() === AttributeType::Middleware 
          : false
      )
    );
    
    $middlewareListNew = Util::Mapper(
      $middlewareListNew, fn(ReflectionAttribute $reflectionAttribute) => (
        $reflectionAttribute->getName()
      )
    );

    return array_merge(
      $middlewareList, 
      $middlewareListNew
    );
  }

  private function setEnvironmentController(
  ): void {
    [ $this->currentController ] = Util::Filter(
      $this->controllers, fn(string | object $controller) => (
        $this->controllerValidate($controller)
      )
    );

    if(is_null($this->currentController)){
      throw new Exception(
        Response::ERROR_CONTROLLER_NOT_FOUND,
        Response::HTTP_NOT_FOUND
      );
    }
  }

  private function setEnvironmentEndpoint(
  ): void {
    $controllerMethods = Util::Filter(
      ReflectUtils::getMethdos($this->currentController), fn(string $endpoint) => (
        $endpoint !== "__construct"
      )
    );

    [ $this->currentEndpoint ] = Util::Filter(
      $controllerMethods, fn(string $method) => (
        $this->endpointValidate($this->currentController, $method)
      )
    );
    

    if(is_null($this->currentEndpoint)){
      throw new Exception(
        Response::ERROR_ROUTE_NOT_FOUND,
        Response::HTTP_NOT_FOUND
      );
    }
  }

  private function widdlewares(
    string $controller,
    string $method,
    array $widdlewares = []
  ): array {
    $widdlewares = $this->middlewareList($widdlewares, ReflectUtils::getReflectClass($controller));
    $widdlewares = $this->middlewareList($widdlewares, ReflectUtils::getReflectMethod($controller, $method));

    return Util::Filter(
      $widdlewares, fn(string $middleware) => (
          Authenticate::class !== $middleware || (
          Authenticate::class === $middleware && in_array(
          AllowAnonymous::class, $widdlewares
        ) === false
      ))
    );
  } 
  
  private function setEnvironmentMiddlewares(
  ): void {
    $middlewares = $this->widdlewares(
      $this->currentController, 
      $this->currentEndpoint
    );

    Util::Mapper($middlewares, fn(string $middleware) => (
      method_exists($middleware, "execute") 
        ? (new $middleware())->execute()
        : [] 
    ));    
  }

  private function setEnvironmentError(
    Exception $error
  ): void {
    exit(
      Response::json(
        $error->getMessage(),
        $error->getCode()
      )->context()
    );
  }

  private function getAttributeExecute(
    ReflectionMethod $reflectionMethod,
    ReflectionAttribute $reflectionAttribute
  ): mixed {
    return (
      call_user_func_array([
        call_user_func_array([ 
          ReflectUtils::getReflectClass(
            $reflectionAttribute->getName()
          ), "newInstance"
        ], $reflectionAttribute->getArguments()), "execute"
      ], (
        $reflectionAttribute->getName() === Param::class
          ? [ $this->getReflectionAttributeIsEndpoint($reflectionMethod)->newInstance()->getEndpoint(), 
              $this->endpoint ] 
          : []
      ))
    );
  }

  private function setEnvironmentParams(
  ): array {
    $reflectionMethod = ReflectUtils::getReflectMethod(
      $this->currentController, $this->currentEndpoint
    );    

    $params = Util::Mapper(
      $reflectionMethod->getParameters(), fn(ReflectionParameter $reflectionParameter) => (
        Util::Mapper($reflectionParameter->getAttributes(), fn(ReflectionAttribute $reflectionAttribute) => (
          $this->getAttributeExecute( $reflectionMethod, $reflectionAttribute )
        ))
      )
    );

    return Util::Mapper(
      $params, fn(array $param) => reset($param)
    );
  }

  private function setEnvironmentResponse(
  ): void {
    $response = call_user_func_array([
      ReflectUtils::setInstanceConstruct(
        $this->currentController
      ), $this->currentEndpoint
    ], $this->setEnvironmentParams());

    if($response instanceof Response){
      exit( $response->context());
    }    
  }

  private function setEnvironmentLoad(
  ): void {
    try {
      $this->setEnvironmentController();
      $this->setEnvironmentEndpoint();
      $this->setEnvironmentMiddlewares();
      $this->setEnvironmentResponse();
    } catch(Exception $Exception) {
      $this->setEnvironmentError(
        $Exception
      );
    }
  }
}