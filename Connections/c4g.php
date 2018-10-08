<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_c4g = "localhost";
$database_c4g = "ktldb";
$username_c4g = "root";
$password_c4g = "";
$c4g = mysql_pconnect($hostname_c4g, $username_c4g, $password_c4g) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_c4g, $c4g);
?>