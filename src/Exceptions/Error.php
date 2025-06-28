<?php

namespace Websyspro\Server\Exceptions;

use Exception;
use Websyspro\Server\Response;

class Error
{
  public static function BadRequest(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: Response::HTTP_BAD_REQUEST
    );
  }

  public static function NotFound(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: Response::HTTP_NOT_FOUND
    );
  }

  public static function InternalServerError(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: Response::HTTP_INTERNAL_SERVER_ERROR
    );
  }

  public static function Unauthorized(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: Response::HTTP_UNAUTHORIZED
    );
  }
}