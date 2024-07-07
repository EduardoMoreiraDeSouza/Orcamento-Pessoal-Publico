<?php

require_once __DIR__ . "/./EditarReceita.php";
require_once __DIR__ . "/./ExcluirReceita.php";
require_once __DIR__ . "/./ExcluirGasto.php";

class ExcluirAvancado extends EditarReceita
{
	public function __construct() {

		if (!isset($_POST['excluir']))
			return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);

		$tipoExclusao  = $_POST['excluir'];

		if (isset($_POST['id_receita'])) {

			$excluir = new ExcluirReceita();

			if ($tipoExclusao == 'este') {
				$excluir-> ExcluirReceita($_POST['id_receita']);
				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}

			elseif ($tipoExclusao == 'todos') {

				$idInterno = $this-> ObterDadosReceita($this-> getSessao(), $_POST['id_receita'])[0]['id_interno_receita'];
				$receitaReferente = $this-> ObterDadosReceita($this-> getSessao(), $idInterno, 'interno');

				$contador = 0;
				foreach ($receitaReferente as $ignored) {
					$excluir-> ExcluirReceita($receitaReferente[$contador]['id_receita']);
					$contador++;
				}

				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}

			else {

				$idInterno = $this -> ObterDadosReceita($this -> getSessao(), $_POST['id_receita'])[0]['id_interno_receita'];
				$receitas = $this -> ObterDadosReceita($this -> getSessao(), $idInterno, 'interno');
				$data = $this -> ObterDadosReceita($this -> getSessao(), $_POST['id_receita'])[0]['dataCompraPagamento'];

				$quantidade = 0;
				foreach ($receitas as $ignored) {
					if ($receitas[$quantidade]['dataCompraPagamento'] >= $data) {
						$excluir-> ExcluirReceita($receitas[$quantidade]['id_receita']);
					}
					$quantidade++;
				}

				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}
		}

		else {

			$excluir = new ExcluirGasto();

			if ($tipoExclusao == 'este') {
				$excluir-> ExcluirGasto($_POST['id_gasto']);

				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}

			elseif ($tipoExclusao == 'todos') {

				$idInterno = $this-> ObterDadosGastos($this-> getSessao(), $_POST['id_gasto'])[0]['id_interno_gasto'];
				$gastoReferente = $this-> ObterDadosGastos($this-> getSessao(), $idInterno,null, 'interno');

				$contador = 0;
				foreach ($gastoReferente as $ignored) {
					$excluir-> ExcluirGasto($gastoReferente[$contador]['id_gasto']);
					$contador++;
				}

				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}

			else {

				$idInterno = $this -> ObterDadosGastos($this -> getSessao(), $_POST['id_gasto'])[0]['id_interno_gasto'];
				$gastos = $this -> ObterDadosGastos($this -> getSessao(), $idInterno, null,'interno');
				$data = $this -> ObterDadosGastos($this -> getSessao(), $_POST['id_gasto'])[0]['dataCompraPagamento'];

				$quantidade = 0;
				foreach ($gastos as $ignored) {
					if ($gastos[$quantidade]['dataCompraPagamento'] >= $data) {
						$excluir-> ExcluirGasto($gastos[$quantidade]['id_gasto']);
					}
					$quantidade++;
				}

				return (bool) $this-> RetornarErro($_SESSION['pagina_pai'], null);
			}
		}
	}
}