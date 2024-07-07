<?php

require __DIR__ . "/../backEnd/verificacoes/VerificarLogin.php";
$login = new VerificarLogin();

if ($login -> VerificarLogin()) {
	$_SESSION['pagina_pai'] = 'gastos';

	require __DIR__ . "/../backEnd/bancoDados/ExecucaoCodigoMySql.php";
	require __DIR__ . "/../backEnd/gerais/FormatacaoDados.php";

	$formatacao = new FormatacaoDados();

	if (isset($_GET['excluir']) and isset($_GET['id'])) {
		require __DIR__ . "/../backEnd/funcionalidades/ExcluirGasto.php";

		$exluir = new ExcluirGasto();
		$exluir -> ExcluirGasto($_GET['id']);
		$exluir -> Redirecionar('gastos', true);
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

		<link href="../css/style.css" rel="stylesheet">

		<title>Orçamento Pessoal - Gastos</title>

		<link rel="shortcut icon" type="image/png" href="../favicon.png"/>

</head>

	<body>
	<nav class="navbar fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand" href="../index.php">< Orçamento Pessoal ></a>

			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
			        data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
			        aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
			     aria-labelledby="offcanvasDarkNavbarLabel">

				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">< Gastos ></h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
					        aria-label="Close"></button>
				</div>

				<div class="offcanvas-body">
					<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

						<li class="nav-item h6">
							<a class="nav-link text-light" href="../index.php">Início</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="../paginas/bancosCorretoras.php">Bancos/Corretoras</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="../paginas/receitas.php">Receitas</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="../paginas/cartaoCredito.php">Cartões de Crédito</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="#">Investimentos (Em Breve)</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link text-light" href="#">Rendimentos (Em Breve)</a>
						</li>
						<li class="nav-item h6">
							<a class="nav-link active text-light" aria-current="page"
							   href="../backEnd/InteracaoFront/sair.php">Sair</a>
						</li>

					</ul>

				</div>
			</div>
		</div>
	</nav>

	<div class="box">
		<section class="banner" id="banner">
			<div class="overlay"></div>
			<div class="container chamada-banner introducao pb-2">

				<?php include(__DIR__ . "/./particoes/formularios/form_data_referencia.php") ?>

				<h2 class="pt-4 mb-4 text-center">
					Meus Gastos
				</h2>

				<main>
					<div class="row text-start mb-4">

						<div class="col"><?php include(__DIR__ . "/./particoes/formularios/novo_banco_corretora.php") ?></div>
						<div class="col-md mb-1"><?php include(__DIR__ . "/./particoes/formularios/novo_debito.php") ?></div>
						<div class="col-sm-3 mb-1"><?php include(__DIR__ . "/./particoes/formularios/novo_gasto_credito.php") ?></div>
						<div class="col-md-3 mb-1"><?php include(__DIR__ . "/./particoes/formularios/novo_cartao_credito.php") ?></div>

					</div>

					<table class="table table-responsive table-dark text-center">
						<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Nome</th>
							<th scope="col">Forma de Pagamento</th>
							<th scope="col">Valor</th>
							<th scope="col">Ações</th>
						</tr>
						</thead>
						<tbody>

						<form class="form-inline" method="post">
							<tr class="form-group">
								<th>0</th>
								<td><?php include(__DIR__ . "/./particoes/filtros/select_filtrar_nome.php") ?></td>
								<td><?php include(__DIR__ . "/./particoes/filtros/select_filtrar_forma_pagamento.php") ?></td>
								<td><?php include(__DIR__ . "/./particoes/filtros/select_filtrar_valor.php") ?></td>
								<td><?php include(__DIR__ . "/./particoes/botoes/submit_filtros.php") ?></td>
							</tr>
						</form>

						<h5 class="text-center text-sm-center texto-alerta">Gastos no crédito serão colocados no mês
							referente à fatura!</h5>

						<?php

						require __DIR__ . "/../backEnd/gerais/ValorFinal.php";
						$valorFinal = new ValorFinal();
						$execucao = new ExecucaoCodigoMySql();

						$execucao -> setCodigoMySql(
							"SELECT * FROM dbName.gastos WHERE email LIKE '" . $login -> getSessao(
							) . "' " . $_SESSION['codigo_variante'] . ";"
						);

						$resultadoExecucao = $execucao -> ExecutarCodigoMySql();
						$saldoTotal = 0;

						while ($dados = mysqli_fetch_assoc($resultadoExecucao)) {

							$mostrarGastos = false;

							if ($_SESSION['mes_referencia'] == 'todos' and $_SESSION['ano_referencia'] != 'todos') {
								// Mostra todos daquele ano
								if (
									$valorFinal -> InformacoesData(
										'y', $dados['dataCompraPagamento']
									) == $_SESSION['ano_referencia']
								) {
									$mostrarGastos = true;
									$parcelasPagas = $dados['parcelas'];
									$vencimento = '*';
									$saldoTotal += $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];
								}
							}

							elseif ($_SESSION['mes_referencia'] != 'todos' and $_SESSION['ano_referencia'] == 'todos') {
								// Mostra todos daquele mês
								if (
									$valorFinal -> InformacoesData(
										'm', $dados['dataCompraPagamento']
									) == $_SESSION['mes_referencia']
								) {
									$mostrarGastos = true;
									$parcelasPagas = $dados['parcelas'];
									$vencimento = '*';
									$saldoTotal += $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];
								}
							}

							elseif ($_SESSION['mes_referencia'] == 'todos' and $_SESSION['ano_referencia'] == 'todos') {
								// Mostra todos os gastos
								$mostrarGastos = true;
								$parcelasPagas = $dados['parcelas'];
								$vencimento = '*';
								$saldoTotal += $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];
							}

							else {

								$dataReferencia = $_SESSION['ano_referencia'] . "-" . $_SESSION['mes_referencia'] . "-" . $execucao -> ultimoDiaMes(
										$_SESSION['mes_referencia'], $_SESSION['ano_referencia']
									);

								if ($dados['formaPagamento'] == 'Crédito') {
									$parcelasPagas = $valorFinal -> parcelasPagasCredito(
										$dados, $dataReferencia
									);
									if (
										$cartao = $valorFinal -> ObterDadosCartoesCredito(
											$dados['id_bancoCorretora'], $valorFinal -> getSessao()
										)
									)
										$vencimento = $cartao['vencimento'] . "/" . $_SESSION['mes_referencia'];

								}
								else {
									$parcelasPagas = $valorFinal -> parcelasDebitadas($dados, $dataReferencia);
									$vencimento = $valorFinal -> InformacoesData(
											'd', $dados['dataCompraPagamento']
										) . "/" . $_SESSION['mes_referencia'];
								}

								if ($parcelasPagas <= $dados['parcelas'] and $parcelasPagas > 0) {
									if (
										$dados['formaPagamento'] == 'Débito' and
										$formatacao -> InformacoesData(
											'm', $dados['dataCompraPagamento']
										) <= $_SESSION['mes_referencia'] and
										$formatacao -> InformacoesData(
											'y', $dados['dataCompraPagamento']
										) <= $_SESSION['ano_referencia']
									) {
										$saldoTotal += $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];
										$mostrarGastos = true;
									}
									elseif ($dados['formaPagamento'] != 'Débito') {
										$saldoTotal += $dados['classificacao'] == 'Correção do Saldo' ? 0 : $dados['valor'];
										$mostrarGastos = true;
									}
								}

							}

							if ($mostrarGastos) {

								?>

								<!-- Editar gastos -->
								<tr>
									<th scope="row"><?= $parcelasPagas . "/" . $dados['parcelas'] ?></th>

									<th><?= $dados['nome'] ?></th>

									<td>
										<?= $dados['formaPagamento'] ?>
									</td>
									<td>
										R$ <?= $formatacao -> formatarValor(
											$dados['valor']
										) ?>
									</td>

									<form class="form" action="./editarGastos.php" method="post">
										<td>
											<button style="text-decoration: none; width: 4vh; height: 4vh;"
											        class="text-primary bg-transparent rounded-circle border border-dark"
											        name="id"
											        value="<?= $dados['id_gasto'] ?>">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
												     fill="currentColor"
												     class="bi bi-pen" viewBox="0 0 16 16">
													<path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
												</svg>
											</button>

											<a href="?excluir=true&id=<?= $dados['id_gasto'] ?>"
											   style="text-decoration: none; margin-left: 0.8vh; width: 4vh; height: 4vh;"
											   class="text-danger ">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
												     fill="currentColor" class="bi bi-trash"
												     viewBox="0 0 16 16">
													<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
													<path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
												</svg>
											</a>
										</td>
									</form>
								</tr>

							<?php }
						} ?>

						<th scope="row">#</th>
						<td>Total</td>
						<td></td>
						<td>R$ <?= $formatacao -> formatarValor($saldoTotal) ?></td>
						<td></td>

						</tbody>
					</table>

				</main>

			</div>
	</div>
	</div>
	</section>
	</div>

	<?php include(__DIR__ . "/./particoes/rodape/rodape_e_script_js.php") ?>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
	        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
	        crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
	        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
	        crossorigin="anonymous">
	</script>
	<script src="../js/javaScript.js"></script>

	</body>
	</html>

<?php } ?>