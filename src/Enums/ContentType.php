<?php

namespace Websyspro\Server\Enums;

enum ContentType:string {
  case multipartFormData = "multipart/form-data";
  case multipartFormDataUrlencoded = "application/x-www-form-urlencoded";
  case applicationJSON = "application/json";
}