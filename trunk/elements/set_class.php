<?php
	require_once 'elements/session.php';
	require_once 'func/image_table.php';
	
	$classDescriptions = array(
		"barbarian"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/barbarian\">(more info)</a>
						</div>",
		"bard"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/bard\">(more info)</a>
						</div>",
		"cleric"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/cleric\">(more info)</a>
						</div>",
		"druid"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/druid\">(more info)</a>
						</div>",
		"fighter"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/fighter\">(more info)</a>
						</div>",
		"monk"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/monk\">(more info)</a>
						</div>",
		"paladin"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/paladin\">(more info)</a>
						</div>",
		"ranger"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/ranger\">(more info)</a>
						</div>",
		"rogue"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/rogue\">(more info)</a>
						</div>",
		"sorcerer"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/sorcerer\">(more info)</a>
						</div>",
		"wizard"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/core-classes/wizard\">(more info)</a>
						</div>",
		"alchemist"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/alchemist\">(more info)</a>
						</div>",
		"cavalier"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/cavalier\">(more info)</a>
						</div>",
		"gunslinger"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/gunslinger\">(more info)</a>
						</div>",
		"inquisitor"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/inquisitor\">(more info)</a>
						</div>",
		"magus"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/magus\">(more info)</a>
						</div>",
		"oracle"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/oracle\">(more info)</a>
						</div>",
		"summoner"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/summoner\">(more info)</a>
						</div>",
		"witch"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/base-classes/witch\">(more info)</a>
						</div>",
		"antipaladin"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/alternate-classes/antipaladin\">(more info)</a>
						</div>",
		"ninja"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/alternate-classes/ninja\">(more info)</a>
						</div>",
		"samurai"=>"<div align=\"center\">
							<a href=\"http://www.d20pfsrd.com/classes/alternate-classes/samurai\">(more info)</a>
						</div>",
	);

	function emitClassTD($class)
	{
		global $classDescriptions;
		
		$char = currentCharacter();
		
		$selected = (ucwords($class) == $char->characterclass);
		
		emitImageTableTD("class", $class, "img/classes/", $classDescriptions[$class], $selected);
	}
?>

<form action="wizard.php" method="post">
	<?php include 'elements/nav_buttons.php'; ?>
    
    <table class="table" width="800" cellspacing="0" cellpadding="0">
        <tr>
            <td height="26" colspan="3" class="table-header">
            	&nbsp;&nbsp;Choose your class
            </td>
        </tr>
        <tr>
            <?php emitClassTD("barbarian"); ?>
            <?php emitClassTD("bard"); ?>
            <?php emitClassTD("cleric"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("druid"); ?>
            <?php emitClassTD("fighter"); ?>
            <?php emitClassTD("monk"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("paladin"); ?>
            <?php emitClassTD("ranger"); ?>
            <?php emitClassTD("rogue"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("sorcerer"); ?>
            <?php emitClassTD("wizard"); ?>
            <?php emitClassTD("alchemist"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("cavalier"); ?>
            <?php emitClassTD("gunslinger"); ?>
            <?php emitClassTD("inquisitor"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("magus"); ?>
            <?php emitClassTD("oracle"); ?>
            <?php emitClassTD("summoner"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("witch"); ?>
            <?php emitClassTD("antipaladin"); ?>
            <?php emitClassTD("ninja"); ?>
        </tr>
        
        <tr>
            <?php emitClassTD("samurai"); ?>
            <?php emitClassTD(NULL); ?>
            <?php emitClassTD(NULL); ?>
        </tr>
    </table>
    
    <?php include 'elements/nav_buttons.php'; ?>
</form>