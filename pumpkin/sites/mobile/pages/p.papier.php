<? // Papier Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Papier'] && canEnterBinary('1.1.1.1.1.1.1.1.1.1')) { ?>
    <?=link_back_head('');?>
    <div class="box">
        <div class="title">&nbsp;ETH Papier</div>
        <div style="text-align:left;margin-top:8px;">
        	<form action="pumpkin/sites/mobile/actions.php" method="get">
            <select name="fach"><option>Grundbau</option><option>Massivbau2</option><option>Baustatik1</option><option>Physik</option><option>Stahlbau1</option><option>Verkehr1</option><option>Bodenmechanik</option><option>Hydrologie</option><option>Chemie</option></select><br />
            Seite von:<input name="von" type="text" /><br />Seite bis:<input name="bis" type="text" /><br />
            <select name="spezial"><option>HU</option><option>Vorlesung</option><option>Pruefung</option><option>Zusammenfassung</option></select><br />
            <input type="submit" value="ok" />
            <input type="hidden" name="matrix" value="1" />
			<input type="hidden" name="a" value="paper" />
            </form>
        </div>
    </div>
    <?=link_back_foot('');?>
<? } ?>