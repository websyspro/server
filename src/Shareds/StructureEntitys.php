<?php

namespace Websyspro\Server\Shareds;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Core\StructureDatabase;

class StructureEntitys
{
  public function __construct(
    public DataList $entitys,
    public string $module
  ){
    $this->Initial();
  }

  private function Initial(
  ): void {
    (new StructureDatabase(
      $this->entitys, 
      $this->module
    ))->Update();    
  }
}