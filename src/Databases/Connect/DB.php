<?php

namespace Websyspro\Server\Databases\Connect;

use PDOException;
use Websyspro\Server\Exceptions\Error;

class DB extends DBUtils
{    
  public function query(
    string $sql
  ): DB {
    try {
      if( $this->connect() ){
        $this->pdoStatement = (
          $this->pdo->query($sql)
        );
      }

      return $this;
    } catch ( PDOException $error ){
      Error::InternalServerError(
        $error->getMessage()
      );

      return $this;
    }
  }

  public function all(
  ): array {
    if( $this->pdoStatement ){
      return $this->pdoStatement->fetchAll();
    } else return [];
  }

  public function execute(
    string $sql
  ): bool {
    try {
      if( $this->connect() ){
        $this->pdo->exec($sql);
      }

      return true;
    } catch ( PDOException $error ){
      Error::InternalServerError(
        $error->getMessage() . " - " . $sql
      );

      return false;
    }
  }    

  public static function set(
    string | null $name = null
  ): DB {
    return (
      new static(
        $name
      )
    );
  }
}