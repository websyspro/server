<?php

use Websyspro\DevTools\Interfaces\DevTools;

require "./vendor/autoload.php";
return new DevTools(
  includes: [ "src" ],
  excludes: [ "vendor" ],
  webSocketHost: "0.0.0.0",
  webSocketPort: 3002,
  httpServerPort: 3001,
  documentRoot: "",
  scriptName: "index.php",
  errorReporting: [ E_ERROR ],
);