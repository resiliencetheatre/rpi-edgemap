<?php
if($_SERVER['HTTPS']!="on") {
$redirect= "https://".$_SERVER['HTTP_HOST'];
header("Location:$redirect"); 
} 
if($_SERVER['HTTPS']=="on") {
$redirect= "https://".$_SERVER['HTTP_HOST'];
header("Location:$redirect");    
}


?>
