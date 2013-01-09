#!/usr/bin/php
<?php
include "class.phpmailer.php";

require_once('conf.php');

$m = new PHPMailer(false);
$m->IsSMTP();

$m->SMTPAuth = false;
$m->Host = Conf::smtphost;
$m->Port = Conf::smtpport;
$m->XMailer = Conf::xmailer;
$m->SetFrom(Conf::fromaddr,Conf::fromname);
$m->SMTPDebug = 1;
$m->CharSet = 'utf-8';

$mail_list=file(Conf::maillist);
$tpl = file_get_contents(Conf::tplpath.Conf::html);
$altbody = file_get_contents(Conf::tplpath.Conf::altbody);
$_list = file(Conf::tplpath.Conf::imgindex);
$img_list=array();
for ($i=0;$i<count($_list);$i++) {
	$_arr = explode("\t",$_list[$i]);
	if (!isset($_arr[1])) continue;
	$img_list[] = array(
		'src'=>Conf::tplpath.trim($_arr[1]),
		'cid'=>trim($_arr[0]),
	);
}
$subjects = file(Conf::subjects);

$persession=8;
$insessid=0;
for ($i=0;$i<count($mail_list);$i++) {
	$_to = trim($mail_list[$i]);
	$_username = substr($_to,0,strpos($_to,'@'));
	if (!$m->ValidateAddress($_to)) {
		echo "Skip invalid email: $_to\n";
		continue;
	}
	$m->ClearAllRecipients();
	$m->ClearAttachments();
	$m->AddAddress($_to);
	$m->Subject=$subjects[rand(0,count($subjects)-1)];
	$m->AltBody = $altbody;
	$m->Body = $tpl;

	$m->IsHTML(true);
	for ($j=0;$j<count($img_list);$j++) {
		$m->AddEmbeddedImage($img_list[$j]['src'],$img_list[$j]['cid']);
	}
	$insessid++;
	if ($insessid>$persession) {
		$insessid=0;
		$m->SmtpClose();
		echo "SMTP manual reconnect\n";
	}
	while(true) {
		$res = $m->Send();
		if (!$res) {
			echo "SMTP connection dropped, reconnect after 10 seconds...\n";
			$m->SmtpClose();
			$insessid=0;
			sleep(10);		
		} else {
			break;
		}
	}
	echo date('d.m.Y H:i:s')." si:$insessid\tSended to $_to\n";
	

}
?>
