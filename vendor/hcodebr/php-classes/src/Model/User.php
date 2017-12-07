<?php

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model{

	const SESSION = "User";

	public static function login($login, $password){

		$sql = new Sql();

		//select da classe Sql que usa praticas anti sql injection. O segundo parametro faz o bind das variaveis.
		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		//verifica se foi encontrado algum usuário.
		if(count($results) === 0){

			//contra barra na exception para buscar a exceção no namespace principal do php. Pois não criamos a nossa exception.
			throw new \Exception("Usuário inexistente ou senha inválida.");
			
		}

		//pega o primeiro registro encontrado.
		//na posição 0 pois o select retorna um array.
		$data = $results[0]; 

		if(password_verify($password, $data["despassword"]) === true){

			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			//retorna esta instância para caso o usuário logado precise dele.
			return $user;

		}else{

			throw new \Exception("Usuário inexistente ou senha inválida.");

		}

	}

	public static function verifyLogin($inadmin = true)
	{	
		if (
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["iduser"] !== $inadmin
		) {
			
			header("Location: /admin/login");
			exit;

		}

	}

}

?>