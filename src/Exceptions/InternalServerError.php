<?php

namespace Websyspro\Server\Exceptions
{
  use Exception;
    use Websyspro\Server\Commons\Log;
    use Websyspro\Server\Enums\LogType;
    use Websyspro\Server\Http\Response;

  class InternalServerError
  {
    public static function handle(
      string $message
    ): Exception {
      Log::Error(
        LogType::Service,
        $message
      );
      
      return throw new Exception(
        message: Response::ERROR_INTERNAL_SERVER,
        code: Response::HTTP_INTERNAL_SERVER_ERROR
      );
    }
  }
}