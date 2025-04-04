<?php

namespace Websyspro\Server\Databases
{
  use PDO;
  use PDOException;

  class Connect
  {
    private mixed $connectHandle = null;
    private mixed $statement = null;

    public function __construct(
      private readonly string $database
    ){}

    public function config(
    ): object {
      return (object)[
        "user" => ((object)(connect))->user,
        "pass" => ((object)(connect))->pass,
        "opts" => sprintf( "%s:host=%s:%s;dbname=%s;charset=utf8",
          ((object)(connect))->type,
          ((object)(connect))->host,
          ((object)(connect))->port,
          ((object)(connect))->name
        ),
      ];
    }

    public function start(
    ): bool {
      if( $this->connectHandle === null ){
        try{
          $this->connectHandle = new PDO(
            $this->config()->opts, 
            $this->config()->user, 
            $this->config()->pass, [
              PDO::ATTR_ERRMODE, 
              PDO::ERRMODE_EXCEPTION
            ]
          );
        } catch ( PDOException $e ){}
      }

      return $this->connectHandle !== null;
    }

    public function isConnected(
    ): bool {
      return $this->connectHandle !== null;
    }

    public function query(
      string $queryString
    ): Connect {
      if( $this->start() ){
        $this->statement = (
          $this->connectHandle->query(
            $queryString
          )
        );

        if(!$this->statement){
          echo "eerro";
        }
      };

      return $this;
    }

    public function fetchAll(
    ): array {
      return $this->statement->fetchAll(
        PDO::FETCH_OBJ
      );
    }

    public static function on(
      string $database
    ): Connect {
      return new static(
        database: $database
      );
    }
  }
}