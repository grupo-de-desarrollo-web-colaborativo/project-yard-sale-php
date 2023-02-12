<?php
http_response_code(404);
header("HTTP/1.0 404 Not Found");
$tpl = __DIR__ . DS . "tpl.404.html";
$error = html::evalTemplate($tpl);
echo $error;
