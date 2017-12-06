<?php

namespace Hcode;

class PageAdmin extends Page{

	//a pasta da views será modificada para ser diferente da views do site principal.
	public function __construct($opts = array(), $tpl_dir = "/views/admin/"){

		//parent para chamar uma função da classe pai
		parent::__construct($opts, $tpl_dir);

	}
}

?>