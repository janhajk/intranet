<?
function CPumpkinList_loadList_startup() {
	$db = $GLOBALS['db'];
	$jsString = '';
	$lst = array();
	$sql = "SELECT id FROM pmkLST WHERE startup = '1' AND active = '1'";
	$db->query($sql);
	while($l = $db->results()) { $lst[] = $l['id'];}
	foreach($lst as $l) {
		 $jsString .= CPumpkinList_loadList($l);
	}
	return $jsString;
}
function CPumpkinList_loadList($ListID) {
	$jsString = '';
	$list = CPumpkinList_getList($ListID);
	$cols = CPumpkinList_getCols($ListID);
	$pmkVar = "pmklist".$ListID;
	$jsString .= "$pmkVar=new CPumpkin_list('".$list['title']."','$pmkVar',".$list['io'].");";
	$jsString .= "$pmkVar.loadData('$pmkVar');";
	$jsString .= "$pmkVar.setMainTable('".$list['maintbl']."');";
	$jsString .= "$pmkVar.sortCol='".$list['sortcol']."';";
	foreach($cols as $c) {
		$jsString .= "$pmkVar.addCol('".$c['title']."','".$c['name']."','".$c['format']."','".$c['io']."',".$c['visible'].");";
	}
	return $jsString;
}

function CPumpkinList_getList($ListID) {
	$db = $GLOBALS['db'];
	$sql = "SELECT * FROM pmkLST WHERE id = '$ListID' LIMIT 0,1";
	$db->query($sql);
	$pmkList = $db->results();
	return $pmkList;
}
function CPumpkinList_getCols($ListID) {
	$db = $GLOBALS['db'];
	$pmkList = Array();
	$sql = "SELECT * FROM pmkLSTcols WHERE list = '$ListID'";
	$db->query($sql);
	while($l = $db->results()) {
		$pmkList[] = $l;
	}
	return $pmkList;
}
?>