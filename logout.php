<?php

session_start();


header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0");


session_unset(); 
session_destroy(); 

header("Location: index.php");
?>
