<?php

namespace Websyspro\Server\Crm;

use Websyspro\Server\Crm\Entities\DistribuidorEntity;
use Websyspro\Server\Crm\Entities\PropostaEntity;
use Websyspro\WorkerServer\Decorators\Module;


#[Module(
  name: 'crm',
  controllers: [],
  entities: [
    PropostaEntity::class,
    DistribuidorEntity::class
  ]
)]
class CrmModule {}
