<?php

namespace Websyspro\Server\Shareds;

class SchedulerTask
{
  public function __construct(
    public readonly string $expression,
    public readonly object $instance
  ){}
}