<?php
$hostname_cf4_HH = "localhost";
$username_cf4_HH = "intellib_zulmad";
$password_cf4_HH = "Admin2010@#";
$database_cf4_HH = "nowascoktl";
$cf4_HH = mysql_pconnect($hostname_cf4_HH, $username_cf4_HH, $password_cf4_HH) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_cf4_HH);
?><?php 
$message_name='';
$messagervd=12233;//$_GET['message'];
$sendfrom='OOOO';//$_GET['recipent'];
$fpath="receivedtexts/message.txt";
$received_date=date("Y-m-d H:i:s");
//(messagereceived_id,message_from,message_message)
$getEmpgrpSQL=" INSERT into sms_messagereived values('','$sendfrom','$messagervd')
";
//,received_date   ,'$received_date'
$qryresults= mysql_query($getEmpgrpSQL);
?>