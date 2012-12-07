<div align="left">
&nbsp;
<br />
<?php
	require_once 'func/steps.php';

	$prevStep = getPrevStep();
	echo '<button class="button" type="submit" name="step" value="' . ${prevStep} . '">< Previous</button>';
	
	$nextStep = getNextStep();
	echo '<button class="button" type="submit" name="step" value="' . ${nextStep} . '">Next ></button>';
	
	echo '<button class="button" type="submit">To Summary >></button>';
?>
<br />
&nbsp;
</div>