<?php
//echo textsms.php?message=$message&recipent=$senderno
echo $_GET['message'];
echo $_GET['recipient'];

$fpath="receivedtexts/message.txt";
file_put_contents($fpath, $_GET['message'].$_GET['recipient']);



?>