<?php

namespace Websyspro\Server\Databases\Connect;

use Websyspro\Server\Commons\Util;

class UpdateBulkList
{
  public array $wheres = [];
  public array $updates = [];

  public function __construct(
    private array $dataList,
    private array $primaryKeys,
    public readonly string $entity,
    public readonly string $database    
  ){
    $this->setUpdates();
    $this->setWheres();
    $this->setClear();
  }

  private function setUpdates(
  ): void {
    $this->updates = (
      array_chunk(
        Util::Mapper(
          Util::Mapper(
            $this->dataList, fn( array $data ) => (
              Util::FilterByKey( $data, fn( string $key ) => (
                in_array( $key, $this->primaryKeys ) === false
              ))
            )
          ), fn( array $column ) => (
            Util::JoinColumns(
              Util::Mapper( $column, (
                fn( string $val, string $key ) => (
                  "{$key}={$val}"
                )
              ))
            )
          )
        ), 512
      )
    );
  }

  private function setWheres(
  ): void {
    $this->wheres = (
      array_chunk(
        Util::Mapper(
          Util::Mapper(
            $this->dataList, fn( array $data ) => (
              Util::FilterByKey( $data, fn( string $key ) => (
                in_array( $key, $this->primaryKeys ) 
              ))
            )
          ), fn( array $column ) => (
            sprintf( "%s=%s", ...[
              end( array_keys( $column )),
              end( array_values( $column ))
            ])
          )
        ), 512
      )
    );
  }

  private function setClear(
  ): void {
    unset( $this->dataList );
    unset( $this->primaryKeys );
  }

  public function execute(
  ): bool {
    if( sizeof( $this->updates ) === 0 ){
      return false;
    }

    return DB::set(
      $this->database
    )->bulkList(
      Util::Mapper(
        Util::MapperByKey(
          $this->updates, fn( int $updateKey ) => (
            Util::Mapper( $this->updates[$updateKey], fn( string $updateItems, int $key ) => (
              "update {$this->entity} set {$updateItems} where {$this->wheres[$updateKey][$key]}"
            ))
          )
        ), fn( array $updateItems ) => (
          Util::JoinScripts(
            $updateItems
          )
        )
      )
    );
  }
}