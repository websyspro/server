<?php

namespace Websyspro\Server\Enums\Entitys;

enum ColumnType: string
{
  case Number = "number";
  case Text = "text";
  case Decimal = "decimal";
  case Time = "time";
  case Date = "date";
  case Datetime = "datetime";
  case Flag = "flag";
}