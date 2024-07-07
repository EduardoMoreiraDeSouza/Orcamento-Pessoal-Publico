<?php

require __DIR__ . "/../backEnd/site/Globais.php";
$globais = new Globais;
$_SESSION['pagina_pai'] = "entrar";

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

	<title>Orçamento Pessoal - Entrar</title>

	<link rel="shortcut icon" type="image/png" href="../favicon.png"/>

</head>
<body>

<nav class="navbar fixed-top">
	<div class="container-fluid">
		<a class="navbar-brand" href="../index.php">< Orçamento Pessoal ></a>

        <?php if (!empty($globais->getSessao())) { ?>

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

                    <?php if (!empty($globais->getSessao())) { ?>

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
						<a class="nav-link text-light" href="../paginas/gastos.php">Gastos</a>
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
						<a class="nav-link active text-light btn" aria-current="page"
						   href="../backEnd/InteracaoFront/sair.php">Sair</a>
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
				<div class="row-md-12 text-center ">
					<h1 class="pt-4">
						Orçamento Pessoal
					</h1>

					<main class="container mt-5 p-3 text-center formulario-usuario">

						<h3 class="text-center">Entrar</h3>

						<form class="mt-3" action="../backEnd/InteracaoFront/entrar.php" method="POST">
							<div class="mb-3 text-start">
								<label for="exampleInputEmail1" class="form-label">E-mail:</label>
								<input type="email" name="email" class="form-control container input-group-text text-center" required>
							</div>
							<div class="mb-3 text-start">
								<label for="exampleInputPassword1" class="form-label">Senha:</label>
								<input type="password" name="senha" class="form-control text-center" required>
							</div>
							<button type="submit" class="botao-primario text-center container-fluid mt-3 mb-5">Entrar</button>

						</form>

					</main>

					<a class="nav-link active text-light cadastrar mb-5" aria-current="page"
					   href="../paginas/cadastrar.php">
						Cadastrar
					</a>

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
</body>
</html>