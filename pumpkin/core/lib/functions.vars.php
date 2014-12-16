<?
function getVar($varname) {
		$db = $GLOBALS['db'];
		$sql = "SELECT * FROM `pmkVars` WHERE `name` = '$varname' LIMIT 0,1";
		$db->query($sql);
		//echo $sql.'<br>';
		//echo $db->errormessage;
		$value = $db->results();
		$value = $value['value'];
		$value = json_decode($value);
		return $value;
}
?>