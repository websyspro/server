<?php

namespace Websyspro\Server\Enums\Entitys;

enum ColumnType: int
{
  case Number = 1;
  case Varchar = 2;
  case Decimal = 3;
  case Time = 4;
  case Date = 5;
  case Datetime = 6;
  case Flag = 7;
}