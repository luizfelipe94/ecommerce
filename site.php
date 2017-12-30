<?php

use \Hcode\Page;

//rota principal do site principal
$app->get('/', function() {

	//chama o construct, com as variaveis e o header da pagina.
	$page = new Page();
	//chama o corpo da pagina.
	$page->setTpl("index");
	//o footer sera chamado quando terminar de carregar, que sera chamado o destruct.
});

?>