<?php
class cookie
{

	function set($nome,$valore,$scadenza = false,$path = '/')
	{
		if($scadenza) $t =  time() + $scadenza;
		else $t =  time() + 3600 * 24 * 7;
		setcookie($nome, $valore, $t,$path);
	$_COOKIE[$nome] = $valore;
	}

	function get($nome)
	{
		if(isset($_COOKIE[$nome]))
			return $_COOKIE[$nome];
		else return false;
	}

	function noset($nome)
	{
		setcookie($nome,'0',time () - 85000);
		unset($_COOKIE[$nome]);
	}

}
?>