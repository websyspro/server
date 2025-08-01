<?php

namespace Websyspro\Server\Shareds;

interface IScheduler
{
  public function run(): void;
}