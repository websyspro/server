<?php

namespace Websyspro\Server\Shareds;

class SchedulerTask
{
  public function __construct(
    public readonly int $minute,
    public readonly string $classRef
  ){}
}