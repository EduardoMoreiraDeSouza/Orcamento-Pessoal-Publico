<?php

require_once __DIR__ . "/../dados/ObterDadosBancosCorretoras.php";

final class ExcluirBancoCorretora extends ObterDadosBancosCorretoras
{
	public function ExcluirBancoCorretora($id_bancoCorretora)
	{
		if (!$this -> VerificarLogin()) return false;

		if ($this-> ObterDadosBancosCorretoras($id_bancoCorretora, $this-> getSessao())[0]['bancoCorretora'] == 'Reserva')
			return (bool) $this -> RetornarErro(null, 'excluirReserva');

		$this -> setCodigoMySql("DELETE FROM dbName.bancosCorretoras WHERE id LIKE '$id_bancoCorretora' AND email LIKE '" . $this -> getSessao() . "';");

		return (bool) $this -> ExecutarCodigoMySql();
	}

}