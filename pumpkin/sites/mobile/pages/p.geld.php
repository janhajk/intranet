<?php
$USER_ACT = USER_ACT;
$nav_geld = '
        <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand"><span class="glyphicon glyphicon-home"></span></a>
                <a href="'.$USER_ACT.'/cronjob.stocks.php" class="navbar-brand">Update</a>
            </div>
        </nav>
';
?>
<? // Geld Seite ?>
<? if(isset($_GET[$page]) && $_GET[$page] == $pages['Kurse'][0] && canEnterBinary('0.0.0.0.0.0.0.0.0.1')) { ?>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {'packages':['annotatedtimeline']});
    </script>
    <script type="text/javascript">
      <?php
      $kurven = array();
      $db->query("SELECT * FROM stocks_names");
      while($r = $db->results()) { $kurven[] = $r; }
      foreach($kurven as $g) { ?>
        function drawVisualization<?=$g['id']; ?>() {
          var data = new google.visualization.DataTable();
          data.addColumn('date', 'Date');
          data.addColumn('number', 'Kurs');
          data.addRows(
            eval([<?php
              $data = diagramFonds($g['id']);
              print $data[0];
            ?>])
          );
          var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('visualization<?=$g['id']; ?>'));
          chart.draw(data, {
            displayAnnotations: true,
            //min      : <?=$data[1]; ?>,
            //max      : <?=$data[2]; ?>,
            scaleType: 'maximized',
            zoomStartTime: new Date((new Date()).getTime()-30*24*60*60*1000),
            displayRangeSelector: false
          });
        }
        google.setOnLoadCallback(drawVisualization<?=$g['id']; ?>);
      <? } ?>
    </script>



    <div class="container">
        <a href="index.php" role="button" class="btn btn-default btn-sm btn-block">back</a>
        <h2><?php echo $pages['Kurse'][1]; ?></h2>
        <p><a href="<?=USER_ACT;?>/cronjob.stocks.php"><span class="glyphicon glyphicon-refresh"></span></a></p>
        <p>Total Value: <?=pmkCurrency(stocks_totalFonds()); ?></p>
		<?php
		foreach($kurven as $g) {
		?>
        <div style="margin-bottom:10px">
            <p><a href="<?=$g['url'];?>"><?=$g['descr'];?></a></p>
            <div id="visualization<?=$g['id'];?>" style="height: 200px;"></div>
        </div>
		<? } ?>
        <a href="index.php" role="button" class="btn btn-default btn-sm btn-block">back</a>
    </div>
<? } ?>