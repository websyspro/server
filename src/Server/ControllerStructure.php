<?php

namespace Websyspro\Server\Server;

use ReflectionAttribute;
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
    ControllerStructureMethod $csm
  ): bool {
    return strtolower( $request->requestMethod )
       === strtolower( $csm->method );
  }

  private function equalsCountPaths(
    Request $request,
    ControllerStructureMethod $csm    
  ): bool {
    return sizeof( $request->endpoint )
       === sizeof( $csm->endpoint );
  }

  private function equalsPathsItems(
    Request $request,
    ControllerStructureMethod $csm    
  ): bool {
    return in_array( 
      false, Util::Mapper( 
        $csm->endpoint, (
          fn( string $path, int $index ) => (
            preg_match( "/^:/", $path ) || (
              $path === $request->endpoint[ $index ]
            )
          )
        )
      )
    ) !== true;
  }  

  public function findEndpoint(
    Request $request   
  ): array {
    return Util::Mapper(
      Util::Filter( $this->endpoints, (
        fn( ControllerStructureMethod $csm ) => (
          in_array( false, [ 
            $this->equalsMethod( $request, $csm ),
            $this->equalsCountPaths( $request, $csm ),
            $this->equalsPathsItems( $request, $csm )
          ]) !== true
        )
      )), function( ControllerStructureMethod $csm ){
        $csm->middlewares = Util::Mapper(
          $csm->middlewares, (
            fn( ReflectionAttribute $reflectionAttribute ) => (
              $reflectionAttribute->newInstance()
            )
          )
        );

        return $csm;
      }
    );
  }

  private function setUnReflect(
  ): void {
    unset( $this->reflect );
  }
}