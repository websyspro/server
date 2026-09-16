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
        "Websyspro Application",
        new HeaderComponent()
      ),
    )
  )
);