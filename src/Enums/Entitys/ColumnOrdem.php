<?php

namespace Websyspro\Server\Enums\Entitys;

enum ColumnOrdem: string {
  case ColumnStart = "Id";
  case ColumnEnd = "Actived|ActivedBy|ActivedAt|CreatedBy|CreatedAt|UpdatedBy|UpdatedAt|Deleted|DeletedBy|DeletedAt";
}