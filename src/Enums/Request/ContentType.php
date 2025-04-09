<?php

namespace Websyspro\Server\Enums\Request;

enum ContentType:string {
  case MultipartFormData = "multipart/form-data";
  case MultipartFormDataUrlencoded = "application/x-www-form-urlencoded";
  case ApplicationJSON = "application/json";
}