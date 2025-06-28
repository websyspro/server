<?php

use Websyspro\Server\Application;
use Websyspro\Server\Applications\ShopsModule;
use Websyspro\Server\Applications\AccountsModule;

Application::Modules([
  ShopsModule::class,
  AccountsModule::class
]);