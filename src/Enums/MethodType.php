<?php

namespace Websyspro\Server\Enums;

enum MethodType: int {
  case post = 1;
  case get = 2;
  case put = 3;
  case delete = 4;
}