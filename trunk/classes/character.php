<?php
	require_once 'characterclass.php';

	class Character
	{				
		//personal info
		var $name;
		var $gender;
		var $deity;
		var $race;
		
		public $inventory = array();
		public $weapons;
		public $equippedArmor;

		public $notes = array();
		
		public function totalLevel()
		{
			$total = 0;
			
			foreach ($this->levels as $className => $classLevel)
			{
				$total += $classLevel;
			}
			
			return $total;
		}
		
		public function getFlatFootedArmorClass()
		{
			$dodgeMod = 0;
			$dexMod = $this->dexMod();
			
			return $this->getArmorClass() - ($dexMod + $dodgeMod);
		}
		
		public function getTouchArmorClass()
		{
			$dexMod = $this->dexMod();
			
			return 10 + $sizeMod + $dexMod + $deflectionMod + $dogeMod;
		}
		
		public function getArmorClass()
		{
			$armorVal = 0;
			$shieldVal = 0;
			
			$naturalBonus = 0;
			$dodgeBonus = 0;
			$deflectionBonus = 0;
			$sizeMod = 0;
			
			return 10 + $armorVal + $shieldVal + $this->dexMod();
		}
		
		public function getBaseAttackBonus()
		{
			$result = 0;
			
			foreach ($this->levels as $className => $classLevel)
			{
				$result += $this->getClassCurrentLevel($className)->baseAttackBonus;
			}
			
			return $result;
		}
		
		public function getMeleeAttackBonus()
		{
			$sizeMod = 0; //todo
			return $this->getBaseAttackBonus() + $this->strMod() + $sizeMod;
		}
		
		public function getRangedAttackBonus()
		{
			$sizeMod = 0; //todo
			return $this->getBaseAttackBonus() + $this->dexMod() + $sizeMod;
		}
		
		public function getCombatManeuverBonus()
		{
			$sizeMod = 0; //todo
			return $this->getBaseAttackBonus() + $this->strMod() + $sizeMod;
		}
		
		public function getCombatManeuverDefense()
		{
			$sizeMod = 0; //todo
			return 10 + $this->getBaseAttackBonus() + $this->strMod() + $this->dexMod() + $sizeMod;
		}
		
		public function getClassCurrentLevel($className)
		{
			$level = $this->levels[$className];
			
			if ($level != NULL)
			{
				$result = ClassLevel::$classes[$className][$level];
			}
			else
			{
				$result = NULL;
			}
			
			//var_dump(ClassLevel::$classes);
			
			return $result;
		}
		
		public function addLevel($className)
		{			
			$currentLevel = $this->levels[$className];
			
			$level = $currentLevel + 1;
						
			$this->levels[$className] = $level;
		}
				
		public function getSpellsAtLevel($level)
		{
			$result = 0;
			
			foreach ($this->levels as $className => $classLevel)
			{
				$result += $this->getClassCurrentLevel($className)->spells[$level];
			}
			
			return $result;
		}
		
		var $levels = array();
		
		public function willSave()
		{
			$result = 0;

			foreach ($this->levels as $className => $classLevel)
			{
				$result += $this->getClassCurrentLevel($className)->willSave;
			}
			
			return $result + $this->wisMod();
		}
		
		public function fortitudeSave()
		{
			$result = 0;
			
			foreach ($this->levels as $className => $classLevel)
			{
				$result += $this->getClassCurrentLevel($className)->fortSave;
			}
			
			return $result + $this->conMod();
		}
		
		public function reflexSave()
		{
			$result = 0;
			
			foreach ($this->levels as $className => $classLevel)
			{
				$result += $this->getClassCurrentLevel($className)->refSave;
			}
			
			return $result + $this->dexMod();
		}
		
		var $xp;
		
		//base ability scores
		var $str;
		var $dex;
		var $con;
		var $int;
		var $wis;
		var $cha;
		
		//racial bonuses
		var $bonusStr;
		var $bonusDex;
		var $bonusCon;
		var $bonusInt;
		var $bonusWis;
		var $bonusCha;
		
		private function getStatTotal($base, $bonus)
		{
			return (isset($base) && isset($bonus))? $base + $bonus : NULL;
		}
		
		public function totalStr()
		{
			return $this->getStatTotal($this->str, $this->bonusStr);
		}
		
		public function totalDex()
		{
			return $this->getStatTotal($this->dex, $this->bonusDex);
		}
		
		public function totalCon()
		{
			return $this->getStatTotal($this->con, $this->bonusCon);
		}
		
		public function totalInt()
		{
			return $this->getStatTotal($this->int, $this->bonusInt);
		}
		
		public function totalWis()
		{
			return $this->getStatTotal($this->wis, $this->bonusWis);
		}
		
		public function totalCha()
		{
			return $this->getStatTotal($this->cha, $this->bonusCha);
		}
		
		public function strMod()
		{
			return self::abilitySkillModifier($this->totalStr());
		}
		
		public function dexMod()
		{
			return self::abilitySkillModifier($this->totalDex());
		}
		
		public function conMod()
		{
			return self::abilitySkillModifier($this->totalCon());
		}
		
		public function intMod()
		{
			return self::abilitySkillModifier($this->totalInt());
		}
		
		public function wisMod()
		{
			return self::abilitySkillModifier($this->totalWis());
		}
		
		public function chaMod()
		{
			return self::abilitySkillModifier($this->totalCha());
		}
		
		static function abilitySkillModifier($abilityVal)
		{
			if ($abilityVal === NULL)
				return NULL;
			else
				return (integer) floor(($abilityVal - 10) / 2);
		}
		
		function Character()
		{
			//constructor
		}
	}
?>