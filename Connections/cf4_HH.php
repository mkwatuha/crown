<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cf4_HH = "localhost";
$database_cf4_HH = "ktldb";
$username_cf4_HH = "root";
$password_cf4_HH = "";
$cf4_HH = mysql_pconnect($hostname_cf4_HH, $username_cf4_HH, $password_cf4_HH) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_cf4_HH);
  
?>