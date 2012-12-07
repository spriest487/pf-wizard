<?php
	function getStep($delta)
	{
		global $_POST;
		
		$step = $_POST["step"];
		
		$stepOrder = array(
						"race",
						"class",
						"abilities",
						"gender"
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
		return getStep(+1);
	}
	
	function getPrevStep()
	{
		return getStep(-1);
	}
?>