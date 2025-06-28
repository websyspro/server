<?php

namespace Websyspro\Server\Applications\Accounts\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\Unique;
use Websyspro\Entity\Decorations\Requireds\NotNull;

class UserEntity
extends BaseEntity
{
  #[Text(255)]
  public string $Name;

  #[Text(320)]
  #[NotNull()]
  #[Unique()]
  public string $Email;
}