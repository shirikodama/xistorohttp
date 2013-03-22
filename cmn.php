<?php

require_once ("db.php");

function opendb ($init=false) {
    $dbfile = './component.db';
    if ($init)
	componentdb::initdb ($dbfile);
    $db = new componentdb ($dbfile);
    return $db;
}

function oval ($part) {
    $oval = 0;
    switch ($part->ctype) {
    case componentdb::TRANSISTOR:
	// NPN transistor
	$oval = $part->p1 && $part->p2 ? 1 : 0;
	break;
    }
    return $oval;
}


?>