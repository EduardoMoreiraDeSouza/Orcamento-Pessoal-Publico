<?php

require_once __DIR__ . "/./NovoDebito.php";

class NovaReceita extends NovoDebito
{
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
		while ($this -> ObterDadosReceita($this -> getSessao(), $idInterno, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if ($this -> getTipoParcelas() == 'replicar') {

			$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
			$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
			$dataPagamento = $this -> getDataCompraPagamento();

			for ($i = 0; $i < $this -> getParcelas(); $i++) {

				$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

				if (!$this -> EntradaDadosReceita(
					$idInterno,
					$this-> getId(),
					$this-> getNome(),
					$this -> getClassificacao(),
					$dataPagamento,
					$this -> getValor(),
					1
				))
					return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], null);

				if ($dia > 28) {
					$ultimoDia = $this -> ultimoDiaMes($mes, $ano);
					while ($ultimoDia < $dia) {
						$dia--;
					}
				}

				$mes = intval($mes) + 1;

				if ($mes > 12) {
					$mes = 1;
					$ano = intval($ano) + 1;
				}

				$mes = $mes < 10 ? "0" . $mes : $mes;

				$dataPagamento = $ano . "-" . $mes . "-" . $dia;
			}
		}

		elseif (!$this -> EntradaDadosReceita(
			$idInterno,
			$this-> getId(),
			$this-> getNome(),
			$this -> getClassificacao(),
			$this -> getDataCompraPagamento(),
			$this -> getValor(),
			$this-> getParcelas()
		))
			return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], null);

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}
}