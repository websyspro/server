<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;

class StructureControllers
{
  public function __construct(
    public DataList $controllers
  ){
    $this->start();
  }

  private function start(
  ): void {
    $this->controllers->mapper(
      fn(string $controller) => (
        new ItemController($controller)
      )
    );
  }
}