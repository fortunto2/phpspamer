#!/usr/bin/php
<?php
require_once('conf.php');
$index = file(Conf::tplpath.Conf::imgindex);
$tpl = file_get_contents(Conf::tplpath.Conf::html);

for ($i=0;$i<count($index);$i++) {
	$_arr = explode("\t",$index[$i]);
	if (!isset($_arr[1])) continue;
	$cid = 'cid:'.trim($_arr[0]);
	$img = Conf::prefix.trim($_arr[1]);
	if (strpos($tpl,$img)) {
		$tpl = str_replace($img,$cid,$tpl);
		echo "Processed IMG: $img -> $cid\n";
	} else {
		echo "Not found IMG: $img\n";
	}

}
file_put_contents(Conf::tpl,$tpl);
echo "Finished\n";
?>
