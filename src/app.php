<?php

App(
  DocType(),
  Html(
    Head(
      StyleLink( "assets/css.css" )
    ),
    Body(
      Div(
        Div( "Websyspro Application" ),
        Div( "version: 1.0.0" )
      ),
    )
  )
);