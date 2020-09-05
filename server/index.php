<?php
header('Access-Control-Allow-Origin : http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
header("Content-type:text/html;charset=utf-8");

/* autoload */
require_once(__DIR__."/vendor/autoload.php");

/* modules */
require_once(__DIR__."/modules/common.php");

/* router */
require_once(__DIR__."/routes/user.php");



$request = $_SERVER['REQUEST_URI'];
$router = explode("/", $request)[1];

$payload = req_to_array();

switch ($router)
{
    case '' :
        require __DIR__ . '/views/index.php';
        break;
    case 'user' :
        userRouter($request, $payload);
        break;
    case '' :
        require __DIR__ . '/views/index.php';
        break;
    case '/asd/create' :
        require __DIR__ . '/views/about.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}