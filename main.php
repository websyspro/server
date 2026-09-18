<?php

use Websyspro\Server\Request;
use Websyspro\Server\WorkerServer;

$ws = new WorkerServer();
$ws->post( "/test/:id", fn( Request $request ) => $request->body );
$ws->start();