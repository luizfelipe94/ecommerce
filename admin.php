<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

?>