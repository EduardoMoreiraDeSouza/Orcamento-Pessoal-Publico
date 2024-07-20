<?php

require __DIR__ . "/../backEnd/verificacoes/VerificarLogin.php";
$login = new VerificarLogin();

if ($login -> VerificarLogin()) {
	$_SESSION['pagina_pai'] = 'gastos';

	require __DIR__ . "/../backEnd/bancoDados/ExecucaoCodigoMySql.php";
	require __DIR__ . "/../backEnd/gerais/FormatacaoDados.php";

	$formatacao = new FormatacaoDados();

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

		<title>Orçamento Pessoal - Editar Gasto</title>

		<link rel="shortcut icon" type="image/png" href="../favicon.png"/>

</head>

	<body>

	<?php require(__DIR__ . "/./particoes/menu/menu.php"); ?>

	<div class="box">
		<section class="banner" id="banner">
			<div class="overlay"></div>
			<div class="container chamada-banner introducao">
				<div class="row">
					<div class="row-md-12 text-center">

						<h2 class="pt-4 mb-4">
							Editar Gasto
						</h2>

						<?php

						$dados = new ObterDadosGastos();

						if (isset($_POST['id']) and !empty($_POST['id'])) {
							$dados = $dados -> ObterDadosGastos($dados -> getSessao(), $_POST['id'])[0];
						}

						else {
							$dados -> Redirecionar($_SESSION['pagina_pai'], true);
						}

						?>

						<form class="form-inline w-75 container" action="../backEnd/InteracaoFront/editarGastos.php"
						      method="post">

							<div class="form-group mt-2">
								<label for="">Banco / Corretora:</label>
								<select class="form-select text-center" name="bancoCorretoraId" required>
									<?php include(__DIR__ . "/./particoes/loops/nomes_bancos_corretoras_select.php") ?>
								</select>
							</div>

							<div class="form-group mt-2">
								<label for="">Nome:</label>
								<input type="text" class="form-control input-group-text" name="nome"
								       placeholder="Nome:"
								       value="<?= $dados['nome'] ?>" required>
							</div>

							<div class="form-group mt-2">
								<label for="">Valor das Parcelas:</label>
								<input type="text" class="form-control input-group-text" name="valor"
								       placeholder="Valor:"
								       value="R$ <?= $formatacao -> formatarValor($dados['valor']) ?>" required>
							</div>

							<div class="form-group mt-2">
								<label for="">Forma de Pagamento:</label>
								<select class="form-select text-center" name="formaPagamento" required>
									<option value="Débito" <?= $dados['formaPagamento'] == 'Débito' ? 'selected' : '' ?>>
										Débito
									</option>
									<option value="Crédito" <?= $dados['formaPagamento'] == 'Crédito' ? 'selected' : '' ?>>
										Crédito
									</option>
								</select>
							</div>

							<div class="form-group mt-2">
								<label>Classificação:</label>
								<select class="form-select text-center" name="classificacao" required>
									<option value="Pessoal" <?= $dados['classificacao'] == 'Pessoal' ? 'selected' : '' ?>>
										Pessoal
									</option>
									<option value="Necessário" <?= $dados['classificacao'] == 'Necessário' ? 'selected' : '' ?>>
										Necessário
									</option>
									<option value="Reserva" <?= $dados['classificacao'] == 'Reserva' ? 'selected' : '' ?>>
										Reserva
									</option>
									<option value="Dívidas" <?= $dados['classificacao'] == 'Dívidas' ? 'selected' : '' ?>>
										Dívidas
									</option>
									<option value="Investimentos" <?= $dados['classificacao'] == 'Investimentos' ? 'selected' : '' ?>>
										Investimentos
									</option>
									<option value="Boas Ações" <?= $dados['classificacao'] == 'Boas Ações' ? 'selected' : '' ?>>
										Boas
										Ações
									</option>
									<option value="Boas Ações" <?= $dados['classificacao'] == 'Correção do Saldo' ? 'selected' : '' ?>>
										Correção do Saldo
									</option>
								</select>
							</div>

							<div class="form-group mt-2">
								<label>Parcelas:</label>
								<input type="number" class="form-control input-group-text" name="parcelas" required
								       placeholder="Parcelas:" step="1" value="<?= $dados['parcelas'] ?>">
							</div>

							<div class="form-group mt-2">
								<label>Data do Pagamento:</label>
								<input type="date" class="form-control text-center"
								       name="dataCompraPagamento" value="<?= $dados['dataCompraPagamento'] ?>" required>
							</div>

							<div class="row">
								<label>Alterações</label>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label" for="flexRadioDefault3">
										Todos (Referentes a este)
									</label>
									<input class="form-check-input" type="radio" name="tipoAlteracao"
									               id="flexRadioDefault3" value="todos">
									<br/>
									<caption>A data acima será posta no 1º gasto</caption>
								</div>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label" for="flexRadioDefault1">
										Somente Este:
									</label>
									<input class="form-check-input" type="radio" name="tipoAlteracao"
									       id="flexRadioDefault1" value="este" checked>

								</div>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label" for="flexRadioDefault2">
										Este e Futuros
									</label>
									<input class="form-check-input" type="radio" name="tipoAlteracao"
									       id="flexRadioDefault2" value="esteFuturos">
								</div>
							</div>

							<button type="submit" class="botao-primario mt-3 mb-4" name="id"
							        value="<?= $dados['id_gasto'] ?>">
								Editar
							</button>
						</form>
						<br/>
						<form class="form-inline w-75 container" action="../backEnd/InteracaoFront/excluirAvancado.php" method="post">

							<div class="row border-top border-danger">
								<label class="text-danger mt-3">Excluir:</label>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label text-dark" for="flexRadioDefault4">
										Todos (Referentes a este)
									</label>
									<input class="form-check-input" type="radio" name="excluir"
									       id="flexRadioDefault4" value="todos">
									<br/>
								</div>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label text-dark" for="flexRadioDefault5">
										Somente Este:
									</label>
									<input class="form-check-input " type="radio" name="excluir"
									       id="flexRadioDefault5" value="este" checked>

								</div>
								<div class="col-sm form-check mt-2 border-bottom border-dark rounded-2">
									<label class="form-check-label text-dark" for="flexRadioDefault6">
										Este e Futuros
									</label>
									<input class="form-check-input" type="radio" name="excluir"
									       id="flexRadioDefault6" value="esteFuturos">
								</div>
							</div>

							<button type="submit" class="btn btn-danger mt-3 mb-4" name="id_gasto" value="<?= $dados['id_gasto'] ?>">
								Excluir
							</button>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php include(__DIR__ . "/./particoes/rodape/rodape_e_script_js.php") ?>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
	        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
	        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
	</script>
	<script src="../js/javaScript.js"></script>

	</body>
	</html>


<?php } ?>