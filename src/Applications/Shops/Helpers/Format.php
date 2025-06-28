<?php

namespace Websyspro\Server\Applications\Shops\Helpers;

class Format
{
  public static function Cpf(
    string $cpf
  ): string {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
  }
}