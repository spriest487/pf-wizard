<?php
	session_name('pf-character');
	session_start();
	
	function currentCharacter()
	{
		$char = $_SESSION['currentCharacter'];
		
		if (isset($char) && $char != 0)
		{
			return $char;
		}
		else
		{
			$char = new Character();
			$_SESSION['currentCharacter'] = $char;
			
			return $char;
		}
	}
?>