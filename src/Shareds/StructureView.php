<?php

namespace Websyspro\Server\Shareds;

class StructureView
{
  public function __construct(
    public string $login,
    public string $home,
    public string $page404,    
  ){}
}