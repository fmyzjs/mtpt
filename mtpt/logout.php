<?php
require_once("include/bittorrent.php");
dbconn();
logoutcookie();
// if($_SERVER['REQUEST_URI']=='/logout.php')logoutcookie();//修正非根目录不能退出
//logoutsession();
//header("Refresh: 0; url=./");
Header("Location: " . get_protocol_prefix() . "$BASEURL/");
?>
