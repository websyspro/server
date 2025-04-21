<?php

namespace Websyspro\Server\Enums\Controllers;

enum MethodType: int {
  case Post = 1;
  case Get = 2;
  case Put = 3;
  case Delete = 4;
}