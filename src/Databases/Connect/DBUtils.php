<?php 

namespace Websyspro\Server\Databases\Connect
{
  use PDO;
  use PDOException;
  use PDOStatement;
    use Websyspro\Server\Commons\Util;
    use Websyspro\Server\Exceptions\Error;
  use Websyspro\Server\Exceptions\InternalServerError;
  use Websyspro\Server\Interfaces\Connects\Config;

  class DBUtils
  {
    public PDO | null $pdo = null;
    public Config $config;
    public PDOStatement | null $pdoStatement = null;

    public function __construct(
      private string | null $database = null
    ){
      $this->connectConfig();
    }

    public function connectConfig(
    ): void {
      $this->config = new Config(
        port: connect->port,
        type: connect->type,
        prefix: connect->prefix,
        database: connect->database,
        hostname: connect->hostname,
        username: connect->username,
        password: connect->password
      );

      $this->config->database = (
        $this->getDatabaseWithPrefix()
      );
    }
    
    public function getDatabaseWithPrefix(
    ): string {
      $this->database = Util::getData(
        $this->database ?? (
          connect->database
        )
      );
      
      $hasPrefixed = substr(
        $this->database, 0, strlen( connect->prefix )
      ) === connect->prefix;

      return $hasPrefixed ? $this->database : sprintf(
        "%s%s", connect->prefix, $this->database
      );
    }

    public function connectUser(
    ): string {
      return $this->config->username;
    }
  
    public function connectPass(
    ): string {
      return $this->config->password;
    }

    public function connectOpts(
    ): array {
      return [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ];
    }

    public function connectDSN(
    ): string {
      $dsnStr = match( $this->config->type ){
        "mysql" => "mysql:host=%s:%s;dbname=%s;charset=utf8mb4",
        "pgsql" => "pgsql:host=%s;port=%s;dbname=%s",
        "sqlserver" => "sqlsrv:Server=%s,%s;Database=%s",

        default => Error::InternalServerError(
          "Invalid connect type: {$this->config->type}"
        )
      };

      return sprintf( $dsnStr,
        $this->config->hostname,
        $this->config->port,
        $this->config->database
      );
    }

    public function connect(
    ): bool {
      try {
        $this->pdo = new PDO(
          $this->connectDSN(),
          $this->connectUser(), 
          $this->connectPass(),
          $this->connectOpts()
        );
        
        return $this->pdo !== null;
      } catch( PDOException $error ) {
        Error::InternalServerError(
          $error->getMessage()
        );

        return false;
      }
    }
  }
}