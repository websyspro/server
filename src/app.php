<?php

use Websyspro\WorkerServer\WorkerServer;
use Websyspro\Server\Accounts\AccountsModule;
use Websyspro\Server\Crm\CrmModule;

$workerService = new WorkerServer(); 
$workerService->registerModules([
  AccountsModule::class,
  CrmModule::class,
]);

$workerService->start();