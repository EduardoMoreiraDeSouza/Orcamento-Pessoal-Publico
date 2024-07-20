<?php

require_once __DIR__ . "/./NovoDebito.php";

class NovoCredito extends NovoDebito
{
	public function __construct()
	{
		if (!$this -> VerificarLogin())
			return false;

		$this -> setId($this -> id());
		$this-> setNome($this -> nome());
		$this -> setClassificacao($this -> classificacao());
		$this -> setDataCompraPagamento($this -> dataCompraPagamento());
		$this -> setValor($this -> valor());
		$this -> setParcelas($this -> parcelas());

		if (
			!$this -> getId() or
			!$this -> getNome() or
			!$this -> getClassificacao() or
			!$this -> getDataCompraPagamento() or
			!$this -> getValor() or
			!$this -> getParcelas()
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		if (!$this -> ObterDadosCartoesCredito($this -> getId(), $this -> getSessao()))
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'naoBancoCorretora');

		if ($this -> getValor() < 0)
			$this -> setValor($this -> getValor() * -1);

		if ($this -> ValorFinal('cartaoCredito', $this -> getId()) < $this -> getValor() * $this -> getParcelas())
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'limiteInsuficiente');

		$idInterno = random_int(1, 99999);
		while ($this -> ObterDadosGastos($this -> getSessao(), $idInterno, null, 'interno')) {
			$idInterno = random_int(1, 99999);
		}

		if (
			!$this -> EntradaDadosGastos(
				$idInterno,
				$this -> getId(),
				$this-> getNome(),
				'Crédito',
				$this -> getClassificacao(),
				$this -> getDataCompraPagamento(),
				$this -> getValor(),
				$this -> getParcelas()
			)
		)
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], null);

		return !$this -> RetornarErro($_SESSION['pagina_pai'], null);
	}
}