<?php

require_once __DIR__ . "/./EditarGastos.php";

class EditarReceita extends EditarGastos
{
	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setId($this -> id());
		$this -> setNome($this -> nome());
		$this -> setBancoCorretoraId($this -> bancoCorretoraId());
		$this -> setClassificacao($this -> classificacao());
		$this -> setDataCompraPagamento($this -> dataCompraPagamento());
		$this -> setValor($this -> valor());
		$this -> setParcelas($this -> parcelas());
		$this -> setTipoAlteracao($this -> tipoAlteracao());

		if (
			!$this -> getId() or
			!$this -> getNome() or
			!$this -> getBancoCorretoraId() or
			!$this -> getClassificacao() or
			!$this -> getDataCompraPagamento() or
			!$this -> getValor() or
			!$this -> getParcelas() or
			!$this -> getTipoAlteracao()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		if ($this -> getValor() <= 0)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'valorAbaixoZero');

		if (!$this -> ObterDadosBancosCorretoras($this -> getBancoCorretoraId(), $this -> getSessao()))
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'naoBancoCorretora');

		$idInterno = random_int(1, 99999);
		while ($this -> ObterDadosReceita($this -> getSessao(), $idInterno, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if ($this -> getTipoAlteracao() == 'todos') {

			$idInterno = $this -> ObterDadosReceita($this -> getSessao(), $this -> getId())[0]['id_interno_receita'];
			$receitas = $this -> ObterDadosReceita($this -> getSessao(), $idInterno, 'interno');
			$quantidade = 0;

			foreach ($receitas as $ignored) {
				$id[] = $receitas[$quantidade]['id_receita'];
				$quantidade++;
			}

			$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
			$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
			$dataPagamento = $this -> getDataCompraPagamento();

			for ($i = 0; $i <= $quantidade; $i++) {

				$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

				if (
					!$this -> AlterarDadosReceita(
						$id[$i],
						$this -> getBancoCorretoraId(),
						$this -> getNome(),
						$this -> getClassificacao(),
						$this -> getValor(),
						$this -> getParcelas(),
						$dataPagamento
					)
				)
					return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

				$mes = intval($mes) + 1;
				$mes = $mes < 10 ? "0" . $mes : $mes;

				if ($dia > 28) {
					$ultimoDia = $this -> ultimoDiaMes($mes, $ano);
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
		elseif ($this -> getTipoAlteracao() == 'este') {
			if (
				!$this -> AlterarDadosReceita(
					$this -> getId(),
					$this -> getBancoCorretoraId(),
					$this -> getNome(),
					$this -> getClassificacao(),
					$this -> getValor(),
					$this -> getParcelas(),
					$this -> getDataCompraPagamento()
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
		}

		elseif ($this -> getTipoAlteracao() == 'esteFuturos') {

			$idInterno = $this -> ObterDadosReceita($this -> getSessao(), $this -> getId())[0]['id_interno_receita'];
			$receitas = $this -> ObterDadosReceita($this -> getSessao(), $idInterno, 'interno');
			$quantidade = 0;

			foreach ($receitas as $ignored) {
				$id[] = $receitas[$quantidade]['id_receita'];
				$datas[] = $receitas[$quantidade]['dataCompraPagamento'];
				$quantidade++;
			}

			$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
			$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
			$dataPagamento = $this -> getDataCompraPagamento();

			for ($i = 0; $i < $quantidade; $i++) {

				$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

				if ($datas[$i] >= $this -> getDataCompraPagamento()) {
					if (
						!$this -> AlterarDadosReceita(
							$id[$i],
							$this -> getBancoCorretoraId(),
							$this -> getNome(),
							$this -> getClassificacao(),
							$this -> getValor(),
							$this -> getParcelas(),
							$dataPagamento
						)
					)
						return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

					$mes = intval($mes) + 1;
					$mes = $mes < 10 ? "0" . $mes : $mes;

					if ($dia > 28) {
						$ultimoDia = $this -> ultimoDiaMes($mes, $ano);
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
		}

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}
}