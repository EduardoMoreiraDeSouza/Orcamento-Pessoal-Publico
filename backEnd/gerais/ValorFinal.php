<?php

require_once __DIR__ . "/./FormatacaoDados.php";

class ValorFinal extends FormatacaoDados
{
	public function ValorFinal($tipo, $id, $dataReferencia = null)
	{
		if ($tipo == 'cartaoCredito') {

			$dadosCartao = $this -> ObterDadosCartoesCredito($id, $this -> getSessao());
			if (!$dadosCartao)
				return false;

			$limiteTotal = $dadosCartao['limite'];
			$gastosCreditoTotal = 0;
			$gastos = $this -> ObterDadosGastos($this -> getSessao());
			$gastoAtual = 0;

			if ($gastos)
				foreach ($gastos as $ignored) {
					if ($gastos[$gastoAtual]['id_bancoCorretora'] == $id)
						if ($gastos[$gastoAtual]['formaPagamento'] == 'Crédito') {

							$parcelasRestantes = $this -> parcelasPagasCredito($gastos[$gastoAtual], $dataReferencia);

							if ($parcelasRestantes > 0 and $parcelasRestantes <= $gastos[$gastoAtual]['parcelas'])
								if ($parcelasRestantes == $gastos[$gastoAtual]['parcelas'])
									$parcelasGasto = 1;
								else
									$parcelasGasto = $gastos[$gastoAtual]['parcelas'] - $parcelasRestantes;
							else if ($parcelasRestantes > $gastos[$gastoAtual]['parcelas'])
								$parcelasGasto = 0;
							else
								$parcelasGasto = $gastos[$gastoAtual]['parcelas'];


							$valorTotalGasto = $gastos[$gastoAtual]['valor'] * ($parcelasGasto);
							$gastosCreditoTotal += $valorTotalGasto;

						}
					$gastoAtual++;
				}

			return $limiteTotal - $gastosCreditoTotal;

		}
		elseif ($tipo == 'bancoCorretora') {

			$gastosDebitoTotal = 0;
			$gastosCreditoTotal = 0;
			$gastos = $this -> ObterDadosGastos($this -> getSessao());
			$gastoAtual = 0;

			if ($gastos) {
				foreach ($gastos as $ignored) {
					if ($gastos[$gastoAtual]['id_bancoCorretora'] == $id) {

						if ($gastos[$gastoAtual]['formaPagamento'] == 'Débito') {
							$parcelasDebitadas = $this -> parcelasDebitadas($gastos[$gastoAtual], $dataReferencia);
							if ($parcelasDebitadas > $gastos[$gastoAtual]['parcelas'])
								$parcelasDebitadas = $gastos[$gastoAtual]['parcelas'];

							$gastosDebitoTotal += $gastos[$gastoAtual]['valor'] * $parcelasDebitadas;
						}

						elseif ($gastos[$gastoAtual]['formaPagamento'] == 'Crédito') {

							$parcelasRestantes = $this -> parcelasPagasCredito($gastos[$gastoAtual], $dataReferencia);

							if ($parcelasRestantes > 0 and $parcelasRestantes <= $gastos[$gastoAtual]['parcelas'])
								if ($parcelasRestantes == $gastos[$gastoAtual]['parcelas'])
									$parcelasGasto = 1;
								else
									$parcelasGasto = $parcelasRestantes;
							else if ($parcelasRestantes > $gastos[$gastoAtual]['parcelas'])
								$parcelasGasto = $gastos[$gastoAtual]['parcelas'];
							else
								$parcelasGasto = 0;


							$valorTotalGasto = $gastos[$gastoAtual]['valor'] * ($parcelasGasto);
							$gastosCreditoTotal += $valorTotalGasto;

						}
					}
					$gastoAtual++;
				}
			}

			$receitaTotal = 0;
			$receita = $this -> ObterDadosReceita($this -> getSessao());
			$receitaAtual = 0;

			if ($receita) {
				foreach ($receita as $ignored) {
					if ($receita[$receitaAtual]['id_bancoCorretora'] == $id) {
						$parcelasRecebidas = $this -> parcelasRecebidas($receita[$receitaAtual], $dataReferencia);
						if ($parcelasRecebidas > $receita[$receitaAtual]['parcelas'])
							$parcelasRecebidas = $receita[$receitaAtual]['parcelas'];

						$receitaTotal += $receita[$receitaAtual]['valor'] * $parcelasRecebidas;
					}
					$receitaAtual++;
				}
			}

			$saldoFinal = $receitaTotal - $gastosDebitoTotal - $gastosCreditoTotal;

			$this -> AlterarDadosBancosCorretoras(
				$id,
				$this -> ObterDadosBancosCorretoras($id, $this -> getSessao())[0]['bancoCorretora'],
				$saldoFinal,
			);

			return $saldoFinal;
		}

		return false;
	}

	public function parcelasPagasCredito($gasto, $dataReferencia = null)
	{
		$dadosCartao = $this -> ObterDadosCartoesCredito($gasto['id_bancoCorretora'], $this -> getSessao());

		if (!$dadosCartao)
			return false;

		$mesPagamento = intval($this -> InformacoesData('m', $gasto['dataCompraPagamento']));
		$anoPagamento = intval($this -> InformacoesData('y', $gasto['dataCompraPagamento']));
		$diaPagamento = intval($this -> InformacoesData('d', $gasto['dataCompraPagamento']));

		if ($dataReferencia == null)
			$dataReferencia = date("Y-m") . "-" . $this -> ultimoDiaMes(date('m'), date('d'));

		if ($diaPagamento >= $dadosCartao['fechamento']) {
			if ($dadosCartao['vencimento'] < $dadosCartao['fechamento']) {
				$mesPagamento += 2;
			}
			elseif ($diaPagamento > $dadosCartao['vencimento'])
				$mesPagamento += 1;
			elseif ($dadosCartao['vencimento'] > $dadosCartao['fechamento'])
				$mesPagamento += 1;
			else
				$mesPagamento += 1;
		}
		elseif ($diaPagamento == $dadosCartao['vencimento']) {
			$mesPagamento += 1;
		}
		else {
			if ($dadosCartao['vencimento'] > $dadosCartao['fechamento'])
				$mesPagamento -= 2;
			else
				$mesPagamento++;
		}

		if ($mesPagamento > 12) {
			$mesPagamento = $mesPagamento - 12;
			$anoPagamento++;

			if (gettype($anoPagamento / 4) == "integer")
				$diasFevereiro = 29;
			else
				$diasFevereiro = 28;

			if (
				$mesPagamento == 2 and intval(
					$this -> InformacoesData('d', $gasto['dataCompraPagamento'])
				) > $diasFevereiro
			)
				$mesPagamento++;
		}

		if ($mesPagamento < 10)
			$mesPagamento = "0" . $mesPagamento;

		if ($dadosCartao['vencimento'] < 10)
			$dadosCartao['vencimento'] = "0" . $dadosCartao['vencimento'];

		$primeiraDataPagamento = $anoPagamento . '-' . $mesPagamento . '-' . $dadosCartao['vencimento'];
		$diferencaMeses = $this -> diferencaMesesData($primeiraDataPagamento, $dataReferencia);


		if ($diferencaMeses > 0)
			return $diferencaMeses;
		else
			return 0;
	}

	public function parcelasDebitadas($gasto, $dataReferencia = null)
	{
		$primeiroMesPagamento = intval($this -> InformacoesData('m', $gasto['dataCompraPagamento']));
		$primeiroAnoPagamento = intval($this -> InformacoesData('y', $gasto['dataCompraPagamento']));
		$primeiroDiaPagamento = intval($this -> InformacoesData('d', $gasto['dataCompraPagamento']));

		if ($dataReferencia == null)
			$dataReferencia = date("Y-m") . "-" . $this -> ultimoDiaMes(date('m'), date('d'));

		if (
			intval($this -> InformacoesData('d', $gasto['dataCompraPagamento'])) > (intval($this -> InformacoesData(
				'd', $dataReferencia
			)) - 15)
		) {
			$primeiroDiaPagamento -= 17;
		}

		if ($primeiroMesPagamento < 10)
			$primeiroMesPagamento = "0" . $primeiroMesPagamento;

		if ($primeiroDiaPagamento < 10)
			$primeiroDiaPagamento = "0" . $primeiroDiaPagamento;

		$primeiraDataPagamento = $primeiroAnoPagamento . '-' . $primeiroMesPagamento . '-' . $primeiroDiaPagamento;

		$diferencaMeses = $this -> diferencaMesesData($primeiraDataPagamento, $dataReferencia);

		if ($diferencaMeses > 0)
			return $diferencaMeses;
		else
			return 0;
	}

	public function parcelasRecebidas($receita, $dataReferencia = null)
	{
		$primeiroMesPagamento = intval($this -> InformacoesData('m', $receita['dataCompraPagamento']));
		$primeiroAnoPagamento = intval($this -> InformacoesData('y', $receita['dataCompraPagamento']));
		$primeiroDiaPagamento = intval($this -> InformacoesData('d', $receita['dataCompraPagamento']));

		if ($dataReferencia == null)
			$dataReferencia = date("Y-m") . "-" . $this -> ultimoDiaMes(date('m'), date('d'));

		if (
			intval($this -> InformacoesData('d', $receita['dataCompraPagamento'])) > (intval($this -> InformacoesData(
					'd', $dataReferencia
				)) - 15)
		) {
			$primeiroDiaPagamento -= 17;
		}

		if ($primeiroMesPagamento < 10)
			$primeiroMesPagamento = "0" . $primeiroMesPagamento;

		if ($primeiroDiaPagamento < 10)
			$primeiroDiaPagamento = "0" . $primeiroDiaPagamento;

		$primeiraDataPagamento = $primeiroAnoPagamento . '-' . $primeiroMesPagamento . '-' . $primeiroDiaPagamento;
		$diferencaMeses = $this -> diferencaMesesData($primeiraDataPagamento, $dataReferencia);

		if ($diferencaMeses > 0)
			return $diferencaMeses;
		else
			return 0;
	}
}