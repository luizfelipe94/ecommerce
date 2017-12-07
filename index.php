<?php 

//vendor/autoload é do composer para trazer as dependencias.
session_start();
require_once("vendor/autoload.php");

//namespaces precisas.
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

//nova aplicação (rota).
$app = new Slim();

$app->config('debug', true);

//rota principal do site principal
$app->get('/', function() {

	//chama o construct, com as variaveis e o header da pagina.
	$page = new Page();
	//chama o corpo da pagina.
	$page->setTpl("index");
	//o footer sera chamado quando terminar de carregar, que sera chamado o destruct.
});

//rota principal da administração
$app->get('/admin', function() {

	User::verifyLogin();

	//lembrando que a função setTpl está na classe pai, que é Page.
	//a função setTpl é modificada para usar o método draw do rainTPL.
	//além disso, as variáveis são setadas(assign) na função setData.
	$page = new Hcode\PageAdmin();
	$page->setTpl("index");

});

$app->get('/admin/login', function(){

	//passando duas opções header e footer false para desabilitar o construtor e o destrutor, que chamam o header e o footer da página, pois na página de login não há header e footer.
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");

	echo $_SESSION[User::SESSION]["deslogin"];
	echo $_SESSION[User::SESSION]["iduser"];

});

//rota para receber os dados do formulário de login.
$app->post('/admin/login', function(){

	//autenticar o usuario por método estático.
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function(){
	User::logout();
	header("Location: /admin/login");
	exit;
});

$app->run();

 ?>