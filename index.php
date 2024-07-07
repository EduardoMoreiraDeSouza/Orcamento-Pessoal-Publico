<?php

require_once __DIR__ . "/./backEnd/site/Globais.php";
$globais = new Globais;

if (!empty($globais -> getSessao())) {

	$_SESSION['pagina_pai'] = 'inicio';
	require __DIR__ . "/./index_backend.php";

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
	      integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT"
	      crossorigin="anonymous">

	<link href="./css/style.css" rel="stylesheet">

	<title>Orçamento Pessoal</title>

	<link rel="shortcut icon" type="image/png" href="./favicon.png"/>

</head>
<body>

<nav class="navbar fixed-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="./index.php">< Orçamento Pessoal ></a>

		<?php if (!empty($globais -> getSessao())) { ?>

			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
			        data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
			        aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

		<?php } ?>

		<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
		     aria-labelledby="offcanvasDarkNavbarLabel">

			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">< Orçamento Pessoal ></h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
				        aria-label="Close"></button>
			</div>

			<div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

					<?php if (!empty($globais -> getSessao())) { ?>

						<li class="nav-item h6">
							<a class="nav-link text-light" href="./index.php">Início</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="./paginas/bancosCorretoras.php">Bancos/Corretoras</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="./paginas/receitas.php">Receitas</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="./paginas/gastos.php">Gastos</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="./paginas/cartaoCredito.php">Cartões de Crédito</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="#">Investimentos (Em Breve)</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="#">Rendimentos (Em Breve)</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link active text-light" aria-current="page"
							   href="./backEnd/InteracaoFront/sair.php">Sair</a>
						</li>

					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</nav>

<div class="box">
	<section class="banner" id="banner">
		<div class="overlay"></div>
		<div class="container chamada-banner introducao">
			<div class="row">
				<div class="col">
					<?php if (!empty($globais -> getSessao())) {
						include(__DIR__ . "/./paginas/particoes/formularios/form_data_referencia.php");
					} ?>
				</div>

				<div class="col-md-12 text-center">

					<h1 class="pt-4 mb-1">
						Orçamento Pessoal
					</h1>

					<p class="apresentacao">Para que você tenha uma melhor vida financeira.</p>
				</div>

				<?php if (empty($globais -> getSessao())) { ?>

					<div class="col-md-12 text-center">
						<ul class="container" style="list-style-type: none;">
							<li class="pt-3">
								<a class="nav-link active text-light entrar btn" aria-current="page"
								   href="./paginas/entrar.php">
									Entrar
								</a>
							</li>
							<li class="pt-2 mb-5">
								<a class="nav-link active text-light cadastrar btn" aria-current="page"
								   href="./paginas/cadastrar.php">
									Cadastrar
								</a>
							</li>
						</ul>
					</div>

				<?php } else { ?>

				<h3 class="pt-4 mb-4 text-center">
					Balanço:
				</h3>

				<div class="col-12">
					<script type="text/javascript"
					        src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Mês', 'Receita', 'Gasto'],
                                ['Jan', <?= $janReceita ?>, <?= $janGastos ?>],
                                ['Fev', <?= $fevReceita ?>, <?= $fevGastos ?>],
                                ['Mar', <?= $marReceita ?>, <?= $marGastos ?>],
                                ['Abr', <?= $abrReceita ?>, <?= $abrGastos ?>],
                                ['Mai', <?= $maiReceita ?>, <?= $maiGastos ?>],
                                ['Jun', <?= $junReceita ?>, <?= $junGastos ?>],
                                ['Jul', <?= $julReceita ?>, <?= $julGastos ?>],
                                ['Ago', <?= $agoReceita ?>, <?= $agoGastos ?>],
                                ['Set', <?= $setReceita ?>, <?= $setGastos ?>],
                                ['Out', <?= $outReceita ?>, <?= $outGastos ?>],
                                ['Nov', <?= $novReceita ?>, <?= $novGastos ?>],
                                ['Dez', <?= $dezReceita ?>, <?= $dezGastos ?>]
                            ]);

                            var options = {
                                title: 'Receitas (R$) x Gastos (R$), Durante o Ano:',
                                width: '100%',
                                height: '500px',
                                curveType: 'function',
                                legend: {position: 'bottom'}
                            };

                            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                            chart.draw(data, options);
                        }
					</script>


					<div id="chart_wrap2">
						<div id="curve_chart"></div>
					</div>
				</div>

				<div class="col mt-5">
					<table class="table table-dark table-striped table-hover table-responsive table-bordered tabela-balanco">
						<tbody>
						<tr>
							<th scope="row" class="text-start text-success">Receita do Mês:</th>
							<td class="text-center text-success col-6">R$ <?= $formatacao -> formatarValor(
									$receitaTotal
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start text-danger">Faturas:</th>
							<td class="text-center text-danger col-6">R$ <?= $formatacao -> formatarValor(
									$fatura
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start text-danger">Débitos:</th>
							<td class="text-center text-danger col-6">R$ <?= $formatacao -> formatarValor(
									$debitosTotais
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start text-danger">Despesas:</th>
							<td class="text-center text-danger col-6">R$ <?= $formatacao -> formatarValor(
									$fatura + $debitosTotais
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start">Balanço:</th>
							<td class="text-center col-6">R$ <?= $formatacao -> formatarValor(
									$receitaTotal - ($fatura + $debitosTotais)
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start">Reserva:</th>
							<td class="text-center col-6">R$ <?= $formatacao -> formatarValor(
									$saldoTotalReserva
								) ?></td>
						</tr>

						<tr>
							<th scope="row" class="text-start">Patrimônio Total:</th>
							<td class="text-center col-6">R$ <?= $formatacao -> formatarValor(
									$saldoTotalBancos
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start">Endividamento:</th>
							<td class="text-center col-6">R$ <?= $formatacao -> formatarValor(
									$endividamento
								) ?></td>
						</tr>
						<tr>
							<th scope="row" class="text-start">Valor da Hora (Base: Seu patrimônio, Hrs:
								220):
							</th>
							<td class="text-center col-6">R$ <?= $formatacao -> formatarValor(
									$saldoTotalBancos / 220
								) ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="row mb-5">
				<div class="col">
					<script type="text/javascript"
					        src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
                        google.charts.load("current", {packages: ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ["Gasto:", "R$", {role: "style"}],
                                ["Pessoal", <?= $pessoal ?>, "#072620"],
                                ["Necessário", <?= $necessario ?>, "#8C3623"],
                                ["Reserva", <?= $reserva ?>, "#F2C879"],
                                ["Dívidas", <?= $dividas ?>, "#BF532C"],
                                ["Investimentos", <?= $investimentos ?>, "#637343"],
                                ["Boas Ações", <?= $boasAcoes ?>, "#CAD9C7"]
                            ]);

                            var view = new google.visualization.DataView(data);
                            view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);

                            var options = {
                                title: "Gastos Referentes Ao Mês e Ano (R$)",
                                width: '100%',
                                height: '500px',
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                            chart.draw(view, options);
                        }
					</script>
					<div id="chart_wrap">
						<div id="columnchart_values"></div>
					</div>
				</div>
			</div>
			<div class="row mb-5">
				<div class="col">
					<script type="text/javascript"
					        src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
                        google.charts.load("current", {packages: ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ["Receita:", "R$", {role: "style"}],
                                ["Salário", <?= $salarioReceita ?>, "#072620"],
                                ["Rendimentos", <?= $rendimentosReceita ?>, "#8C3623"],
                                ["Empreendimentos", <?= $empreendimentosReceita ?>, "#F2C879"],
                                ["Empréstimos", <?= $emprestimosReceita ?>, "#BF532C"],
                                ["Reserva", <?= $reservaReceita ?>, "#637343"],
                                ["outros", <?= $outrosReceita ?>, "#CAD9C7"]
                            ]);

                            var view = new google.visualization.DataView(data);
                            view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);

                            var options = {
                                title: "Receitas Referentes Ao Mês e Ano (R$)",
                                width: '100%',
                                height: '500px',
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
                            chart.draw(view, options);
                        }
					</script>

					<div id="chart_wrap1">
						<div id="columnchart_values1"></div>
					</div>
				</div>
			</div>

			<h3 class="pt-4 mb-4 mt-5 text-center recomendacoes">
				Recomendações (Opcional):
			</h3>

			<div class="row">
				<div class="col-sm text-center">
					<table class="table bg-dark text-light table-responsive tabela-dividas">
						<caption>Enquanto Tiver Dívidas</caption>
						<thead>
						<tr>
							<th scope="col" class="text-center">Gastos:</th>
							<th scope="col" class="text-center">Porcentagem:</th>
							<th scope="col" class="text-end">Valor:</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>Pessoais:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td>Reserva:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td>Dívidas:</td>
							<td class="text-center">30%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 30
								) ?></td>
						</tr>
						<tr>
							<td>Necessárias:</td>
							<td class="text-center">50%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 50
								) ?></td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm">
					<table class="table bg-dark text-light table-responsive tabela-quitar-dividas">
						<caption >Após Quitar as Dívidas</caption>
						<thead>
						<tr>
							<th scope="col" class="text-center">Gastos:</th>
							<th scope="col" class="text-center">Porcentagem:</th>
							<th scope="col" class="text-end">Valor:</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="text-center">Pessoais:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Reserva:</td>
							<td class="text-center">30%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 30
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Boas Ações:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Necessárias:</td>
							<td class="text-center">50%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 50
								) ?></td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm">
					<table class="bg-dark text-light table table-responsive tabela-investimentos">
						<caption>Após a Reserva Cheia</caption>
						<thead>
						<tr>
							<th scope="col" class="text-center">Gastos:</th>
							<th scope="col" class="text-center">Porcentagem:</th>
							<th scope="col" class="text-end">Valor:</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="text-center">Pessoais:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Investimentos:</td>
							<td class="text-center">30%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 30
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Boas Ações:</td>
							<td class="text-center">10%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 10
								) ?></td>
						</tr>
						<tr>
							<td class="text-center">Necessárias:</td>
							<td class="text-center">50%</td>
							<td class="text-end">R$<?= $formatacao -> formatarValor(
									($receitaTotal / 100) * 50
								) ?></td>
						</tr>
						</tbody>
					</table>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>
</div>

<?php include(__DIR__ . "/./paginas/particoes/rodape/rodape_e_script_js.php") ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
</body>
</html>
