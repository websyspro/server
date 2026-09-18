<?php

namespace Websyspro\Server\Crm\Entities;

use Websyspro\Entity\BaseUUIDEntity;
use Websyspro\Entity\Decorators\Entity;
use Websyspro\Entity\Decorators\Length;
use Websyspro\Entity\Decorators\Precision;
use Websyspro\Entity\Decorators\Synchronize;
use Websyspro\Entity\Types\ColumnDecimal;
use Websyspro\Entity\Types\ColumnText;

#[Entity( "Distribuidor" )]
#[Synchronize( false )]
class DistribuidorEntity
extends BaseUUIDEntity
{
  #[Length(2)]
  public ColumnText $Uf;

  #[Precision(18, 2)]
  public ColumnDecimal $Majoracao;

  #[Precision(18, 2)]
  public ColumnDecimal $Desconto;

  #[Length(100)]
  public ColumnText $Nome;

  #[Precision(18, 2)]
  public ColumnDecimal $MargemLucro;

  #[Length(100)]
  public ColumnText $Cnpj;
}