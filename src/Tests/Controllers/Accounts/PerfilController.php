<?php

namespace Websyspro\Server\Tests\Controllers\Accounts;

use Websyspro\Server\Decorators\Authenticate;
use Websyspro\Server\Decorators\Controller;

#[Authenticate()]
#[Controller( "perfil" )]
class PerfilController  {}