<?php

require_once __DIR__ . "/../formulario/Formulario.php";

class NovoBancoCorretora extends Formulario
{
	private $nome;
	private $bancoCorretoraId;
	private $saldo;

	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setBancoCorretoraId($this -> bancoCorretora());
		$this -> setSaldo($this -> saldo());

		if (
			!$this -> getBancoCorretoraId()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		$id_bancoCorretora = 0;

		if ($bancos = $this -> ObterDadosBancosCorretoras(null, $this -> getSessao())) {
			$contador = 0;
			foreach ($bancos as $ignored) {
				if ($bancos[$contador]['bancoCorretora'] == $this -> getBancoCorretoraId())
					return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'x2bancosCorretoras');
			}
		}

		if (
			!$this -> EntradaDadosBancosCorretoras(
				$this -> getBancoCorretoraId()
			)
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		if ($this -> getSaldo() != 0 and $bancos = $this -> ObterDadosBancosCorretoras(null, $this -> getSessao())) {
			$contador = 0;
			foreach ($bancos as $ignored) {
				if ($bancos[$contador]['bancoCorretora'] == $this -> getBancoCorretoraId())
					$id_bancoCorretora = $bancos[$contador]['id'];
				$contador++;
			}
		}

		$idInterno = random_int(1, 99999);
		while ($this -> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if ($this -> getSaldo() > 0) {
			if (
				!$this -> EntradaDadosReceita(
					$idInterno,
					$id_bancoCorretora,
					'Correção do Saldo',
					'Correção do Saldo',
					date('Y-m-d'),
					$this -> getSaldo(),
					1
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
		}
		elseif ($this -> getSaldo() < 0) {
			if (
				!$this -> EntradaDadosGastos(
					$idInterno,
					$id_bancoCorretora,
					'Correção do Saldo',
					'Débito',
					'Correção do Saldo',
					date('Y-m-d'),
					$this -> getSaldo() * -1,
					1
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
		}

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}


	protected function getBancoCorretoraId()
	{
		return $this -> bancoCorretoraId;
	}

	protected function setBancoCorretoraId($bancoCorretoraId): void
	{
		$this -> bancoCorretoraId = $bancoCorretoraId;
	}

	protected function getSaldo()
	{
		return $this -> saldo;
	}

	protected function setSaldo($valor)
	{
		$this -> saldo = $valor;
	}

	protected function getNome()
	{
		return $this -> nome;
	}

	protected function setNome($nome): void
	{
		$this -> nome = $nome;
	}
}