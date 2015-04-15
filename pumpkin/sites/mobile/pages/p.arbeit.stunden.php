<? // Arbeit.Stunden Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == 'arbeit.stunden' && canEnterBinary('0.0.0.0.1.0.0.0.0.1')) {
	if(isset($_GET['l'])) {$limit = (int) $_GET['l'];}
	?>
    <div class="container" style="max-width:700px !important;">
        <a href="index.php?p=<?php echo $pages['Arbeit'][0]; ?>" role="button" class="btn btn-default btn-sm btn-block">back</a>
        <h2>Time Sheet</h2>
        <a href="<?=SITE_HTML;?>/actions.php?a=xlsstunden">csv download all</a>

        <h3>Last <?php echo $limit; ?></h3>
        <div class="list-group">
            <?php
            $items = stunden_overview($limit);
            foreach ($items as $key=>$value) {?>
            <a href="?p=arbeit.stunden.edit&id=<?php echo $value['id'];?>" class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $value['date'] . ' '.substr($value['von'],0,5).'-'.substr($value['bis'],0,5);?></h4>
                <p class="list-group-item-text">
                    <?php echo htmlspecialchars($value['comment']);?>
                </p>
            </a><?php
                                     }
            ?>
            <a href="?p=arbeit.stunden&l=<?php echo ($limit+20); ?>" class="list-group-item">
                more
            </a>
        </div>
    </div>
<? } ?>