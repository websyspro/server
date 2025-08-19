<?php

namespace Websyspro\Server
{
  class Http
  {
    public static function get(
      string $url
    ): object {
      $requestFile = file_get_contents($url);
      return json_decode(json_encode(json_decode($requestFile, true)));
    }
  }
}