<?php

namespace Websyspro\Server\Decorators\Entity\Util
{
  class Now
  {
    public static function execute(
    ): string {
      return date( "Y-m-d G:i:s" );
    }
  }
}