<?php


$sql = "SELECT * 
FROM  `stocks_kurse` 
WHERE  `pid` =1
AND  `date` >=  '2012-12-15'
ORDER BY  `stocks_kurse`.`date` ASC";

$con = mysqli_connect("localhost","root","","");
$result = mysqli_query($con,$sql);

print '<table>';
while($r = mysqli_fetch_array($result)) {
  print '<tr><td>'.date("d.m.Y", strtotime($r['date'])).'</td><td>'.$r['kurs'].'</td></tr>';
}
print '</table>';
mysqli_close($con);