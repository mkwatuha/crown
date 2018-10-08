<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" src="../template/js/jquery-1.6.2.min.js"></script>
   
<script>
//sendSMS
function SendTimedSms(){
    var xmlHttp;
	try{	
		xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}
		catch (e){
		    try{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				alert("No AJAX!?");
				return false;
			}
		}
	}

xmlHttp.onreadystatechange=function(){
	if(xmlHttp.readyState==4){
	
	document.getElementById('responsesms').innerHTML=xmlHttp.responseText;
	setTimeout('SendTimedSms()',20000);
	}
}
xmlHttp.open("GET","sendSMS.php?statusAction=rrspd",true);
xmlHttp.send(null);
}
//setTimeout('checkURL()',20000);

///cehck open port

function checkURL() {

    var url = "http://localhost:5000/" ;
    var isAccessible = false;

    $.ajax({
        url: url,
        type: "get",
        cache: false,
        dataType: 'jsonp',
        crossDomain : true,
        asynchronous : false,
        jsonpCallback: 'deadCode',
        complete : function(xhr, responseText, thrownError) {
            if(xhr.status == "200") {
                isAccessible = true;
				setTimeout('checkURL()',20000);
				SendTimedSms();
                //alert("Request complete, isAccessible==> " + isAccessible); // this alert does not come when port is blocked
            }
        }
    });
    return isAccessible;

}
function deadCode() {
    alert("Inside Deadcode"); // this does not execute in any cases
}

</script>
</head>

<body>
<table width="564" height="204" border="1">
  <tr>
    <td><input type="button" onclick="checkURL();"</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
