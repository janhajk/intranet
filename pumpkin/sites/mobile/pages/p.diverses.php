<? // diverses Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['diverses'] && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) { ?>
    <?=link_back_head('');?>
    <div class="box">
        <div class="title">Diverses</div>
        <div>
			<ul>
				<li><a href="?p=diverses.scriptures">Scriptures</a></li>
        <li><a href="?p=diverses.buchmormonepub">Buch Mormon epub</a></li>
			</ul>
        </div>
    </div>
    <?=link_back_foot('');?>
<? } ?>