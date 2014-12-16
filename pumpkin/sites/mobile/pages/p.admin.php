<? // admin Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['admin'] && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) { ?>
    <?=link_back_head('');?>
    <div class="box">
        <div class="title">admin</div>
        <div>
			<ul>
				<li><a href="<?=SITE_HTML;?>/actions.php?a=UpdateHt">Update htpasswd</a></li>
				<li>Backup <a href="<?=SITE_HTML;?>/actions.php?a=backup&bkpD=1&bkpF=0">DB</a>...<a href="<?=SITE_HTML;?>/actions.php?a=backup&bkpD=1&bkpF=1">all</a></li>
				<li><form action="<?=SITE_HTML;?>/actions.php" method="post"><input type="submit" value="Update Includes" /><input type="hidden" name="a" value="updateIncludes" /></form></li>
			</ul>
        </div>
    </div>
    <?=link_back_foot('');?>
<? } ?>