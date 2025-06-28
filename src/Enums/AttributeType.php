<?php

namespace Websyspro\Server\Enums;

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
  case EventBeforeInsert = 12;
  case EventBeforeUpdate = 13;
  case EventBeforeDelete = 14;
  case EventAfterInsert = 16;
  case EventAfterUpdate = 17;
  case EventAfterDelete = 18;
}