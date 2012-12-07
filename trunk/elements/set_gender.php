<?php
	require_once('classes/character.php');
	require_once('elements/session.php');

	//existing settings
	$char = currentCharacter();
?>

<form method="post" action="wizard.php">
   <?php include 'elements/nav_buttons.php'; ?>
   
    <table class="table" width="400" cellpadding="4" cellspacing="0">
        <tr>
            <td class="table-header">
            	Full Name:
            </td>
        </tr>
        <tr>            
        	<td class="table" align="left" style="vertical-align: middle">            
            <input class="text" name="name" size="30" value="<?php echo $char->name; ?>" />
            </td>
        </tr>
		<tr>
            <td class="table-header">
            	Deity or Religion:
            </td>
        </tr>
		<tr>            
        	<td class="table" align="left" style="vertical-align: middle">            
            <input class="text" name="deity" size="30" value="<?php echo $char->deity; ?>" />
            </td>
        </tr>
        
        <tr>
            <td class="table-header">
            	Gender:
            </td>
        </tr>
        <tr>      
        	<td class="table" align="left" style="vertical-align: middle">
            
            <input class="button vertical-middle" type="radio" name="gender" id="gender1" value="1" <?php if ($char->gender=="Male") echo 'checked="checked"'; ?> />
            <label class="body-text vertical-middle" for="gender1">Male</label>
            
            <input class="button vertical-middle" type="radio" name="gender" id="gender2" value="2" <?php if ($char->gender=="Female") echo 'checked="checked"'; ?> />
            <label class="body-text vertical-middle" for="gender2">Female</label>
            
            <input class="button vertical-middle" type="radio" name="gender" id="gender0" value="0" <?php if ($char->gender=="Neither") echo 'checked="checked"'; ?> />
            <label class="body-text vertical-middle" for="gender0">Neither</label>
            </td>
        </tr>
    </table>
    
    <br />
    
    <?php
    	include 'elements/nav_buttons.php';
    ?>
</form>


