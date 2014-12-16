<?
function loadJSTimeline() {
	return (isset($_GET['timeline']) && $_GET['timeline']==1)?'<script src="http://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></script>':'';
}

function onLoadTimeline() {
return (isset($_GET['timeline']) && $_GET['timeline']==1)?'onLoad();':'';
}
?>