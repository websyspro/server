<?php

namespace Websyspro\Server\Enums;

enum AttributeType: int
{
  case controller = 1;
  case middleware = 2;
  case endpoint = 3;
  case parameter = 4;
  case column = 5;
  case requireds = 6;
  case uniques = 7;
  case indexes = 8;
  case foreigns = 9;
  case primaryKey = 10;
  case generations = 11;
  case eventBeforeInsert = 12;
  case eventBeforeUpdate = 13;
  case eventBeforeDelete = 14;
  case eventAfterInsert = 16;
  case eventAfterUpdate = 17;
  case eventAfterDelete = 18;
}