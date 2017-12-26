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

	//gambiarra para limpar a sessão.
	$_SESSION[User::SESSION] = null;

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

$app->get('/admin/users', function(){

	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));

});

$app->get('/admin/users/create', function(){

	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");

});

$app->get('/admin/users/:iduser/delete', function($iduser){

	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;
	

});

$app->get('/admin/users/:iduser', function($iduser){

	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

$app->post('/admin/users/create', function(){

	User::verifyLogin();
	$user = new User();

	//verifica se o chebox está marcado.
	$_POST["inadmin"] = isset($_POST["inadmin"])?1:0;

	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;

});

$app->post('/admin/users/:iduser', function($iduser){

	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = isset($_POST["inadmin"])?1:0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();
	header("Location: /admin/users");
	exit;

});

$app->get("/admin/forgot", function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot");

});

$app->post("/admin/forgot", function(){

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-sent");

});

$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});



$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-success");

});


$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories", array(
		"categories"=>$categories
	));

});

$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});

$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");

	exit;
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	//primeiro verifica se existe para depois excluir.
	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");

	exit;

});

$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
	]);

});

$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");

	exit;

});



$app->run();

 ?>