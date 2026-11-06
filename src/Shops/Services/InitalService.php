<?php

namespace Websyspro\Server\Shops\Services;

use Websyspro\ArrowToSql\Repository;
use Websyspro\Server\Shops\Entities\BoxEntity;
use Websyspro\Server\Shops\Entities\CashMovementEntity;
use Websyspro\Server\Shops\Entities\ConfigEntity;
use Websyspro\Server\Shops\Entities\CustomerEntity;
use Websyspro\Server\Shops\Entities\DocumentEntity;
use Websyspro\Server\Shops\Entities\DocumentItemEntity;
use Websyspro\Server\Shops\Entities\OperatorEntity;
use Websyspro\Server\Shops\Entities\ProductEntity;
use Websyspro\Server\Shops\Entities\ProductGroupEntity;
use Websyspro\Server\Shops\Imports\BoxImport;
use Websyspro\Server\Shops\Imports\CashMovementImport;
use Websyspro\Server\Shops\Imports\ConfigImport;
use Websyspro\Server\Shops\Imports\CustomerImport;
use Websyspro\Server\Shops\Imports\DocumentImport;
use Websyspro\Server\Shops\Imports\DocumentItemImport;
use Websyspro\Server\Shops\Imports\OperatorImport;
use Websyspro\Server\Shops\Imports\ProductGroupImport;
use Websyspro\Server\Shops\Imports\ProductImport;
use Websyspro\WorkerServer\Enums\LogContext;
use Websyspro\WorkerServer\Logger;
use function sizeof;

class InitalService
{
  public function __construct(
  ){
    foreach( $this->importArr() as $entity => $import ){
      $repo = new Repository( $entity );
      if( $repo->paged(1, 1)->rows()->count() === 0 ){
        $rows = $import::rows();
        $repo->create( $rows );

        Logger::info( 
          $this->getMessage( 
            $repo->getEntityStructure()->getEntityAlias(),
              sizeof( $rows )
          ), LogContext::Database
        );
      }
    }
  }

  private function importArr(
  ): array {
    return [
      OperatorEntity::class => OperatorImport::class,
      CustomerEntity::class => CustomerImport::class,
      ProductGroupEntity::class => ProductGroupImport::class,
      ProductEntity::class => ProductImport::class,
      BoxEntity::class => BoxImport::class,
      ConfigEntity::class => ConfigImport::class,
      DocumentEntity::class => DocumentImport::class,
      DocumentItemEntity::class => DocumentItemImport::class,
      CashMovementEntity::class => CashMovementImport::class
    ];
  }

  private function getMessage(
    string $alias,
    int $rows
  ): string {
    return "Importing {$rows} records into table {$alias}";
  }
}