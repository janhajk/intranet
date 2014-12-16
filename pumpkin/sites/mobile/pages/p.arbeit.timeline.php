<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.timeline' && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) { ?>
	<script language="javascript">
	var tl;
	 function onLoad() {
            var theme = Timeline.ClassicTheme.create();
            theme.event.bubble.width = 300;
            theme.event.bubble.height = 600;
	   var eventSource = new Timeline.DefaultEventSource();
	   var bandInfos = [
		 Timeline.createBandInfo({
			 eventSource:    eventSource,
			 width:          "90%", 
			 intervalUnit:   Timeline.DateTime.DAY, 
			 intervalPixels: 250, 
			 timeZone:		 2,
			 theme:			 theme		
		 })
	   ];
	   tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);
	   tl.loadJSON("<?=USER_ACT;?>/json.arbeit.php?"+ (new Date().getTime()), function(json, url) { eventSource.loadJSON(json, url); });
	 }
	</script>	
	<?=link_back_head($pages['Arbeit']);?>
	<div class="box" style="max-width:100%;">
		<div id="my-timeline" style="height: 200px; border: 1px solid #aaa;width:100%"></div>
		<noscript>
		This page uses Javascript to show you a Timeline. Please enable Javascript in your browser to see the full page. Thank you.
		</noscript>
	</div>
	<?=link_back_foot($pages['Arbeit']);?>
<? } ?>