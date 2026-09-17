<?php

namespace Websyspro\Server\Decorators\Server;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Body
{
    public function __construct(
        public readonly string $key = ''
    ) {}
}
