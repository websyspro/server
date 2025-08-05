<?php

namespace Websyspro\Server\Shareds;

class SchedulerTask
{
  public function __construct(
    public readonly mixed $expression,
    public readonly object $object
  ){}
}