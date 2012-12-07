<?php
	function getStep($delta)
	{
		global $_POST;
		
		$step = $_POST["step"];
		
		$stepOrder = array(
						"class",
						"gender",
						"attributes",
						"skills",
						);
	
		$stepNum = array_search($step, $stepOrder);
		$nextStepNum = $stepNum + $delta;
		
		if ($nextStepNum >= 0
			&& $nextStepNum < count($stepOrder))
		{
			return $stepOrder[$nextStepNum];
		}
		else
		{
			return "";
		}
	}

	function getNextStep()
	{
		return $getStep(+1);
	}
	
	function getPrevStep()
	{
		return $getStep(-1);
	}
?>

<form method="post" action="wizard.php">
<?php
	$prevStep = getPrevStep();
	echo '<button name="step" value="${prevStep}">Previous</button>';
?>
<?php
	$nextStep = getNextStep();
	echo '<button name="step" value="${nextStep}">Next</button>';
?>
</form>