<?php

namespace Websyspro\Server\Decorators\Entity\Enums
{
  enum AttributeType: int {
    case Columns = 1;
    case Uniques = 2;
    case Indexes = 3;
    case Foreigns = 4;
    case Requireds = 5;
    case PrimaryKey = 6;
    case AutoIncrement = 7;
    case TriggersBeforeCreate = 8;
    case TriggersBeforeUpdate = 9;
    case TriggersBeforeDelete = 10;
    case TriggersAfterCreate = 11;
    case TriggersAfterUpdate = 12;
    case TriggersAfterDelete = 13;
  }
}