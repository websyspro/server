<?php

namespace Websyspro\Server\Enums\Request;

enum RequestType: int {
  case BODY = 1;
  case FILE = 2;
  case PARAMS = 3;
  case QUERY = 4;
}