<?php

namespace Websyspro\Server\Enums;

enum RequestType: int {
  case body = 1;
  case file = 2;
  case params = 3;
  case query = 4;
}