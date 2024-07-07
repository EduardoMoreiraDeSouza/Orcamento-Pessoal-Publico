<?php

require_once __DIR__ . "/./AlterarDadosCartoesCredito.php";

class ObterDadosGastos extends AlterarDadosCartoesCredito
{
    private $dados;

    public function ObterDadosGastos($email, $id_gastos = null, $id_bancoCorretora = null, $tipoId = null)
    {
	    $this -> gerarCodigoMySql($email, $id_gastos, $id_bancoCorretora, $tipoId);

        $this -> setDados($this -> CarregarResultadosMySql(true));

        return !empty($this -> getDados()) ? $this -> getDados() : false;
    }

	private function gerarCodigoMySql($email, $id_gasto, $id_bancoCorretora, $tipoId): void
	{
		$codigo = "SELECT * FROM dbName.gastos WHERE ";

		if ($tipoId != null)
			$tipoId = 'id_interno_gasto';
		else
			$tipoId = 'id_gasto';

		if ($id_gasto != null and $id_bancoCorretora != null and $email != null)
			$codigoVariante = "$tipoId LIKE '$id_gasto' AND id_bancoCorretora LIKE '$id_bancoCorretora' AND email LIKE '$email';";
		elseif ($id_gasto != null and $id_bancoCorretora == null and $email == null)
			$codigoVariante = "$tipoId LIKE '$id_gasto';";
		elseif ($id_gasto == null and $id_bancoCorretora != null and $email == null)
			$codigoVariante = "id_bancoCorretora LIKE '$id_bancoCorretora';";
		elseif ($id_gasto == null and $id_bancoCorretora == null and $email != null)
			$codigoVariante = "email LIKE '$email';";
		elseif ($id_gasto != null and $id_bancoCorretora != null and $email == null)
			$codigoVariante = "$tipoId LIKE '$id_gasto' AND id_bancoCorretora LIKE '$id_bancoCorretora';";
		elseif ($id_gasto != null and $id_bancoCorretora == null and $email != null)
			$codigoVariante = "$tipoId LIKE '$id_gasto' AND email LIKE '$email';";
		elseif ($id_gasto == null and $id_bancoCorretora != null and $email != null)
			$codigoVariante = "id_bancoCorretora LIKE '$id_bancoCorretora' AND email LIKE '$email';";

		$this -> setCodigoMySql($codigo . $codigoVariante);
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