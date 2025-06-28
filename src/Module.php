<?php

namespace Websyspro\Server;

use Websyspro\Commons\DataList;

class Module
{
  public function __construct(
    public DataList $controllers
  ){}

  public static function Set(
    DataList $controllers
  ): Module {
    return new static(
      $controllers
    );
  }
}