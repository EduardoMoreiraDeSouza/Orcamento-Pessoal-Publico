<?php

require_once __DIR__ . "/./AlterarDadosGastos.php";

class ObterDadosReceita extends AlterarDadosGastos
{
    private $dados;

    public function ObterDadosReceita($email, $id = null, $tipoId=null)
    {
        if ($email == null)
            return false;

		if ($tipoId != null)
			$tipoId = 'id_interno_receita';
		else
			$tipoId = 'id_receita';

		if ($id != null)
			$this -> setCodigoMySql("SELECT * FROM dbName.receitas WHERE email LIKE '$email' and $tipoId LIKE '$id';");
		else
            $this -> setCodigoMySql("SELECT * FROM dbName.receitas WHERE email LIKE '$email';");

        $this -> setDados($this -> CarregarResultadosMySql(true));

        return !empty($this -> getDados()) ? $this -> getDados() : false;
    }

    private function getDados()
    {
        return $this -> dados;
    }

    private function setDados($dados): void
    {
        $this -> dados = $dados;
    }
}