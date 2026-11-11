<?php

use Websyspro\WorkerServer\WorkerServer;

$workerService = new WorkerServer(); 
$workerService->registerModules(
  // register modules
);

$workerService->start();