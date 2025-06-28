<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;

class StructureControllers
{
  public function __construct(
    public DataList $controllers
  ){
    $this->Initial();
  }

  private function Initial(
  ): void {
    $this->controllers->Mapper(
      fn(string $controller) => (
        new ItemController($controller)
      )
    );
  }
}