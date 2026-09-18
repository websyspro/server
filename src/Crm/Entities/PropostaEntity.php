<?php

namespace Websyspro\Server\Crm\Entities;

use Websyspro\Entity\BaseUUIDEntity;
use Websyspro\Entity\Decorators\Column;
use Websyspro\Entity\Decorators\Entity;
use Websyspro\Entity\Decorators\ForeignKey;
use Websyspro\Entity\Decorators\Index;
use Websyspro\Entity\Decorators\InitialDefault;
use Websyspro\Entity\Decorators\Length;
use Websyspro\Entity\Decorators\Precision;
use Websyspro\Entity\Decorators\Synchronize;
use Websyspro\Entity\Decorators\Unique;
use Websyspro\Entity\Types\ColumnAutoUUID;
use Websyspro\Entity\Types\ColumnDecimal;
use Websyspro\Entity\Types\ColumnFlag;
use Websyspro\Entity\Types\ColumnInt;
use Websyspro\Entity\Types\ColumnText;

#[Entity( "Proposta" )]
#[Synchronize( false )]
class PropostaEntity 
extends BaseUUIDEntity
{
  #[Column( "Status" )]
  public ColumnFlag $Status;
  public ColumnAutoUUID $ContatoId;
  
  #[Index(1)]
  public ColumnAutoUUID $InstituicaoId;
  
  #[Index(2)]
  #[ForeignKey( DistribuidorEntity::class )]  
  public ColumnAutoUUID $DistribuidorId;

  #[Index(2)]
  #[Unique()]
  public ColumnAutoUUID $ConsultorVendasEspeciaisId;

  #[Length(3000)]
  public ColumnText $Observacao;

  #[Length(100)]
  public ColumnText $PrazoFaturamento;

  #[Length(100)]
  public ColumnText $NomeContato;

  #[Length(100)]
  public ColumnText $NomeProposta;

  #[Precision(18,2)]
  public ColumnDecimal $DescontoFinalCliente;

  public ColumnFlag $Arquivada;

  #[Length(3000)]
  public ColumnText $ComentarioArquivamento;

  #[InitialDefault(0)]
  public ColumnInt $Versao;

  #[Length(100)]
  public ColumnText $IdPipelineZoho;
  
  public ColumnFlag $Frete;
}
