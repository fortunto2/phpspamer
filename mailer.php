<?php
include "class.phpmailer.php";
$m = new PHPMailer(true);
$m->IsSMTP();

/*
$m->SMTPAuth = true;
$m->Username = '';
$m->Password = '';
*/
$m->SMTPAuth = false;
$m->Host = '192.168.100.10';
$m->Port = 25;


$m->SetFrom('noreply@yoursite.com','Yoursite.com');
$m->SMTPDebug = 1;
$m->CharSet = 'utf-8';

$mail_list=file('mail_list.txt');
$tpl = file_get_contents('tpl/template.html');
$altbody = file_get_contents('tpl/altbody.txt');
$_list = file('tpl/images.txt');
$img_list=array();
for ($i=0;$i<count($_list);$i++) {
	$_arr = explode("\t",$_list[$i]);
	if (!isset($_arr[1])) continue;
	$img_list[] = array(
		'src'=>'tpl/'.trim($_arr[1]),
		'cid'=>trim($_arr[0]),
	);
}
$subjects = file('subjects.txt');

for ($i=0;$i<count($mail_list);$i++) {
	$_to = trim($mail_list[$i]);
	$_username = substr($_to,0,strpos($_to,'@'));
	if (!$m->ValidateAddress($_to)) {
		echo "Skip invalid email: $_to\n";
		continue;
	}
	$m->ClearAllRecipients();
	$m->AddAddress($_to);
	$m->Subject=$subjects[rand(0,count($subjects)-1)];
	$m->AltBody = $altbody;
	$m->Body = $tpl;

	$m->IsHTML(true);
	for ($j=0;$j<count($img_list);$j++) {
		$m->AddEmbeddedImage($img_list[$j]['src'],$img_list[$j]['cid']);
	}
	$m->Send();
	echo "Sended to $_to\n";
	

}
?>
