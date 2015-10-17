<?php
include_once('/opt/lampp/lib/php/DB.php');
include_once('/opt/lampp/lib/php/DB/oci8.php');

/** 
* Maintains the connection to Oracle DB
* @package db_source 
* @version 2.0.1
* @author JL
*/
class ora_db
{
 public $context;
 public $MSG_r;
 public $db;
 public $oraConn;
 public $dsn = array(
    'phptype'   => 'oci8',
    'username'  => 'irene',
    'password'  => 'imarket',
    'hostspec' => "(DESCRIPTION =
    (SOURCE_ROUTE=YES)
    (ADDRESS_LIST=
      (ADDRESS=(PROTOCOL=TCP)(HOST=link2.helex.gr)(PORT=1633))
      (ADDRESS = (PROTOCOL = TCP)(HOST = athex-db2-vip.athexnet.gr)(PORT = 1524))
    )
    (CONNECT_DATA =
      (SID = SURV2)
      (SERVER = DEDICATED)
    )
  )
 ",                            
 );
 
 function __construct($parent)
 {
	$this->context = $parent;
	$this->db="";
	}

/** 
* Performs the connection to Oracle DB
* @version 2.0.1
* @author JL
*/ 
 function connect_to_source()
 {
  $parent=$this->context;
 
//  if ($parent->init_flag==_DO_INITIALIZATION || $parent->init_flag==_DO_PARTIAL_INITIALIZATION) {
 	  $this->db = new DB_oci8();
	  if (!$this->db)
	       die("connect_to_source - oci8 is null! ".OCIError()."\n");
		   else
		   print("connect_to_source - oci8 is OK! "."\n");
		   
	  $this->oraConn = $this->db->connect($this->dsn);

	  if (DB::isError($this->oraConn)){
			$parent->errorlog->addlog ( "ORA Connection Error - ".$this->oraConn->getCode()." == ".$this->oraConn->getMessage() );
			$parent->errorMessage = $this->oraConn->getMessage() . "- Info:".$this->oraConn->getDebugInfo();
			return false;	
			}
	  if ($this->oraConn!=1) {
	      $parent->errorMessage= "ORA Connection Error";
		  return false;
		  }
//	 }
  $parent->errorlog->addlog ("Source:".$parent->source);
  return true;	
  }
  
 function db_query() 
 {
  $parent=$this->context;
  if (!$this->db) 	
      die("Object oci8 is null!\n");
  $stmt = $this->db->Prepare( stripslashes($this->query)); 
  $error = @ocierror();

  if (DB::isError($stmt) || $stmt==false) {
		$parent->errorlog->addlog(  'Prep err! ' .$stmt->getCode()."==".stripslashes($this->query));
		//$this->disconnectORA();
		return $stmt; 
		} 
  if ($this->db->Execute($stmt)) {
  		$parent->errorlog->addlog( 'db_query: -Execute OK! ');	  
		return $stmt; 
		} 
//db_check_errors($php_errormsg); 
	$this->db->freeResult($stmt); 
	//$this->disconnectORA();
	
	return $stmt; 
  } 

} // class
?>
