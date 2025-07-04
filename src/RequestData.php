<?php

namespace Websyspro\Server;

use Websyspro\Commons\Util;
use Websyspro\Server\Enums\ContentType;
use Websyspro\Server\Enums\RequestType;

class RequestData
{
  public static function getBody(
  ): array {
    if(RequestData::equestMethod() === "POST"){
      if(in_array(RequestData::contentType(), [
        ContentType::applicationJSON->value,
        ContentType::multipartFormData->value,
        ContentType::multipartFormDataUrlencoded->value,					
      ])){
        if(RequestData::contentType() !== ContentType::applicationJSON->value){
          return $_POST;
        }

        return json_decode(
          RequestData::getPhpInput(), true
        );
      }
    }

    return RequestData::contentFromFile(
      RequestType::body
    );
  }

  public static function getQuery(
  ): array {
    return $_GET;
  }
  
  public static function getParams(	
    array $controllerEndpoint = [],
    array $requestEndpoint = [],
    array $params = []
  ): array {
    foreach( $controllerEndpoint as $key => $path ){
      if((bool)preg_match( "/^:/", $path ) === true){
        $params[ preg_replace("/^:/", "", $path) ] = (
          preg_replace('/\?.*/', '', $requestEndpoint[ $key ])
        );
      }
    }

    return $params;
  }
  
  public static function getFile(
    RequestType $requestType
  ): array {
    return (
      RequestData::equestMethod() !== "POST"
        ? RequestData::contentFromFile($requestType)
        : RequestData::contentPostFile()
    );
  }

  private static function equestMethod(
  ): string {
    ["REQUEST_METHOD" => $requestMethod] = $_SERVER;
    return $requestMethod;
  }

  private static function extractContentType(
    string $contentType
  ): string {
    [ $contentType ] = explode(";", $contentType);
    return $contentType;
  }		

  public static function contentType(
  ): string | null {
    if(isset($_SERVER["CONTENT_TYPE"]) === false ) {
      return null;
    }

    [ "CONTENT_TYPE" => $contentType,
      "CONTENT_LENGTH" => $contentLength
    ] = $_SERVER;

    return (int)$contentLength !== 0
      ? static::extractContentType( $contentType )
      : null;
  }		

  private static function getPhpInput(
  ): string {
    return file_get_contents(
      "php://input"
    );
  }

  private static function contentLoadFileList(
    array $bufferArr = []
  ): array {
    $inputHandle = fopen( "php://input", "r" );
    while (( $buffer = fgets( $inputHandle, 4096 )) !== false) {
      $bufferArr[] = $buffer;
    }

    return array_slice(
      $bufferArr,
      0, sizeof(
        $bufferArr
      ) - 1
    );
  }

  private static function extractName(
    string $value
  ): string {
    [ , $value ] = explode(
      ";",
      $value
    );
    return preg_replace(
      "/(^name=\")|(\"$)/",
      "",
      trim(
        $value
      )
    );
  }

  private static function extractFile(
    string $value
  ): string {
    [, , $value] = explode( ";", $value);

    if(is_null($value)){
      return "";
    }

    return preg_replace("/(^filename=\")|(\"$)/", "", trim($value));
  }	
  
  private static function extractType(
    string $value
  ): string {
    return preg_replace( "/^Content-Type: /", "", trim($value));
  }

  private static function extractSize(
    array $value
  ): float {
    return (float)strlen(
      implode( "", array_slice( $value, 3 ))
    ) - 2;
  }

  private static function extractBody(
    array $value
  ): string {
    return base64_encode( implode( "", array_slice($value, 3)));
  }

  private static function contentFromFile(
    RequestType $requestType,
    array $content = [],
    int $cursor = -1,
  ): array {
    if( static::contentType() === ContentType::multipartFormData->value ){
      foreach( static::contentLoadFileList() as $buffer ) {
        if( preg_match("/^-{28}\d+$/", trim($buffer)) === 1 ){
          ++$cursor;
        }

        $data[$cursor][] = $buffer;
      }

      foreach( Util::Mapper($data, fn(array $groupBuffer) => array_slice($groupBuffer, 1)) as $contextBuffers ){
        [	$contextDetails, $contextType, $contextValue ] = $contextBuffers;

        $contextDetailsName = RequestData::extractName($contextDetails);
        $contextDetailsFile = RequestData::extractFile($contextDetails);
        $contentSize = RequestData::extractSize($contextBuffers);
        $contentBody = RequestData::extractBody($contextBuffers);
        $contentType = RequestData::extractType($contextType);
        
        if( $requestType === RequestType::body && empty(trim($contextType)) === true ){
          $content[ $contextDetailsName ] = trim( $contextValue );
        }

        if( $requestType === RequestType::file && empty(trim($contextType)) === false ){
          $contentBody = RequestData::extractBody( $contextBuffers);

          $content[ $contextDetailsName ] = [
            "name" => $contextDetailsFile,
            "type" => $contentType,
            "size" => $contentSize,
            "body" => $contentBody
          ];						
        }
      }

      return $content;
    } elseif ( RequestData::contentType() === ContentType::multipartFormDataUrlencoded->value ) {
      parse_str( RequestData::getPhpInput(), $content );
      return $content;
    } elseif ( RequestData::contentType() === ContentType::applicationJSON->value ) {
      return json_decode( RequestData::getPhpInput(), true );
    }
    
    return [];
  }

  private static function contentPostFile(
  ): array {
    return Util::Mapper(
      $_FILES, fn( $file ) => [
        "name" => $file[ "name" ],
        "type" => $file[ "type" ],
        "size" => $file[ "size" ],
        "body" => base64_encode(
          file_get_contents(
            $file[ "tmp_name" ]
          )
        )
      ]
    );
  }
}