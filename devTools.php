<?php

use Websyspro\DevTools\Interfaces\DevTools;

require "./vendor/autoload.php";
return new DevTools(
  includes: [ "src" ],
  excludes: [],
  webSocketHost: "0.0.0.0",
  webSocketPort: 3002,
  httpServerPort: 3001,
  documentRoot: "vendor/websyspro/wpengine/src/Core",
  scriptName: "src/index.php",
  errorReporting: [ E_ERROR ],
);