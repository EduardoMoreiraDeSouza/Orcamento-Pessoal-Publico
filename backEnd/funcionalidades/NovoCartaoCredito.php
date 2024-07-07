<?php

require_once __DIR__ . "/./NovoBancoCorretora.php";

class NovoCartaoCredito extends NovoBancoCorretora
{
    private $limite;
    private $fechamento;
    private $vencimento;
	private $id;


    public function __construct()
    {
        if (!$this-> VerificarLogin()) return false;

        $this-> setId($this-> id());
        $this-> setLimite($this-> valor());
        $this-> setFechamento($this-> fechamento());
        $this-> setVencimento($this-> vencimento());

        if (
            !$this-> getId() or
            !$this-> getLimite() or
            !$this-> getFechamento() or
            !$this-> getVencimento()
        )
            return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], null);

        elseif ($this-> getFechamento() == $this-> getVencimento())
            return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'fechamentoVencimento');

        elseif ($this-> ObterDadosCartoesCredito($this-> getId(), $this-> getSessao()))
            return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], 'x2cartoesCredito');

	    if ($this-> ObterDadosBancosCorretoras($this-> getId(), $this-> getSessao())[0]['bancoCorretora'] == 'Reserva')
		    return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'cartaoReserva');

        elseif (!$this-> EntradaDadosCartoesCredito(
			$this-> getId(),
            $this-> getLimite(),
            $this-> getFechamento(),
            $this-> getVencimento())
        )
            return (bool)$this-> RetornarErro($_SESSION['pagina_pai'], null);

        return !$this-> RetornarErro($_SESSION['pagina_pai'], null);
    }

    protected function setLimite($limite): void
    {
        $this-> limite = $limite;
    }

    protected function getLimite()
    {
        return $this-> limite;
    }

    protected function setFechamento($fechamento): void
    {
        $this-> fechamento = $fechamento;
    }

    protected function getFechamento()
    {
        return $this-> fechamento;
    }

    protected function setVencimento($vencimento): void
    {
        $this-> vencimento = $vencimento;
    }

    protected function getVencimento()
    {
        return $this-> vencimento;
    }
	protected function getId()
	{
		return $this -> id;
	}

	protected function setId($id): void
	{
		$this -> id = $id;
	}

}