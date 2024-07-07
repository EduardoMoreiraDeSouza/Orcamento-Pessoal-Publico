<?php

require_once __DIR__ . "/../verificacoes/VerificarSenha.php";

class FormatacaoDados extends VerificarSenha
{
	public function somenteNumerosPontos($numeros)
	{
		return trim(preg_replace('/[^0-9.,]/', '', $numeros));
	}

	public function somenteNumeros($numeros)
	{
		return trim(preg_replace('/[^0-9]/', '', $numeros));
	}

	public function somenteLetrasAcento($letras): int
	{
		return trim(preg_replace('/[^A-Za-zá-üÁ-Ü]/', '', $letras));
	}

	public function somenteLetras($letras): string
	{
		return trim(preg_replace('/[^A-Za-z]/', '', $letras));
	}

	public function fraseMaiuscula($frase): string
	{
		return trim(ucwords($frase));
	}

	public function fraseMinuscula($frase): string
	{
		return trim(strtolower($frase));
	}

	public function formatarValorDB($valor)
	{
		$negativo = false;

		if (
			substr_count($valor, ',') > 1 or str_contains(substr($valor, -3, 3), '.') or str_contains(
				substr($valor, -3, 3), '*'
			)
		) {
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'valorInvalido');
		}

		if (str_contains($valor, '-'))
			$negativo = true;

		$valor = str_replace('.', '', $valor);
		$valor = str_replace(',', '.', $this -> somenteNumerosPontos($valor));

		if ($negativo)
			$valor = (floatval($valor)) * -1;

		if (empty($valor))
			return 0;

		return number_format($valor, 2, '.', '');
	}

	public function formatarValor($valor): string
	{
		if (substr_count($valor, ',') > 1) {
			return (bool) $this -> RetornarErro($_SESSION['pagina_pai'], 'valorInvalido');
		}

		return number_format($valor, 2, ",", ".");
	}
}
