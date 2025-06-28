<?php

namespace Websyspro\Server\Applications\Shops\Imports;

class ConfigImport {
	public static function rows(
	): array {
		return [
			[ "Id" => 1, "PasswordReleaseDiscount" => "123456", "PurchaseLimitPerCustomer" => "2000,0000" ]
		];
	}
}