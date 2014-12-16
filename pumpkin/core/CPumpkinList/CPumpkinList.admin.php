<?
// **************************************************************************************************
	//Lib laden
	include($_SERVER['DOCUMENT_ROOT'].'/config.php');
// **************************************************************************************************
	header('Content-Type: text/html');
	
	
	// Wenn ein Eintrag gespeichert wird
	if(isset($_POST['act']) && $_POST['act'] == 'save') {
		$sql = "UPDATE `pmkLST` SET `title`	= '".$_POST['title']."'  WHERE `id` = ".$_POST['lid'];
		$db->query($sql);
		$sql = "UPDATE `pmkLST` SET `io` 	= '".$_POST['io']."'  WHERE `id` = ".$_POST['lid'];
		$db->query($sql);
		$sql = "UPDATE `pmkLST` SET `tbl` 	= '".$_POST['tbl']."'  WHERE `id` = ".$_POST['lid'];
		$db->query($sql);
		print("Eintrag gespeichert");
	}
	
	
	
	// Wenn die Liste geladen wird
	else {
		$sql = "SELECT * FROM `pmkLST` WHERE `user` = ".$_SESSION['myid'];
		// Wenn Administrator
		if(canEnterBinary('0.0.0.0.0.0.0.0.1.1')) {
			$sql = "SELECT * FROM `pmkLST` WHERE `user` = ".$_SESSION['myid']." OR `user` = 0";
		}
		// Wenn Super-Administrator
		if(canEnterBinary('0.0.0.0.0.0.0.0.0.1')) {
			$sql = "SELECT * FROM `pmkLST`";
		}
		$db->query($sql);
		?>
		<div id="pmkADMINlists">
			<table style="width:98%;border:1px solid #000;">
				<thead>
					<tr style="vertical-align:top;text-align:left;">
						<th>id</th>
						<th>Titel</th>
						<th>io</th>
						<th>Tabelle</th>
						<th>Benutzer</th>
                        <th>SQL</th>
                        <th>Haupttabelle</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?
					while($r = $db->results()) {
					?>
					<tr id="pmkADMINlistsFRM<?=$r['id'];?>" style="vertical-align:top;text-align:left;">
                    	<td><input type="hidden" name="lid" value="<?=$r['id'];?>" /><?=$r['id'];?>&nbsp;</td>
						<td><input name="title" value="<?=$r['title'];?>" type="text" style="width:120px"  /></td>
						<td>
							<select style="width:120px" name="io">
								<option<?=(!$r['io']?' selected=selected':'');?> value="0">nur lesen</option>
								<option<?=($r['io'] ?' selected=selected':'');?> value="1">lesen und schreiben</option>
							</select></td>
						<td><input name="tbl" value="<?=$r['tbl'];?>" type="text" style="width:120px"  /></td>
						<td><?=$r['user'];?></td>
                        <td><textarea name="sql"><?=$r['sql'];?></textarea></td>
                        <td><select></select></td>
						<td><input type="button" onclick="Pumpkin_editLists_Save(<?=$r['id'];?>)" value="speichern" /><input type="hidden" name="act" value="save" />
                        	<a href="javascript:Pumpkin_editLists_ShowSpalten(<?=$r['id'];?>)">Spalten</a>
                        </td>  
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
		<?
	}

// **************************************************************************************************
	//Lib schliessen
	endlib();
// **************************************************************************************************
?>