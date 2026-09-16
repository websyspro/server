<?php

use Websyspro\Application\Components\HeaderComponent;

App(
  DocType(),
  Html(
    Head(
      StyleLink( "assets/css.css" )
    ),
    Body(
      Div(
        "Websyspro Application<br/>version: 1.0.0"
      ),
      new HeaderComponent(),
      new HeaderComponent()
    )
  )
);