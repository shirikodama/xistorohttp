<?php

  // fab a component like a transistor
  //   ?component=transistor&output=url[&id=[random-number]]
  //  returns: {"id":"[transistor-id]"}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


require_once ("cmn.php");

$opts = $_GET;

$db = opendb (true);

switch ($opts ['component']) {
case 'transistor':
    $ctype = componentdb::TRANSISTOR;
    break;
default:
    die ('{"error":"unknown component"}');
    break;
}

if ($opts ['id']) {
    $id = $opts ['id'];
    $part = $db->test ($id);
    if ($part) {
	if (isset ($opts ['p1']))
	    $p1 = $opts ['p1'];
	else
	    $p1 = $part->p1;
	if (isset ($opts ['p2']))
	    $p2 = $opts ['p2'];
	else
	    $p2 = $part->p2;
	if (isset ($opts ['output']))
	    $out = $opts ['output'];
	else
	    $out = $part->output;
	$oval = oval ($part);
    } else {
	$p1 = 0;
	$p2 = 0;
	$out = $opts ['output'];
	$oval = NULL;
    }
} else {
    $id = floor (rand ()*100000000);
    $p1 = 0;
    $p2 = 0;
    $out = $opts ['output'];
    $oval = NULL;
}

$db->create ($id, $ctype, $out, $p1, $p2, $oval);

die (sprintf ('{"id":"%s"}', $id));

?>