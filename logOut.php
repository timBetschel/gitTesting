<?php
//found log out code from: https://www.php.net/manual/en/function.session-unset.php

session_start();
session_unset(); //unsets session variables
session_destroy();//ends current session
header("location: index.php");
?>
