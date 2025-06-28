<?php

namespace Websyspro\Server\Shareds;

use ReflectionMethod;
use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Server\Enums\AttributeType;

class ItemController
{
  public DataList $name;
  public DataList $middlewares;
  public DataList $routes;

  public function __construct(
    public string $controller
  ){
    $this->InitialName();
    $this->InitialMiddlewares();
    $this->InitialRoutes();
  }

  private function InitialName(
  ): void {
    $this->name = Reflect::InstancesFromAttributes($this->controller)->Where(
      fn(mixed $item) => $item->attributeType === AttributeType::Controller
    );
  }

  private function InitialMiddlewares(
  ): void {
    $this->middlewares = Reflect::InstancesFromAttributes($this->controller)->Where(
      fn(mixed $item) => $item->attributeType === AttributeType::Middleware
    );   
  }

  private function InitialRoutes(
  ): void {
    $this->routes = Reflect::MethodsFromClass($this->controller)->Mapper(
      fn(ReflectionMethod $reflectionMethod) => new StructureRoute($reflectionMethod)
    );
  }
}