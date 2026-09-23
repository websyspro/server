<?php

namespace Websyspro\Server\Accounts\Entities;

use Websyspro\Entity\Decorators\Column;
use Websyspro\Entity\Decorators\Entity;
use Websyspro\Entity\Decorators\Index;
use Websyspro\Entity\Decorators\Length;
use Websyspro\Entity\Decorators\Precision;
use Websyspro\Entity\Decorators\PrimaryKey;
use Websyspro\Entity\Decorators\Required;
use Websyspro\Entity\Decorators\Unique;
use Websyspro\Entity\Types\ColumnAutoIncrement;
use Websyspro\Entity\Types\ColumnBigInt;
use Websyspro\Entity\Types\ColumnBlob;
use Websyspro\Entity\Types\ColumnDate;
use Websyspro\Entity\Types\ColumnDatetime;
use Websyspro\Entity\Types\ColumnDecimal;
use Websyspro\Entity\Types\ColumnDouble;
use Websyspro\Entity\Types\ColumnFlag;
use Websyspro\Entity\Types\ColumnInt;
use Websyspro\Entity\Types\ColumnSmallInt;
use Websyspro\Entity\Types\ColumnText;
use Websyspro\Entity\Types\ColumnTime;
use Websyspro\Entity\Types\ColumnTimeStamp;

#[Entity("test")]
class TestEntity
{
  #[PrimaryKey()]
  #[Required()]
  public ColumnAutoIncrement $fieldAutoIncrement;

  #[Column( "fieldSmallInt" )]
  public ColumnSmallInt $fieldSmallInt;

  #[Index(1)]
  #[Required()]
  public ColumnInt $fieldInt;

  #[Index(1)]
  public ColumnBigInt $fieldBigInt;

  #[Precision(18,12)]
  public ColumnDecimal $fieldDecimal;

  #[Length(128)]
  #[Required()]
  #[Index(2)]
  #[Unique(1)]
  public ColumnText $fieldText;
  
  public ColumnDouble $fieldDouble;
  public ColumnDatetime $fieldDatetime;
  public ColumnTimeStamp $fieldTimeStamp;
  public ColumnDate $fieldDate;
  public ColumnTime $fieldTime;
  public ColumnFlag $fieldFlag;
  public ColumnBlob $fieldBlob;
}
