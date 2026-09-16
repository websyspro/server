<?php

namespace Websyspro\Application\Components;

use Websyspro\Elements\Abstracts\Component;

class HeaderComponent
extends Component 
{
  public function __construct(
  ){
    parent::__construct([ "version: 1.0.0" ]);
  }
}