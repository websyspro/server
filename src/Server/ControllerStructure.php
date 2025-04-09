<?php

namespace Websyspro\Server\Server;

use Websyspro\Server\Commons\Util;
use Websyspro\Server\Commons\Reflect;
use Websyspro\Server\Commons\ReflectMethod;
use Websyspro\Server\Enums\Reflect\AttributeType;

class ControllerStructure
{
  public string $name;
  public array $middlewares = [];
  public array $endpoints = [];

  public Reflect $reflect;

  public function __construct(
    public readonly string $objectClass
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
      $this->objectClass
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
            new ControllerStructureMethod(
              $reflectMethod
            )
          )
        )
      ), fn( ControllerStructureMethod $imethod ) => (
        $imethod->methodName !== "__construct"
      )
    );
  }

  private function equalsMethod(
    Request $request,
    ControllerStructureMethod $controllerStructureMethod
  ): bool {
    return strtolower( $request->requestMethod )
       === strtolower( $controllerStructureMethod->method );
  }

  private function equalsPaths(
    Request $request,
    ControllerStructureMethod $controllerStructureMethod    
  ): bool {
    return sizeof( $request->endpoint )
       === sizeof( $controllerStructureMethod->endpoint );
  }

  private function equalsPathsItems(
    Request $request,
    ControllerStructureMethod $controllerStructureMethod    
  ): bool {
    return in_array( 
      false, Util::Mapper( 
        $controllerStructureMethod->endpoint, (
          fn( string $path, int $index ) => (
            preg_match( "/^:/", $path ) || (
              $path === $request->endpoint[ $index ]
            )
          )
        )
      )
    ) !== true;
  }  

  public function find(
    Request $request   
  ): array {
    return Util::Filter(
      $this->endpoints, (
        fn( ControllerStructureMethod $controllerStructureMethod ) => (
          in_array(
            false, [ 
              $this->equalsMethod( $request, $controllerStructureMethod ),
              $this->equalsPaths( $request, $controllerStructureMethod ),
              $this->equalsPathsItems( $request, $controllerStructureMethod )
            ]
          ) !== true
        )
      )
    );
  }

  private function setUnReflect(
  ): void {
    unset( $this->reflect );
  }
}