<?php

namespace Hcode;

use Rain\Tpl;

class Page{

	private $tpl;
	private $options = []; //variáveis principais da página.
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];

	//por padrão, a pasta views será passada por parametro já setada.
	public function __construct($opts = array(), $tpl_dir = "/views/"){

		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed.
		);

		Tpl::configure( $config );

		//atributo da propria classe para ter acesso dos outros métodos.
		$this->tpl = new Tpl;

		//as variaveis são passadas de acordo com a rota.

		$this->setData($this->options["data"]);

		//header da pagina chamado no construct.
		if($this->options["header"] === true) $this->tpl->draw("header");
	}

	//metodo otimizado para validar as variaveis.
	private function setData($data = array()){
		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}

	//metodo para chamar o corpo da pagina.
	public function setTpl($name, $data = array(), $returnHTML = false){

		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);
	}


	//rodape sera chamado no destruct.
	public function __destruct(){

		if($this->options["footer"] === true) $this->tpl->draw("footer");
	}

}

?>