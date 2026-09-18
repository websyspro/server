<?php

namespace Websyspro\Server\Accounts\Entities;

use Websyspro\Entity\BaseUUIDEntity;
use Websyspro\Entity\Decorators\Entity;
use Websyspro\Entity\Decorators\ForeignKey;
use Websyspro\Entity\Decorators\Length;
use Websyspro\Entity\Decorators\Required;
use Websyspro\Entity\Types\ColumnText;

#[Entity("access")]
class AccessEntity extends BaseUUIDEntity
{
  #[Required()]
  #[ForeignKey(UserEntity::class)]
  public ColumnText $userId;

  #[Required]
  #[Length(255)]
  public ColumnText $passwordHash;
}
