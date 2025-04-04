<?php

namespace Websyspro\Server\Databases\Commands
{
  use Websyspro\Server\Commons\Util;
  use Websyspro\Server\Entitys\StructureAttribute;
  use Websyspro\Server\Entitys\StructureDesignResult;

  class CommandsUtil
  {
    public int $type = 1;
    public array $command = [];
    public array $message = [];

    public function __construct(
      private readonly StructureDesignResult $StructureDesignResult,
      private readonly StructureAttribute | null $structureAttribute = null
    ){
      $this->setCommand();
    }

    public function getStructureDesignResult(
    ): StructureDesignResult {
      return $this->StructureDesignResult;
    }

    public function getEntity(
    ): string {
      return $this->getStructureDesignResult()->getEntity();
    }

    public function getNotNull(
      string $name
    ): string | null {
      return $this->getStructureDesignResult()->getNotNull($name);
    }
    
    public function getName(
    ): string {
      return $this->structureAttribute->name;
    }    
    
    public function getArgs(
    ): string {
      [ $structureAttribute ] = Util::Filter(
        $this->StructureDesignResult->columns, fn( StructureAttribute $structureAttribute ) => (
          $structureAttribute->name === $this->structureAttribute->name
        )
      );

      return $structureAttribute->args;
    }    

    public function setCommand(
    ): void {}
  }
}