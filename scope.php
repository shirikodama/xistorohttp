<?php

  // test the state of a component

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once ("cmn.php");

$opts = $_GET;
if (! isset ($opts ['id']))
    die ('{"error":"scope.php?id=[transistor-id]"}');

$db = opendb ();

$part = $db->test ($opts ['id']);    
print json_encode ($part);

?>