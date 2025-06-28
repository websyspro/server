<?php

namespace Websyspro\Server\Applications\Shops\Services;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Repository;
use Websyspro\Logger\Enums\LogType;
use Websyspro\Logger\Message;
use Websyspro\Server\Applications\Shops\Entitys\BoxEntity;
use Websyspro\Server\Applications\Shops\Entitys\CashMovementEntity;
use Websyspro\Server\Applications\Shops\Entitys\ConfigEntity;
use Websyspro\Server\Applications\Shops\Entitys\CustomerEntity;
use Websyspro\Server\Applications\Shops\Entitys\DocumentEntity;
use Websyspro\Server\Applications\Shops\Entitys\DocumentItemEntity;
use Websyspro\Server\Applications\Shops\Entitys\OperatorEntity;
use Websyspro\Server\Applications\Shops\Entitys\ProductEntity;
use Websyspro\Server\Applications\Shops\Entitys\ProductGroupEntity;
use Websyspro\Server\Applications\Shops\Imports\BoxImport;
use Websyspro\Server\Applications\Shops\Imports\CashMovementImport;
use Websyspro\Server\Applications\Shops\Imports\ConfigImport;
use Websyspro\Server\Applications\Shops\Imports\CustumerImport;
use Websyspro\Server\Applications\Shops\Imports\DocumentImport;
use Websyspro\Server\Applications\Shops\Imports\DocumentItemImport;
use Websyspro\Server\Applications\Shops\Imports\OperatorImport;
use Websyspro\Server\Applications\Shops\Imports\ProductGroupImport;
use Websyspro\Server\Applications\Shops\Imports\ProductImport;

class InitService
{
  public function __construct(
  ){
    $this->Imports();
  }

  private function Imports(
  ): void {
    DataList::Create([
      OperatorEntity::class => OperatorImport::class,
      CustomerEntity::class => CustumerImport::class,
      ProductGroupEntity::class => ProductGroupImport::class,
      ProductEntity::class => ProductImport::class,
      BoxEntity::class => BoxImport::class,
      ConfigEntity::class => ConfigImport::class,
      DocumentEntity::class => DocumentImport::class,
      DocumentItemEntity::class => DocumentItemImport::class,
      CashMovementEntity::class => CashMovementImport::class      
    ])->Mapper(
      function(string $import, string $entity){
        $repositoryEntity = (
          Repository::Entity(
            $entity
          )
        );

        if($repositoryEntity->Count() === 0){
          if($repositoryEntity->InsertFromImport($import::rows())){
            Message::Infors(LogType::Database, sprintf(
              "Successfully import %s records from the updated %s table", ...[
                sizeof($import::rows()), $repositoryEntity->structureTable->table
              ]
            ));
          }
        }
      }
    );
  }
}