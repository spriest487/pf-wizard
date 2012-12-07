<?php
	require_once 'classes/character.php';
	
	$g_hitDie = array
		(
			"barbarian" => 12,
			"bard" => 8,
			"cleric" => 8,
			"druid" => 8,
			"fighter" => 10,
			"monk" => 8,
			"paladin" => 10,
			"ranger" => 10,
			"druid" => 8,
			"rogue" => 8,
			"sorceror" => 6,
			"wizard" => 6,
			"alchemist" => 8,
			"cavalier" => 10,
			"gunslinger" => 10,
			"inquisitor" => 8,
			"magus" => 8,
			"oracle" => 8,
			"summoner" => 8,
			"witch" => 6,
			"antipaladin" => 10,
			"ninja" => 8,
			"samurai" => 10,
		);
		
	$g_skillPoints = array
		(
			"barbarian" => 4,
			"bard" => 6,
			"cleric" => 2,
			"druid" => 4,
			"fighter" => 2,
			"monk" => 4,
			"paladin" => 2,
			"ranger" => 6,
			"rogue" => 8,
			"sorceror" => 2,
			"wizard" => 2,
			"alchemist" => 4,
			"cavalier" => 4,
			"gunslinger" => 4,
			"inquisitor" => 6,
			"magus" => 2,
			"oracle" => 4,
			"summoner" => 2,
			"witch" => 2,
			"antipaladin" => 2,
			"ninja" => 8,
			"samurai" => 4,
		);
	
	class ClassLevel
	{		
		public $levelIndex;
	
		public $baseAttackBonus;
		
		public $fortSave;
		public $refSave;
		public $willSave;
		
		public $special = array();
		
		public $spells = array();
		
		public static function lookUpLevel($className, $level)
		{
			return self::$classes[$className][$level];
		}
		
		public static function getSpecialsAtLevel($className, $level)
		{
			for ($l = 1; $l <= $level; ++$l)
			{
				$levelSpecials = self::lookUpLevel($className, $l)->special;
				
				//break up by comma
				$specials = split(",", $levelSpecials);
				
				foreach ($specials as $key => $special)
				{
					$special = ucwords(trim($special));
					
					$result[] = $special;
				}
			}
			
			return $result;
		}
		
		//this is essentially designed so the function call looks like a line from the pathfinder website's character level charts
		public function ClassLevel($baseAttackBonus,
												$fortSave,
												$refSave,
												$willSave,
												$special,
												$spells)
		{
			$this->baseAttackBonus = $baseAttackBonus;
			
			$this->fortSave = $fortSave;
			$this->refSave = $refSave;
			$this->willSave = $willSave;
			
			$this->special = $special;
			
			$this->spells = $spells;
		}
		
		public static $classes = array();
		
		protected static function addClassLevel($name, $level)
		{
			if (!isset(self::$classes[$name]))
			{
				self::$classes[$name] = array();
				self::$classes[$name][1] = $level;
			}
			else
				self::$classes[$name][] = $level;
				
			$level->levelIndex = count(self::$classes[$name]);
		}
		
		public static function dbConnect()
		{
			//mysql connection settings
			$db_host = 'localhost';
			$db_name = 'spacetra_charwiz';
			$db_user = 'spacetra_tsunami';
			$db_pass = 'M4caron0';
			
			//connect to the db
			$db_connection = mysql_connect($db_host, $db_user, $db_pass);
			$db_selected = mysql_select_db($db_name, $db_connection);
			
			//check for connection errors
			if (!$db_connection || !$db_selected)
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		
		public static function initClasses()
		{
			if (self::dbConnect() === TRUE)
			{
				global $g_hitDie;
								
				foreach ($g_hitDie as $className => $d)
				{					
					for ($level = 1; $level <= 20; ++$level)
					{
						$query = "SELECT * FROM classlevels WHERE class='$className' AND level='$level'";
						
						$result = mysql_query($query);
						
						if (mysql_num_rows($result) > 0)
						{
							$data = mysql_fetch_array($result, MYSQL_ASSOC);
							
							$baseAttack = $data['baseAttack'];
							$fortSave = $data['fortSave'];
							$refSave = $data['refSave'];
							$willSave = $data['willSave'];
							$special = $data['special'];
							$spells = array($data['spells0'],
											$data['spells1'],
											$data['spells2'],
											$data['spells3'],
											$data['spells4'],
											$data['spells5'],
											$data['spells6'],
											$data['spells7'],
											$data['spells8'],
											$data['spells9'],);
							
							$newLevel = new ClassLevel($baseAttack, $fortSave, $refSave, $willSave, $special, $spells);
							
							self::addClassLevel($className, $newLevel);
						}						
					}
				}
			}		
			
		}
	}
	
	ClassLevel::initClasses();
?>
