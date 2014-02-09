<?
class Station
{
	var $id;
	
	private $town_id;
	
	function getTown()
	{
		return $this->town_id;
	}
	
	static function getStation($id)
	{
		if(!is_array(Station::$_singleton))
			Station::$_singleton = array();
		
		if(!in_array(Station::$_singleton[$id]))
		{
			Station::$_singleton[$id] = new Station(...);
		}
		
		return Station::$_singleton[$id];
	}
}
?>