<?php

namespace Websyspro\Server\Databases\Entity\Utils;

class Now
{
  public static function get(
  ): string {
    return date( "Y-m-d H:i:s" );
  }
}