<?
$clink_back_head = '<a href="index.php%link%"><div class="backlink"></div></a>';
$clink_back_foot = '<a href="index.php%link%"><div class="backlink"></div></a><p>&nbsp;</p>';
function link_back_head($p) {return str_replace('%link%','?p='.$p,$GLOBALS['clink_back_head']);}
function link_back_foot($p) {return str_replace("%link%","?p=".$p,$GLOBALS['clink_back_foot']);}

$page = 'p';

$elementtpl = "<div class=\"box\"><div class=\"title\">%title%</div><div>%content%</div></div>";
$pagetpl = $clink_back_head.$elementtpl.$clink_back_foot;

function makePage($title, $content) {
	echo str_replace("%content%", $content,str_replace("%title%",$title,$GLOBALS['pagetpl']));
}

function makeElement($title, $content) {
	echo str_replace("%content%", $content,str_replace("%title%",$title,$GLOBALS['elementtpl']));
}

$pages = array(
	'Kurse'		=>2, 
	'Arbeit'	=>3, 
	'Papier'	=>7, 
	'psw'		=>8, 
  	'diverses'	=>11, 
	'admin'		=>12
);
?>
