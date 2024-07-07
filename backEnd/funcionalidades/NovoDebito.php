<?php

require_once __DIR__ . "/../funcionalidades/EditarBancoCorretora.php";

class NovoDebito extends EditarBancoCorretora
{
	private $classificacao;
	private $dataCompraPagamento;
	private $valor;
	private $parcelas;
	private $tipoParcelas;

	/**
	 * @throws \Random\RandomException
	 */
	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setId($this -> id());
		$this -> setNome($this -> nome());
		$this -> setClassificacao($this -> classificacao());
		$this -> setDataCompraPagamento($this -> dataCompraPagamento());
		$this -> setValor($this -> valor());
		$this -> setParcelas($this -> parcelas());
		$this -> setTipoParcelas($this -> tipoParcelas());

		if (
			!$this -> getId() or
			!$this -> getNome() or
			!$this -> getClassificacao() or
			!$this -> getDataCompraPagamento() or
			!$this -> getValor() or
			!$this -> getParcelas() or
			!$this -> getTipoParcelas()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		if (!$this -> ObterDadosBancosCorretoras($this -> getId(), $this -> getSessao()))
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'naoBancoCorretora');

		$idInterno = random_int(1, 99999);
		while ($this -> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if ($this -> getTipoParcelas() == 'replicar') {

			$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
			$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
			$dataPagamento = $this -> getDataCompraPagamento();

			for ($i = 0; $i < $this -> getParcelas(); $i++) {

				$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

				if (
					!$this -> EntradaDadosGastos(
						$idInterno,
						$this -> getId(),
						$this -> getNome(),
						'Débito',
						$this -> getClassificacao(),
						$dataPagamento,
						$this -> getValor(),
						1
					)
				)
					return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

				$mes = intval($mes) + 1;
				$mes = $mes < 10 ? "0" . $mes : $mes;

				if ($dia > 28) {
					$ultimoDia = $this->ultimoDiaMes($mes, $ano);
					while ($ultimoDia < $dia) {
						$dia--;
					}
				}

				if ($mes > 12) {
					$mes = 1;
					$ano = intval($ano) + 1;
				}

				$dataPagamento = $ano . "-" . $mes . "-" . $dia;
			}
		}

		elseif (
			!$this -> EntradaDadosGastos(
				$idInterno,
				$this -> getId(),
				$this -> getNome(),
				'Débito',
				$this -> getClassificacao(),
				$this -> getDataCompraPagamento(),
				$this -> getValor(),
				$this -> getParcelas()
			)
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}

	protected function getClassificacao()
	{
		return $this -> classificacao;
	}

	protected function setClassificacao($classificacao)
	{
		$this -> classificacao = $classificacao;
	}

	protected function getDataCompraPagamento()
	{
		return $this -> dataCompraPagamento;
	}

	protected function setDataCompraPagamento($dataCompraPagamento)
	{
		$this -> dataCompraPagamento = $dataCompraPagamento;
	}

	protected function getValor()
	{
		return $this -> valor;
	}

	protected function setValor($valor)
	{
		$this -> valor = $valor;
	}

	protected function getParcelas()
	{
		return $this -> parcelas;
	}

	protected function setParcelas($parcelas)
	{
		$this -> parcelas = $parcelas;
	}

	protected function getTipoParcelas()
	{
		return $this -> tipoParcelas;
	}

	protected function setTipoParcelas($tipoParcelas): void
	{
		$this -> tipoParcelas = $tipoParcelas;
	}

}