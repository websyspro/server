<?php

namespace Websyspro\Server\Decorators;

use Attribute;
use Exception;
use Websyspro\Server\Enums\AttributeType;

#[Attribute( Attribute::TARGET_CLASS )]
class Authenticate
{
	public function AttributeType(
	): AttributeType {
		return AttributeType::Middleware;
	}

	public function execute(
	): void {
		throw new Exception("This is a custom error message.", 401);
	}
}
