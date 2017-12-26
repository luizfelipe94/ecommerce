<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Category extends Model{

	public static function listAll(){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY idcategory");

	}

	public function save(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()
		));

		$this->setData($results[0]);

	}

	public function get($idCategory){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idCategory", [
			":idCategory"=>$idCategory
		]);

		$this->setData($results[0]);

	}

	//delete não espera parametros pois já espera que o objeto esteja carregado.
	public function delete(){

		$sql = new Sql();

		$sql->select("DELETE FROM tb_categories WHERE idcategory = :idcategory",[
			"idcategory"=>$this->getidcategory()
		]);
	}


}

?>