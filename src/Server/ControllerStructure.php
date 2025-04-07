<?php

namespace Websyspro\Server\Server;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\ReflectMethod;
use Websyspro\Server\Enums\Reflect\AttributeType;
use Websyspro\Server\Interfaces\Conntrollers\IMethod;

class ControllerStructure
{
  public string $name;
  public array $middlewares = [];
  public array $endpoints = [];

  public Reflect $reflect;

  public function __construct(
    public readonly string $entityClass
  ){
    $this->setReflect();
    $this->setControllerName();
    $this->setControllerMiddlewares();
    $this->setControllerEndpoints();
    $this->setUnReflect();
  }

  private function setReflect(
  ): void {
    $this->reflect = new Reflect(
      $this->entityClass
    );
  }

  private function setControllerName(
  ): void {
    [ $attribute ] = Util::Filter(
      $this->reflect->getAttriutes(), (
        fn( object $instance ) => (
          $instance->attributeType === 
            AttributeType::Controller
        )  
      )
    );

    if( $attribute !== null ){
      $this->name = $attribute->name;
    }
  }

  private function setControllerMiddlewares(
  ): void {
    $this->middlewares = Util::Filter(
      $this->reflect->getAttriutes(), (
        fn( object $instance ) => (
          $instance->attributeType === 
            AttributeType::Middleware
        )  
      )
    );    
  }

  private function setControllerEndpoints(
  ): void {
    $this->endpoints = Util::Filter(
      Util::Mapper(
        $this->reflect->getMethods(), (
          fn( ReflectMethod $reflectMethod ) => (
            new IMethod( $reflectMethod )
          )
        )
      ), fn( IMethod $imethod ) => (
        $imethod->methodName !== "__construct"
      )
    );
  }

  private function setUnReflect(
  ): void {
    unset( $this->reflect );
  }
}