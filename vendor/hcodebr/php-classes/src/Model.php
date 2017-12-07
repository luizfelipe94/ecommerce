<?php

//esta classe terá a inteligencia de fazer getters e setters de uma classe de forma dinâmica.

namespace Hcode;

class Model{

	private $values = [];

	public function __call($name, $args){

		//pegar as 3 primeiras letras da função (get ou set).
		$method = substr($name, 0, 3);
		//pega o resto do nome da função. O strlen conta a quantidade de caracteres.
		$fieldName = substr($name, 3, strlen($name)); 

		switch ($method) {
			
			case "get":
				return $this->values[$fieldName];
			break;

			case "set":
				$this->values[$fieldName] = $args[0];
			break;
		}

	}

	public function setData($data = array()){

		foreach ($data as $key => $value) {
			
			//set e key = nome do método.
			$this->{"set".$key}($value);

		}

	}

	public function getValues(){
		return $this->values;
	}

}

?>