<?php
	require_once 'classes/character.php';
	require_once 'classes/characterclass.php';
	require_once 'elements/session.php';

	//page header
	include 'html/header.html';
	
    $step = $_POST['step'];
	
	if ($_POST['clear'] == "true")
	{
		$_SESSION["currentCharacter"] = NULL;
	}
	
    $currentChar = currentCharacter();
	
	//process POSTed updates to the sheet
	if (isset($_POST['gender']))
	{
		if ($_POST['gender'] == "0")
			$currentChar->gender = "Neither";
		else if ($_POST['gender'] == "1")
			$currentChar->gender = "Male";
		else if ($_POST['gender'] == "2")
			$currentChar->gender = "Female";
	}
	
	function getPostedMultiLine($val, &$var)
	{
		if (isset($val))
		{		
			$var = split("\n", $val);
			
			//remove \r too
			foreach($var as $i => &$string)
			{
				$string = ereg_replace("\r", "", $string);
			}
		}
	}
	
	getPostedMultiLine($_POST['notes'], $currentChar->notes);
	getPostedMultiLine($_POST['inventory'], $currentChar->inventory);
	
	function getPostedUpdate($varName, &$target)
	{
		$var = $_POST[$varName];
		
		if (isset($var))
		{
			$target = $var;
		}
	}
	
	function getPostedUpdateText($varName, &$target)
	{
		$var = $_POST[$varName];
		
		if (isset($var))
		{
			$target = ucwords($var);
		}
	}
	
	getPostedUpdateText('name', $currentChar->name);
	getPostedUpdateText('deity', $currentChar->deity);
	//getPostedUpdateText('class', $currentChar->characterclass);
	
	$postedClass = $_POST['class'];
	if (isset($postedClass))
	{
		$currentChar->levels = array();
		//$postedClass => ClassLevel::$classes[$postedClass][1]);
		$currentChar->addLevel($postedClass);
	}
	
	$postedRace = $_POST['race'];
	if (isset($postedRace)
		&& $postedRace !== strtolower($currentChar->race)) //check if the race has changed
	{
		$currentChar->race = ucwords($postedRace);
		
		//reset abilities on race change
		$currentChar->str = NULL;
		$currentChar->dex = NULL;
		$currentChar->con = NULL;
		$currentChar->int = NULL;
		$currentChar->wis = NULL;
		$currentChar->cha = NULL;
		$currentChar->bonusStr = NULL;
		$currentChar->bonusDex = NULL;
		$currentChar->bonusCon = NULL;
		$currentChar->bonusInt = NULL;
		$currentChar->bonusWis = NULL;
		$currentChar->bonusCha = NULL;
	}

	if ($_POST['abilities_valid'] == "true")
	{
		getPostedUpdate('str', $currentChar->str);
		getPostedUpdate('dex', $currentChar->dex);
		getPostedUpdate('con', $currentChar->con);
		getPostedUpdate('int', $currentChar->int);
		getPostedUpdate('wis', $currentChar->wis);
		getPostedUpdate('cha', $currentChar->cha);
		
		getPostedUpdate('bonus_str', $currentChar->bonusStr);
		getPostedUpdate('bonus_dex', $currentChar->bonusDex);
		getPostedUpdate('bonus_con', $currentChar->bonusCon);
		getPostedUpdate('bonus_int', $currentChar->bonusInt);
		getPostedUpdate('bonus_wis', $currentChar->bonusWis);
		getPostedUpdate('bonus_cha', $currentChar->bonusCha);
	}
	
	//function shamelessly stolen from Stack Overflow
	function objectToObject($instance, $className)
	{
		return unserialize(sprintf(
			'O:%d:"%s"%s',
			strlen($className),
			$className,
			strstr(strstr(serialize($instance), '"'), ':')
		));
	}
	
	$charJson = stripslashes($_POST['char_json']);
	$charData = json_decode($charJson);
	
	if ($charData != FALSE)
	{		
		$currentChar = objectToObject($charData, "Character");
		//have to manually convert the inner arrays..
		$currentChar->levels = get_object_vars($currentChar->levels);
		
		$_SESSION['currentCharacter'] = $currentChar;
	}

	switch ($step)
	{
		case 'race':
			include 'elements/set_race.php';
		break;
		
		case 'class':
			include 'elements/set_class.php';
		break;
		
		case 'gender':
			include 'elements/set_gender.php';
		break;
		
		case 'abilities':
			include 'elements/set_abilities.php';
		break;
		
		case 'skills':
			include 'elements/set_skills.php';
		break;
	
		default:
		{
			//start page
			echo '<p class="body-text">Welcome to the Pathfinder character creation wizard.</p>';
			echo '<p class="body-text">Any fields left blank during the character creation process can be filled in by the DM.</p>';
			echo '<p class="body-text">Your current character:</p>';
			
			include 'html/get_started.html';
			?>
			<br />
			<?php
			
			include 'elements/character-table.php';
			
			include 'html/get_started.html';
			?>
			<br />
			<?php
		}
	}
	
	//page footer
	include 'html/footer.html';
?>