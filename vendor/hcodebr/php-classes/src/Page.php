<?php

namespace Hcode;

use Rain\Tpl;

class Page{

	private $tpl;
	private $options = [];
	private $defaults = [
		"data"=>[]
	];


	public function __construct($opts = array()){

		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed.
		);

		Tpl::configure( $config );

		//atributo da propria classe para ter acesso dos outros métodos.
		$this->tpl = new Tpl;

		//as variaveis são passadas de acordo com a rota.

		$this->setData($this->options["data"]);

		//header da pagina chamado no construct.
		$this->tpl->draw("header");
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

		$this->tpl->draw("footer");
	}

}

?>