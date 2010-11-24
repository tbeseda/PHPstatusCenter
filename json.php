<?
header('Content-type: text/javascript' );
$callback = $_GET['callback'];

$myFile = 'latest.json';
$fh = fopen($myFile, 'r');
$update = fgets($fh);
fclose($fh);

echo($callback . '(' . $update . ')');

?>