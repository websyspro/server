<?php

namespace Websyspro\Server\Decorators;

use Attribute;
use Websyspro\Server\Enums\AttributeType;

#[Attribute(Attribute::TARGET_METHOD)]
class FileValidate
{
	public function AttributeType(
	): AttributeType {
		return AttributeType::Middleware;
	}

	public function execute(
	): void {}	
}