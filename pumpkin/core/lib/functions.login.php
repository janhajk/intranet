<?
// **************************************************************************************************
// Logt ein Benutzer ein
// **************************************************************************************************
function login($e) {
		$u = getUserArrayfromEmail($e);
		$_SESSION['loged'] = 1;
		$_SESSION['vorname'] = $u['vorname'];
		$_SESSION['nachname'] = $u['nachname'];
		$_SESSION['email'] = $u['email'];
		$_SESSION['psw'] = $u['psw'];
		$_SESSION['rights'] = $u['rights'];
		$_SESSION['last_action'] = $u['last_action'];
		$_SESSION['browser'] = $u['browser'];
		$_SESSION['comment'] = $u['comment'];
		$_SESSION['myid'] = $u['id'];
		//addlog('',2);
}
// **************************************************************************************************
?>