<?php
$reset_link = str_replace("globalsite.php", "password_reset.php", $_SERVER["PHP_SELF"]);
$reset_link = "http://".$_SERVER["HTTP_HOST"]."/".$reset_link;


?>