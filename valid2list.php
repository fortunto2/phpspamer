#!/usr/bin/php
<?php
if (!isset($argv[1])) {
	die("What file?".PHP_EOL);
}
if (!isset($argv[2])) {
	die("How second file name?".PHP_EOL);
}
$fn = $argv[1];
$fnto = $argv[2];
if (!file_exists($fn)) {
	die("Not found your shit.".PHP_EOL);
}

$mails = file($fn);
for ($i=0;$i<count($mails);$i++) {
	$a = explode("\t",$mails[$i]);
	if (!isset($a[1])) continue;
	$mail = trim($a[0]);
	$result = trim($a[1]);
	if ($result=='1') {
		file_put_contents($fnto,$mail."\n",FILE_APPEND);
	}
}
echo "DONE,sir!\n";
?>
