<?php
header('Access-Control-Allow-Origin: *');

require($_SERVER['DOCUMENT_ROOT']."/hobbies/vendor/autoload.php");
$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/hobbies/app/Http/Controllers']);
header('Content-Type: application/json');
echo $openapi->toJson();