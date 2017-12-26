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

		//atualiza o arquivo após um save ou delete
		Category::updateFile();

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

		Category::updateFile();
	}

	//atualiza o arquivo com as categorias listadas no site principal
	public static function updateFile(){

		$categories = Category::listAll();

		$html = [];

		foreach ($categories as $row) {
			
			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');

		}

		//implode converte array pra string
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));


	}


}

?>