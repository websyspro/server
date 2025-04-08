<?php

namespace Websyspro\Server\Enums\Reflect;

enum AttributeType: int
{
  case Column = 1;
  case Controller = 2;
  case Middleware = 3;
  case Endpoint = 4;
  case Parameter = 5;
}