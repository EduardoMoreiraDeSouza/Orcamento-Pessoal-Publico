<?php

require_once __DIR__ . "/./NovoCredito.php";

class EditarGastos extends NovoCredito
{

	protected $tipoAlteracao;
	protected $formaPagamento;
	protected $id;

	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setId($this -> id());
		$this -> setNome($this -> nome());
		$this -> setBancoCorretoraId($this -> bancoCorretoraId());
		$this -> setFormaPagamento($this -> formaPagamento());
		$this -> setClassificacao($this -> classificacao());
		$this -> setDataCompraPagamento($this -> dataCompraPagamento());
		$this -> setValor($this -> valor());
		$this -> setParcelas($this -> parcelas());
		$this -> setTipoAlteracao($this -> tipoAlteracao());

		if (
			!$this -> getId() or
			!$this -> getNome() or
			!$this -> getBancoCorretoraId() or
			!$this -> getFormaPagamento() or
			!$this -> getClassificacao() or
			!$this -> getDataCompraPagamento() or
			!$this -> getValor() or
			!$this -> getParcelas() or
			!$this -> getTipoAlteracao()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		if (!$this -> ObterDadosBancosCorretoras($this -> getBancoCorretoraId(), $this -> getSessao()))
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'naoBancoCorretora');

		if ($this -> getFormaPagamento() == "Crédito") {

			if (!$this -> ObterDadosCartoesCredito($this -> getBancoCorretoraId(), $this -> getSessao()))
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'cartaoNaoExite');

			$valorAntigo = $this -> ObterDadosGastos($this -> getSessao(), $this -> getId(), null)[0]['valor'];
			if (
				($this -> ValorFinal(
						'cartaoCredito', $this -> getBancoCorretoraId()
					) + $valorAntigo) < $this -> getValor() * $this -> getParcelas()
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'limiteInsuficiente');

			if (
				!$this -> AlterarDadosGastos(
					$this -> getId(),
					$this -> getBancoCorretoraId(),
					$this -> getNome(),
					$this -> getFormaPagamento(),
					$this -> getClassificacao(),
					$this -> getValor(),
					$this -> getDataCompraPagamento(),
					$this -> getParcelas()
				)
			)
				return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		} else {

			if ($this-> getTipoAlteracao() == 'todos') {

				$idInterno = $this-> ObterDadosGastos($this -> getSessao(), $this -> getId(), null)[0]['id_interno_gasto'];
				$gastos = $this-> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno');
				$quantidade = 0;

				foreach ($gastos as $ignored) {
					$id[] = $gastos[$quantidade]['id_gasto'];
					$quantidade++;
				}

				$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
				$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
				$dataPagamento = $this -> getDataCompraPagamento();

				for ($i = 0; $i <= $quantidade; $i++) {

					$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

					if (
						!$this -> AlterarDadosGastos(
							$id[$i],
							$this -> getBancoCorretoraId(),
							$this -> getNome(),
							$this -> getFormaPagamento(),
							$this -> getClassificacao(),
							$this -> getValor(),
							$dataPagamento,
							$this -> getParcelas()
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
			} elseif ($this-> getTipoAlteracao() == 'este') {
				if (
					!$this -> AlterarDadosGastos(
						$this -> getId(),
						$this -> getBancoCorretoraId(),
						$this -> getNome(),
						$this -> getFormaPagamento(),
						$this -> getClassificacao(),
						$this -> getValor(),
						$this -> getDataCompraPagamento(),
						$this -> getParcelas()
					)
				)
					return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);
			}

			elseif ($this-> getTipoAlteracao() == 'esteFuturos') {

				$idInterno = $this-> ObterDadosGastos($this -> getSessao(), $this -> getId(), null)[0]['id_interno_gasto'];
				$gastos = $this-> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno');
				$quantidade = 0;

				foreach ($gastos as $ignored) {
					$id[] = $gastos[$quantidade]['id_gasto'];
					$datas[] = $gastos[$quantidade]['dataCompraPagamento'];
					$quantidade++;
				}

				$mes = $this -> InformacoesData('m', $this -> getDataCompraPagamento());
				$ano = $this -> InformacoesData('y', $this -> getDataCompraPagamento());
				$dataPagamento = $this -> getDataCompraPagamento();

				for ($i = 0; $i < $quantidade; $i++) {

					$dia = $this -> InformacoesData('d', $this -> getDataCompraPagamento());

					if ($datas[$i] >= $this -> getDataCompraPagamento()) {
						if (
							!$this -> AlterarDadosGastos(
								$id[$i],
								$this -> getBancoCorretoraId(),
								$this -> getNome(),
								$this -> getFormaPagamento(),
								$this -> getClassificacao(),
								$this -> getValor(),
								$dataPagamento,
								$this -> getParcelas()
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
			}

		}

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}

	protected function getFormaPagamento()
	{
		return $this -> formaPagamento;
	}

	protected function setFormaPagamento($formaPagamento): void
	{
		$this -> formaPagamento = $formaPagamento;
	}

	protected function getId()
	{
		return $this -> id;
	}

	protected function setId($id): void
	{
		$this -> id = $id;
	}

	protected function getTipoAlteracao()
	{
		return $this -> tipoAlteracao;
	}

	protected function setTipoAlteracao($tipoAlteracao): void
	{
		$this -> tipoAlteracao = $tipoAlteracao;
	}
}