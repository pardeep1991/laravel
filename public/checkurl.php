<?php

$domain1 = $_GET['link'];

function get_http_response_code($domain1) {
  $headers = get_headers($domain1);
  return substr($headers[0], 9, 3);
}

$get_http_response_code = get_http_response_code($domain1);

echo $get_http_response_code;

?>