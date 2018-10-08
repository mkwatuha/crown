<?php
include("sendsms.php");
if(isset($_REQUEST['recipient']))
{
	$recipient =$_REQUEST['recipient'];
	$Msg =$_REQUEST['message'];
	SendSms($recipient, $Msg, true);
}
?>