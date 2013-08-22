<?php
require_once("include/bittorrent.php");
dbconn();
require_once(get_langfile_path());
loggedinorreturn();
$body = $_POST['body'];
print ("<div width=100% border=1 cellspacing=0 cellpadding=10 align=left>\n");
print ("<div><div align=left>".format_comment($body)."<br /><br /></div></div></div>");
?>
