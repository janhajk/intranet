<? // Links Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Links'] && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) { ?>
    <?=link_back_head('');?>
    <div class="box">
        <div class="title">Links</div>
		<? if(isset($_GET['a']) && $_GET['a'] == 'ss') { ?>
        <div>
            <div class="header2">Links</div>
			<ul style="margin-left:0.5em; padding-left:0.5em">
				<?
				$q = $_GET['q'];
				$sql = "SELECT * FROM links_links WHERE title LIKE '%$q%' OR url LIKE '%$q%'";
				$db->query($sql);
				while($r = $db->results()) {
					echo "<li><a href=\"".$r['url']."\">".substr($r['title'],0,50)."</a></li>";
				}
				?>
			</ul>
        </div>
		<? } ?>
        <div>
            <div class="header2">Suche</div>
				<form action="" method="get">
					<input type="text" name="q" value="">
					<input type="hidden" name="a" value="ss">
					<input type="hidden" name="<?=$page;?>" value="<?=$pages['Links'];?>">
					<input type="submit" value="go">
				</form>
        </div>
        <div>
            <div class="header2">Upload</div>
				<form action="<?=SITE_HTML;?>/actions.php" enctype="multipart/form-data" method="post">
					<input type="file" name="file" />
					<input type="hidden" name="a" value="uploadLinkFile" />
					<input type="submit" value="go" />
				</form>
        </div>
    </div>
    <?=link_back_foot('');?>
<? } ?>