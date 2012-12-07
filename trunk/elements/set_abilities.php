<?php
	require_once "classes/character.php";
	require_once "elements/session.php";

	$racialBonuses = array
	(
		"human"=>array(),
		
		"dwarf"=>array(
					"con"=>2,
					"wis"=>2,
					"cha"=>-2,
					),
		
		"elf"=>array(
					"dex"=>2,
					"int"=>2,
					"con"=>-2,
					),
					
		"half-elf"=>array(),
		
		"half-orc"=>array(),
		
		"halfling"=>array(
					"dex"=>2,
					"cha"=>2,
					"str"=>-2,
					),
					
		"gnome"=>array(
					"con"=>2,
					"cha"=>2,
					"str"=>-2,
					),
	);
	
	$racesWithFreeBonus = array
	(
		"human",
		"half-elf",
		"half-orc",
	);
	
	$char = currentCharacter();
	$race = strtolower($char->race);
	
	$hasFreeBonus = !(array_search($race, $racesWithFreeBonus) === FALSE);
	
	function getRacialBonus($stat)
	{
		global $race, $racialBonuses;
		
		$charRaceBonuses = $racialBonuses[$race];
		
		if ($charRaceBonuses != NULL)
		{
			$bonus = $charRaceBonuses[$stat];
			
			return $bonus;			
		}
		else
		{
			return 0;
		}
	}
	
	function bonusValToSignedString($val)
	{
		$bonusString = "";
		
		if ($val > 0)
			$bonusString = "+";
			
		return $bonusString . $val;
	}
	
	function emitHiddenInput($abilityName, $initVal)
	{	
		echo "<input type=\"hidden\" id=\"${abilityName}\" name=\"${abilityName}\" value=\"${$initVal}\" />";
	}

	function emitAbilitySpinnerTD($abilityName, $valOnCharacter, $fullName)
	{
		global $char;
		
		//padding column
		echo "<td width=\"8\" class=\"table-header\">&nbsp;</td>";
		
		//stat full name label
		echo "<td width=\"100\" align=\"right\" class=\"table-header\">";
		echo $fullName;
		echo "&nbsp;&nbsp;</td>";
		
		//padding colun
		echo "<td width=\"8\" class=\"table-header\">&nbsp;</td>";
		
		//base value input spinner	
		if (isset($valOnCharacter))
			$initialSpinnerValue = $valOnCharacter;
		else
			$initialSpinnerValue = 10;
		
		echo "<td class=\"body-text table\" width=\"50\">";
		$id = $abilityName . "Spinner";
		echo "	<input class=\"value-table\" style=\"text-align:right; padding-right: 4px;\" type=\"number\" min=\"8\" max=\"18\" value=\"${initialSpinnerValue}\" id=\"${id}\" onchange=\"validate(this); doUpdateTotals()\" />";
		echo "</td>";
		
		//padding column
		echo "<td width=\"4\" class=\"table-header\">&nbsp;</td>";
		
		//cost to upgrade column
		$upgradeCostID = $abilityName . "Upgrade";
		echo "<td class=\"value-table table-header\" style=\"font-color:FFF; text-align: center; \" width=\"60\" id=\"${upgradeCostID}\">";
		echo "	0";
		echo "</td>";
		
		//padding columns
		echo "<td width=\"4\" class=\"table-header\">&nbsp;</td>";
		echo "<td width=\"20\" class=\"body-text\">&nbsp;</td>";
		
		//racial bonuses
		global $hasFreeBonus;
		
		//regular fixed racial bonus
		if (!$hasFreeBonus)
		{
			$bonus = getRacialBonus($abilityName);
			$bonusAbs = (abs($bonus));
			
			$bonusSign = "";
			if ($bonus >= 0)
				$bonusSign = "+";
			else
				$bonusSign = "-";
			
			global $race;
	
			echo '<td class="value-table" width="110" align="center">';
			echo '	<table width="60" border="0" cellpadding="0">';
			echo '		<tr>';
			echo '			<td width="50%" class="value-table">';
			echo "				${bonusSign}";
			echo '			</td>';
			echo '			<td width="50%" class="value-table">';
			echo "				${bonusAbs}";
			echo '			</td>';
			echo '		</tr>';
			echo '	</table>';
			echo '</td>';
		}
		else //human-style free +2 bonus
		{
			$raceBonusRadioID = $abilityName . 'FreeBonus';
			$raceBonusLabelID = $abilityName . 'BonusLabel';
			
			//is something already selected? if so it'll have a non-zero ability score (just assume for now that only one ability will have this)
			$charProperties = get_object_vars($char);
			$checked = ($charProperties["bonus" .$abilityName] > 0? "checked=\"checked\"" : "");
			
			echo "<td class=\"value-table\" width=\"110\" align=\"center\">${bonusSign}";
			echo "	<input type=\"radio\" name=\"racialbonus\" class=\"vertical-middle\" id=\"${raceBonusRadioID}\" onchange=\"doUpdateTotals()\" ${checked}/>";
			echo "	<label class=\"vertical-middle\" for=\"${raceBonusRadioID}\" id=\"${raceBonusLabelID}\">0</label>";
			echo "</td>";
		}
		
		//padding column
		echo "<td width=\"20\" class=\"body-text\">&nbsp;</td>";
		
		//totals column
		$totalLabelID = $abilityName . "Total";
		//echo "<td width=\"100\" align=\"right\" class=\"body-text\" style=\"font-size: 16px; font-weight:bold;\" id=\"${totalLabelID}\">0</td>";
		echo '<td class="value-table" width="110" align="right">';
			echo '	<table width="60" border="0" cellpadding="0">';
			echo '		<tr>';
			echo '			<td width="50%" class="value-table" align="right">';
			echo "				=";
			echo '			</td>';
			echo '			<td width="50%" class="value-table" align="right">';
			echo "				<span id=\"${totalLabelID}\"></span>";
			echo '			</td>';
			echo '		</tr>';
			echo '	</table>';
			echo '</td>';
		
		echo "<td class=\"body-text\">&nbsp;</td>";
		
		//skill bonus column
		$skillBonusID = $abilityName . "SkillMod";
		echo "<td class=\"value-table\" width=\"50\" alight=\"right\">";
		echo "	<span style=\"text-align:right\" id=\"$skillBonusID\">0</span>";
		echo '</td>';
		echo '<td width="40">&nbsp;</td>';
	}
?>

<script language="javascript">
	var startingPoints = 10;
	var stats = ["str", "dex", "con", "int", "wis", "cha"];
	
	var race = "<?php echo $race; ?>";
	var hasFreeBonus = <?php echo $hasFreeBonus? "true" : "false"; ?>;

	var baseRacialBonus = new Array();
	<?php
		function printRacialBonusArrayEntry($stat)
		{
			$val = getRacialBonus($stat);
			if (!isset($val))
				$val = 0;
				
			echo "baseRacialBonus['${stat}'] = ${val};";
		}
		
		printRacialBonusArrayEntry("str");
		printRacialBonusArrayEntry("dex");
		printRacialBonusArrayEntry("con");
		printRacialBonusArrayEntry("int");
		printRacialBonusArrayEntry("wis");
		printRacialBonusArrayEntry("cha");
	?>

	function getCostOfNextUpgrade(val)
	{
		var checkVal = parseInt(val) + 1;
		
		if (checkVal <= 10)
		{
			return 1;
		}
		else
		{
			var costOfNextUpgrade = 0;
			for (var i = 10;
				i < checkVal;
				++i)
			{
				if (i%2 == 0)
					costOfNextUpgrade += 1;
			}
			
			return costOfNextUpgrade;
		}
	}

	function getPointsSpentOnStat(val)
	{
		if (val > 10)
		{
			//incrementally..
			var costOfNextUpgrade = 0;
			var totalCost = 0;
			for (var i = 10;
				i < val;
				++i)
			{
				if (i%2 == 0)
					costOfNextUpgrade += 1;
					
				totalCost += costOfNextUpgrade;
			}
			
			return totalCost;
		}
		else
		{
			return val - 10;
		}		
	}
	
	function getSpinnerValue(stat)
	{
		var spinnerName = stat + "Spinner";
		
		return document.getElementById(spinnerName).value;
	}

	function getRemainingPoints()
	{
		var totalSpent = 0;
		totalSpent += getPointsSpentOnStat(getSpinnerValue('str'));
		totalSpent += getPointsSpentOnStat(getSpinnerValue('dex'));
		totalSpent += getPointsSpentOnStat(getSpinnerValue('con'));
		totalSpent += getPointsSpentOnStat(getSpinnerValue('wis'));
		totalSpent += getPointsSpentOnStat(getSpinnerValue('int'));
		totalSpent += getPointsSpentOnStat(getSpinnerValue('cha'));
		
		return startingPoints - totalSpent;
	}
	
	function validate(spinner)
	{
		if (spinner.value < 8)
			spinner.value = 8;
		if (spinner.value > 18)
			spinner.value = 18;
	}
	
	//oh god.. guess we need a js version of this function too
	//adds sign to positive string for printed bonus values, ie +1
	function bonusValToSignedString(val)
	{
		return val > 0? "+" +val : val.toString();
	}
	
	//...and this one
	function abilityScoreToSkillBonus(abilityVal)
	{
		return Math.floor((abilityVal - 10) / 2);
	}
	
	function doUpdateTotals()
	{
		var remaining = getRemainingPoints();
		var remainingLabel = document.getElementById("remainingPoints");
		remainingLabel.innerHTML = remaining;
		
		//validate
		var valid = (remaining >= 0);
		
		var humanBonusStat = null;
		
		for (var statIt in stats)
		{
			var stat = stats[statIt];
			
			var statUpgradeLabelID = stat + "Upgrade";
			
			var freeBonus = 0;
			
			<?php if ($hasFreeBonus === TRUE): ?>
				//update the bonus selector
				var bonusRadioBtnID = stat + "FreeBonus";
				var bonusRadioBtn = document.getElementById(bonusRadioBtnID);
				var bonusLabelID = stat + "BonusLabel";
				var bonusLabel = document.getElementById(bonusLabelID);
				
				if (bonusRadioBtn.checked)
				{
					freeBonus = 2;
					bonusLabel.innerHTML = "+2";
					
					humanBonusStat = stat;
				}
				else
				{
					bonusLabel.innerHTML = "--";
				}
			<?php endif; ?>
			
			var spinnerValue = getSpinnerValue(stat);
			var baseValue = parseInt(spinnerValue);
			var bonusValue = parseInt(baseRacialBonus[stat]) + freeBonus;
			
			var statTotal = baseValue + bonusValue;
			
			var upgradeLabel = document.getElementById(statUpgradeLabelID);
			upgradeLabel.innerHTML = getCostOfNextUpgrade(spinnerValue);			
			
			//update hidden inputs if valid
			if (valid)
			{
				var statInput = document.getElementById(stat);
				var statBonusInput = document.getElementById("bonus_" + stat);
				
				statInput.value = baseValue;
				statBonusInput.value = bonusValue;
			}
			
			//update total label regardless
			var statTotalLabelName = stat + "Total";
			var statTotalLabel = document.getElementById(statTotalLabelName);
			statTotalLabel.innerHTML = statTotal;
			
			//update skill bonus based on total
			var skillBonusLabelID = stat + "SkillMod";
			var skillBonusLabel = document.getElementById(skillBonusLabelID);
			skillBonusLabel.innerHTML = bonusValToSignedString(abilityScoreToSkillBonus(statTotal));
		}
		
		var validElement = document.getElementById("abilities_valid");		
		if (valid)
			validElement.value = "true";
		else
			validElement.value = "false";
		
		//update error and hint labels
		var errorsLabel = document.getElementById("totalsErrors");
		errorsLabel.innerHTML = "";
		
		if (remaining < 0)
			errorsLabel.innerHTML = "Warning: You have spent too many points. If you navigate away from this page your changes will not be saved.";
		
		var hintsLabel = document.getElementById("totalsHints");
		hintsLabel.innerHTML = "";
		
		var hintNewline = false;
		
		<?php if ($hasFreeBonus === TRUE): ?>		
			if (humanBonusStat == null)
			{
				hintsLabel.innerHTML += "Hint: You have not spent your +2 bonus from being human or part-human on any stat.";
				hintNewline = true;
			}
		<?php endif; ?>
		
		if (remaining > 0)
		{
			if (hintNewline)
			{
				hintsLabel.innerHTML += "<br />";
				hintsNewline = false;
			}
							
			hintsLabel.innerHTML += "Hint: You have not spent all of your points.";
		}
	}
</script>

<form action="wizard.php" method="post">
	<?php
    	include "elements/nav_buttons.php";
		
		emitHiddenInput("str", $char->str);
		emitHiddenInput("dex", $char->dex);
		emitHiddenInput("con", $char->con);
		emitHiddenInput("int", $char->int);
		emitHiddenInput("wis", $char->wis);
		emitHiddenInput("cha", $char->cha);
		
		emitHiddenInput("bonus_str", $char->bonusStr);
		emitHiddenInput("bonus_dex", $char->bonusDex);
		emitHiddenInput("bonus_con", $char->bonusCon);
		emitHiddenInput("bonus_int", $char->bonusInt);
		emitHiddenInput("bonus_wis", $char->bonusWis);
		emitHiddenInput("bonus_cha", $char->bonusCha);
				
		emitHiddenInput("abilities_valid", "false");
	?>
    
    <table class="table" border="0" cellpadding="0" cellspacing="0" width="800">   
    	<tr height="34" valign="middle">
        	<td colspan="100" class="table-header">
            	&nbsp;&nbsp;Choose your base ability values
				<?php
					if (!$hasFreeBonus)
						echo '. Modifiers from race are applied afterwards.';
					else
						echo ' and select an ability score to apply your racial +2 bonus to.';
				?>
			</td>
        </tr>
        <tr>
        	<td colspan="3" class="table-header"></td>
        
        	<td colspan="1" class="table-header" align="center">
            	Base Value
            </td>
            
            <td class="table-header" colspan="1">
            </td>
            
            <td class="table-header" colspan="1" align="center">
            	Cost to Upgrade
            </td>
            
            <td class="table-header" colspan="2"></td>
            
            <td align="center" class="table-header" colspan="1">
            	<?php 
					if ($hasFreeBonus)
						echo "Select stat for racial +2";
					else
						echo "Racial bonus";
				?>
            </td>
            
            <td class="table-header" colspan="1"></td>
            
            <td class="table-header" align="right">
            	Total
            </td>
			
			<td class="table-header" align="right" colspan="2">
				Resulting Skill Modifier
			</td>
            
            <td class="table-header" colspan="1000">
            </td>
        </tr>
        <tr>
			<?php emitAbilitySpinnerTD("str", $char->str, "Strength"); ?>
        </tr>
        
		<tr>
        	<?php emitAbilitySpinnerTD("dex", $char->dex, "Dexterity"); ?>
        </tr>
        
        <tr>
            <?php emitAbilitySpinnerTD("con", $char->con, "Constitution"); ?>
        </tr>
        
        <tr>
            <?php emitAbilitySpinnerTD("int", $char->int, "Intelligence"); ?>
        </tr>
        
        <tr>
            <?php emitAbilitySpinnerTD("wis", $char->wis, "Wisdom"); ?>
        </tr>
        
        <tr>
            <?php emitAbilitySpinnerTD("cha", $char->cha, "Charisma"); ?>
        </tr>
        
        <tr height="60">
        	<td colspan="3" align="right" class="table-header">
            	Points free to spend
            </td>
            
            <td colspan="2" id="remainingPoints" class="table-header" style="font-size: 36px; font-weight: bold; text-align: center">
            	0
            </td>
            
            <td class="table-header" colspan="2"></td>
        </tr>
    </table>
    
    <?php include "elements/nav_buttons.php"; ?>
</form>

<p class="body-text">
    <span style="color:#F00; font-size:14px;" id="totalsErrors"></span>
    <span style="color:#F93; font-size:14px;" id="totalsHints"></span>
</p>

<script language="javascript">
	document.body.onload = doUpdateTotals();
</script>