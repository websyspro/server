<?php

namespace Websyspro\Server\Commons
{
  use Websyspro\Server\Enums\LogType;

  class Log
  {
    public static float $startTimer;

    public static function setStartTimer(
    ): void {
      Log::$startTimer = microtime(true);
    }
  
    public static function getNowTimer(
    ): int { 
      $starDiff = round(( 
        microtime(true) - Log::$startTimer
      ) * 1000);
  
      Log::setStartTimer(); 
      return $starDiff;
    }

    public static function getNow(
    ): string {
      return date( "[D M  d H:i:s Y]" );
    }

    public static function getOrigem(
    ): string {
      [ "REMOTE_ADDR" => $remoteAddr, 
        "SERVER_PORT" => $serverPort ] = $_SERVER;

      return $remoteAddr !== null && $serverPort !== null
        ? "[{$remoteAddr}]:{$serverPort}"
        : "[::1]:00000";
    }
    
    public static function Message(
      LogType $logType,
      string $logText
    ): void {
      fwrite( fopen('php://stdout', 'w'), (
        sprintf("\x1b[37m%s %s\x1b[32m LOG \x1b[33m[{$logType->value}] \x1b[32m{$logText}\x1b[37m \x1b[37m+%sms\n", 
          Log::getNow(),
          Log::getOrigem(),
          Log::getNowTimer(),
        )
      ));
    }

    public static function Error(
      LogType $logType,
      string $logText      
    ): void {
      fwrite( fopen('php://stdout', 'w'), (
        sprintf( "\x1b[37m%s %s\x1b[32m LOG \x1b[33m[{$logType->value}] \x1b[31m{$logText} \x1b[37m+%sms\n",
          Log::getNow(),
          Log::getOrigem(),
          Log::getNowTimer(),
        )
      ));
    }
  }
}