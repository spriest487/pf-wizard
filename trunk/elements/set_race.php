<?php
	require_once 'elements/session.php';
	require_once 'func/image_table.php';
	
	$raceDescriptions = array
	(
		"human"=>"<ul>
					<li>+2 to any ability score</li>
					<li>One extra Feat at level 1</li>
					<li>One additional skill point per level</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/human\">(more info)</a>
				</div>",
		"dwarf"=>"<ul>
					<li>+2 Constitution, +2 Wisdom, -2 Charisma</li>
					<li>Never slowed by heavy armor or encumbrance</li>
					<li>Bonuses against poison, magic and Giants</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/dwarf\">(more info)</a>
				</div>",
		"elf"=>"<ul>
					<li>+2 Dexterity, +2 Intelligence, -2 Constitution</li>
					<li>Immune to magical sleep effects, bonus against enchantments</li>
					<li>Bonus to spell penetration and spell identification</li>
					<li>Can see twice as far as a human in the dark</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/elf\">(more info)</a>
				</div>",
		"half-elf"=>"<ul>
					<li>+2 to any ability score</li>
					<li>Immune to magical sleep effects, bonus against enchantments</li>
					<li>Bonuses for taking more than one class</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/half-elf\">(more info)</a>
				</div>",
		"half-orc"=>"<ul>
					<li>+2 to any ability score</li>
					<li>Bonus to intimidation</li>
					<li>Continues to fight for an extra round after losing all hitpoints</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/half-orc\">(more info)</a>
				</div>",
		"halfling"=>"<ul>
					<li>+2 Dexterity, +2 Charisma, -2 Strength</li>
					<li>Bonus to saving throws, extra bonus against fear</li>
					<li>Bonuses to acrobatics, perception and climbing</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/halfling\">(more info)</a>
				</div>",
		"gnome"=>"<ul>
					<li>+2 Constitution, +2 Charisma, -2 Strength</li>
					<li>Bonuses against illusion spells and Giants</li>
					<li>Bonus to casting Illusion spells</li>
					<li>Innate magic based on Charisma</li>
				</ul>
				<div align=\"center\">
				<a href=\"http://www.d20pfsrd.com/races/core-races/gnome\">(more info)</a>
				</div>",
	);

	function emitRaceTD($race)
	{
		global $raceDescriptions;
		
		$char = currentCharacter();
		
		$selected = (ucwords($race) == $char->race);
		
		emitImageTableTD("race", $race, "img/races/", $raceDescriptions[$race], $selected);
	}
?>

<form action="wizard.php" method="post">
	<?php include 'elements/nav_buttons.php'; ?>
    
    <table class="table" width="800" cellspacing="0" cellpadding="0">
        <tr>
            <td height="26" colspan="3" class="table-header">
            	&nbsp;&nbsp;Choose your race
            </td>
        </tr>
        <tr>
            <?php emitRaceTD("human"); ?>
            <?php emitRaceTD("dwarf"); ?>
            <?php emitRaceTD("elf"); ?>
        </tr>
        
        <tr>
            <?php emitRaceTD("half-elf"); ?>
            <?php emitRaceTD("half-orc"); ?>
            <?php emitRaceTD("halfling"); ?>
        </tr>
        
        <tr>
            <?php emitRaceTD("gnome"); ?>
            <?php emitRaceTD(NULL); ?>
            <?php emitRaceTD(NULL); ?>
        </tr>        
    </table>
    
    <?php include 'elements/nav_buttons.php'; ?>
</form>

<p class="warning-text">
	Warning: If you have already allocated your ability scores, changing race will reset them.
</p>