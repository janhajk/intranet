<?
function writeHTMLBody() { ?>
	<body>
	<? /* Messages Ausgabe */ ?>
	<div id="msg" class="msg" style="display:none;"></div>
	<? /* Begin: Programmteil */ ?>
	<div class="site">
			<? /* Titelliste */ ?>
			<? include (PMKROOT.'/parts/titlebar.php'); ?>
			<? /* Menu */ ?>
			<div class="menu" id="menu">
				<? include(PMKROOT.'/parts/menu.php'); ?>
			</div>
			<? /* Hauptfenster */ ?>
			<div class="main_content">
				<div class="sheet" id="sheet">
					<div class="sheet-inside" id="sheet-inside"><div id="pmkPrgrmLogo" style="display:block;"><?=end(getVar('pmkPageProjectTitle'));?><img style="margin-top:20px;margin-left:40px;" src="<?=PMKHTTP;?>/user/images/logos/hplbl_logo.gif" alt="HPL Logo" /></div></div>
				</div>
			</div><?
			include (PMKROOT.'/parts/footer.php'); ?>
	</div>
	<? /* Ende: Programmteil */ ?>
	</body></html><?
}
?>