<?php

namespace Websyspro\Server\Shareds;

use ReflectionMethod;
use ReflectionParameter;
use Websyspro\Commons\DataList;
use Websyspro\Server\Decorations\Controllers\Param;
use Websyspro\Server\Decorations\Middlewares\AllowAnonymous;
use Websyspro\Server\Decorations\Middlewares\Authenticate;
use Websyspro\Server\Enums\AttributeType;
use Websyspro\Server\Request;

class StructureRoute
{
  public DataList $endpoint;
  public DataList $middlewares;

  public function __construct(
    public ReflectionMethod $reflectionMethod
  ){
    $this->InitialEndpoint();
    $this->InitialMiddlewares();
  }

  private function Attributes(
  ): DataList {
    return DataList::Create($this->reflectionMethod->getAttributes())->Mapper(
      fn(mixed $attribute) => $attribute->newInstance()
    );
  }

  private function InitialEndpoint(
  ): void {
    $this->endpoint = $this->Attributes()->Where(
      fn(mixed $attribute) => $attribute->attributeType === AttributeType::Endpoint
    );    
  }

  private function InitialMiddlewares(
  ): void {
    $this->middlewares = $this->Attributes()->Where(
      fn(mixed $attribute) => $attribute->attributeType === AttributeType::Middleware
    );
  }

  public function isEndpoint(
    DataList $requestEndpoint,
    string $requestMethod
  ): bool {
    $routeEndpoint = DataList::Create(
      explode("/", preg_replace( "/(^\/)|(\/$)/", "", (
        $this->endpoint->First()->endpoint
      )))
    );

    $routeEndpoint->Where(
      fn(string $endpoint) => (
        empty($endpoint) === false
      )
    );

    if(strtolower($requestMethod) !== strtolower($this->endpoint->First()->methodType->name)){
      return false;
    }

    if($requestEndpoint->Count() !== $routeEndpoint->Count()){
      return false;
    }

    return (
      $routeEndpoint->Where(
        fn(string $path, int $index) => (
          preg_match( "/^:/", $path ) || (
            $path === $requestEndpoint->Eq($index)
          )
        )
      )
    )->Count() === $requestEndpoint->Count();
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
    $properties = DataList::Create(
      $this->reflectionMethod->getParameters()
    );

    $properties->Mapper(
      fn(ReflectionParameter $reflectionParameter) => (
        DataList::Create($reflectionParameter->getAttributes())
          ->First()->newInstance()
      )
    );

    $properties->Mapper(fn(object $parameter) => $parameter instanceof Param 
      ? $parameter->Execute(explode("/", $this->endpoint->First()->endpoint), $request->endpoint) : $parameter->Execute()
    );

    return $properties;    
  }

  private function Middlewares(
    Request $request,
    DataList $middlewaresFromController
  ): void {
    $middlewaresFromController->Where(
      fn(object $middlewareFromController) => (
        $middlewareFromController instanceof Authenticate && (
          $this->middlewares->Where(
            fn(object $middleware) => $middleware instanceof AllowAnonymous
          )->Exist() === false
        )
      )
    );

    $this->middlewares->ForEach(
      fn(object $middleware) => (
        $middlewaresFromController->Add(
          $middleware
        )
      )
    );

    $middlewaresFromController->ForEach(
      fn(object $middleware) => $middleware->Execute($request)
    );
  }

  public function Execute(
    Request $request,
    DataList $middlewaresFromController,
  ): void {
    $this->Middlewares(
      $request, $middlewaresFromController
    );

    call_user_func_array([
      $this->getInstance(), $this->getMethod()
    ], $this->getParameters($request)->All())->send();
  } 
}