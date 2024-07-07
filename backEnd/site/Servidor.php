<?php

require_once __DIR__ . "/../verificacoes/VerificarLogin.php";

abstract class Servidor extends VerificarLogin
{
    private $servidor;
    private $usuario;
    private $senhaServidor;
    private $DBname;

    public function Servidor($informacao)
    {
        if ($this -> getLocalServidor() == 'local') {

            $this -> setServidor('localhost');
            $this -> setUsuario('root');
            $this -> setSenhaServidor('');
            $this -> setDBname('orcamentoPessoal');

        }

        elseif ($this -> getLocalServidor() == 'global') {

            $this -> setServidor('localhost');
            $this -> setUsuario('');
            $this -> setSenhaServidor('');
            $this -> setDBname('');

        }

        switch ($informacao) {
            case 'servidor':
                return $this -> getServidor();

            case 'usuario':
                return $this -> getUsuario();

            case 'senhaServidor':
                return $this -> getSenhaServidor();

            case 'dbName':
                return $this -> getDBname();

            default:
                break;
        }
    }

    protected function getServidor()
    {
        return $this -> servidor;
    }

    protected function setServidor($servidor): void
    {
        $this -> servidor = $servidor;
    }

    protected function getUsuario()
    {
        return $this -> usuario;
    }

    protected function setUsuario($usuario): void
    {
        $this -> usuario = $usuario;
    }

    protected function getSenhaServidor()
    {
        return $this -> senhaServidor;
    }

    protected function setSenhaServidor($senhaServidor): void
    {
        $this -> senhaServidor = $senhaServidor;
    }

    protected function getDBname()
    {
        return $this -> DBname;
    }

    protected function setDBname($DBname): void
    {
        $this -> DBname = $DBname;
    }
}
