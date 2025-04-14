<?php

namespace Websyspro\Server\Enums\Entitys;

enum CommandType: int {
  case Entitys = 1;
  case Columns = 2;
  case PrimaryKeys = 3;
  case Generationns = 4;
  case Uniques = 5;
  case Statistics = 6;
  case ForeignKeys = 7;
}