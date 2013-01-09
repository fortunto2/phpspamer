#!/usr/bin/php
<?php
require_once('conf.php');

function sWrite( $socket, $data, $echo = false ){
	if( $echo ) echo $data;
	fputs( $socket, $data );
	$answer = fread( $socket, 1 );
	$remains = socket_get_status( $socket );
	if( $remains --> 0 ) $answer .= fread( $socket, $remains['unread_bytes'] );
	return $answer;
}
function add_checked($email,$result,$msg) {
	$msg = str_replace("\n",'',$msg);
	$msg = str_replace("\r",'',$msg);
	file_put_contents(Conf::emailvalidlist,"$email\t".(int)$result."\t$msg\n",FILE_APPEND);
}


$mails = file(Conf::maillist);

for ($i=0;$i<count($mails);$i++) {
	$mails[$i]=trim($mails[$i]);
	$_arr = explode('@',$mails[$i]);
	if (!isset($_arr[1])) {
		echo "Not a valid email: [{$mails[$i]}]".PHP_EOL;
		add_checked($mails[$i],false,'nonvalid');
		continue;
	}
	$domain=trim($_arr[1]);
	$user=trim($_arr[0]);
	echo "Check MX ".$domain." ... ";
	$cmd = 'dig mx '.$domain.' | grep MX | tail -n 1';
	$R=array();
	exec($cmd,$R);
	if (substr($R[0],0,1)==';') {
		echo 'No MX retranslator'.PHP_EOL;
		add_checked($mails[$i],false,'no MX host');
	} elseif(isset($R[0])) {
		$R = explode("\t",$R[0]);
		if (isset($R[5])) {		
			$mxhost = trim(substr($R[5],strpos($R[5],' ')));
			echo $mxhost.PHP_EOL;
			add_checked($mails[$i],true,''); // remove this if uncommented next code
			/* This for deeper checking: check an account on smtp server 
			* ---
			$socket = fsockopen($mxhost, 25, $errno, $errstr, 10);
			if(!$socket){
				echo 'Fail connect to MX retranslator'.PHP_EOL;
				add_checked($mails[$i],false,'fail connect to mx host');
			} else {
				//connected to SMTP
				sWrite( $socket, "" );
				sleep(1);
				sWrite( $socket, "EHLO test.com\r\n" );
				sWrite( $socket, "MAIL FROM:<dummy@test.com>\r\n" );
				$response = sWrite( $socket, "RCPT TO: <{$mails[$i]}>\r\n" );
				//echo $response;
				sWrite( $socket, "QUIT\r\n" );
				fclose( $socket );
				if (substr($response,0,3)=='250') {
					echo "Mail {$mails[$i]} exists".PHP_EOL;
					add_checked($mails[$i],true,'');
				} else {
					echo "ERROR: ".$response.PHP_EOL;;
					add_checked($mails[$i],false,$response);
				}

			}*/
		} else {
			echo "NO RECORDS".PHP_EOL;
			add_checked($mails[$i],false,'bad MX result format');
		}
	} else {
		echo "NO RESULT".PHP_EOL;
		add_checked($mails[$i],false,'no dig result');
	}


	//$socket = fsockopen( $mx, 25, $errno, $errstr, 10 );
}


?>
