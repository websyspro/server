<?php

namespace Websyspro\Server\Shareds;

use ReflectionMethod;
use ReflectionParameter;
use Websyspro\Commons\DataList;
use Websyspro\Server\Decorations\Controllers\Body;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Request;

class StructureRouteParam
{
  public function __construct(
    public object $instance,
    public string $instanceType
  ){}
}

class StructureRoute
{
  public DataList $endpoint;
  public DataList $middlewares;

  public function __construct(
    public ReflectionMethod $reflectionMethod
  ){
    $this->startEndpoint();
    $this->startMiddlewares();
  }

  private function Attributes(
  ): DataList {
    return DataList::Create($this->reflectionMethod->getAttributes())->Mapper(
      fn(mixed $attribute) => $attribute->newInstance()
    );
  }

  private function startEndpoint(
  ): void {
    $this->endpoint = $this->Attributes()->Where(
      fn(mixed $attribute) => $attribute->attributeType === AttributeType::endpoint
    );    
  }

  private function startMiddlewares(
  ): void {
    $this->middlewares = $this->Attributes()->Where(
      fn(mixed $attribute) => $attribute->attributeType === AttributeType::middleware
    );
  }

  public function isEndpoint(
    DataList $requestEndpoint,
    string $requestMethod
  ): bool {
    $routeEndpoint = DataList::create(
      explode("/", preg_replace( "/(^\/)|(\/$)/", "", (
        $this->endpoint->first()->endpoint
      )))
    );

    $routeEndpoint->where(
      fn(string $endpoint) => (
        empty($endpoint) === false
      )
    );

    if(strtolower($requestMethod) !== strtolower($this->endpoint->first()->methodType->name)){
      return false;
    }

    if($requestEndpoint->count() !== $routeEndpoint->count()){
      return false;
    }

    return (
      $routeEndpoint->where(
        fn(string $path, int $index) => (
          preg_match( "/^:/", $path ) || (
            $path === $requestEndpoint->eq($index)
          )
        )
      )
    )->count() === $requestEndpoint->count();
  }

  private function getInstance(
  ): object {
    $hasMethodConstruct = method_exists(
      $this->reflectionMethod->class, "__construct"
    );

    if($hasMethodConstruct === true){
      return InstanceDependences::gets($this->reflectionMethod->class);
    } else return new $this->reflectionMethod->class();
  }

  private function getMethod(
  ): string {
    return $this->reflectionMethod->name;
  }

  private function getParameters(
    Request $request
  ): DataList {
    $properties = DataList::create(
      $this->reflectionMethod->getParameters()
    );

    $properties->mapper(
      function(ReflectionParameter $reflectionParameter){
        [ $reflectionAttribute ] = $reflectionParameter->getAttributes();

        return new StructureRouteParam(
          $reflectionAttribute->newInstance(),
          $reflectionParameter->getType()->getName()
        );
      }
    );

    $properties->mapper(
      function(StructureRouteParam $structureRouteParam) use($request){
        if($structureRouteParam->instance instanceof Param){
          return $structureRouteParam->instance ->execute(
            $structureRouteParam->instanceType,
            explode("/", $this->endpoint->first()->endpoint), 
            $request->endpoint
          );
        } else
        if($structureRouteParam->instance instanceof Body){
          return $structureRouteParam->instance->execute(
            $structureRouteParam->instanceType
          );
        } else {
          return $structureRouteParam->instance->execute();
        }
      }
    );

    return $properties;    
  }

  private function middlewares(
    Request $request,
    DataList $middlewaresFromController
  ): void {
    $middlewaresFromController->where(
      fn(object $middlewareFromController) => (
        $middlewareFromController instanceof Authenticate && (
          $this->middlewares->where(
            fn(object $middleware) => $middleware instanceof AllowAnonymous
          )->exist() === false
        )
      )
    );

    $this->middlewares->forEach(
      fn(object $middleware) => (
        $middlewaresFromController->Add(
          $middleware
        )
      )
    );

    $middlewaresFromController->forEach(
      fn(object $middleware) => $middleware->execute($request)
    );
  }

  public function execute(
    Request $request,
    DataList $middlewaresFromController,
  ): void {
    $this->middlewares(
      $request, $middlewaresFromController
    );

    call_user_func_array([
      $this->getInstance(), $this->getMethod()
    ], $this->getParameters($request)->All())->send();
  } 
}