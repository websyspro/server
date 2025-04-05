<?php

namespace Websyspro\Server\Exceptions
{
  use Exception;
  use Websyspro\Server\Http\Response;

  class BadRequest
  {
    public static function handle(
      string $message
    ): Exception {
      return throw new Exception(
        message: $message,
        code: Response::HTTP_BAD_REQUEST
      );
    }
  }
}