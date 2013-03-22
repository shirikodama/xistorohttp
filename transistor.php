<?php

  // become an internet transistor; use fab.php to make the critters
  //  ?id=[transistor-id]&pin=[1|2]&state=[0|1]

require_once ("cmn.php");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$opts = $_GET;
if (! isset ($opts ['pin']) || ! isset ($opts ['state']) || ! isset ($opts ['id']))
    die ('{"error":"transistor.php?id=[transistor-id]&pin=[c|b]&state=[0|1]"}');

$db = opendb ();

$part = $db->test ($opts ['id']);    
if (! $part)
    die ('{"error":"unknown id"}');
if ($opts ['pin'] == 1 || $opts ['pin'] == 'collector' || $opts ['pin'] == 'c')
    $part->p1 = $opts ['state'];
if ($opts ['pin'] == 2 || $opts ['pin'] == 'base' || $opts ['pin'] == 'b')
    $part->p2 = $opts ['state'];
$oval = oval ($part);
$db->pinvals ($part->cid, $part->p1, $part->p2, $oval);



$output = sprintf ('{"output":%d}', $oval);
print $output;

if ($part->output) {
    if ($part->oval === NULL || @$opts ['force'] || $part->oval != $oval) {
	$url = sprintf ("%s&state=%d", $part->output, $oval);
	error_log ("url=$url");
	post($url);
    } else
	error_log ("damping dup oval: $part->cid = $oval");
} else
    error_log ("unwired output for $part->cid out=$oval");

function post ($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    //getting response from server
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
	// moving to display page to display curl errors
	error_log ("transistor error: " . curl_error($ch));
	return NULL;
    } else {
	curl_close($ch);
    }
    return $response;
}


?>