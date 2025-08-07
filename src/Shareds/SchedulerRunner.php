<?php

namespace Websyspro\Server\Shareds;

use Exception;
use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Logger\Log;

class SchedulerRunner
{
  private int $interval = 60;
  private bool $isReady = false;

  public function __construct(
    private readonly DataList $modules
  ){
    $this->setModules();
    $this->setPathDefault();
  }
  
  private function getInstanceFromTask(
    string $moduleClass
  ): object {
    $moduleClassConstructor = method_exists(
      $moduleClass, "__construct"
    );

    if($moduleClassConstructor === true){
      return InstanceDependences::gets($moduleClass);
    } else return new $moduleClass;    
  }

  private function getExpression(
    string $refClass
  ): string {
    return Reflect::instancesFromAttributes($refClass)->first()->expression;
  }

  private function setModules(
  ): void {
    $this->modules->mapper(
      fn(string $moduleClass) => (
        Reflect::instancesFromAttributes(
          $moduleClass
        )
      )
    );
    
    $this->modules->reduce(
      [], fn(mixed $curr, DataList $item ) => (
        array_merge($curr, $item->first()->Schedulers)
      )
    );

    $this->modules->mapper(
      fn(string $refClass) => (
        new SchedulerTask(
          expression: $this->getExpression($refClass), 
          instance: $this->getInstanceFromTask($refClass)
        )
      )
    );
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
    Log::message(
      LogType::context,
        "Start Runner"
    );

    while($this->isLoop()){
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

  private function matchCronPart(
    string $expr,
    int $value
  ): bool {
    if ($expr === "*"){
      return true;
    }

    $exprs = explode(
      ",", $expr
    );

    foreach($exprs as $part){
      if(str_contains($part, "/") === true){
        [$range, $step] = explode(
          "/", $part
        );

        if($range === "*") {
          if ($value % (int)$step === 0){
            return true;
          }
        }
      } else
      if(str_contains($part, "-") === true){
        [$start, $end] = explode(
          "-", $part
        );

        if($value >= (int)$start && $value <= (int)$end){
          return true;
        } else
        if((int)$part === $value){
          return true;
        }
      }
    }

    return false;
  }

  private function hasCronDueNow(
    string $expression,
    array $getdate
  ): bool {
    $expressionParts = preg_split(
      "#\s+#", trim($expression)
    );

    if(sizeof($expressionParts) !== 5){
      return false;
    }

    [$min, $hour, $day, $month, $weekday] = $expressionParts;

    return (
      $this->matchCronPart($min, $getdate["minutes"]) && 
      $this->matchCronPart($hour, $getdate["hours"]) && 
      $this->matchCronPart($day, $getdate["mday"]) &&
      $this->matchCronPart($month, $getdate["mon"]) &&
      $this->matchCronPart($weekday, $getdate["wday"])
    );
  }

  private function startTask(
  ): void {
    $this->modules->forEach(
      function(SchedulerTask $schedulerTask){
        if($this->hasCronDueNow($schedulerTask->expression, getdate()) === true){
          try {
            $schedulerTask->instance->run();
          } catch(Exception $error){
            Log::error(LogType::service, "Error {$error->getMessage()}");
          }
        }
      }
    );
  }
}