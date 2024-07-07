<?php

require __DIR__ . "/./backEnd/bancoDados/ExecucaoCodigoMySql.php";
require __DIR__ . "/./backEnd/gerais/FormatacaoDados.php";
require __DIR__ . "/./backEnd/gerais/ValorFinal.php";
include(__DIR__ . "/./paginas/filtro/filtros.php");

if (!isset($_SESSION['ano_referencia']))
	$_SESSION['ano_referencia'] = date('Y');
if (!isset($_SESSION['mes_referencia']))
	$_SESSION['mes_referencia'] = date('m');
if (!isset($_SESSION['codigo_variante']))
	$_SESSION['codigo_variante'] = '';

if ($_SESSION['pagina_pai'] == 'bancosCorretoras' or $_SESSION['pagina_pai'] == 'cartaoCredito' or $_SESSION['pagina_pai'] == 'inicio') {
	if (!intval($_SESSION['ano_referencia']))
		$_SESSION['ano_referencia'] = date('Y');
	if (!intval($_SESSION['mes_referencia']))
		$_SESSION['mes_referencia'] = date('m');
}

$formatacao = new FormatacaoDados();
$valorFinal = new ValorFinal();
$execucao = new ExecucaoCodigoMySql();

$receitaTotal = 0;
$salarioReceita = 0;
$rendimentosReceita = 0;
$empreendimentosReceita = 0;
$emprestimosReceita = 0;
$reservaReceita = 0;
$outrosReceita = 0;

$saldoTotalBancos = 0;
$saldoTotalReserva = 0;

$janReceita = 0;
$fevReceita = 0;
$marReceita = 0;
$abrReceita = 0;
$maiReceita = 0;
$junReceita = 0;
$julReceita = 0;
$agoReceita = 0;
$setReceita = 0;
$outReceita = 0;
$novReceita = 0;
$dezReceita = 0;

$janGastos = 0;
$fevGastos = 0;
$marGastos = 0;
$abrGastos = 0;
$maiGastos = 0;
$junGastos = 0;
$julGastos = 0;
$agoGastos = 0;
$setGastos = 0;
$outGastos = 0;
$novGastos = 0;
$dezGastos = 0;

$gastosTotais = 0;
$debitosTotais = 0;
$endividamento = 0;

$pessoal = 0;
$necessario = 0;
$reserva = 0;
$dividas = 0;
$investimentos = 0;
$fatura = 0;
$boasAcoes = 0;

$execucao -> setCodigoMySql(
	"SELECT * FROM dbName.receitas WHERE email LIKE '" . $execucao -> getSessao(
	) . "' " . $_SESSION['codigo_variante'] . ";"
);
$resultadoExecucao = $execucao -> ExecutarCodigoMySql();

while ($dados = mysqli_fetch_assoc($resultadoExecucao)) {

	$mostrarReceita = false;

	$mesReceita = $valorFinal -> InformacoesData('m', $dados['dataCompraPagamento']);
	$anoReceita = $valorFinal -> InformacoesData('y', $dados['dataCompraPagamento']);
	$contador = 0;
	$valor = $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];

	while ($contador < $dados['parcelas']) {
		if (!intval($_SESSION['ano_referencia']))
			$_SESSION['ano_referencia'] = $anoReceita;

		if ($mesReceita == 1 and $anoReceita == $_SESSION['ano_referencia'])
			$janReceita += $valor;
		elseif ($mesReceita == 2 and $anoReceita == $_SESSION['ano_referencia'])
			$fevReceita += $valor;
		elseif ($mesReceita == 3 and $anoReceita == $_SESSION['ano_referencia'])
			$marReceita += $valor;
		elseif ($mesReceita == 4 and $anoReceita == $_SESSION['ano_referencia'])
			$abrReceita += $valor;
		elseif ($mesReceita == 5 and $anoReceita == $_SESSION['ano_referencia'])
			$maiReceita += $valor;
		elseif ($mesReceita == 6 and $anoReceita == $_SESSION['ano_referencia'])
			$junReceita += $valor;
		elseif ($mesReceita == 7 and $anoReceita == $_SESSION['ano_referencia'])
			$julReceita += $valor;
		elseif ($mesReceita == 8 and $anoReceita == $_SESSION['ano_referencia'])
			$agoReceita += $valor;
		elseif ($mesReceita == 9 and $anoReceita == $_SESSION['ano_referencia'])
			$setReceita += $valor;
		elseif ($mesReceita == 10 and $anoReceita == $_SESSION['ano_referencia'])
			$outReceita += $valor;
		elseif ($mesReceita == 11 and $anoReceita == $_SESSION['ano_referencia'])
			$novReceita += $valor;
		elseif ($mesReceita == 12 and $anoReceita == $_SESSION['ano_referencia'])
			$dezReceita += $valor;

		$contador++;
		$mesReceita++;

		if ($mesReceita > 12) {
			$mesReceita = 1;
			$anoReceita++;
		}
	}

	$dataReferencia = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> ultimoDiaMes(
			$_SESSION['mes_referencia'], $_SESSION['ano_referencia']
		);

	$parcelasPagas = $valorFinal -> parcelasRecebidas($dados, $dataReferencia);

	if (
		$parcelasPagas <= $dados['parcelas'] and
		$formatacao-> InformacoesData('m', $dados['dataCompraPagamento']) <= $_SESSION['mes_referencia'] and
		$formatacao-> InformacoesData('y', $dados['dataCompraPagamento']) <= $_SESSION['ano_referencia']
	) {

		$receitaTotal += $dados['valor'];
		$dataPagamento = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> InformacoesData(
				'd', $dados['dataCompraPagamento']
			);

		if ($dados['classificacao'] == 'Salário') {
			$salarioReceita += $valor;
		}
		elseif ($dados['classificacao'] == 'Rendimentos') {
			$rendimentosReceita += $valor;
		}
		elseif ($dados['classificacao'] == 'Empreendimentos') {
			$empreendimentosReceita += $valor;
		}
		elseif ($dados['classificacao'] == 'Empréstimos') {
			$emprestimosReceita += $valor;
		}
		elseif ($dados['classificacao'] == 'Reserva') {
			$reservaReceita += $valor;
		}
		elseif ($dados['classificacao'] == 'Outros') {
			$outrosReceita += $valor;
		}
	}
}

$execucao -> setCodigoMySql(
	"SELECT * FROM dbName.cartoesCredito WHERE email LIKE '" . $execucao -> getSessao(
	) . "' " . $_SESSION['codigo_variante'] . ";"
);
$resultadoExecucao1 = $execucao -> ExecutarCodigoMySql();

while ($dadosCartoesCredito = mysqli_fetch_assoc($resultadoExecucao1)) {

	$dataReferencia = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> ultimoDiaMes(
			$_SESSION['mes_referencia'], $_SESSION['ano_referencia']
		);

	$gastos = $valorFinal -> ObterDadosGastos(
		$valorFinal -> getSessao(), null, $dadosCartoesCredito['id_bancoCorretora']
	);

	$contador = 0;
	if ($gastos) {
		foreach ($gastos as $ignored) {
			$parcelasPagas = $valorFinal -> parcelasPagasCredito(
				$gastos[$contador], $dataReferencia
			);
			if (
				$parcelasPagas <= $gastos[$contador]['parcelas'] and
				$parcelasPagas > 0 and
				$gastos[$contador]['formaPagamento'] == 'Crédito'
			) {
				$fatura += $gastos[$contador]['valor'];
			}
			$contador++;
		}
	}

}

$execucao -> setCodigoMySql(
	"SELECT * FROM dbName.gastos WHERE email LIKE '" . $execucao -> getSessao(
	) . "' " . $_SESSION['codigo_variante'] . ";"
);
$resultadoExecucao2 = $execucao -> ExecutarCodigoMySql();

while ($dados = mysqli_fetch_assoc($resultadoExecucao2)) {

	$dataReferencia = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> ultimoDiaMes(
			$_SESSION['mes_referencia'], $_SESSION['ano_referencia']
		);

	$valor = $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];

	if ($dados['formaPagamento'] == 'Crédito') {

		$parcelasPagas = $valorFinal -> parcelasPagasCredito(
			$dados, $dataReferencia
		);

		$mesGastos = intval($valorFinal -> InformacoesData('m', $dados['dataCompraPagamento']));
		$anoGastos = $valorFinal -> InformacoesData('y', $dados['dataCompraPagamento']);
		$contador = 0;

		while ($contador <= $dados['parcelas']) {

			if (!intval($_SESSION['ano_referencia']))
				$_SESSION['ano_referencia'] = $anoGastos;

			if ($mesGastos == 1 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "01" . "-" .$execucao-> ultimoDiaMes('01', $_SESSION['ano_referencia'])) > 0)
				$janGastos += $valor;
			elseif ($mesGastos == 2 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "02" . "-" .$execucao-> ultimoDiaMes('02', $_SESSION['ano_referencia'])) > 0)
				$fevGastos += $valor;
			elseif ($mesGastos == 3 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "03" . "-" .$execucao-> ultimoDiaMes('03', $_SESSION['ano_referencia'])) > 0)
				$marGastos += $valor;
			elseif ($mesGastos == 4 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "04" . "-" .$execucao-> ultimoDiaMes('04', $_SESSION['ano_referencia'])) > 0)
				$abrGastos += $valor;
			elseif ($mesGastos == 5 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "05" . "-" .$execucao-> ultimoDiaMes('05', $_SESSION['ano_referencia'])) > 0)
				$maiGastos += $valor;
			elseif ($mesGastos == 6 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "06" . "-" .$execucao-> ultimoDiaMes('06', $_SESSION['ano_referencia'])) > 0)
				$junGastos += $valor;
			elseif ($mesGastos == 7 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "07" . "-" .$execucao-> ultimoDiaMes('07', $_SESSION['ano_referencia'])) > 0)
				$julGastos += $valor;
			elseif ($mesGastos == 8 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "08" . "-" .$execucao-> ultimoDiaMes('08', $_SESSION['ano_referencia'])) > 0)
				$agoGastos += $valor;
			elseif ($mesGastos == 9 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "09" . "-" .$execucao-> ultimoDiaMes('09', $_SESSION['ano_referencia'])) > 0)
				$setGastos += $valor;
			elseif ($mesGastos == 10 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "10" . "-" .$execucao-> ultimoDiaMes('10', $_SESSION['ano_referencia'])) > 0)
				$outGastos += $valor;
			elseif ($mesGastos == 11 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "11" . "-" .$execucao-> ultimoDiaMes('11', $_SESSION['ano_referencia'])) > 0)
				$novGastos += $valor;
			elseif ($mesGastos == 12 and $_SESSION['ano_referencia'] == $anoGastos and $valorFinal-> parcelasPagasCredito($dados, $_SESSION['ano_referencia'] . "-" . "12" . "-" .$execucao-> ultimoDiaMes('12', $_SESSION['ano_referencia'])) > 0)
				$dezGastos += $valor;

			$contador++;
			$mesGastos++;
			if ($mesGastos > 12) {
				$mesGastos = 1;
				$anoGastos++;
			}
		}

	}

	else {
		$parcelasPagas = $valorFinal -> parcelasDebitadas($dados, $dataReferencia);

		$mesGastos = $valorFinal -> InformacoesData('m', $dados['dataCompraPagamento']);
		$anoGastos = $valorFinal -> InformacoesData('y', $dados['dataCompraPagamento']);
		$contador = 0;

		while ($contador < $dados['parcelas']) {

			if (!intval($_SESSION['ano_referencia']))
				$_SESSION['ano_referencia'] = $anoGastos;

			if ($mesGastos == 1 and $_SESSION['ano_referencia'] == $anoGastos)
				$janGastos += $valor;
			elseif ($mesGastos == 2 and $_SESSION['ano_referencia'] == $anoGastos)
				$fevGastos += $valor;
			elseif ($mesGastos == 3 and $_SESSION['ano_referencia'] == $anoGastos)
				$marGastos += $valor;
			elseif ($mesGastos == 4 and $_SESSION['ano_referencia'] == $anoGastos)
				$abrGastos += $valor;
			elseif ($mesGastos == 5 and $_SESSION['ano_referencia'] == $anoGastos)
				$maiGastos += $valor;
			elseif ($mesGastos == 6 and $_SESSION['ano_referencia'] == $anoGastos)
				$junGastos += $valor;
			elseif ($mesGastos == 7 and $_SESSION['ano_referencia'] == $anoGastos)
				$julGastos += $valor;
			elseif ($mesGastos == 8 and $_SESSION['ano_referencia'] == $anoGastos)
				$agoGastos += $valor;
			elseif ($mesGastos == 9 and $_SESSION['ano_referencia'] == $anoGastos)
				$setGastos += $valor;
			elseif ($mesGastos == 10 and $_SESSION['ano_referencia'] == $anoGastos)
				$outGastos += $valor;
			elseif ($mesGastos == 11 and $_SESSION['ano_referencia'] == $anoGastos)
				$novGastos += $valor;
			elseif ($mesGastos == 12 and $_SESSION['ano_referencia'] == $anoGastos)
				$dezGastos += $valor;

			$contador++;
			$mesGastos++;
			if ($mesGastos > 12) {
				$mesGastos = 1;
				$anoGastos++;
			}
		}
	}

	if ($parcelasPagas <= $dados['parcelas'] and $parcelasPagas > 0) {
		if (
			$dados['formaPagamento'] == 'Débito' and
			$formatacao-> InformacoesData('m', $dados['dataCompraPagamento']) <= $_SESSION['mes_referencia'] and
			$formatacao-> InformacoesData('y', $dados['dataCompraPagamento']) <= $_SESSION['ano_referencia']
		) {
			$gastosTotais += $valor;
			$debitosTotais += $valor;
		}

		else {
			$gastosTotais += $dados['valor'];
		}

		if ($dados['classificacao'] == 'Pessoal') {
			$pessoal += $valor;
		}
		elseif ($dados['classificacao'] == 'Necessário') {
			$necessario += $valor;
		}
		elseif ($dados['classificacao'] == 'Reserva') {
			$reserva += $valor;
		}
		elseif ($dados['classificacao'] == 'Dívidas') {
			$dividas += $valor;
		}
		elseif ($dados['classificacao'] == 'Investimentos') {
			$investimentos += $valor;
		}
		elseif ($dados['classificacao'] == 'Boas Ações') {
			$boasAcoes += $valor;
		}

	}

	$parcelas = $dados['parcelas'] - $parcelasPagas;
	if ($parcelasPagas < 0)
		$parcelas = $dados['parcelas'];
	elseif ($parcelasPagas > $dados['parcelas'])
		$parcelas = 0;

	$endividamento += $valor * $parcelas;
}

$execucao -> setCodigoMySql(
	"SELECT * FROM dbName.bancosCorretoras WHERE email LIKE '" . $execucao -> getSessao(
	) . "' " . $_SESSION['codigo_variante'] . ";"
);
$resultadoExecucao3 = $execucao -> ExecutarCodigoMySql();

while ($dadosBancosCorretoras = mysqli_fetch_assoc($resultadoExecucao3)) {

	$dataReferencia = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> ultimoDiaMes(
			$_SESSION['mes_referencia'], $_SESSION['ano_referencia']
		);

	$valorFinal = new ValorFinal(
		'bancoCorretora', $dadosBancosCorretoras['id'], $dataReferencia
	);
	$saldo = $valorFinal -> ValorFinal(
		'bancoCorretora', $dadosBancosCorretoras['id'], $dataReferencia
	);
	$saldoTotalBancos += floatval($saldo);

	if ($dadosBancosCorretoras['bancoCorretora'] == 'Reserva') {
		$saldoTotalReserva += floatval($saldo);
	}
}

?>