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
    $this->startName();
    $this->startMiddlewares();
    $this->startRoutes();
  }

  public function getName(): string {
    return $this->name->first()->name;
  }

  private function startName(
  ): void {
    $this->name = Reflect::InstancesFromAttributes($this->controller)->where(
      fn(mixed $item) => $item->attributeType === AttributeType::controller
    );
  }

  private function startMiddlewares(
  ): void {
    $this->middlewares = Reflect::InstancesFromAttributes($this->controller)->where(
      fn(mixed $item) => $item->attributeType === AttributeType::middleware
    );   
  }

  private function startRoutes(
  ): void {
    $this->routes = Reflect::MethodsFromClass($this->controller)->mapper(
      fn(ReflectionMethod $reflectionMethod) => new StructureRoute($reflectionMethod)
    );
  }
}