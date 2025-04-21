<?php

namespace Websyspro\Server\Databases\Connect;

use PDO;
use PDOStatement;
use PDOException;
use Websyspro\Server\Commons\Util;
use Websyspro\Server\Exceptions\Error;
use Websyspro\Server\Enums\Connects\ConnectDriver;

class DB extends PDO
{
  private array $dns = [];
  private PDOStatement $state;

  public function __construct(
    private string | null $hostname,
    private string | null $username,
    private string | null $password,
    private string | null $driver,
    private string | null $charset,
    private string | null $prefix,
    private string | null $port,
    private string | null $database = null
  ){}

  public static function set(
    string | null $database = null
  ): DB {
    return new static(
      connect->hostname,
      connect->username,
      connect->password,
      connect->driver,
      connect->charset,
      connect->prefix,
      connect->port, (
        $database
      )
    );
  }

  public function getDsnDriver(
  ): string {
    switch( $this->driver ){
      case ConnectDriver::MySQL->value:
          $this->dns[] = "mysql:host={$this->hostname}";
          $this->dns[] = "charset={$this->charset}";
          $this->dns[] = "port={$this->port}";
    
          if( $this->database !== null ){
            $this->dns[] = "dbname={$this->database}";
          }
        break;
      case ConnectDriver::Postgres->value:
          $this->dns[] = "pgsql:host={$this->hostname}";
          $this->dns[] = "dbname={$this->database}";    
          $this->dns[] = "port={$this->port}"; 
        break;
      case ConnectDriver::DBLib->value:
          $this->dns[] = "dblib:host={$this->hostname}:{$this->port}";
          $this->dns[] = "dbname={$this->database}"; 
        break;
      case ConnectDriver::SQLServer->value:
          $this->dns[] = "sqlsrv:Server={$this->hostname},{$this->port}";
          $this->dns[] = "Database={$this->database}"; 
        break;
    }

    return Util::Join(
      ";", $this->dns
    );
  }

  private function getUsername(
  ): string {
    return $this->username;
  }

  private function getPassword(
  ): string {
    return $this->password;
  }

  private function getOptions(
  ): array {
    return [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
  }

  public function getPrefix(
  ): string | null {
    return $this->prefix;
  }

  public function getDatabase(
  ): string | null {
    return $this->database;
  }  

  private function getConnect(
  ): bool {
    try {
      parent::__construct(
        $this->getDsnDriver(),
        $this->getUsername(),
        $this->getPassword(),
        $this->getOptions()
      );

      return true;
    } catch ( PDOException $error ){
      Error::InternalServerError(
        $error->getMessage()
      );

      return false;
    }
  }

  public function get(
    string $sql
  ): DB {
    try {
      if( $this->getConnect() ){
        $this->state = (
          $this->query(
            $sql
          )
        );
      }
    } catch ( PDOException $error ) {
      Error::InternalServerError(
        $error->getMessage()
      );
    }

    return $this;
  }

  public function call(
    string $sql
  ): bool {
    try {
      if( $this->getConnect() ){
        $this->exec(
          $sql
        );

        return true;
      }
    } catch ( PDOException $error ) {
      Error::InternalServerError(
        $error->getMessage()
      );
    }

    return false;
  }

  public function bulkList(
    array $bulks = []
  ): bool {
    try {
      if( $this->getConnect() ){
        $this->beginTransaction();
       
        Util::Mapper( $bulks, (
          fn( string $bulk ) => (
            $this->call( $bulk )
          )
        ));
        
        if( $this->inTransaction() ){
          $this->commit();
        }
        
        return true;
      }
    } catch ( PDOException $error ) {
      Error::InternalServerError(
        $error->getMessage()
      );

      if( $this->inTransaction() ){
        $this->rollBack();
      }
    }

    return false;    
  }

  public function all(
  ): array {
    if( $this->state ){
      return $this->state->fetchAll();
    }

    return [];
  }

  public function count(
  ): int {
    if( $this->state ){
      return $this->state->rowCount();
    }  
    
    return 0;
  }
}