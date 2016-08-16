<?php
	echo 'Mail Script Start.';
	
	// the message
$msg = "Sundial Test Message. First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
//$res = mail("syedtufail@gmail.com","Sundial Test Message",$msg);
$res = mail("AjaiKumar.Tiwari@itcinfotech.com","Sundial Test Message",$msg);
if($res){
	echo $res.'- Mail send..';
}else {
	echo $res.'- Mail send Fail..';

}	
	
	
?>
