<?php 

//vendor/autoload é do composer para trazer as dependencias.
session_start();
require_once("vendor/autoload.php");

//namespaces precisas.
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

//nova aplicação (rota).
$app = new Slim();

$app->config('debug', true);

require_once("site.php");
require_once("admin.php");
require_once("admin-users.php");
require_once("admin-forgot.php");
require_once("admin-categories.php");
require_once("admin-products.php");





$app->run();

 ?>