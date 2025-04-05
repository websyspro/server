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
      return date( "d/m/Y, H:i:s" );
    }
    
    public static function Message(
      LogType $logType,
      string $logText
    ): void {
      $getTimer = Log::getNowTimer();
      $getNow = Log::getNow();
  
      fwrite( fopen('php://stdout', 'w'), (
        "\x1b[32mWebSysPro - \x1b[37m{$getNow}\x1b[32m LOG \x1b[33m[{$logType->value}] \x1b[32m{$logText}\x1b[37m \x1b[37m+{$getTimer}ms\n"
      ));
    }

    public static function Error(
      LogType $logType,
      string $logText      
    ): void {
      $getTimer = Log::getNowTimer();
      $getNow = Log::getNow();
  
      fwrite( fopen('php://stdout', 'w'), (
        "\x1b[32mWebSysPro - \x1b[37m{$getNow}\x1b[32m LOG \x1b[33m[{$logType->value}] \x1b[31m{$logText} \x1b[37m+{$getTimer}ms\n"
      ));
    }
  }
}