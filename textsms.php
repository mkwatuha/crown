<?php 
echo "shee"   ;
require_once('Connections/c4g.php');
//echo textsms.php?message=$message&recipent=$senderno
$message_name='';
$messagervd=$_GET['message'];
$sendfrom=$_GET['recipent'];

//received sms

$received_date=date("Y-m-d H:i:s");
//(messagereceived_id,message_from,message_message)

//update credit balance


  $cbalBonga=explode('points Bonga Balance',$messagervd);
    if($cbalBonga[1]) {
     //re-request if the balance provided is bonga
    updateCreditBalanceStatus(2);
    } 
    
$cbal=explode('Current balance:',$messagervd);
if($cbal[1]){
$acbal=explode('KSH.',$cbal[1]);
    if($acbal[0]) {
    updateCreditBalance(trim($acbal[0]));
    updateCreditBalanceStatus(3);
    }

} 
     
   

  

//sms_receptresp
$smsMSh=explode('*',$_GET['message']);
if($smsMSh[0]==13){
$phone_number=$sendfrom;
$partial=substr($phone_number,0,3);
					if($partial==254){
					$phone_number='0'.substr($phone_number,3,10);
					}
$receipt_number=trim($smsMSh[1]);
//echo $phone_number.'==================================';
//sendSms($phone_number, $receipt_number, $debug=false);


$sendstatus="Pending";
$insertRecAuto="insert into sms_receptresp(phone_number,receipt_number,status) values('$phone_number','$receipt_number','$sendstatus')";

$qryresults= mysql_query($insertRecAuto) or die($insertRecAuto);
$currStatus=getSystemStatus();
				if($currStatus=='ON'){
				sendSMSReply();
				//delete from handler
				
				}
}

$fpath="receivedtexts/message.txt";
$received_date=date("Y-m-d H:i:s");

$created_by='system auto save';
$date_created=date('Y-m-d');
$uuid=gen_uuid();
$stdcolumnsinster="created_by,date_created,
						changed_by,
						date_changed,
						voided,
						voided_by,
						date_voided,
						uuid";
$stdcolumnsvals="'$created_by',
                     '$date_created',
						'$changed_by',
						'$date_changed',
						'$voided',
						'$voided_by',
						'$date_voided',
						'$uuid'";
$getEmpgrpSQL="insert into sms_messagereceived(
message_from,
message_message,
$stdcolumnsinster) values(
'$sendfrom',
'$messagervd',
$stdcolumnsvals)
";
///file_put_contents($fpath, $getEmpgrpSQL);
//if(($message_message) && ($message_from)){
$qryresults= mysql_query($getEmpgrpSQL) or die(mysql_error());
//}
$nofunds=strrpos($messagervd,"account balance is not sufficient");

$balanceRequest=strrpos($messagervd,'bill');
if(($messagervd=='stopserver')||($nofunds>0)){
$qryresults= mysql_query(" update sms_systemmode set current_mode='Off' where systemmode_id='1'") or die(mysql_error());
autoResponse($sendfrom,"Server Stopped",$messagervd);
}
$rvsms=strtoupper($messagervd);
if(($balanceRequest>0)||($rvsms=='BILL')){
$partial=substr($sendfrom,0,3);
if($partial==254){
$sendfrom='0'.substr($sendfrom,3,10);
}
//echo $sendfrom;
$sql=" select  admin_customer.customer_name,admin_customer.account_number , admin_customer.balance , admin_customer.due_date from admin_customer where admin_customer.phone_number='$sendfrom' order by date_created desc limit 1";

$qryresults= mysql_query($sql) or die(mysql_error());
         $cntreg_stmnt=mysql_num_rows($qryresults);
         $rsp='';
		if($cntreg_stmnt>0){
				while($rws=mysql_fetch_array($qryresults)){
				$count++;
				$account_number=$rws['account_number'];
				$balance=$rws['balance'];
				$due_date=$rws['due_date'];
				$customer_name=$rws['customer_name'];
				$rsp="$customer_name, your account $account_number has unpaid $balance due $due_date";
				
						
						autoResponse($sendfrom,$rsp,$messagervd);

				}
				
		}
//SendSms($sendfrom, $rsp, $debug=false);
}


///////Adminstrative

$syst=strtoupper($messagervd);
if($syst=='SYSTEMSTATUS'){
$qryresults= mysql_query("select count(*) from sms_smshandle") or die(mysql_error());
$rws=mysql_fetch_array($qryresults);

$msgTo="$rws[0] pending messages";
autoResponse($sendfrom,$msgTo,$messagervd);
///
                       
						
///SendSms($sendfrom, $msgTo, $debug=false);

}

?><?PHP
function autoResponse($message_from,$message_message,$request_type){
$sms_autoresponse='';
$effective_date=date("Y-m-d H:i:s");
$created_by='system auto save';
$date_created=date('Y-m-d');
$uuid=gen_uuid();
$stdcolumnsinster="date_created,
						changed_by,
						date_changed,
						voided,
						voided_by,
						date_voided,
						uuid";
$stdcolumnsvals="'$date_created',
						'$changed_by',
						'$date_changed',
						'$voided',
						'$voided_by',
						'$date_voided',
						'$uuid'";
						
$sms_receivedrqts="insert into sms_receivedrqts(receivedrqts_id,
						message_from,
						request_type,
						message_message,
						$stdcolumnsvals
						) values('$receivedrqts_id',
						'$message_from',
						'$request_type',
						'$message_message',
						$stdcolumnsvals)";
						$qryresults= mysql_query($sms_receivedrqts) or die(mysql_error());
}
?><?php
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

?><?php
//SERVER IP  eg localhost

//HTTP REQUEST FUNCTION
function httpRequest($url){
    $pattern = "/http...([0-9a-zA-Z-.]*).([0-9]*).(.*)/";
    preg_match($pattern,$url,$args);
    $in = "";
    $fp = fsockopen("$args[1]", $args[2], $errno, $errstr, 30);
    if (!$fp) {
       return("$errstr ($errno)");
    } else {
        $out = "GET /$args[3] HTTP/1.1\r\n";
        $out .= "Host: $args[1]:$args[2]\r\n";
        $out .= "User-agent: Crowny PHP client\r\n";
        $out .= "Accept: */*\r\n";
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        while (!feof($fp)) {
           $in.=fgets($fp, 128);
        }
    }
    fclose($fp);
    return($in);
}?><?php
function SendSms($phone, $msg, $debug=false)
{
 	  global $serverurl;
	  $serverurl="http://localhost:5000/";
   	  $url=$serverurl;
	  $url.= 'sendsms?';
      $url.= 'Recipient='.urlencode($phone);
      $url.= '&Message='.$msg;

	  //$url.= '&Message='.urlencode($msg);
     //xxecho $url;
       if ($debug) { echo "Request URL: <br>$url<br><br>"; }

      //Open the URL to send the message
      $response = httpRequest($url);
      if ($debug) {
           echo "Response: <br><pre>".
           str_replace(array("<",">"),array("&lt;","&gt;"),$response).
           "</pre><br>"; }

      return($response);
}
?><?php
function sendSMSReply(){

		$qry="SELECT  receptresp_id,phone_number, receipt_number FROM  sms_receptresp  where status='Pending'";
	$resultsSelect=mysql_query($qry) or die('Could not execute the query');
	$cntreg_stmnt=mysql_num_rows($resultsSelect);

		if($cntreg_stmnt>0){
		    $responses=$cntreg_stmnt;
		    $created_by='Auto Response';
			$date_created=date('Y-m-d');
			$uuid=gen_uuid();
			$stdcolumnsinster="date_created,changed_by,date_changed,voided,voided_by,date_voided,uuid";
			$stdcolumnsvals="'$date_created','$changed_by','$date_changed','$voided','$voided_by','$date_voided','$uuid'";
				while($rws=mysql_fetch_array($resultsSelect)){
                 $receptNum=trim($rws['receipt_number']);
				  $reciepient=trim($rws['phone_number']);
				    $receptresp_id=trim($rws['receptresp_id']);
				 $retrievedresponse=getReceiptPyament($receptNum);
	             
				 SendSms($reciepient, $retrievedresponse, $debug=false);
				 $SQLUpdate=" Update sms_receptresp set status='Sent' where receptresp_id=$receptresp_id ";
				 $resultsSelect=mysql_query($SQLUpdate) or die('Could not execute the query');
	              $insertSMSInd="Insert into sms_indsms(reciepient,message,$stdcolumnsinster) values   ('$reciepient','$retrievedresponse',$stdcolumnsvals)";
	$results=mysql_query($insertSMSInd) or die('Could not execute the query');

                }
	}else{
	$responses=0;
	}
return $responses;
}

?><?php

function getSystemStatus(){
$sql="select current_mode from sms_systemmode limit 1";
$query_Rcd_getbody= $sql;
$Rcd_tbody_results = mysql_query($query_Rcd_getbody) or die(mysql_error());
$cmdata='';
$current_mode='';
while ($rows=mysql_fetch_array($Rcd_tbody_results)){
$current_mode=$rows['current_mode'];
}

return trim(strtoupper($current_mode));

}

?><?php
function getReceiptPyament($receptNum){
$receptNum=strtoupper($receptNum);
		$qry=" SELECT  
		amount, 
		date_received,
		CONCAT(admin_person.first_name,' ',admin_person.middle_name,' ',admin_person.last_name ) person_fullname 
		
		FROM  sms_oafreceipt inner join admin_person on admin_person.person_id = sms_oafreceipt.person_id 
		 where ucase(oafreceipt_name)='$receptNum' 
		 
		 ";
	$resultsSelect=mysql_query($qry) or die('Could not execute the query');
	$cntreg_stmnt=mysql_num_rows($resultsSelect);

		if($cntreg_stmnt>0){
		   		while($rws=mysql_fetch_array($resultsSelect)){
                   $rwsResults=$rws['amount'].' Received On '.$rws['date_received'].' From '.$rws['person_fullname'];
                  }
		} else{
		$rwsResults='The Receipt Number '.$receptNum.' is not valid';
		}
 return $rwsResults;
}

?><?php
function updateCreditBalance($balance){
$sql="update sms_creditbalance set balance='$balance' ";
$Rcd_tbody_results = mysql_query($sql) or die(mysql_error());

}

function updateCreditBalanceStatus($balstatus){
$sql="update sms_creditbalance set sys_track='$balstatus' where creditbalance_id=1";
$query_Rcd_getbody= $sql;
$Rcd_tbody_results = mysql_query($query_Rcd_getbody) or die(mysql_error());


}
?><