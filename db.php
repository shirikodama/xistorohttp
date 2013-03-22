<?php

  // common database routines to store/access/modify our components

class componentdb {

    const TRANSISTOR = 1;

    public function __construct ($file) {
	try {
	    $this->db = new PDO ("sqlite:$file", NULL, NULL);
	} catch (PDOException $e) {
	    die("400 db connect failed: " .  $e->getMessage () . "\n");	
	}
	$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public static function initdb ($file) {
	if (! file_exists ($file)) {
	    file_put_contents ($file, '');
	    chmod ($file, 0755);
	}
	$curq = "create table if not exists components (";
	$curq .= "cid bigint, ";
	$curq .= "ctype int, ";
	$curq .= "output text, ";
	$curq .= "p1 tinyint, ";
	$curq .= "p2 tinyint, ";
	$curq .= "oval tinyint,";
	$curq .= "primary key(cid)";

	$curq .= ')';
	$db = new PDO ("sqlite:$file", NULL, NULL);
	$q = $db->prepare ($curq);
	$rv = $q->execute ();
	return $rv;
    }

    // create/modify a component
    public function create ($cid, $ctype, $output, $p1=0, $p2=0, $oval=NULL) {
	if ($this->test ($cid)) {
	    $curq = "update components set output=?,p1=?,p2=?,oval=? where cid=?";
	    $q = $this->db->prepare ($curq);
	    $q->bindParam (1, $output);
	    $q->bindParam (2, $p1);
	    $q->bindParam (3, $p2);
	    $q->bindParam (4, $oval);
	    $q->bindParam (5, $cid);
	    $rv = $q->execute ();
	} else {
	    $curq = "insert into components (cid, ctype, output, p1, p2, oval) values (?,?,?,?,?,?)";
	    $q = $this->db->prepare ($curq);
	    $q->bindParam (1, $cid);
	    $q->bindParam (2, $ctype);
	    $q->bindParam (3, $output);
	    $q->bindParam (4, $p1);
	    $q->bindParam (5, $p2);
	    $q->bindParam (6, $oval);
	    $rv = $q->execute ();
	}
	return $rv;
    }    

    // return the input pin values, NULL if component doesn't exist
    public function test ($cid) {
	$curq = "select * from components where cid=?";
	$q = $this->db->prepare ($curq);
	if (! $q) {
	    error_log ("can't prepare $curq");
	    return NULL;
	}
	$q->bindParam (1, $cid);
	if ($q->execute () === false)
	    return NULL;
	return $q->fetchObject ();
    }

    // update the pin values
    public function pinvals ($cid, $p1, $p2, $oval) {
	$curq = "update components set p1=?, p2=?,oval=? where cid=?";
	$q = $this->db->prepare ($curq);
	$q->bindParam (1, $p1);
	$q->bindParam (2, $p2);
	$q->bindParam (3, $oval);
	$q->bindParam (4, $cid);
	$rv = $q->execute ();
	return $rv;
    }
  
}

?>