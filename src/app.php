<?php

use Websyspro\Server\WorkerServer;

$ws = new WorkerServer(); 
$ws->get( "/health", fn() => [ 
  "success" => true,
  "content" => "Server running"
]);

$ws->start();