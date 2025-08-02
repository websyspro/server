<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Logger\Log;

class SchedulerRunner
{
  private int $interval = 60;
  private bool $isReady = false;

  public function __construct(
    private readonly DataList $modules
  ){
    $this->setPathDefault();
  }

  private function setPathDefault(
  ): void {
    $dir = dirname($this->pathRunner());
    if(is_dir($dir) === false){
      mkdir($dir, 0777, true);
    }
  }

  private function pathStop(
  ): string {
    return rootdir . "/tmp/scheduler/stop";
  }

  private function pathRunner(
  ): string {
    return rootdir . "/tmp/scheduler/runner";
  }  

  private function setRunner(
  ): void {
    file_put_contents(
      $this->pathRunner(), ""
    );
  }

  public function stop(
  ): void {
    file_put_contents(
      $this->pathStop(), ""
    );   
  }

  public function isRunning(
  ): bool {
    return (
      file_exists(
        $this->pathRunner()
      )
    );
  }  

  public function isLoop(
  ): bool {
    if(file_exists($this->pathStop())){
      return false;
    }

    return true;
  }

  public function start(
  ): void {
    while($this->isLoop()){
      Log::message(
        LogType::context,
          "Start Runner"
      );

      if($this->isReady === true){
        $this->startTask();
      }

      if($this->isReady === false){
        $this->setReady();
      }

      sleep($this->interval);
    }

    $this->unlinkRunner();
  }

  private function setReady(    
  ): void {
    if(file_exists($this->pathStop())) {
      $this->isReady = unlink($this->pathStop());
    } else {
        $this->isReady = true;
    }

    if($this->isReady === true && !file_exists($this->pathRunner())) {
      $this->setRunner();
    }
  }

  private function unlinkRunner(
  ): void {
    if(file_exists($this->pathRunner()) === true){
      $isUnlinkRunner = unlink(
        $this->pathRunner()
      );

      if($isUnlinkRunner === true){
        // Todo para logs
      }
    }
  }

  private function startTask(
  ): void {
    // Loop nos modules
    Log::message(LogType::context, "Is ready para executar {$this->modules->count()}tasks");
  }
}