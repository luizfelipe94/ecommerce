<?php 

//vendor/autoload é do composer para trazer as dependencias.
require_once("vendor/autoload.php");

//namespaces precisas.
use \Slim\Slim;
use \Hcode\Page;

//nova aplicação (rota).
$app = new Slim();

$app->config('debug', true);

//rota principal
$app->get('/', function() {

	//chama o construct, com as variaveis e o header da pagina.
	$page = new Page();
	//chama o corpo da pagina.
	$page->setTpl("index");
	//o footer sera chamado quando terminar de carregar, que sera chamado o destruct.

});

$app->run();

 ?>