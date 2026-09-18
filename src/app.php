<?php

use Websyspro\Server\WorkerServer;

$ws = new WorkerServer(); 
$ws->get( "/health", fn() => "Server running" );
$ws->start();