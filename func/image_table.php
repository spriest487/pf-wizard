<?php
	function emitImageTableTD($attrName, $item, $imgPath, $descText, $selected)
	{
		echo '<td valign="bottom">';
		
		//wrap the image in a table for layout purposes
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr>';
		
		echo '<td class="text-body" align="center" valign="bottom">';
		
		//the image itself (blank if $item is NULL)
		if (isset($item))
		{
			$imgSrc = $imgPath . $item . '.jpg';
			echo '<img src="' . $imgSrc . '" border="0" />';
		}
		else
		{
			echo "&nbsp;";
		}
		
		echo '</td></tr>';
				
		if (isset($descText))
		{
			echo '<tr><td class="tiny-text" align="left" valign="bottom">';
			echo $descText;
			echo '<br></td></tr>';
		}
		
		echo '<td class="table-header" height="26" align="center">';
		
		//radio button for selecting this image (not used if $item is NULL)
		if (isset($item))
		{
			$itemBtnName = $item . "_btn";		
			echo "<input class=\"middle-align\" id=\"${itemBtnName}\" type=\"radio\" name=\"${attrName}\" value=\"${item}\"";
			
			//check the box if $selected is true
			if ($selected == true)			
				echo 'checked="checked"';
				
			echo " />";
	
			$capitalizedItem = ucwords($item);
			echo "<label class=\"middle-align\"  for=\"${itemBtnName}\">${capitalizedItem}</label>";
		}
		else
		{
			echo "&nbsp;";
		}
		
		echo '</td>';
		echo '</td></tr></table>';
	}
?>