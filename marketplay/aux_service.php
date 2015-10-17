<?php
/** 
* This script contains a manufacturing class, which serves requests independed of the market object located in service3.php
* @package aux_service
* @version 1.0.0
* @author JL
*/

require_once dirname(__FILE__) . '/dp_database.php';
require_once dirname(__FILE__) . '/class.filemarket.php';
require_once dirname(__FILE__) . '/mos_func.php';
require_once dirname(__FILE__) . '/csv_reader.php';
/**
* Include scripts with classes of all services
*/
require_once dirname(__FILE__) . "/class.msg_log.php";
require_once dirname(__FILE__) . '/aux_msgadmin.php';
require_once dirname(__FILE__) . '/aux_misc.php';
require_once dirname(__FILE__) . '/aux_setup.php';
require_once dirname(__FILE__) . '/aux_sessions.php';
require_once dirname(__FILE__) . '/aux_graphEvents.php';
require_once dirname(__FILE__) . '/aux_oraQueryParams.php';
require_once dirname(__FILE__) . '/aux_docs.php';
require_once dirname(__FILE__) . '/aux_traders.php';
require_once dirname(__FILE__) . '/class.ora_db.php';

/**
* $buffer contains $_POST (case of a form send) or $_REQUEST (regular AJAX request) serialized!
* Session included
*/
global $main_socket;
global $aux_services;
global $filter;
global $ses;
global $preg;

$preg="/[\s,]*\{([^\{]+)\}[\s,]*|[\s,]+/";

include("supersocket.class.php");

function sig_handler($signo)
{
 echo "sig_handler=".$signo."\n";
     switch ($signo) {
         case SIGTERM:
             // handle shutdown tasks
             exit;
             break;
         case SIGHUP:
             // handle restart tasks
             break;
         case SIGUSR1:
             echo "I am in life !!!\n";
             break;
         default:
             // handle all other signals
     }
}

//pcntl_signal(SIGTERM, "sig_handler");
//pcntl_signal(SIGHUP,  "sig_handler");
if (pcntl_signal(SIGUSR1, "sig_handler")==false)
	echo "Could not set SIGUSR1 !!!!\n";


				   
function handle_client($socket_id, $channel_id, $buffer, &$obj)
{
 global $aux_services;
/**
* $buffer contains $_POST (case of a form send) or $_REQUEST (regular AJAX request) serialized!
* Session included
*/		
	$data=unserialize($buffer);
	//$data=json_decode($buffer);
	$cmd=$data["task"];
/**
* Remove task key from $data to prevent erros in case $data contain $_POST var
*/	
	unset($data["task"]); 
	
/*		
if ($cmd=='updateMarketOptions'){
		print  "interact=[". $buffer."==".$cmd."]\n";
	print_r($data);
	}
*/	
/**
* Call method using the Manufactoring class aux_services
*/
		  $aux_services->$cmd( $obj, $socket_id, $channel_id,$data);
	}

 $aux_services = new aux_services("aux_misc","aux_setup","aux_sessions","aux_misc","graphEvents","oraQueryParams","aux_docs","aux_msgadmin","aux_traders");

// =========================== Connection to SERVICE3 ==========================================	
echo "aux_service=> Creating socket....\n";
posix_kill(posix_getpid(), SIGUSR1);
$main_socket = new SuperSocket(array("*:12346")); // will listen on ALL IPs over port 10000
$main_socket->assign_callback("DATA_SOCKET_CHANNEL", "handle_client");
$main_socket->recvq=1074;
$main_socket->start();
$main_socket->loop();

/**
*	aux_basic
*/
class aux_basic
{
 private static $m_dp;
 private static $m_CRMdp;
 private static $m_ORAdp;
 private static $m_errorlog;
 private static $m_this;
 public  $dp;
 public  $CRMdp;
 public  $ORAdp;
 public  $errorlog;
 public $mixins;
 public $source;
 public $errorMessage;
/**
* 	Basic Class (inherited into all other classes) - Create DB Session
*/ 
 function __construct()
 {  
  self::$m_this=$this;
  $this->source = "aux_services";
  $this->errorlog=$this->get_errorlog();
  $this->dp=$this->get_dp();
  $this->CRMdp=$this->get_crm();
  $this->ORAdp=$this->get_ora();
  
/*  
  $this->errorlog = new msg_log;
  
  $this->dp = new dp_database( 'localhost', 'root', '', 'imk_mplay', '', 0 );
  $this->CRMdp = new dp_database( 'localhost', 'root', '', 'CRM1', '', 0 );
  if (!$this->CRMdp)
  		print("CRM error DB!"."\n");
  
  $this->ORAdp = new ora_db($this);
  if ( $this->ORAdp->connect_to_source() == false)
		$this->errorlog->addLog("ORA Connection Error - ".$this->oraConn->getCode()." == ".$this->oraConn->getMessage());
*/		
  }
  
  public static function get_dp()
    {
	    if (!self::$m_dp)
        {
            self::$m_dp = new dp_database( 'localhost', 'root', '', 'imk_mplay', '', 0 );
			print("imk_mplay connection OK!"."\n");
        }
        return self::$m_dp;
    }
  
  public static function get_crm()
    {
        if (!self::$m_CRMdp)
        {
            self::$m_CRMdp = new dp_database( 'localhost', 'root', '', 'CRM1', '', 0 );
			if (!self::$m_CRMdp)
  				print("CRM error DB!"."\n");
				else
				  print("CRM connection OK!"."\n");
        }
        return self::$m_CRMdp;
    }
	
  public static function get_ora()
    { 
        if (!self::$m_ORAdp)
        {
         //  echo $this->source."\n";
			self::$m_ORAdp = new ora_db(self::$m_this);
  			if ( self::$m_ORAdp->connect_to_source() == false)
				 $this->errorlog->addLog("ORA Connection Error - ".self::$m_ORAdp->oraConn->getCode()." == ".self::$m_ORAdp->oraConn->getMessage());
        }
        return self::$m_ORAdp;
    }
	
  public static function get_errorlog()
    {
        if (!self::$m_errorlog)
        {
            self::$m_errorlog = new msg_log;
			print("errorlog connection OK!"."\n");
        }
        return self::$m_errorlog;
    }	 
 } // Class basic

/**
* 	Main Class - Create the Manufacturing object
*/ 
class aux_services extends aux_basic
{
 public $objs=array();
/**
* 	Create as objects all arguments passed to __construct, in order to create multi-inheritance
*/  

				   
 public function __construct() 
 {
  	
	$drones = func_get_args();
    foreach($drones as $drone) 
			$this->objs[$drone] = new $drone();
    foreach($this->objs as $obj) 
			$obj->hive = $this;
  }

/**
* 	Called when method does not exist!
*/  
 public function __call($name, $arguments)
    {
        if (!empty($this->objs))
        {
            foreach ($this->objs as $mixin)
            {
                if (method_exists($mixin, $name))
                {
                    return call_user_func_array(array($mixin, $name), $arguments);
                }
            }
        }
       
	    print('Non-existent method was called in class '.__CLASS__.': '.$name);
    }

/**
* 	Called when attribute does not exist!
*/	
 public function __get($attr) 
 {
    foreach($this->objs as $obj)
      if(isset($obj->$attr))
        return $obj->$attr;
  }
 }
?>