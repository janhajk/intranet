<?

/*
Rechte:
10 = Superadmin
 9 = Admin
 8 = ASTRA
 7 = Übersetzer
 6 = Filiale
 5 = Benutzer Kanton
 4 = Reader/Nur Leserechte
 
*/


// $e als code von allen Rechten in Binärform von Stufe 1 bis 10, Punktgetrennt, wobei 0=kein Recht, 1=hat Recht
// zb, $e = canEnterBinary('0.0.0.0.0.0.0.0.0.1')
function canEnterBinary($e) {
	if (!isset($_SESSION['rights'])) { 
		return 0;
	}
	else {
		$a = explode('.', $e);
		if($a[$_SESSION['rights']-1] == 1) {
			return 1;
		}
		else {
			return 0;
		}
	}
}

function rightsDropDown($userrights) {

		$selected = ($userrights==5) ? " selected=\"selected\"" : "";
		echo "<option value=\"5\" $selected>Benutzer</option>";

		$selected = ($userrights==6) ? " selected=\"selected\"" : "";
		echo "<option value=\"6\" $selected>Filiale</option>";

		$selected = ($userrights==7) ? " selected=\"selected\"" : "";
		echo "<option value=\"7\" $selected>&Uuml;bersetzer</option>";

		$selected = ($userrights==8) ? " selected=\"selected\"" : "";
		echo "<option value=\"8\" $selected>ASTRA</option>";
		
		$selected = ($userrights==9) ? " selected=\"selected\"" : "";
		echo "<option value=\"9\" $selected>Admin</option>";		

		$selected = ($userrights==10) ? " selected=\"selected\"" : "";
		echo "<option value=\"10\" $selected>Superadmin</option>";			
}
?>