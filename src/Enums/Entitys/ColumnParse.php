<?php

namespace Websyspro\Server\Enums\Entitys;

enum ColumnParse: string {
  case DatetimeBr = "/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}:\d{2}:\d{2})/";
  case DateBr = "/(\d{2})\/(\d{2})\/(\d{4})/";
}