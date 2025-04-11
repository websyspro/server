<?php

namespace Websyspro\Server\Databases\Structure\Drivers;

use Websyspro\Server\Databases\Structure\StructureTable;

class MySql extends AbstractDriver
{
  public function setMapperStart(
  ): void {
    $this->setMapperEntityPersisteds();
  }
  
  private function setMapperEntityPersisteds(
  ): void {
    $this->setMapperEntityPersistedsData();
    $this->setMapperEntityPersistedsColumns();
    $this->setMapperEntityPersistedsRequireds();
    $this->setMapperEntityPersistedsPrimaryKeys();
    $this->setMapperEntityPersistedsGenerations();
    $this->setMapperEntityPersistedsUniques();
    $this->setMapperEntityPersistedsStatistics();
    $this->setMapperEntityPersistedsForeignKeys();
  }

  private function setMapperEntityPersistedsData(
  ): void {}

  private function setMapperEntityPersistedsColumns(
  ): void {}

  private function setMapperEntityPersistedsRequireds(
  ): void {}

  private function setMapperEntityPersistedsPrimaryKeys(
  ): void {}  

  private function setMapperEntityPersistedsGenerations(
  ): void {}
  
  private function setMapperEntityPersistedsUniques(
  ): void {}

  private function setMapperEntityPersistedsStatistics(
  ): void {}
  
  private function setMapperEntityPersistedsForeignKeys(
  ): void {}  

  private function setMapperEntityColumn(
    StructureTable $structure
  ): void {}
}