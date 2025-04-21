<?php

namespace Websyspro\Server\Databases\Connect;

use Websyspro\Server\Commons\Util;

class InsertBulkList
{
  public array $names = [];
  public array $inserts = [];
  public array $insertsValues = [];

  public function __construct(
    private array $dataList,
    public readonly string $table,
    public readonly string $database
  ){
    $this->setNames();
    $this->setBreakList();
  }

  private function setNames(
  ): void {
    [ $names ] = $this->dataList;
    $this->names = (
      array_keys( $names )
    );
  }

  private function setBreakList(
  ): void {
    $this->inserts = array_chunk(
      Util::Mapper( $this->dataList, fn( array $data ) => (
        sprintf( "(%s)", Util::JoinColumns( $data ))
      )), 512
    );
  }

  public function execute(
  ): bool {
    if( sizeof( $this->inserts ) === 0 ){
      return false;
    }

    return DB::set(
      $this->database
    )->bulkList(
      Util::Mapper(
        $this->inserts, fn( array $values ) => sprintf(
          "insert into {$this->table} (%s) values %s", ...[
            Util::JoinColumns( $this->names ),
            Util::JoinColumns( $values )
          ]
        )
      )
    );
  }
}