<?php

namespace Websyspro\Server\Shareds;

interface ISchedule
{
  public function run(): void;
}