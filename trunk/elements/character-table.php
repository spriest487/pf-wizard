<?php
	require_once 'classes/character.php';
	require_once 'classes/characterclass.php';
	require_once 'elements/session.php';

	function emitJumpToStepButton($step)
	{
		echo "<form action=\"wizard.php\" method=\"post\">";
		echo "	<button class=\"button\" type=\"submit\" value=\"${step}\" name=\"step\">";
		echo "Edit >></button>";
		echo "</form>";
	}
	
	function characterTableRow($attr, $val, $jump=NULL)
	{
		echo '<tr class="body-text char-table-row">';
		echo "	<td align=\"right\" width=\"250\" class=\"table-header\">${attr}&nbsp;&nbsp;</td>";
		echo "	<td class=\"table\" width=\"350\">${val}</td>";
		
		if (isset($jump))
		{
			echo "<td>";
			emitJumpToStepButton($jump);
			echo "</td>";
		}
		
		echo '</tr>';
	}
	
	function emitSectionRow($name)
	{
		echo "<tr><td align=\"center\" class=\"table-header\" colspan=\"2\">${name}</td></tr>";
	}	
?>

<style type="text/css">
	.ability-mod-text {
		font-size: 10px;
	}
	
	.char-text {
		width: 400px;
		height: 150px;
		overflow: auto;
		resize: vertical;
	}
	
	.stat-hint-text {
		font-size:10px;
		font-style:italic;
	}
	
	.AC-val {
		font-size: 18px;
		font-weight: bold;
	}
</style>

<table cellpadding="2" cellspacing="0">
	<?php emitSectionRow("Personal Info"); ?>
    
    <tr>        
        <?php
			$char = currentCharacter();
			characterTableRow("Race", ucwords($char->race), "race");
			
			if ((!isset($char->levels))
				|| count($char->levels) === 0)
			{
				characterTableRow("Classes", "None", "class");
			}
			
			$classCount = 1;			
			foreach ($char->levels as $class => $classLevel)
			{				
				echo "<br />";
				
				//only show the jump button for the first class in the list
				$classJump = $classCount === 1? "class" : NULL;
				
				$level = $char->getClassCurrentLevel($class);
				
				characterTableRow("Class " .$classCount, ucwords($class) . ": Level " .$level->levelIndex, $classJump);
				
				++$classCount;
			}
			
			characterTableRow("Name", ucwords($char->name), "gender");
			characterTableRow("Gender", ucwords($char->gender));
			characterTableRow("Deity", ucwords($char->deity));
		?>
    </tr>
    
    <?php emitSectionRow("Ability Scores"); ?>
    
	<?php		
		$char = currentCharacter();
		
		function abilityRow($fullname, $val, $jump = NULL)
		{
			if ($val == NULL)
				$cellContents = NULL;
			else
			{				
				$skillVal = Character::abilitySkillModifier($val);
				if ((integer) $skillVal > 0)
					$skillVal = "+" . $skillVal;
				
				$skillText = $skillVal;
				
				$cellContents = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="40" align="right">';
				$cellContents .= $val;
				$cellContents .= '</td><td class="ability-mod-text" width="140" align="right">';
				$cellContents .= '=> bonus to skills: ';
				$cellContents .= '</td><td align="right">';
				$cellContents .= $skillText;
				$cellContents .= '</td><td width="40">&nbsp;</td></tr></table>';
			}
			
			characterTableRow($fullname, $cellContents, $jump);				
		}
		
		function getTotal($val1, $val2)
		{
			if ($val1 !== NULL &&
				$val2 !== NULL)
			{
				return $val1 + $val2;
			}
			else
				return NULL;
		}
		
		abilityRow("Strength", getTotal($char->str, $char->bonusStr), "abilities");
		abilityRow("Dexterity", getTotal($char->dex, $char->bonusDex));
		abilityRow("Constitution", getTotal($char->con, $char->bonusCon));
		abilityRow("Intelligence", getTotal($char->int, $char->bonusInt));
		abilityRow("Wisdom", getTotal($char->wis, $char->bonusWis));
		abilityRow("Charisma", getTotal($char->cha, $char->bonusCha));
	?>
	
	<?php
		function rightAlignWithPadding($text)
		{
			return "<div class=\"value-text-ralign\">${text}</div>";
		}
		
		function charStatHint($text)
		{
			return "<span class=\"stat-hint-text\">${text}</span>";
		}
		
		function getHintedValueCell($text, $hint)
		{
			return "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td class=\"text-body\" width=\"48\" align=\"center\">
							$text
						</td>
						<td class=\"stat-hint-text\">
							$hint
						</td>
					</tr>
				</table>";
		}
		
		$strMOD = $char->strMod();
		$dexMOD = $char->dexMod();
	
		emitSectionRow("Combat");
		
		$bab = getHintedValueCell($char->getBaseAttackBonus(), "From class levels"); 
		characterTableRow("Base Attack Bonus", $bab);
		
		$meleeAB = getHintedValueCell($char->getMeleeAttackBonus(), "Base Attack + STR mod + size");
		characterTableRow("Melee Attack Bonus", $meleeAB);
		
		$rangeAB = getHintedValueCell($char->getRangedAttackBonus(), "Base Attack + DEX mod + size");
		characterTableRow("Ranged Attack Bonus", $rangeAB);	
		
		$cmBonus = getHintedValueCell($char->getCombatManeuverDefense(), " (Base Attack + STR mod + size)");
		characterTableRow("Combat Maneuver Bonus", $cmBonus);	
		
		$cmDef = getHintedValueCell($char->getCombatManeuverDefense(), " (Base Attack + STR mod + DEX mod + size + other)");
		characterTableRow("Combat Maneuver Defense", $cmDef);			
		
		emitSectionRow("Saving Throws");
		
		$naturalAC = 0;
		$deflectionAC = 0;
		$dodgeAC = 0;
		$enhancementAC = 0;
		$sizeAC = 0;
		
		$baseAC = getHintedValueCell($char->getArmorClass(), 
									"Formula: 10 + armor + shield + DEX mod<br />
									+$enhancementAC enhancement bonus from items<br />
									+$naturalAC from natural defences<br />
									+$dodgeAC from dodge bonuses<br />
									+$deflectionAC from magical deflection<br />
									+$sizeAC from size");
									
		$ffAC = getHintedValueCell($char->getFlatFootedArmorClass(),
									"AC minus DEX mod and all dodge bonuses");
									
		$touchAC = getHintedValueCell($char->getTouchArmorClass(),
									"AC minus everything but DEX mod and dodge bonuses");
									
		characterTableRow("Armor Class (AC)", rightAlignWithPadding($baseAC));
		characterTableRow("Flat-footed AC", rightAlignWithPadding($ffAC));
		characterTableRow("Touch AC", rightAlignWithPadding($touchAC));
		
		characterTableRow("Fortitude Save", rightAlignWithPadding($char->fortitudeSave()));
		characterTableRow("Reflex Save", rightAlignWithPadding($char->reflexSave()));
		characterTableRow("Will Save", rightAlignWithPadding($char->willSave()));
		
		emitSectionRow("Spells");
		
		$spellRowCount = 0;
		
		function emitSpellRow($level)
		{
			global $char;
			global $spellRowCount;
			
			$spellText = "Level $level Spells";
			$spellValue = $char->getSpellsAtLevel($level);
			
			if ($spellValue !== 0)			
			{
				characterTableRow($spellText, rightAlignWithPadding($spellValue));
				
				++$spellRowCount;
			}
		}
		
		for ($spell_it = 0;
			$spell_it <= 9;
			++$spell_it)
		{
			emitSpellRow($spell_it);
		}
		
		if ($spellRowCount === 0)
		{
			characterTableRow(NULL, "None");
		}
		
		emitSectionRow("Other fields");
	?>
	
	<tr>
		<td class="table-header" align="right">
			Class specials&nbsp;&nbsp;
		</td>
		<td class="body-text">
				<?php				
					foreach ($char->levels as $class => $level)
					{
						$specials = ClassLevel::getSpecialsAtLevel($class, $level);
						
						foreach ($specials as $k => $special)
						{
							echo $special;
							echo "<br />";
						}
					}
				?>
		</td>
	</tr>
	
	<form action="wizard.php" method="post">
		<tr>
			<td class="table-header" align="right">
				Inventory&nbsp;&nbsp;
			</td>
			<td>
				<textarea spellcheck="false" name="inventory" class="char-text"><?php
						foreach ($char->inventory as $idx => $item)
						{
							echo $item;
							echo "\n";
						}
					?></textarea>
			</td>
		</tr>
		<tr>
			<td class="table-header" align="right">
				Notes&nbsp;&nbsp;
			</td>
			<td>
				<textarea spellcheck="false" name="notes" class="char-text"><?php
						foreach ($char->notes as $idx => $item)
						{
							echo $item;
							echo "\n";
						}
					?></textarea>
			</td>
		</tr>
		
		<tr>
			<td class="table-header" align="center">&nbsp;
			
			</td>
			<td align="center">
				<button type="submit" class="button">Update details</button>
			</td>
		</tr>
	</form>
</table>
<br />


<p class="body-text">
	The following is the JSON code for this character. Save this if you want a local copy of your character.
</p>
<p class="body-text">
	To load a previously created character paste the JSON code into this area and press the button to the right.
</p>

<form action="wizard.php" method="post">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<textarea spellcheck="false" class="char-text" name="char_json">
					<?php
						$jsonChar = json_encode($char);
						echo $jsonChar;
					?>
				</textarea>
			</td>
			<td valign="top">
				<button class="button" type="submit">Submit JSON</button>
			</td>
		</tr>
	</table>
</form>
&nbsp;
