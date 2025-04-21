<?php

namespace Websyspro\Server\Enums\Connects;

enum ConnectDriver: string {
  case MySQL = "mysql";
  case Postgres = "pgsql";
  case SQLServer = "sqlsrv";
  case DBLib = "dblib";
}