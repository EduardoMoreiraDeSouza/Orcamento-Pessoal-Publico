<?php

require_once __DIR__ . "/../gerais/ValorFinal.php";

abstract class Formulario extends ValorFinal
{
    private $dados;

    protected function bancoCorretora()
    {
        $this->setDados($this->fraseMaiuscula($this->fraseMinuscula($this-> antiInjecaoMySql(addslashes($_POST['bancoCorretora'])))));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function nome()
    {
        $this->setDados($this-> fraseMaiuscula($this-> antiInjecaoMySql(addslashes($_POST['nome']))));

		if (strlen($this-> getDados()) <= 0 or strlen($this-> getDados()) > 60)
			return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'nome');

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function classificacao()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['classificacao'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function dataCompraPagamento()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['dataCompraPagamento'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function fechamento()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['fechamento'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function vencimento()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['vencimento'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function id()
    {
        $this->setDados($this->somenteNumeros($this-> antiInjecaoMySql(addslashes($_POST['id']))));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

	protected function bancoCorretoraId()
	{
		$this->setDados($this-> antiInjecaoMySql(addslashes($_POST['bancoCorretoraId'])));

		if ($this->dadosDefinidos())
			return $this->getDados();

		return false;
	}

    protected function valor()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['valor'])));

        if ($this->dadosDefinidos()) {

			$this-> setDados(str_replace('-', '', $this-> getDados()));

			if (!$this-> formatarValorDB($this-> getDados()))
		        return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'valorInvalido');

	        if (str_contains($this->getDados(), '*'))
                $this->setDados(number_format($this->formatarValorDB($this->getDados()) / $this->parcelas(), 2, '.', ''));
			else
				$this-> setDados($this-> formatarValorDB($this->getDados()));

            return $this->getDados();
        }

        return false;
    }

    protected function parcelas()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['parcelas'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

	protected function tipoParcelas()
	{
		$this->setDados($this-> fraseMinuscula($this-> antiInjecaoMySql(addslashes($_POST['tipoParcelas']))));

		if ($this->dadosDefinidos())
			return $this->getDados();

		return false;
	}

	protected function tipoAlteracao()
	{
		$this->setDados($this-> fraseMinuscula($this-> antiInjecaoMySql(addslashes($_POST['tipoAlteracao']))));

		if ($this->dadosDefinidos())
			return $this->getDados();

		return false;
	}

	protected function formaPagamento()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['formaPagamento'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function saldo()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['saldo'])));

        if (!$this-> formatarValorDB($this->getDados()))
            return false;

        return $this->formatarValorDB($this->getDados());
    }

    protected function email()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['email'])));

        if ($this->dadosDefinidos())
            return $this->getDados();

        return false;
    }

    protected function senha()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['senha'])));

        if (!$this->dadosDefinidos())
            return false;

        elseif (strlen($this->getDados()) >= 8) {
            $this->setDados(hash('sha256', $this->getDados()));
            return $this->getDados();
        }

        return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'senhaPequena');
    }

    protected function confirmarSenha()
    {
        $this->setDados($this-> antiInjecaoMySql(addslashes($_POST['confirmarSenha'])));

        if (!$this->dadosDefinidos())
            return false;

        elseif (strlen($this->getDados()) >= 8) {
            $this->setDados(hash('sha256', $this->getDados()));

            if ($this->getDados() != $this->senha())
                return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'confirmarSenhaErro');

            return $this->getDados();
        }

        return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'senhaPequena');
    }


    private function dadosDefinidos()
    {
		$this-> iniciarSessao();
	    if (!isset($_SESSION['pagina_pai']))
		    $_SESSION['pagina_pai'] = 'inicio';

        if (!$this->getDados() or empty($this->getDados())) {
            $this->setDados(null);

            return (bool)$this->RetornarErro($_SESSION['pagina_pai'], 'vazio');
        }

        return true;
    }

    private function getDados()
    {
        return $this->dados;
    }

    private function setDados($dados): void
    {
        $this->dados = $dados;
    }

	private function antiInjecaoMySql($dados) {
		$dados = $this-> fraseMinuscula($dados);

		$codigosMySql = [
			'select ',
			' from ',
			' or ',
			' like ',
			' and ',
			'delete',
			'drop',
			'update',
			'database',
			';'
		];

		$contador = 0;
		foreach ($codigosMySql as $ignored) {
			$dados = str_replace($codigosMySql[$contador], '', $dados);
			$contador++;
		}

		return $this-> fraseMaiuscula($dados);
	}

}
