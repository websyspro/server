<?php

namespace Websyspro\Server\Enums\Reflect;

enum AttributeType: int
{
  case Controller = 1;
  case Middleware = 2;
  case Endpoint = 3;
  case Parameter = 4;
  case Column = 5;
  case Requireds = 6;
  case Uniques = 7;
  case Indexes = 8;
  case Foreigns = 9;
  case PrimaryKey = 10;
  case Generations = 11;
  case TriggersBeforeCreate = 12;
  case TriggersBeforeUpdate = 13;
  case TriggersBeforeDelete = 14;
  case TriggersAfterCreate = 16;
  case TriggersAfterUpdate = 17;
  case TriggersAfterDelete = 18;
}