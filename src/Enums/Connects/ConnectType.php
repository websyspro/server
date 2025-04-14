<?php

namespace Websyspro\Server\Enums\Connects;

enum ConnectType: string {
  case MySQL = "mysql";
  case SQLServer = "sqlsrv";
}