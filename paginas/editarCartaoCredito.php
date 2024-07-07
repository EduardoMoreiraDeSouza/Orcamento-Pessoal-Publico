<?php

require __DIR__ . "/../backEnd/verificacoes/VerificarLogin.php";
$login = new VerificarLogin();

if ($login -> VerificarLogin()) {
	$_SESSION['pagina_pai'] = 'cartaoCredito';

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

		<title>Orçamento Pessoal - Editar Cartão de Crédito</title>

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
							Editar Cartão de Crédito
						</h2>

						<?php

						$dados = new ObterDadosCartoesCredito();

						if (isset($_POST['id']) and !empty($_POST['id'])) {
							$dados = $dados -> ObterDadosCartoesCredito($_POST['id'], $dados -> getSessao());
						}

						else {
							$dados -> Redirecionar($_SESSION['pagina_pai'], true);
						}

						?>

						<form class="form-inline w-75 container" action="../backEnd/InteracaoFront/editarCartaoCredito.php" method="post">

							<div class="form-group mt-2">
								<label for="">Banco / Corretora: (Não pode ser alterado)</label>
								<select class="form-select text-center" name="bancoCorretoraId" required disabled>
									<?php include(__DIR__ . "/./particoes/loops/nomes_bancos_corretoras_select.php") ?>
								</select>
							</div>

							<div class="form-group mt-2">
								<label for="">Limite Total:</label>
								<input type="text" class="form-control input-group-text" name="valor"
								       placeholder="Valor:"
								       value="R$ <?= $formatacao -> formatarValor($dados['limite']) ?>">
							</div>

							<div class="form-group mt-2">
								<label>Dia do Fechamento:</label>
								<input type="number" class="container input-group-text"
								       name="fechamento"
								       placeholder="Fechamento:" step="1" max="31" min="1"
								       value="<?= $dados['fechamento'] ?>">
							</div>
							<div class="form-group mt-2">
								<label>Dia do Fechamento:</label>
								<input type="number" class="container input-group-text"
								       name="vencimento"
								       placeholder="Vencimento:" step="1" min="1" max="31"
								       value="<?= $dados['vencimento'] ?>">
							</div>

							<button type="submit" class="botao-primario mt-3 mb-4" name="id" value="<?= $dados['id_bancoCorretora'] ?>">
								Editar
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