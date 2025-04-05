<?php

namespace Websyspro\Server\Databases\Connect\Enums
{
  enum ConnectType: string {
    case MySQL = "mysql";
    case SQLServer = "sqlsrv";
  }
}