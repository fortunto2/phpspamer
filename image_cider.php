<?php
class Conf {
	const prefix='images/';
	const index='tpl/images.txt';
	const tpl='tpl/template.html';
}

$index = file(Conf::index);
$tpl = file_get_contents(Conf::tpl);

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
