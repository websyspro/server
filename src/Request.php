<?php

namespace Websyspro\Server;

use ReflectionNamedType;
use Websyspro\Commons\Reflect;
use Websyspro\Commons\Util;
use Websyspro\Jwt\Decode;
use Websyspro\Server\Enums\RequestType;
use Websyspro\Server\Exceptions\Error;

class Request
{
  public string $uri;
  public string $method;

  public string|null $base;
  public string|null $ver;
  public string|null $module;
  public string|null $controller;

  public array $endpoint;

  public function __construct(
  ){
    $this->setEnvironment();
    $this->setEnvironmentPaths();
  }

  private function setEnvironment(
  ): void {
    [ "REQUEST_URI" => $this->uri, 
      "REQUEST_METHOD" => $this->method
    ] = $_SERVER; 
  
    $this->uri = preg_replace(
      [ "/(?<=[^\/])\?/", "/^\/*/", "/\/*$/"  ], 
      [ "/?", "" ], $this->uri
    );  
  }

  private function setEnvironmentPaths(
  ): void {
    [ $this->base, $this->ver, $this->module, $this->controller 
    ] = explode( "/", $this->uri );

    $this->endpoint = Util::where(
      array_slice( explode( 
        "/", preg_replace( "/\?.+/", "", $this->uri )
      ), 4 ), fn( string $path ) => $path !== ""
    );
  }

  private static function isPrimitiveType(
    string $type
  ): bool {
    return in_array($type, [
      "int", "integer", "float", "double", "string", "bool", "boolean", "array", "null"
    ], true);
  }

  private static function hydrateObject( 
    mixed $data,
    string $className
  ): object {
    if (!class_exists($className)) {
        Error::badRequest("Classe {$className} não encontrada.");
    }

    $refClass = Reflect::class($className);
    $instance = $refClass->newInstanceWithoutConstructor();

    foreach($refClass->getProperties() as $prop){
      $prop->setAccessible(true);

      $type = $prop->getType();
      if (!$type instanceof ReflectionNamedType) {
        continue;
      }

      $typeName = $type->getName();
      $propName = $prop->getName();

      if(!array_key_exists($propName, $data)){
        continue;
      }

      $value = $data[$propName];

      if (Request::isPrimitiveType($typeName)) {
        settype($value, $typeName);
        $prop->setValue($instance, $value);
      } else if (class_exists($typeName) && is_array($value)) {
        $nestedObj = Request::hydrateObject($value, $typeName);
        $prop->setValue($instance, $nestedObj);
      } else {
        $prop->setValue($instance, $value);
      }
    }

    return $instance;
  }

  public static function data(
		string|null $key,
    string $instanceType,
		RequestType $requestType,
		array $controllerEndpoint = [],
    array $requestEndpoint = []
	): mixed {
		$requestData = match($requestType)
		{
			RequestType::file => RequestData::getFile( RequestType::file ),
			RequestType::body => RequestData::getBody( RequestType::body ),
			RequestType::query => RequestData::getQuery( RequestType::query ),
			RequestType::params => RequestData::getParams(
				$controllerEndpoint, $requestEndpoint
			)
		};

		if( is_array( $requestData )){
			if( is_null( $key ) === false ){
				if( isset( $requestData[ $key ] )){
					return Request::hydrateObject(
            $requestData[$key], $instanceType
          );
				} else return null;
			}

			return Request::hydrateObject(
        $requestData, $instanceType
      );;
		}

		return null;
	}

  public static function accessToken(
  ): Decode|null {
    ["HTTP_AUTHORIZATION" => $httpAuthorization] = $_SERVER;

    if($httpAuthorization === null){
      return null;
    }

    [$bearer, $token] = explode(
      " ", $httpAuthorization
    );

    if($bearer === null || $bearer !== "Bearer"){
      return null;
    }

    if($token === null){
      return null;
    }

    return new Decode(
      $token, publickey
    );
  }
}