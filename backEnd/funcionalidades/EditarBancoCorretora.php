<?php

require_once __DIR__ . "/../funcionalidades/Entrar.php";

class EditarBancoCorretora extends Entrar
{
	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setId($this -> id());
		$this -> setBancoCorretoraId($this -> bancoCorretora());
		$this -> setSaldo($this -> saldo());

		if (
			!$this -> getBancoCorretoraId() or
			!$this -> getId()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		elseif ($this-> ObterDadosBancosCorretoras($this-> getId(), $this-> getSessao())[0]['bancoCorretora'] == 'Reserva' and $this-> getBancoCorretoraId() != 'Reserva')
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'excluirReserva');

		if (
			!$this -> AlterarDadosBancosCorretoras(
				$this -> getId(),
				$this -> getBancoCorretoraId(),
				'0'
			)
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		$idInterno = random_int(1, 99999);
		while ($this -> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if ($this -> getSaldo() > floatval($this -> ValorFinal('bancoCorretora', $this -> getId()))) {
			if (
				!$this -> EntradaDadosReceita(
					$idInterno,
					$this -> getId(),
					'Correção do Saldo',
					'Correção do Saldo',
					date('Y-m-d'),
					$this -> getSaldo() - floatval($this -> ValorFinal('bancoCorretora', $this -> getId())),
					1
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
		}

		elseif ($this -> getSaldo() < floatval($this -> ValorFinal('bancoCorretora', $this -> getId()))) {
			if (
				!$this -> EntradaDadosGastos(
					$idInterno,
					$this -> getId(),
					'Correção do Saldo',
					'Débito',
					'Correção do Saldo',
					date('Y-m-d'),
					floatval($this -> ValorFinal('bancoCorretora', $this -> getId())) - $this -> getSaldo(),
					1
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
		}

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}
}
