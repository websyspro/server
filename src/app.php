<?php

use Websyspro\Server\Accounts\AccountsModule;
use Websyspro\WorkerServer\WorkerServer;
use Websyspro\Server\Shops\ShopsModule;
use Websyspro\Server\Crm\CrmModule;

$workerService = new WorkerServer(); 
$workerService->registerModules(
  AccountsModule::class,
  ShopsModule::class,
  CrmModule::class
);

$workerService->start();