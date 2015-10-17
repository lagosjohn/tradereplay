<?php
/** 
* External sell of main procces. Initially new object of messages_io class created, marketprocces started and i/o ports establishd to Meteor
* @package Service3
* @version 2.0.1
* @author JL
*/

require_once(dirname(__FILE__) ."/class.filemarket.php"); 
require_once (dirname(__FILE__) .'/JSON.php'); 
include("supersocket.class.php");

DEFINE('_ch_accounts','accounts');
DEFINE('_ch_orderbookS','OBS');
DEFINE('_ch_mktdepthB','MDB');
DEFINE('_ch_mktdepthB_temp','MDB');
DEFINE('_ch_mktdepthS','MDS');
DEFINE('_ch_orders','orders');
DEFINE('_ch_trades','trades');
DEFINE('_ch_hilo','hiloPrices');
DEFINE('_ch_events','events');
DEFINE('_ch_events_temp','events');
DEFINE('_ch_traders','traders');
DEFINE('_ch_announce','hbrd');
DEFINE('_ch_mktsearch','mktsearch');
DEFINE('_ch_messages','messages');

//  indexes into channels array
DEFINE('_buffered', 0);	// true/false
DEFINE('_msg', 1);		// msg string reference
DEFINE('_msg_local',1);
DEFINE('_cache_len',3);
DEFINE('_reTransmit',-1);
DEFINE('_BUF',1);

//print ("GET=".$argv[1]);
global $super_market;
global $preg;

/** 
* Create Only once when script loads, new array of objects MarketSocket is created!
* @version 2.0.1
* @author JL
*/
$super_market = new MarketSocket;
$preg="/[\s,]*\{([^\{]+)\}[\s,]*|[\s,]+/";
//===========================================================================


/** 
* Callback function called when a command written from a client to the {@link $socket_id, $channel_id}!
* @version 2.0.1
* @author JL
* @param string $buffer -contains comma separated client data-, int $socket_id, int $channel_id (parameters passed from the SuperSocket class)
*/

function handle_client($socket_id, $channel_id, $buffer, &$obj)
	{
	global $super_market;
	global $preg;
		//$obj->write($socket_id, $channel_id, $buffer."==".$channel_id."==".$socket_id);
//	list($cmd,$comx_data)=explode(',',$buffer);
	
	$ar=preg_split($preg,stripslashes($buffer), 0,  PREG_SPLIT_DELIM_CAPTURE);

	$cmd=$ar[0];
	$comx_data=$ar[1];
	if (count($ar) == 2)
	    list($data,$ses)=explode("_",$comx_data);
		else{
			$data=$ar[1];
			$ses=ltrim($ar[3],"_");
			$super_market->markets[$ses]->filter="{".$ar[2]."}";
			}
//if ($cmd!='market_tick')
//		print  "interact=". count($ar)."==".$data."==".$ses."\n";
	if (method_exists('Market', $cmd))
		{
		 
		  if ( empty($super_market->markets[$ses]) )
				$super_market->addMarket($ses);
		  $super_market->markets[$ses]->$cmd( $obj, $socket_id, $channel_id,$data);
		 }
	}


// =========================== Connection to SERVICE3 ==========================================	

$super_market->socket = new SuperSocket(array("*:12345","*:12344")); // will listen on ALL IPs over port 10000
$super_market->socket->assign_callback("DATA_SOCKET_CHANNEL", "handle_client");
$super_market->socket->recvq=1074;
$super_market->socket->start();
$super_market->socket->loop();

// =========================== Connection to METEOR ==============================================
if (!is_resource($super_market->op) or feof($super_market->op) or ($haswritten and !$buf)) {
		echo "Reconnecting to Meteor\n";
				
		if (!($super_market->op = fsockopen("127.0.0.1", 4671, $errno, $errstr, 5))) {
			echo "Meteor not responding\n";
			sleep(5);
			continue;
			}
			
		socket_set_blocking($super_market->op,false);
		}

 
/** 
* Class that holds all communication ports, an array of new market object
* @version 2.0.1
* @author JL
*/
class MarketSocket
{
 
 public $markets;
 public $op;
 public $socket;
 public $ses;
 public $init;
 
 function __construct()
 {
 $this->markets=array();
 $this->init=0;
 /*$this->socket = new SuperSocket(array("*:12345","*:12344")); // will listen on ALL IPs over port 10000
 $this->socket->assign_callback("DATA_SOCKET_CHANNEL", "handle_client");
 $this->socket->recvq=1074;
 $this->socket->start();
 $this->socket->loop();*/
 }
 
 function addMarket($ses)
 {
  $this->markets[$ses]=new Market($ses);
  $this->markets[$ses]->parent=$this;
  print("addMarket==".$ses." op=".$this->op."\n");
  }
 
}

/** 
* Class that inherits {@link MarketSocket}, services all the Client requests, passes results from main procces to the Client
* @version 2.0.1
* @author JL
* @param string $ses -the Session where this market exists-
*/
class Market extends MarketSocket
{

public $__marketbeat_flag;
public $dp,$columns,$data_io,$ctrl_io, $delay, $mkt_stat,$orders_cntr,$fwrd_step,$mkt_action;
public $colors;
public $parent;
public $phases_info;
public $isin;
public $filter;  // oraSecs query
public $last_time;
public $ses;
public $series; // counter used for Bid/Ask Graphics
public $tableIS;
public $tableIT;
public $tradeEngineAvailability;
public $msg_out;
public $channels;
public $filesDir;

function __construct($ses)
{
 $this->ses=$ses;
 $this->series=0;
 $this->__marketbeat_flag=false;
 $this->orders_cntr=0;
 $this->mkt_action='';
 $this->colors=array("#F454AE","#15F93A","#5515F9","#C954F4","#C0BD60","#09891E","#15A6F9","#F2EA11","#F7A31F","#E5332A");
 $this->filesDir="js/graphs/";
 
// $this->channels=array();
 
 $this->channels=array(
 				_ch_accounts=>array(0,'','',1,9=>array()),
 				_ch_orderbookS=>array(true,'','',0),
				_ch_mktdepthB=>array(0,'','',1,9=>array()),
				_ch_orders=>array(0,'','',5,9=>array()),
				_ch_trades=>array(0,'','',5,9=>array()),
				_ch_hilo=>array(true,'','',1,9=>array()),
				_ch_events=>array(0,'','',20,9=>array()),
				_ch_traders=>array(true,'','',1,9=>array()),
				_ch_announce=>array(0,'','',0),
				_ch_mktsearch=>array(0,'','',1,0=>array()),
				_ch_messages=>array(0,'','',1,0=>array()),
				);
}

 function initGraph()
 { 
	$this->createFile( $this->secInfo->graphProjectedFname1,"");
	$this->createFile( $this->secInfo->graphProjectedFname2,"");
	$this->createFile( $this->secInfo->graphProjectedEventsFname,"xml");
	$this->createFile($this->secInfo->graphBidAskFname,"xml");
	$this->init_BidAskFile($this->secInfo->graphBidAskFname,"txt");
	$this->createFile($this->secInfo->graphBidAskEventsFname,"xml");
	//$this->createFile("js/graphs/projected_data1_".$this->ses.".txt","");
	//$this->createFile("js/graphs/projected_data2_".$this->ses.".txt","");
	//$this->createFile("js/graphs/projected_events_".$this->ses.".xml","xml");
	//$this->init_BidAskFile("js/graphs/bidask_data_".$this->ses,"txt");
	//$this->createFile("js/graphs/bidask_events_".$this->ses.".xml","xml"); 
	print("Graph for Session:".$this->ses."-".$this->secInfo->getIsinSec()." Inited ...!\n");
	}

function removeSessionFiles()
{
 $this->secInfo->removeSessionFiles();
 
 /*if (file_exists($this->filesDir.$this->secInfo->graphProjectedFname1))
 	unlink($this->filesDir.$this->secInfo->graphProjectedFname1);
 if (file_exists($this->filesDir.$this->secInfo->graphProjectedFname2))	
 	unlink($this->filesDir.$this->secInfo->graphProjectedFname2);
 if (file_exists($this->filesDir.$this->secInfo->graphProjectedEventsFname))
	 unlink($this->filesDir.$this->secInfo->graphProjectedEventsFname);
 if (file_exists($this->filesDir.$this->secInfo->graphBidAskFname))
 	unlink($this->filesDir.$this->secInfo->graphBidAskFname);
 if (file_exists($this->filesDir.$this->secInfo->graphBidAskEventsFname))
	 unlink($this->filesDir.$this->secInfo->graphBidAskEventsFname);
 if (file_exists($this->filesDir.$this->secInfo->graphHourFname))
 	unlink($this->filesDir.$this->secInfo->graphHourFname);

 if (file_exists($this->filesDir.$this->secInfo->graphTradersFname['B']))
 	 unlink($this->filesDir.$this->secInfo->graphTradersFname['B']);
 
 if (file_exists($this->filesDir.$this->secInfo->graphTradersFname['S']))
	 unlink($this->filesDir.$this->secInfo->graphTradersFname['S']);*/
 }
 	
function __destruct ()
{
 unset($this->data_io);
 }
 
function newSeries()
{
 $this->series++;
 return $this->series;
 }

function setMeteorMsgs()
{
 $this->channels[_ch_accounts][_msg]=&$this->data_io->accScript;
 $this->channels[_ch_accounts][_msg + _msg_local]=&$this->msg_out;
 $this->channels[_ch_orderbookS][_msg]=null;
 $this->channels[_ch_mktdepthB][_msg]=&$this->data_io->mdScriptB;
 $this->channels[_ch_mktdepthB][_msg + _msg_local]=&$this->msg_out;  
 $this->channels[_ch_orders][_msg]=&$this->data_io->ordersScript;
 $this->channels[_ch_trades][_msg]=&$this->data_io->tradesScript;
 $this->channels[_ch_trades][_msg + _msg_local]=&$this->msg_out;
 $this->channels[_ch_hilo][_msg]=&$this->data_io->hiloScript;
 $this->channels[_ch_events][_msg]=&$this->data_io->eventsScript; 
 $this->channels[_ch_events][_msg + _msg_local]=&$this->msg_out; 
 $this->channels[_ch_traders][_msg]=&$this->data_io->tradersScript; 
 $this->channels[_ch_announce][_msg]=null; 
 $this->channels[_ch_mktsearch][_msg + _msg_local]=&$this->msg_out;
 $this->channels[_ch_messages][_msg + _msg_local]=&$this->msg_out; 
 }
 
function getMeteorMsgs()
{
 foreach($this->channels as $key=>$val)
 {
    if ($val[_buffered]==_BUF) {
		$this->sendMsgToMeteor($key);
         print "\n Buffered channel ".$key ." transmited! \n";
	 	}
	}

 }

/** 
* Common function to send to METEOR messages comming from main process
* @version 2.0.1
* @author JL
* @param string $channel, int $local -where message is internally created, or buffered from the main process-
*/
function sendMsgToMeteor($channel, $local=0)
{
  $this->tradeEngineAvailability = false;
  $isin = $this->secInfo->getIsinSec();
    
  if ($local == _reTransmit) // reTransmit msgs cache
  {   
   for ($i=0; $i<$this->channels[$channel][_cache_len]; $i++)
   		if ($this->channels[$channel][9][$isin][$i]!="")
			$err=fwrite($this->op, "ADDMESSAGE ".$channel."_".$this->ses." ".$this->channels[$channel][9][$isin][$i]."\r\n");
   return $err;
   }
   
  if ($this->channels[$channel][_buffered]==true && $this->mkt_stat == _MARKET_RUNNING)
      return true;
//	  print ("sendMsgToMeteor - ".$channel."_".$this->ses."==".$this->channels[$channel][_buffered]."\n");
//  if ($channel==_ch_accounts && $local==1) 
 //      print ("sendMsgToMeteor - ".$this->channels[$channel][_msg+$local]."\n"); 
	  
  $script=$this->channels[$channel][_msg+$local];	  
//   print ("sendMsgToMeteor - ".$channel." ".$script."\n");
  if ($script!='' && $script!=null)
	  $err=fwrite($this->op, "ADDMESSAGE ".$channel."_".$this->ses." ".$script."\r\n");
	  
  if ($this->channels[$channel][_cache_len] > 0)
  {
   
   if (count($this->channels[$channel][9][$isin]) > $this->channels[$channel][_cache_len])
 	   array_shift($this->channels[$channel][9][$isin]);
   array_push($this->channels[$channel][9][$isin],$script);  
   }
   
  $this->channels[$channel][_msg+$local]="";
  
  return $err;
 }


/**
 * @deprecated deprecated since version 2.0
 * @subpackage Service3 
 */  
function startup_fic($obj,$socket_id, $channel_id,$data)
{
//global $ctrl_io;
print "startup_fic ==".$data." ses=".$this->ses."\n";
$s="ok";
/*
$this->ctrl_io=new messages_io("imarket","ficsnd");
	if ($this->ctrl_io!=false)
	{
	if ($this->ctrl_io->_Init($data)!=false) {	
		$this->ctrl_io->createTables();	
		$this->ctrl_io->createFilter(); // read Market Configuration	
		$this->ctrl_io->prepareMarket();
		$s="ok";
		}
	}
*/
$obj->write($socket_id, $channel_id, $s);
}

/** 
* The first function called from the Client (detailsPanel.js) when button _TOOLBAR_START_START pressed, Initializes the main process

* @version 2.0.1
* @author JL
* @param Market $obj, int $socket_id,  int $channel_id, string $data -fdate- (parameters passed from the calling function {@link handle_client})
*/
function startup_sup($obj, $socket_id, $channel_id,$data)
{
//global $data_io;
$s="error";

print "startup_sup =="." ses=".$this->ses."\n";

$this->data_io=new messages_io("imk_mplay","supsnd");
	if ($this->data_io!=null)
	{	
	if ($this->data_io->_Init($data,$this->ses)==false) {	
		$this->parent->init = 1;
		$s=$this->data_io->errorMessage;
		$obj->write($socket_id, $channel_id, $s);
		return;
		}
	
	print "Enter  createTables..."."\n";
	$ret = $this->data_io->createTables();	
	
	print "Enter  createFilter..."."\n";
	if ($this->data_io->createFilter()==false) { // read Market Configuration	
		$s=$this->data_io->errorMessage;
		$obj->write($socket_id, $channel_id, $s);
		return;
		}
	
	print "Enter  getSession..."."\n";
	$this->data_io->getSession($this->ses);	
    $mkt = $this->data_io->markets[$this->data_io->cfg->market];

	if ($mkt->qsec_missed != "" && $this->data_io->init_flag!=_DO_INITIALIZATION)
		$this->data_io->init_flag=_DO_PARTIAL_INITIALIZATION;
	
	print "Enter  prepareMarket... init=".$this->data_io->init_flag."==".$mkt->qsec_missed."\n";	
	
	if ($this->data_io->prepareMarket()==false) {
	    $s=$this->data_io->errorMessage;
		$obj->write($socket_id, $channel_id, $s);
		return;
		} 
	
	$this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
	$this->isin = $this->secInfo->getIsinSec();
	
	print "get Active : ".$this->isin."\n";
	$this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);

//	print("SEC:".$this->data_io->markets[$this->data_io->cfg->market]->getSecQstr()."\n");//"==".$this->data_io->markets[$this->data_io->cfg->market]->getSecQstr()
	$msgId='IS';

    $this->tableIS=$this->data_io->Msgs_collection->getMsg('IS');
    if( ($this->tableIS instanceof Collection) == false) {
	 	 $this->data_io->errorlog->addlog( 'get_msgTrades = table is null - IS');
	     die("Messages template problem");
		 }
	$this->tableIT=$this->data_io->Msgs_collection->getMsg('IT');
    if( ($this->tableIT instanceof Collection) == false) {
	 	 $this->data_io->errorlog->addlog( 'get_msgTrades = table is null - IT');
	     die("Messages template problem");
		 }
	
	$this->setMeteorMsgs(); 
	
	$s1="";	
	if ($this->data_io->cfg->init == _DO_INITIALIZATION || $this->data_io->init_flag ==_DO_PARTIAL_INITIALIZATION){
		$this->custom_start='99999999';
		
		$this->data_io->creOBStats=1;
		do
		{
		  $this->phases_info=$this->secInfo->getPhase();
		  if ($this->secInfo->sessionSecs == 0)
		  {
		  // $this->createFile($this->secInfo->graphBidAskFnameGlobal,"txt");  //init_BidAskFile
	      // $this->createFile($this->secInfo->graphBidAskEventsFnameGlobal,"xml");
		   $this->last_time='00000000';
		   $this->createOB();
		   $this->data_io->createMarketStats();
		   $this->secInfo->gatherInfo();
		   $this->phases_info->resetPhasesCntr();
		    $cp = $this->phases_info->get_currphase();
			print("INIT - phase pos=$cp"."\n");
			$this->data_io->createHourGraphData();
			$this->createHourTradesGraph();	
			$this->createTradersGraph("B");  // pie chart
			$this->createTradersGraph("S");	
		   }
		  }while(($this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->setActiveNextSec()));
			  
 	    $this->data_io->creOBStats=0;
		
		
		$s1="partial";
		}
	
	// Reset anyway the init flag
	$this->data_io->cfgInit(0);
		
	$this->secInfo=$this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
	$this->isin = $this->secInfo->getIsinSec();
	$this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);
//	$this->data_io->createMarketStats();
	do
		{
		 print "Init : ".$this->secInfo->getIsinSec()."\n";
		 $this->phases_info=$this->secInfo->getPhase();
		 foreach($this->channels as $id=>$channel)
		 	if ($this->channels[$id][_cache_len] > 0)
				$this->channels[$id][9][$this->secInfo->getIsinSec()]=array();
		 
//		 $this->initGraph();
		$this->data_io->readSecStatistics();
	
	//	$this->data_io->createHourGraphData();
	//	$this->createHourTradesGraph();  // write Trades per hour into text file
		
	//	$this->createTradersGraph("B");  // pie chart
	//	$this->createTradersGraph("S");		
		} while(($this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->setActiveNextSec())!=false);
	
	$this->secInfo=$this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
	$this->isin = $this->secInfo->getIsinSec();
	$this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);
	
	print "startup_sup END: "." ses=".$this->ses." ISIN=".$this->secInfo->getIsinSec()."\n";	 	 
	$s="ok:".$s1;
	}
	
//		Clear messages buffer from METEOR
//system ("killall -s SIGUSR1 meteord", $ret);
//print "Clear message: ".$ret."\n";

$obj->write($socket_id, $channel_id, $s);	
}

function disconnectAll()
{
 $this->disconnectFic();
 return($this->disconnectSup());
 }
 
function disconnectFic()
{
// if( ($this->ctrl_io->sourceObj instanceof logs_source))
//      return($this->ctrl_io->sourceObj->disconnectORA());
 }

function disconnectSup()
{
 if( ($this->data_io->sourceObj instanceof db_source))
	  return($this->data_io->sourceObj->disconnectORA());
	else return -1;
 }

/** 
* Establishes a communication to the Meteor XHTTP Server
* @version 2.0.1
* @author JL
* @param Market $obj, int $socket_id (answer back to dispatcher script),  int $channel_id, string $data -fdate- (parameters passed from the calling function {@link handle_client})
*/ 
function openMeteor($obj, $socket_id, $channel_id,$data)
{
 if (!is_resource($this->op) or feof($this->op) or ($haswritten and !$buf)) {
		echo "Reconnecting to Meteor\n";
		
		system ("killall -s SIGUSR1 meteord", $ret);
		print "Clear message: ".$ret."\n";
		
		if (!($this->op = fsockopen("127.0.0.1", 4671, $errno, $errstr, 5))) {
			echo "Meteor not responding\n";
			sleep(5);
			continue;
			}
		socket_set_blocking($this->op,false);
		}
		
 print "open Meteor: ".$this->op." ses=".$this->ses."\n";
 
 $obj->write($socket_id, $channel_id, "ok");	
 }
 
 
function STARTctrl($obj, $socket_id, $channel_id,$data)
{
//global $op,$ctrl_io,$ch_ctrl;
//global $posStart,$countRows;

$this->posStart=0;
$this->countRows=($data!='') ?$data :1;

$this->ch_ctrl = "ctrl";
$this->op = false;
print "STARTctrl ==".$data."\n";

$this->ctrl_io->init_MarketResolutionTable;
$this->ctrl_io->createFilter(); // read Market Configuration	
$this->ctrl_io->errorlog->addlog( 'createFilter: - '. "ok");
echo "filter=".$this->ctrl_io->filters["ISIN_CODE"]."/n";

//while(true) {
// $posStart < $countRows ) { //&& $posStart < $countRows
// Open a controller channel to Meteor server
	if (!is_resource($this->op) or feof($this->op) or ($haswritten and !$buf)) {
		echo "Reconnecting to Meteor\n";
		if (!($this->op = fsockopen("127.0.0.1", 4671, $errno, $errstr, 5))) {
			echo "Meteor not responding\n";
			sleep(5);
			continue;
			}
		socket_set_blocking($this->op,false);
		}
		
// Write a random word
	$haswritten = false;
	$buf = "";
//	$msg = $this->ctrl_io->fetchNextJSON($this->posStart++,$this->countRows); //$countRows

	//$msg="{rows:[{id:1001,data:["
	//.'\"100\",\"A Time to Kill\",\"John Grisham\",\"12.99\",\"1\",\"05/01/1998\"]}]}';
	//$msg="{w:'ohmygod',c:13.6}";

	$this->ctrl_io->errorlog->addlog( 'fetchNext: - '. $msg);
	
//	if ($msg=='eof')
//	    break;
	$out = "ADDMESSAGE ".$this->ch_ctrl." ".$msg."\r\n"; 
	//echo $out;
//	echo $msg;
	$haswritten=true;
///	fwrite($this->op, $out);
	
//	GET /push/1/xhrinteractive/ctrl.h HTTP/1.1
//  {rows:[{id:'1001',data:'100','A Time to Kill','John Grisham','12.99','1','05/01/1998']}]}
//	$posStart+=$countRows;
	
// Give Meteor time to respond - 10ms
///	usleep(20000);	
///	if ($haswritten) {
		//$nr=socket_recv($op,$buf,4096,0);
///		$buf = fread($this->op, 4096);
///	}
///	echo "buf=".$buf;
		//echo "service2 return: ".$buf."\n";
		//$out = "TERM ".$ch_ctrl."\n";
		//fwrite($op, $out);
	//}
// Sleep for 1s

	//	sleep(1);
	echo "Have read: ". $this->posStart."\n";
	$obj->write($socket_id, $channel_id, "ok");	
	//print printHeader();
}

/** 
* MARKETBEAT thread calls in order to receive instructions what to do. Responding with 'start' cmd, MARKETBEAT initiates the beat mechanism of sending every $this->delay secs
* @version 2.0.1
* @author JL
* @param Market $obj, int $socket_id (answer back to dispatcher script),  int $channel_id, string $data -usually delay- (parameters passed from the calling function {@link handle_client})
*/ 
function market_wait($obj, $socket_id, $channel_id,$data)
{
 //global $op, $data_io, $delay,$mkt_stat;
 $s="error";

 print "market_wait ==".$data." ses=".$this->ses."\n";

 	 $obj->write($socket_id, $channel_id, "start,".$this->delay."_".$this->ses);	
}


 
/** 
* Every beat into MARKETBEAT calls this function. Process the next action to the market object.
* @version 2.0.1
* @author JL
* @param Market $obj, int $socket_id (answer back to dispatcher script),  int $channel_id, string $data -usually delay/session- (parameters passed from the calling function {@link handle_client})
*/ 
function market_tick($obj, $socket_id, $channel_id, $data)
{
// global $op, $data_io,$delay,$mkt_stat, $ch_orders,$ch_events,$ch_beat,$ch_trades,$orders_cntr,$fwrd_step,$ch_orderbookB,$ch_orderbookS,$__marketbeat_flag,$mkt_action;
 
 //$delay=$data;
 
	 
 if ($this->mkt_stat==_MARKET_RUNNING)
 	  $cmd="continue_run";

 if (tradeEngineAvailability==false) {
	 $obj->write($socket_id, $channel_id, $cmd.",".$this->delay."_".$this->ses); 
     return;
	 }
	 
 if ($this->mkt_stat==_MARKET_RUNNING || $this->mkt_stat=='n' || $this->mkt_action=='fw') 
    {
	 //   =============== PHASES EVENTS ================    //
	 $ret = $this->data_io->checkPhases();
 
	 if ( ($ret & _FETCH_RET_EVENT) == _FETCH_RET_EVENT ) 
		{ 
		   //$this->msg_out = $this->data_io->eventsScript;
		   $err = $this->sendMsgToMeteor(_ch_events);
		   if ($err==false)
		        print "market_tick: fwrite msg FAILED! - ".$data." ses=".$this->ses."\n";
//$script="{rows:[{id:'". "1" ."',data:["."'New Announcement!'"."]}]}"; 
//$out = "ADDMESSAGE "._ch_announce." ".$script."\r\n";
//fwrite($this->op, $out);

/*
		   if ($this->mkt_action=='fw_custom_period') 
			  {
				if ($this->data_io->getCustomPeriod()==true)
						$this->addEventGraph();
				}
				else	
					$this->addEventGraph();	
*/					
		  }
	
	//  ======== GET Current PHASE info ========  //
	 $cp = $this->phases_info->get_currphase();
	 $mf = $this->phases_info->phases[$cp];
	 $cphase_t = key($this->phases_info->phases[$cp]);
	 //	 ======================================  //

	 //  ======== END of AUCTION PHASE ========  //
	 if ( $mf[_PHASE_STAT]==_PHASE_DONE )
	 {
	 print("Phase DONE:".$mf[_PHASE_OPEN_COMMENT]."\n");
	    if ( $mf[_PHASE_DONE_COMMENT]==_DO_OPEN_PRICE )
	 	 {
		  $ret = $this->data_io->getProjected($this->phases_info->get_currphaseTime(), $this->phases_info->getNextPhaseStartTime());
	
		  if ( ($ret & _FETCH_RET_ORDERS) == _FETCH_RET_ORDERS )
	  		 $this->transmitProjected();
		  		  
		  }		  
		}
		else 
		  {
	 //	 ======================================  //
	 
	//   ============ FETCH AN ORDER =============   //
	 unset($this->secInfo->lastOrders);	
	 if ($mf[_PHASE_STAT]==_PHASE_OPEN && $mf[_PHASE_RUN_COMMENT]==_DO_AUCTION ) {
			if ( ($ret & _FETCH_RET_TRADES)==_FETCH_RET_TRADES) {
					print ("_DO_AUCTION: Auction transmitTrades...".$this->data_io->Read_trades."\n");
	  				$this->transmitTrades(); // Auction resulted 
					}
			    }
				
	 $this->data_io->computeLimit($mf,$cphase_t);	 // compute next posStart	
//	 print($mf[_PHASE_OPEN_COMMENT]."==".$mf[_PHASE_RUN_COMMENT]."\n");  
	 $ret = $this->data_io->fetchNextOrder($this->isin);
//	print("fetchNextOrder:".$ret."\n");
	 $this->phases_info->advancePhaseCntr($this->secInfo->count);  // advance the posStart
	 $buf = "";
//	 print("market_tick - New fetch - Limit=".$this->data_io->count."\n");
	 
 	if ($this->mkt_action=='fw') {
 	 	$this->orders_cntr++;
//print "tick ==".$orders_cntr."==".$fwrd_step."   ";		
	 	if ($this->orders_cntr == $this->fwrd_step) {
			$this->fwrd_step=0;
		 	$this->mkt_stat='p';
			$this->mkt_action='';
			$this->msg_out = '{"rows":[{"id":"99","data":[{"TIME":"'.$this->orders_cntr.'","PHASE":'.'"FWRD_PAUSED"'.'}]}]}';
			//$this->data_io->eventsScript = '{"rows":[{"id":"99","data":[{"TIME":"'.$this->orders_cntr.'","PHASE":'.'"FWRD_PAUSED"'.'}]}]}';
			$err = $this->sendMsgToMeteor(_ch_events);
			
			//print "tick start == ".$orders_cntr."==".$fwrd_step."\n";
			$this->orders_cntr=0;
		 	}
 		}
	
						
 	if ($this->mkt_stat=='n')
        $this->mkt_stat='p';
		
	 if ($ret != _FETCH_RET_ERROR)
	 	{
		 
		 if ( ($ret & _FETCH_RET_ORDERS) == _FETCH_RET_ORDERS ) {
		 	   /*
				if ($this->data_io->mdScriptB!='') {
			   		$out = "ADDMESSAGE "._ch_mktdepthB." ".$this->data_io->mdScriptB."\r\n";
			   		fwrite($this->op, $out);
					if ($this->mkt_action=='fw_custom_period') {
						if ($this->data_io->getCustomPeriod()==true)
							$this->addMDbidGraph();
						   }
						   else
							$this->addMDbidGraph();					  
					}
				if ($this->data_io->mdScriptS!='') {
					$out = "ADDMESSAGE "._ch_mktdepthS." ".$this->data_io->mdScriptS."\r\n";
			   		fwrite($this->op, $out);
					if ($this->mkt_action=='fw_custom_period') {
						if ($this->data_io->getCustomPeriod()==true)
							$this->addMDaskGraph();
						   }
						   else
							$this->addMDaskGraph();					  
					
					//print("ADDMESSAGE "._ch_mktdepthS." ".$this->data_io->MDScriptS."\n");
					}
				*/
			   $this->getFetchedOrders();
			   $ret = $this->updateOrderBook();
			   //if ($ret!=-1) // Order is Canceled or is Inactive
				   $this->sendMktDepth((($ret==-1) ?-1 :0));	
			  
			print "after updateOrderBook == ".$ret."==".$this->secInfo->nextOrderKey."\n";   
			   if ( ($ret & _OBOOK_RET_ORDER)==_OBOOK_RET_ORDER )  // the next order received could be canceled, or an update, so no need to check trades!
			   	  {
					$cp = $this->phases_info->get_currphase();
					$mf = $this->phases_info->phases[$cp];
					print "after updateOrderBook 2== ".$cp."==".$mf[_PHASE_OPEN_COMMENT]."\n"; 
			   		if ($mf[_PHASE_OPEN_COMMENT]==_CONTINUOUS_OPEN) {
						$t_ret = 0;
						
						 $this->data_io->marketTradeUnmatched=true;
						 while($this->data_io->marketTradeUnmatched)
						 {
						  $t_ret = $this->data_io->doTheMatching ();
						  print "after doTheMatching 2== ".$t_ret."\n"; 
						  if (($t_ret & _FETCH_RET_TRADES)==_FETCH_RET_TRADES)  { 
							  if ($this->transmitTrades()==_FETCH_RET_NO_TRADES) {   // Transmit all and Save last trade !
							      $this->data_io->marketTradeUnmatched=false;
								  break;
								  }
						      
							  $this->sendMktDepth($t_ret);	
							  }
							  else break;  	  	  
						  }	  
						}
					}

			    //$this->sendMktDepth($t_ret & _FETCH_RET_TRADES);
				
			  // $out = "ADDMESSAGE ".$ch_beat." "."{market_steps:".$orders_cntr++."}\r\n";
			  // fwrite($op, $out);
			   }	 
		 }  
		}	  
		 $haswritten=true;

// Give Meteor time to respond - 10ms
		 usleep(20000);	
		 if ($haswritten) {
			 $buf = fread($this->op, 4096);
	    	}
		 }
		// print "tick end == ".$orders_cntr."==".$fwrd_step."\n";
	     

 //print "market_tick: stat ==".$mkt_stat."\n";
 	 
 if ($this->mkt_stat==_MARKET_STOPPED)
 	   $obj->write($socket_id, $channel_id, "stop,-1"."_".$this->ses);
   else
	   $obj->write($socket_id, $channel_id, $cmd.",".$this->delay."_".$this->ses); 
}


/*********************************************************************************
		Decide to send Market Depth
*********************************************************************************/
function sendMktDepth($t_ret=0)
{
 if ( $this->data_io->cfg->market_depth > 0 ) {
 
       				 if ($this->mkt_action=='fw_custom_period' )
		  				 {
		  				  if ( $this->data_io->getCustomPeriod() != false )
   		     					$this->transmitMD( ($t_ret & _FETCH_RET_TRADES) );//
							}
						else	
							$this->transmitMD(( $t_ret!=-1 ?($t_ret & _FETCH_RET_TRADES) :$t_ret)); //-1 when Cancel
							}
 }
 							
/********************** read OB and pack MD for sending out ******************************************
*
*		transmit Market Depth
*
*******************************************************************************/
function transmitMD( $hasTrade=0 )
{
//  print ("transmitMD - ".'order/trades - '.$hasTrade."\n");
// $this->data_io->MDScriptB='';
// $this->data_io->MDScriptS='';
 
 $rows=($hasTrade==1) ?$this->secInfo->lastTrades :$this->secInfo->lastOrders; 
 $orders = count($rows);
 if (!$orders || !$rows) {
	  print ("transmitMD - ".'no order/trades rows-'.$hasTrade."\n");
	  return;
	  }
 $row=$rows[0];
// foreach($rows as $row)
 		{
		 $this->data_io->getMD( (($hasTrade==0) ?$row->SIDE :''), $row->PRICE, $hasTrade);
print("transmitMD:".$hasTrade."\n");
	//	 if ($row->SIDE == 'B') {
		 	if ($this->data_io->mdScriptB!='') 
				{
				 //print ("transmitMD - mdScriptB - Send OK - ".$this->data_io->mdScriptB."\n");
				 // print ("transmitMD - ".$this->channels[_ch_mktdepthB][_msg]."\n");
			   		//$this->msg_out = $this->data_io->mdScriptB;
					//$this->msg_out=$this->data_io->mdScriptB;
			   		$this->sendMsgToMeteor(_ch_mktdepthB); //,_msg_local);
					//$this->addMDbidGraph();	
					}
			if ($this->data_io->accScript!='') 
				{
				  //print ("transmitMD acc - ".$this->data_io->accScript."\n");
			   		//$this->msg_out = $this->data_io->accScript;
			   		$this->sendMsgToMeteor(_ch_accounts);
					}		
			//}
		/*    else
			   if ($this->data_io->mdScriptS!='') {
//			   print ("transmitMD S- ".$this->data_io->mdScriptS."\n");
			   		$out = "ADDMESSAGE "._ch_mktdepthS." ".$this->data_io->mdScriptS."\r\n";
			   		fwrite($this->op, $out);
					$this->addMDaskGraph();	
					}	*/		
		 }
 }

/********************** read Projected (IK) and pack for sending them out ******************************************
*
*		transmit Projected Prices
*
*******************************************************************************/
function transmitProjected()
{
 $rows=$this->secInfo->projected; 
 if (!$rows) {
	  print ("transmitProjected - ".'no projected rows'.'\n');
	  return;
	  }
 $projected = count($rows);
 $this->secInfo->projectedScript='';
 $this->secInfo->ar_graphObj["projected"]->createFiles();
 
 foreach($rows as $row)
 		{																//&& count($row->PROJECTED_OPENING_PRICE)>2
		 if ((int)$row->PROJECTED_OPENING_PRICE==99999 || $row->PROJECTED_OPENING_PRICE==0)
		 	$price=$this->secInfo->startPrice / pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]);
	//	if ($row->ORDER_NUMBER=="")
		       $price= (strpos($row->PROJECTED_OPENING_PRICE,".")==false ?((int)($row->PROJECTED_OPENING_PRICE."00")) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])) 
				 :$row->PROJECTED_OPENING_PRICE/pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
		 
//		 $vol = (int)$row->TOTAL_TRADED_VOLUME * 1;
    	 $milis= $this->data_io->formatTimeStr($row->OASIS_TIME).'0';
//		 $hi = (strpos($row->TODAYS_HIGH_PRICE,".")==false ?((int)$row->TODAYS_HIGH_PRICE) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])) :$row->TODAYS_HIGH_PRICE);
//		 $low = (strpos($row->TODAYS_LOW_PRICE,".")==false ?((int)$row->TODAYS_LOW_PRICE) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])) :$row->TODAYS_LOW_PRICE);
		 $vol = ((int)$row->VOLUME) * 1;
		// if ($price > 0) {
		 	$this->secInfo->ar_graphObj["projected"]->addDataFile($milis.",".$price.",".($vol==0 ?",":$vol));
			//}
		 if ($row->ORDER_NUMBER!="")
		 {
		  $url="javascript:createTradeOrdersWindow_bid('" . $row->OASIS_TIME ."','" . "Precall Order" ."','". $row->ORDER_NUMBER ."','". "00000"."','Order Detail','order');";
 		  $this->secInfo->ar_graphObj["projected"]->addEventFile($milis, "","Order:".$row->ORDER_NUMBER."<br>"."Id: ".$row->ORDER_ID."<br>Pr: ".$price."<br>B Vol: ".$vol, $url, 1); // ordr: graph id
		  }
		 }
		 
 $this->secInfo->ar_graphObj["projected"]->close();		 
/*
 $rows=$this->secInfo->projected_orders; 
 foreach($rows as $row)
 		{
		 $omilis= $this->data_io->formatTimeStr($row->otime).'0';
		 $ob_price= ((int)$row->oprice) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
		 $ovol = ((int)$row->ovolume) * 1;
		 
		 $this->addDataFile($this->secInfo->graphProjectedFname2,$omilis.",".$ob_price);
		 
		 $url=$url_b."javascript:createTradeOrdersWindow_bid('" . $row->otime ."','" . "Precall Order" ."','". $row->onumber ."','". "00000"."','Order Detail','order');";
 		 $this->addEventFile($this->secInfo->graphProjectedEventsFname,$omilis,$row->onumber,"Id: ".$row->oid."<br>Pr: ".$ob_price."<br>B Vol: ".$ovol, $url, 1); // ordr: graph id
		 }
*/		 
 $this->secInfo->openAuctionPrice = $rows[$projected-1]->OPENING_PRICE_AUCTION_PRICE;
 $open = ((int)$this->secInfo->openAuctionPrice) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));	 
 	
 }
  
/********************** Fetch Orders ******************************************
*
*		get Orders
*
*******************************************************************************/
function getFetchedOrders()
{
 $rows=$this->secInfo->lastOrders; 
 if (!$rows) {
	  print ("getFetchedOrders - ".'no order rows'.'\n');
	  return;
	  }
 $isin=$this->secInfo->getIsinSec();
  
 $orders = count($rows);
 $this->data_io->ordersScript='';
 $pstat=$this->data_io->getCustomPeriod();
 
 foreach($rows as $row)
 		{
		 $this->data_io->packOrder($this->tableIT, $row, &$orders,(($this->mkt_action=='fw_custom_period' && $pstat==false) ?'sendOnlyTime' :'sendAll'));	
		 }
// print $this->data_io->orersScript."\n";
 
// $this->msg_out = $this->data_io->ordersScript;
 $this->sendMsgToMeteor(_ch_orders);
 
 if ($this->secInfo->MDchange) {
	 $this->data_io->packHiLo($this->tableIT, false); // true: Send Orders/Trades totals also!		
//	 $this->msg_out = $this->data_io->hiloScript;
	 $this->sendMsgToMeteor(_ch_hilo);
	 $this->secInfo->MDchange=0;
	 }

 if ($this->mkt_action=='fw_custom_period') 
    {
//	print('fw_custom_period -'.$rows[0]->OASIS_TIME ."==". $this->custom_stop."\n");	
 	 if ($rows[0]->OASIS_TIME > $this->custom_start && ($pstat==false)) {
						 	$this->msg_out='{"rows":[{"id":'. 98 .',"data":[{"TIME":"'.$this->data_io->formatTimeStr($rows[0]->OASIS_TIME).'","PHASE":"'."CUSTOM_PERIOD_START".'"}]}]}';
							$this->sendMsgToMeteor(_ch_events,_msg_local);
							$this->data_io->setCustomPeriod(true);
							}
						elseif ($rows[0]->OASIS_TIME > $this->custom_stop) {
							$this->mkt_stat='p';
							$this->mkt_action='';
						 	$this->msg_out='{"rows":[{"id":'. 99 .',"data":[{"TIME":"'.$this->data_io->formatTimeStr($rows[0]->OASIS_TIME).'","PHASE":"'."CUSTOM_PERIOD_STOP".'"}]}]}';
							$this->sendMsgToMeteor(_ch_events,_msg_local);
							$this->data_io->setCustomPeriod(false);
							}
 /*  
			  	$dat=$this->json_decode2($this->data_io->ordersScript, true);			
				foreach($dat as $rows=>$row)
					foreach($row as $sub=>$data)
						foreach($data as $col=>$val)
						if ($col=='data')
						{
						 $ar_time=explode(":",$val[0]);
						 $oasis_time=$ar_time[0].$ar_time[1].$ar_time[2].$ar_time[3];
						 if ($oasis_time > $this->custom_start && ($this->data_io->getCustomPeriod()==false)) {
						 	$msg="{rows:[{id:'99',data:['".$val[0]."','"."CUSTOM_PERIOD_START"."']}]}";
							$out = "ADDMESSAGE "._ch_events." ".$msg."\r\n";
							fwrite($this->op, $out);
							$this->data_io->setCustomPeriod(true);
							}
						elseif ($oasis_time > $this->custom_stop) {
							$this->mkt_stat='p';
							$this->mkt_action='';
						 	$msg="{rows:[{id:'99',data:['".$val[0]."','"."CUSTOM_PERIOD_STOP"."']}]}";
							$out = "ADDMESSAGE "._ch_events." ".$msg."\r\n";
							fwrite($this->op, $out);
							$this->data_io->setCustomPeriod(false);
							}
						}
*/						
				}
						//addDataFile("js/graphs/market_data.txt",date("Y/m/d",strtotime($data_io->getSessionDate())).' '.substr($val[0],0,strlen($val[0])) .",". $val[4] .",". $val[5]);
 }
			 
			 
/********************** Order Book ******************************************
*
*		update OrderBook after a new Order Received
*
*****************************************************************/
function updateOrderBook()
{
 if (is_a($this->phases_info,'msg_info'))  		//check Sec Phase
    {
	 $cp = $this->phases_info->get_currphase();
	 $mf = $this->phases_info->phases[$cp];

//	print "updateOrderBook=".$this->data_io->ordersScript."\n";
	//if ($mf[_PHASE_OPEN_COMMENT]==_CONTINUOUS_OPEN)
	//	{
	$rows=$this->secInfo->lastOrders; 
 	if (!$rows) {
	  	print ("updateOrderBook - ".'no order rows'.'\n');
	  	return 0;
	  	}
 $orders = count($rows);
 
 foreach($rows as $row)
 		{
		 $this->data_io->packOrder($this->tableIT, $row, &$orders);	
		 	 
		 $ret = $this->data_io->setOrderBook ($row->ORDER_ID,$row->OASIS_TIME,$row->ORDER_NUMBER,$row->SIDE,$row->PRICE,$row->VOLUME,$row->DISCLOSED_VOLUME,$row->ORDER_STATUS);
		 if ( ($ret & _OBOOK_RET_ERROR)==_OBOOK_RET_ERROR )
								{
								 print "setOrderBook error:".$ret."==".$row->OASIS_TIME." - ".$row->ORDER_NUMBER." - ".$row->ORDER_ID."\r\n";
								 die("Cannot continue... bye bye! \n");
								 }
			   				 if (($ret & _OBOOK_RET_NO_ORDER)==_OBOOK_RET_NO_ORDER )	 
				 				{
				 				 print "setOrderBook Cancel Order: ".$ret."==".$row->OASIS_TIME." - ".$row->ORDER_NUMBER." - ".$row->ORDER_ID."\r\n";
								 return -1;
								 }
		 }
 return $ret;		 
/*		 
	 $dat=$this->json_decode2($this->data_io->ordersScript, true);
	 foreach($dat as $rows=>$row)
			foreach($row as $sub=>$data)
				foreach($data as $col=>$val)
						if ($col=='data')
							{
							 $oasisTime=$this->strTimeToOasis_time($val[0]);

							 $ret = $this->data_io->setOrderBook ($oasisTime,$val[3],$val[2],$val[5],$val[4],$val[7]);
							 if ( ($ret & _OBOOK_RET_ERROR)==_OBOOK_RET_ERROR )
								{
								 print "setOrderBook error:".$ret."==".$val[0]." - ".$val[3]."\r\n";
								 
								 }
			   				 if (($ret & _OBOOK_RET_NO_ORDER)==_OBOOK_RET_NO_ORDER )	 
				 				{
				 				 print "setOrderBook Cancel Order: ".$ret."==".$val[0]." - ".$val[3]."\r\n";
								 
								 }
							 return $ret;
							 }
		//}	*/					 
	 }
}

/********************** Match Orders ******************************************
*
*		get Trades
*
*****************************************************************/
function transmitTrades()
{
  
//  print "getTrades - ".$ret."\r\n";
 // if ( ($ret & _FETCH_RET_TRADES) == _FETCH_RET_TRADES)
 // {
      $rows=$this->secInfo->lastTrades; 
	  if (!$rows) {
	         print ("getTrades - no trade rows\n");
	  		 return;
			 }
	 
	  $trades = count($rows);
//	  $r=$trades;
//  print ("transmitTrades - ".$trades."\n");

	  for($i=0; $i<count($this->secInfo->ob_entries); $i++)
	  	print("OB matched:".$this->secInfo->ob_entries[$i][0]."==".$this->secInfo->ob_entries[$i][1]."==".$this->secInfo->ob_entries[$i][2]."\n"); 
		
	  foreach($rows as $row)
		{
	 	if (is_array($this->secInfo->trade_numbers))
			if (in_array($row->TRADE_NUMBER,$this->secInfo->trade_numbers)){
		    	$this->data_io->errorlog->addlog( 'DUP trades : '.$this->secInfo->nextOrderKey."===".$row->TRADE_NUMBER);
//		$this->errorlog->addlog( 'DUP Trade: Id= '.$row->TRADE_NUMBER);
		//print("DUP Trade: ".$row->TRADE_NUMBER."\n");
//		print_r($this->trade_numbers);
			return _FETCH_RET_NO_TRADES;
			}

		$this->data_io->getMatchedOrders($row);
		
		$ret = $this->data_io->tradeUpdateOB($row);
	    if ( ($ret & _OBOOK_RET_ORDER)==_OBOOK_RET_ORDER)
	 		  $this->secInfo->trade_numbers[]=$row->TRADE_NUMBER;
			 else
			   return _FETCH_RET_NO_TRADES; 
	// Save Low - High Prices;	
	//	$this->data_io->updateLoHiPrice($row->PRICE);
		
		$idx=$row->BUY_TRADER_ID.'-'.$row->BUY_CSD_ACCOUNT_ID;
		if ( array_key_exists($idx, $this->secInfo->bid_traders) )
				$this->secInfo->bid_traders[$idx][1]+=$row->VOLUME;
					 
		$idx=$row->SELL_TRADER_ID.'-'.$row->SELL_CSD_ACCOUNT_ID;
		if ( array_key_exists($idx, $this->secInfo->ask_traders) )
		 	   $this->secInfo->ask_traders[$idx][1]+=$row->VOLUME;
			   
		$r=1;
		$this->data_io->packTrade($this->tableIS, $row, &$r);
	//	$this->data_io->packHiLo($this->tableIT);
		
//		$this->msg_out = "ADDMESSAGE "._ch_hilo." ".$this->data_io->hiloScript."\r\n";
//		$this->sendMsgToMeteor();
		
//		$this->msg_out = $this->data_io->tradesScript;
		$this->sendMsgToMeteor(_ch_trades);
		
		//$out = "ADDMESSAGE "._ch_hilo." ".$this->data_io->hiloScript."\r\n";
		//fwrite($this->op, $out);
//if ($rows[0]->SELL_ORDER_NUMBER=='30279')
//print($this->data_io->tradesScript."\n");	
		
		
		//$this->data_io->tradesScript='';		

		//    ['10:36:38:830','T','000798','11.6','100','Î£Î™00C','00008055','125294','Î–Î•005','00008081','6','116000','23103','10363090','00008055','11.6','1.08','23180','10363883','00008081','11.6','1',11.54,11.7]
		 	//['10:31:52:18','O','000201','11.54','196','MD001','MD','00000027','Î‘Î¦012','Î‘Î¦','00004319','2261.84','27','0','196','4319','0','1368',11.54,11.54]		 
		   		//print $this->data_io->tradesScript;
	/*								
		if ($this->mkt_action=='fw_custom_period') 
			{
			 if ($this->data_io->getCustomPeriod()==true)
				 $this->addTradeGraph($row);
			 }
			else ;
				//$this->addTradeGraph($row);
		*/		
		}

/*========>	$this->data_io->getTopAccounts();
	
	if ($this->data_io->accTradesScript!='') 
	{
//print("TopAcc=".$this->data_io->accTradesScript."\n");
		$this->msg_out = $this->data_io->accTradesScript;
		$this->sendMsgToMeteor(_ch_accounts, _msg_local);
		$this->data_io->accTradesScript='';
		}
*/	
	$this->data_io->updateLoHiPrice($row->PRICE);  // update from the last trade row ONLY!
	$this->data_io->packHiLo($this->tableIT);
//	$this->msg_out = $this->data_io->hiloScript;
	$this->sendMsgToMeteor(_ch_hilo);
	
/*	
	$out = "ADDMESSAGE "._ch_trades." ".$this->data_io->tradesScript."\r\n";
	fwrite($this->op, $out);
*/
//============== SAVE last trade ================//
	$this->secInfo->lastTrade=$rows[$trades-1];
//	}	

 return 1;
 }
 
 		
function strTimeToOasis_time($val)
{
 $ar_time=explode(":",$val);
 $oasis_time=$ar_time[0].$ar_time[1].$ar_time[2].$ar_time[3];

 return $oasis_time;
}

/******************************************************************
*			reverseMatching
*******************************************************************/
function reverseMatching()
{
 $rows=$this->secInfo->lastOrders; 
 if (!$rows) {
	  print ("reverseMatching - ".'no order rows'.'\n');
	  return;
	  }
 $isin=$this->secInfo->getIsinSec();
  
 foreach($rows as $row)
 		{
		 $this->data_io->reverseMatching($row->ORDER_NUMBER);	
		 } 
 }

function setProgressMsg($id,$msg)
{
 $msg='{"rows":[{"id":"'. $id .'","data":[{"MSG":"'.$msg.'"}]}]}';
 $this->msg_out = $msg;
 $this->sendMsgToMeteor(_ch_messages, _msg_local);
 }
  
/********************** create OB ******************************************
*
*		createOB
*
*****************************************************************/
function createOB ()
{
// $this->last_time='00000000';
 print ("createOB - Starts ".$this->last_time." for:".$this->secInfo->getIsinSec()."\n");
// $this->createAskBidFile_csv($this->secInfo->graphBidAskFname,"txt");
  $entries = $this->data_io->countEntries($this->last_time,$this->custom_start);
  $p=0;
  $ptot=0;
  if ($entries)
  {
   $progressBytes = $entries / ($entries<101 ?10 :100);
   $progressBits =  ($entries<101 ?10 :100);
   
   $this->setProgressMsg(1,$progressBytes);
   }
  
 while($this->last_time < $this->custom_start )
 {
  $ret = $this->data_io->checkPhases();

  $cp = $this->phases_info->get_currphase();
  $mf = $this->phases_info->phases[$cp];
  if (!is_array($this->phases_info->phases[$cp]))
  {
   print("createOB - NOT Phase array:".$cp."\n");
   break;
   }
  $cphase_t = key($this->phases_info->phases[$cp]);
  
  if ( ($ret & _FETCH_RET_EVENT) == _FETCH_RET_EVENT  ) 
		{ 
		   //$out = "ADDMESSAGE "._ch_events." ".$this->data_io->eventsScript."\r\n";
		   //$err=true;
		   //$err = fwrite($this->op, $out);
		   //$this->msg_out = $this->data_io->eventsScript;
		if  ($this->data_io->creOBStats==0) 
		     $this->sendMsgToMeteor(_ch_events);
		
		if  ($this->data_io->creOBStats) {	 
			 $stat=$this->phases_info->get_currphaseStat();
			 $ptime=($stat == _PHASE_DONE ?$this->phases_info->get_currphaseTimeDone() :$this->phases_info->get_currphaseTime());
			 $pcomment=($stat == _PHASE_DONE ?$mf[_PHASE_DONE_COMMENT] :$mf[_PHASE_OPEN_COMMENT]);
			 $milis= $this->data_io->formatTimeStr($ptime);
		     $aprice= (int)($this->secInfo->ask_1==0 ?$this->secInfo->startPrice :$this->secInfo->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); //((int)$this->data_io->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					 					
									//	if ($bprice!=$aprice){
			$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'.';'.';'. ';'.';'. $aprice );
											
			$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addEventFile(
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,"","Phase: ".$pcomment, "", 0,-1);
			 }
		   }
		   
  if ($cphase_t==_PHASE_END) {
	  print ("createOB - "._PHASE_END."\n");
      break;
	 }
	 	 
  if ( $mf[_PHASE_STAT]==_PHASE_DONE )
	 {
	    $msg='{"rows":[{"id":'. 08 .',"data":[{"MSG":"Phase Done:'.$mf[_PHASE_DONE_COMMENT].'"}]}]}';
		$this->msg_out = $msg;
		$this->sendMsgToMeteor(_ch_messages, _msg_local);
	    print("createOB - Phase DONE:".$mf[_PHASE_DONE_COMMENT]."==".($ret & _FETCH_RET_TRADES)."\n");
		/*
		 if ( $this->data_io->creOBStats)
		  {
		   $milis= $this->data_io->formatTimeStr($this->phases_info->get_currphaseTimeDone());
		   $aprice= ((int)$this->secInfo->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); //((int)$this->data_io->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					 					
									//	if ($bprice!=$aprice){
			$this->addDataFile(($this->data_io->creOBStats ?$this->secInfo->graphBidAskFnameGlobal :$this->secInfo->graphBidAskFname),
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'. ';'.';'. ';'.';'. $aprice );
											
			$this->addEventFile(($this->data_io->creOBStats ?$this->secInfo->graphBidAskEventsFnameGlobal :$this->secInfo->graphBidAskEventsFname),
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,"","Phase: ".$mf[_PHASE_DONE_COMMENT], "", 0,-1);
		   }	*/
		   	
		if ( $mf[_PHASE_DONE_COMMENT]==_DO_OPEN_PRICE && ($this->data_io->creOBStats==0))
	 	 {  	
		 
		  $ret = $this->data_io->getProjected($this->phases_info->get_currphaseTime(), $this->phases_info->getNextPhaseStartTime());
	
		  if ( ($ret & _FETCH_RET_ORDERS) == _FETCH_RET_ORDERS )
		  		$this->transmitProjected();  		  
		  }
		 
		 
	continue;	  
   }
 
//  print("createOB - Phase DONE:".$mf[_PHASE_RUN_COMMENT]."\n");	
  if ($mf[_PHASE_STAT]==_PHASE_OPEN && $mf[_PHASE_RUN_COMMENT]==_DO_AUCTION ) 
  {
			if ( ($ret & _FETCH_RET_TRADES)==_FETCH_RET_TRADES) 
			   {
	  				$this->tradesUpdateOB($mf[_PHASE_RUN_COMMENT]);
			    }
   }
				
 $this->data_io->computeLimit($mf,$cphase_t);	 // compute next posStart	
//	 print($mf[_PHASE_OPEN_COMMENT]."==".$mf[_PHASE_RUN_COMMENT]."\n");  
 $ret = $this->data_io->fetchNextOrder();   //$this->isin
 $p++; // progress
 if ($entries && $p > $progressBits)
 {
  $ptot++;
  $p=0;
  $this->setProgressMsg(2,$ptot);
  }
  
 $this->last_time=$this->secInfo->lastOrders[0]->OASIS_TIME; 
 //$last_time.=(strlen($this->custom_start)>8 ?"0" :"");
 
 	if ($this->last_time >= $this->custom_start && ($pstat==false)) 
	   {
		 if ($this->data_io->creOBStats==0) // Its not a full Initialization  mode
		    {
				$msg='{"rows":[{"id":'. 98 .',"data":[{"TIME":"'.$this->data_io->formatTimeStr($this->last_time).'","PHASE":"'."CUSTOM_PERIOD_START".'"}]}]}';
				//$out = "ADDMESSAGE "._ch_events." ".$msg."\r\n";
				//fwrite($this->op, $out);
				$this->msg_out = $msg;
		   		$this->sendMsgToMeteor(_ch_events, _msg_local);
				}
		 $this->data_io->setCustomPeriod(true);
		}
	 elseif ($this->last_time >= $this->custom_stop) 
	        {
			 if ($this->data_io->creOBStats==0) // Its not a full Initialization  mode
				 {
				 	$msg='{"rows":[{"id":'. 99 .',"data":[{"TIME":"'.$this->data_io->formatTimeStr($this->last_time).'","PHASE":"'."CUSTOM_PERIOD_STOP".'"}]}]}';
					//$out = "ADDMESSAGE "._ch_events." ".$msg."\r\n";
					//fwrite($this->op, $out);
					$this->msg_out = $msg;
		   			$this->sendMsgToMeteor(_ch_events, _msg_local);
					}
			  $this->mkt_stat='p';
			  $this->mkt_action='';
	  		  $this->data_io->setCustomPeriod(false);
			  }
  	
  $this->phases_info->advancePhaseCntr($this->secInfo->count);  // advance the posStart +1
 if ($ret != _FETCH_RET_ERROR)
	{	 
	
	 if ( ($ret & _FETCH_RET_ORDERS) == _FETCH_RET_ORDERS ) 
		{
		   $rows=$this->secInfo->lastOrders; 
 		   if (!$rows) {
	  			print ("FetchNext - ".'no order rows'.'\n');
	  			return 0;
	  	 }
 		 
				 
		 $orders = count($rows);
		  
		 foreach($rows as $row)
 		 {	 	 
		  
		  if ($mf[_PHASE_OPEN_COMMENT]==_CONTINUOUS_OPEN) 
						$this->data_io->getMD_1();
						 
		  $ret = $this->data_io->setOrderBook ($row->ORDER_ID,$row->OASIS_TIME,$row->ORDER_NUMBER,$row->SIDE,$row->PRICE,$row->VOLUME,$row->DISCLOSED_VOLUME,$row->ORDER_STATUS);
		  if ( ($ret & _OBOOK_RET_ERROR)==_OBOOK_RET_ERROR )
								{
								 print "setOrderBook error:".$ret."==".$row->OASIS_TIME." - ".$row->ORDER_NUMBER." - ".$row->ORDER_ID."\r\n";
								 die("Cannot continue... bye bye! \n");
								 }
  
		  if ( ($ret & _OBOOK_RET_NO_ORDER) == _OBOOK_RET_NO_ORDER && $this->data_io->creOBStats)
		  {
		  // if (($row->SIDE=='B' && $row->PRICE==$this->secInfo->bid_1) || ($row->SIDE=='B' && $row->PRICE==$this->secInfo->ask_1))
		//	   $this->data_io->getMD_1();
		   $milis= $this->data_io->formatTimeStr($row->OASIS_TIME);
		   $aprice= ((int)($row->PRICE==0 ?$this->secInfo->startPrice :$row->PRICE)) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); //((int)$this->data_io->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
		   if ($row->ORDER_STATUS=='I' && $row->LAST_UPDATE_USER_ID==""){
			   $vol = $row->MATCHED_VOLUME * 1;
			   $status_flag = 'i';
			   }
			   else {
				   	  $vol =  ($row->MATCHED_VOLUME==0 ?$row->VOLUME :$row->MATCHED_VOLUME) * 1;	
					  $status_flag = $row->ORDER_STATUS;
					  }
									//	if ($bprice!=$aprice){
			$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'.';'.';' .';'.$vol.';'. $aprice );
											
			$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addEventFile(
								    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,$row->SIDE,"Trader: ".$row->TRADER_ID.'-'.$row->CSD_ACCOUNT_ID
												."<br>P:".$row->PRICE ."(".$row->VOLUME.")"."<br>S:".$row->ORDER_STATUS, "", 0,$row->PRICE,$status_flag);
		   }
		   
		  if ( ($ret & _OBOOK_RET_ORDER)==_OBOOK_RET_ORDER )  // the next order received could be canceled, or an update, so no need to check trades!
		  	  {
					//$cp = $this->phases_info->get_currphase();
					//$mf = $this->phases_info->phases[$cp];
			   		if ($mf[_PHASE_OPEN_COMMENT] ==_PRECALL_OPEN) {
						if ($this->data_io->creOBStats)	
									 {
										$milis= $this->data_io->formatTimeStr($row->OASIS_TIME);
					 					$pprice="";
					 					if ($row->SIDE=="B"){
										    $bprice= ((int)($row->PRICE==99999 ?0 :$row->PRICE)) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); 
											$aprice= ""; 
											}
					 					if ($row->SIDE=="S"){   //$this->secInfo->startPrice
										    $aprice= ((int)($row->PRICE==0 ?0 :$row->PRICE)) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); 
											$bprice= ""; 
											}
											
					 					if ($row->prj_price!=''){
											if (strpos($row->prj_price,".")==true)
											    $pprice = $row->prj_price;
											   else
												  $pprice = (int)($row->prj_price."00") / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
											//print("prj_price =".$row->prj_price."==".$pprice."\n");
											}
											
										$vol = $row->DISCLOSED_VOLUME * 1;
									//	if ($bprice!=$aprice){
										if (($row->BOARD_ID=="B" && $row->SIDE=='B'))
											$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
											    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis. ';'.';'.';'. ';' .$vol.';'.$bprice);
											elseif($row->M_C_FLAG=="Q")
											  $this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
											    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'.$pprice.';'.$bprice. ';'.$aprice.';' .$vol.';');
											else
											  $this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
											    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'.$pprice.';'.$bprice. ';'.$aprice.';' .$vol.';');
										
										if ($row->M_C_FLAG=="Q" || ($row->BOARD_ID=="B" && $row->SIDE=='B'))	
											$this->secInfo->ar_graphObj["bidask_g"]->addEventFile(
											    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,$row->SIDE,"Trader: ".$row->TRADER_ID.'-'.$row->CSD_ACCOUNT_ID
												."<br>P:".$row->PRICE, "", 0,$row->PRICE,$row->M_C_FLAG);
											//	}
										}
						}
						
					if ($mf[_PHASE_OPEN_COMMENT]==_CONTINUOUS_OPEN) {
						$t_ret = 0;
						
						if ($this->data_io->creOBStats)	
									 {
										if (($row->BOARD_ID=="B" && $row->SIDE=='B') || $row->M_C_FLAG=="Q"|| $row->M_C_FLAG=="M")
											$this->updateGraphFile($row);
										elseif ($this->secInfo->MDchange==1)
						  				{
										 $this->updateGraphFile($row);
										}
									}     
	
						$this->secInfo->MDchange=0;		
	
						$this->data_io->marketTradeUnmatched=true;
						while($this->data_io->marketTradeUnmatched)
						 {
						  $t_ret = $this->data_io->doTheMatching ();
						 							 
						 // print("createOB - doTheMatching:".$mf[_PHASE_RUN_COMMENT]."== ret:".$ret."==tret:".$t_ret."\n");	
					      if ( ($t_ret & _FETCH_RET_TRADES)==_FETCH_RET_TRADES) 
							 {
							  $o_ret = $this->tradesUpdateOB($mf[_PHASE_RUN_COMMENT]); 
							  if ($o_ret==_FETCH_RET_NO_TRADES)
							  	  $this->data_io->marketTradeUnmatched = 0; // if no trades, break the while!
							
							  }
							  else{
								  
								  break;
							      }  							  
						  }
						  	  
						}
			   }
		  }	   
		}
	}	 
 }
 if ($this->data_io->creOBStats==0)
 {
	 $this->data_io->packHiLo($this->tableIT, true); // true: Send Orders & Trades totals also!		
	// $out = "ADDMESSAGE "._ch_hilo." ".$this->data_io->hiloScript."\r\n";
	 //fwrite($this->op, $out);
	 $this->sendMsgToMeteor(_ch_hilo);
	 $this->secInfo->packMembers();
	 $this->msg_out = $this->secInfo->membersScript;
	// print("membersSript:".$this->secInfo->membersScript."\n");
	 $this->sendMsgToMeteor(_ch_trades,_msg_local);
	 }
	 else {
		  $this->secInfo->ar_graphObj["bidask_g"]->close();
	 	  }
 
 //====>$this->data_io->getTopAccounts();
 //print("TopAcc=".$this->data_io->accTradesScript."\n");
 if ($this->data_io->accTradesScript!='') 
	{
		$this->msg_out = $this->data_io->accTradesScript;
		//fwrite($this->op, $out);
		
		$this->sendMsgToMeteor(_ch_accounts, _msg_local);
		$this->data_io->accTradesScript='';
		}
		
 print ("createOB - Stopped now: ".$this->secInfo->lastOrders[0]->OASIS_TIME." Custom ask:" . $this->custom_start."\n");
 $this->setProgressMsg(9,"END");

 return 1;		 
 }

//=================================
//	Add new Graph
//=================================
function updateGraphFile($row)
{
 $milis= $this->data_io->formatTimeStr($row->OASIS_TIME);
					 					
 $bprice= ((int)($this->secInfo->bid_1==99999 ?0 :$this->secInfo->bid_1)) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); 
 $aprice= ((int)$this->secInfo->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
 $vol = $row->VOLUME*1;					 					
									//	if ($bprice!=$aprice){
 if ( ($row->BOARD_ID=="B" && $row->SIDE=='B') || $row->M_C_FLAG=="Q"|| $row->M_C_FLAG=="M") {
       $bprice= ((int)$row->PRICE / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])));  
	   $this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
							    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'. ';'.';' .';' .$vol.';'.$bprice);
						
		}
	else
		$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
							    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'.';'.$bprice .';'. $aprice .';' .$vol.';');
 $msg="";
 if ($row->M_C_FLAG=="Q")
 	 $msg=	"Market Maker<br>";
  elseif ($row->M_C_FLAG=="M")
 	 $msg=	"ShortSell<br>";
   elseif($row->BOARD_ID=="B")
 	  $msg="PreAgreed<br>";

if ($row->BOARD_ID=="B" && $row->SIDE!='B') 
   ;
else	
    $this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addEventFile(
							    date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,$row->SIDE,$msg."Trader: ".$row->TRADER_ID.'-'.$row->CSD_ACCOUNT_ID
												."<br>P:".$row->PRICE, "", 0, $row->PRICE, ($row->BOARD_ID=="B" ?$row->BOARD_ID :$row->M_C_FLAG)); 
 }
 
function tradesUpdateOB($phase_run_comment)
{  
  $rows=$this->secInfo->lastTrades; 
  if (!$rows)  
       return _FETCH_RET_NO_TRADES;
	 
				  $trades = count($rows);
				  $r=$trades;
// print ("transmitTrades - ".$trades."\n");
 	  
				  foreach($rows as $row)
					{
					// print("TRADE: ".$row->TRADE_NUMBER."==".$row->OASIS_TIME."==".$row->BUY_ORDER_NUMBER."\n");
					/*if ($row->SELL_ORDER_NUMBER=='17977'){
						$fl=1;
					    print("TRADE: ".$row->TRADE_NUMBER."==".$row->OASIS_TIME."==".$row->BUY_ORDER_NUMBER."\n");
						}
						else
						$fl=0;*/
				 	 if (is_array($this->secInfo->trade_numbers))
					 	if (in_array($row->TRADE_NUMBER,$this->secInfo->trade_numbers)){
	    				 	$this->data_io->errorlog->addlog( 'DUP trades : '.$this->secInfo->nextOrderKey."===".$row->TRADE_NUMBER);
						 	return _FETCH_RET_NO_TRADES;
							 }
					 if ($this->data_io->creOBStats==0)
					 	 $this->data_io->updateLoHiPrice($row->PRICE);
					 $this->data_io->getMatchedOrders($row);
					 $ret = $this->data_io->tradeUpdateOB(&$row);
					 if ($ret==_OBOOK_RET_NO_ORDER)
					     return _FETCH_RET_NO_TRADES;
					 
					 if ($phase_run_comment == _DO_AUCTION) 
					 	 ;//$this->data_io->getMD_1();  // get first level only
					 
					 $idx=$row->BUY_TRADER_ID.'-'.$row->BUY_CSD_ACCOUNT_ID;
					 
					 if ( array_key_exists($idx, $this->secInfo->bid_traders) ){
					// print("bid_traders: ".$idx."\n");
					 		$this->secInfo->bid_traders[$idx][1]+=(int)$row->VOLUME;
							}
					
					if ($this->data_io->creOBStats==0)
					{ 
					 $idx=$row->SELL_TRADER_ID.'-'.$row->SELL_CSD_ACCOUNT_ID;
					 if ( array_key_exists($idx, $this->secInfo->ask_traders) )
					 	   $this->secInfo->ask_traders[$idx][1]+=(int)$row->VOLUME;
					 
					 
				 	$bmemId=mb_substr($row->BUY_TRADER_ID,0,2, 'UTF-8');
					$this->secInfo->members["B"][$bmemId][1] += $row->VOLUME;
					//$this->secInfo->members["B"][$bmemId][2] = round((float)(($this->secInfo->members["B"][$bmemId][1] * 100) / $this->secInfo->members["B"][$bmemId][0]),2);

					$smemId=mb_substr($row->SELL_TRADER_ID,0,2, 'UTF-8');
					$this->secInfo->members["S"][$smemId][1] += $row->VOLUME;
					//$this->secInfo->members["S"][$smemId][2] = round((float)(($this->secInfo->members["S"][$smemId][1] * 100) / $this->secInfo->members["S"][$smemId][0]),2);
//					 $this->errorlog->addlog("memberSEll: ".$smemId."==".$secInfo->members["S"][$smemId][0]."==".$secInfo->members["S"][$smemId][1]."==".$secInfo->members["S"][$smemId][2]);
					}
										
					 if ($this->data_io->creOBStats==1)
					 {
					// $this->data_io->getMD_1();
					 $milis= $this->data_io->formatTimeStr($row->OASIS_TIME);
					 $price=  ((int)$row->PRICE) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					 //print("Trade:".$this->secInfo->bid_1."\n");
					 $bprice = (int)($this->secInfo->bid_1==99999 ?0 :$this->secInfo->bid_1);
					// if ($bprice==0)
					//     $bprice=$row->PRICE;
					 $bprice= (int)$bprice / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); 

					 $aprice= (int)$this->secInfo->ask_1;
					// if ($aprice==0)
					//     $aprice=$row->PRICE;
					 $aprice= (int)$aprice / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					
					if ($price	> $aprice)
					    $aprice=$price;
					if ($price	< $bprice)
					    $bprice=$price;	
						
					// $bprice= ((int)$row->BID) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); //((int)($this->data_io->bid_1=='99999' ?'0' :$this->data_io->bid_1)) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					// $aprice= ((int)$row->ASK) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS])); //((int)$this->data_io->ask_1) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
					//
					// $this->addDataFile(($this->data_io->creOBStats ?$this->secInfo->graphBidAskFnameGlobal :$this->secInfo->graphBidAskFname),date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'. $price.';'.$bprice .';'. $aprice .';'. $row->VOLUME );
					 $this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addDataFile(
					 			date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis.';'. $price.';'.$bprice.';'.$aprice .';'. $row->VOLUME.';' );
					 
					 //if ($this->data_io->creOBStats)
					 	//{
					 	$url="javascript:App.createTradeOrdersWindow_bid('" . $row->OASIS_TIME ."','" . $row->TRADE_NUMBER ."','". $row->BUY_ORDER_NUMBER ."','". $row->SELL_ORDER_NUMBER."','Buy/Sell Detail-1','order',".($this->data_io->creOBStats ?"'noOB'" :"'OB'").");";
//if ($row->OASIS_TIME=="135630260")
  //print "Orders:".$row->OASIS_TIME." - ".$row->BUY_ORDER_NUMBER." - ".$row->TRADE_NUMBER." - ".$trades."\r\n";	 
	 // ..... IT TAKES EVENTS from the GLOBAL EVENTS ...!
	 					$sideTrade = strtolower($this->secInfo->tr_orders[0]["btime"] < $this->secInfo->tr_orders[0]["atime"] ?$this->secInfo->tr_orders[0]["aside"] :$this->secInfo->tr_orders[0]["bside"]);
					 	$this->secInfo->ar_graphObj[($this->data_io->creOBStats ?"bidask_g" :"bidask")]->addEventFile(
									date("Y-m-d",strtotime($this->data_io->getSessionDate())).' '.$milis,$row->PHASE_ID,"B: ".$row->BUY_TRADER_ID.'-'.$row->BUY_CSD_ACCOUNT_ID."&nbsp;S: ".$row->SELL_TRADER_ID.'-'.$row->SELL_CSD_ACCOUNT_ID."<br>Trade:".$row->TRADE_NUMBER, "", $trades,"",$sideTrade,$row->SELL_M_C_FLAG); // ordr: graph id
					 	//}
					 }
					 
					 if ( ($ret & _OBOOK_RET_ORDER)==_OBOOK_RET_ORDER)
				 	 		$this->secInfo->trade_numbers[]=$row->TRADE_NUMBER; 
					 }
					// if ($fl==1)
					// print("TRADE:-----------------\n");
 return _FETCH_RET_TRADES;
 }
//-------------------------------------------------
//      Treaders Graph
//-------------------------------------------------
function createTradersGraph($side)
{
  $this->data_io->getTradersGraph($side);
  $rows=$this->secInfo->TradersGraph; 
// print("createTradersGraph - ".count($rows)."/n");
  if (!$rows)  
       return _FETCH_RET_NO_TRADES;
	 
//  $this->createAskBidFile_csv($this->secInfo->graphTradersFname[$side],"txt");
  
  foreach($rows as $row) 
  { 
   $this->secInfo->ar_graphObj[$side."traders"]->addDataFile($row->acc.';'. $row->ovol);
   }
   
   $this->secInfo->ar_graphObj[$side."traders"]->close();
 }

function createHourTradesGraph()
{
 if (($this->secInfo instanceof sec_info)==false){
	  print("createHourTradesGraph - Invalid secInfo!"."/n");
	  return;
 	  }
 $rows=$this->secInfo->hourGraphData; 
 //$idx = $this->data_io->indexOf($this->data_io->filter_sec, $this->secInfo->getIsinSec());
//  print("createHourTradesGraph - ".$this->secInfo->getIsinSec()."==".count($rows)."\n");
  if (!$rows)  
       return _FETCH_RET_NO_TRADES;
 
  $this->secInfo->ar_graphObj["hourtrades"]->createFiles() ;
//  $this->createFile($this->secInfo->graphHourFname,"");
  $sdate=date("Y-m-d",strtotime($this->data_io->getSessionDate() ));
  
  foreach($rows as $row) 
  { 
    $this->secInfo->ar_graphObj["hourtrades"]->addDataFile( $sdate.' '.$row->tim.';'.$row->v.';'.$row->p
																	.';'.($row->v0==0 ?'' :$row->v0).';'.($row->p0==0 ?'' :$row->p0)
																	.';'.($row->v1==0 ?'' :$row->v1).';'.($row->p1==0 ?'' :$row->p1));
   }
  $this->secInfo->ar_graphObj["hourtrades"]->close();
 }
 
 
/********************** CONTROL the Market ******************************************
*
*		Start up Market
*
*****************************************************************/
function run_trade($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat,$__marketbeat_flag,$fwrd_step,$orders_cntr;
$s="error";

//$data_io
//shell_exec("php marketbeat.php");
	if ($this->secInfo->surv_str	!= ""){
		$this->msg_out ='{"rows":[{"id":"SURV","data":['.$this->secInfo->surv_str.']}]}';
	 	$this->sendMsgToMeteor(_ch_mktdepthB, _msg_local);
		$this->secInfo->surv_str	= "";
		}
		
//		Start up Market beat
	if ($this->__marketbeat_flag==false) { // $this->mkt_stat!=_MARKET_PAUSED) {
		proc_close (proc_open ("php /u01/imarket/marketplay/marketbeat.php ".$this->ses." --foo=1 &", Array (), $foo));
//		$this->createFile("trades_data_".$this->ses.".txt","");
		//$this->createAskBidFile("js/graphs/bidask_data","xml");
//		$this->createFile("js/graphs/mdb_data.txt","");
//		$this->createFile("js/graphs/mda_data.txt","");
//		$this->createFile("js/graphs/projected_data1.txt","");
//		$this->createFile("js/graphs/projected_data2.txt","");
//		$this->createFile("js/graphs/projected_events.xml","xml");
//		$this->createFile("js/graphs/bidask_events.xml","trade");
//		$this->createAskBidFile_csv("js/graphs/bidask_data","txt");
//		$this->createFile("js/graphs/trades_events_".$this->ses.".xml","xml");
		print "run_trade == marketbeat set ON - ".$this->mkt_stat."\n";
		}

	if ($this->mkt_action!='fw' || ($this->mkt_action=='fw' && $this->orders_cntr < $this->fwrd_step)) {
		list($this->delay,$this->mkt_stat)=explode(":",$data);  // stat="r" : RUN
		$this->__marketbeat_flag=true;
		print "run_trade ==".$this->delay."|=Stat=|".$this->mkt_stat."|=Action=|".$this->mkt_action." ses=".$this->ses."\n";
		}
	
$obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
*
*		Dummy func - just to create the Market Object
*
*****************************************************************/
function createMarket($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;
$s="error";

print "Market Created ==".$data."\n";
$obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
*
*		set Engine Avail
*
*****************************************************************/
function setEngineAvail($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;
$s="error";

$this->tradeEngineAvailability=(bool)$data;
//print "stop trade ==".$data." == Disconnected:".$ret."\n";
$obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
*
*		STOP the Market
*
*****************************************************************/
function stop_trade($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;
$s="error";

$this->mkt_stat=$data;

//	$ret=$this->disconnectAll();
print "stop trade ==".$data." == Disconnected:".$ret."\n";

$obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
*
*		DELETE the Market Object
*
*****************************************************************/
function deleteMarket($obj, $socket_id, $channel_id,$data)
{
 if( ($this->data_io instanceof messages_io) == true) 
 {
  if ($data=="clear")
  	  $this->data_io->sourceObj->clearSessionData();
	  
//  if ($this->data_io->cfg->init == _DO_INITIALIZATION || $this->data_io->init_flag ==_DO_PARTIAL_INITIALIZATION)
/**
* Oracle is always connected when session is opened. On exit must closed!
*/  
  $this->disconnectSup();
 
  $this->secInfo=$this->data_io->markets[$this->data_io->cfg->market]->getActiveSec(); 
/* 
  do {
 	 $this->removeSessionFiles();
  }while($this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->setActiveNextSec());
*/ 
  $this->data_io->clear_beforeDelMkt();
 }
 
 system ("killall -s SIGUSR1 meteord", $ret);
 if ($this->op!=false && !feof($this->op))
     fclose($this->op);
 
 unset($this->parent->markets[$this->ses]);  // Delete Service Object of this Session
 $this->parent->markets[$this->ses]=null;
 
 
 print "Market deleted!"."\n";
 $obj->write($socket_id, $channel_id, "ok");
 }

/**
*		delete Session
*/
function deleteSession($obj, $socket_id, $channel_id,$data) {
 unset($this->parent->markets[$this->ses]);  // Delete Service Object of this Session
 
 print "Session ".$this->ses." deleted!"."\n";
 $obj->write($socket_id, $channel_id, "ok");
 }

/**
*		Next Order
*/
function next_Order($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;

$s="error";
$this->mkt_stat=$data;
//market_tick($obj, $socket_id, $channel_id,$data);
print "next_Order ==".$channel_id."\n";

$obj->write($socket_id, $channel_id, "one_step,".$this->delay."_".$this->ses);	
}

/**
*		Previous Order / back 2 steps and get Next
*/
function prev_Order($obj, $socket_id, $channel_id,$data)
{
$s="error";
// Return: -1 when Events need refresh , 0 is normal

// ==== Before step back, make a reverse Matching---->
$this->secInfo->reverseMatchingFlag=true;

if ($this->secInfo->lastOrders)
	$this->reverseMatching($this->secInfo->lastOrders->ORDER_NUMBER);
	
$this->secInfo->reverseMatchingFlag=false;
// ==== End of reverse Matching----->

$this->stepBackOrders();	// back two steps, and thw cmd in data is 'n' next
$this->mkt_stat=$data;  
print "prev_Order = #".$channel_id."\n";

$obj->write($socket_id, $channel_id, "step_back,".$this->delay."_".$this->ses);	
}

function stepBackOrders()
{
 $ret = $this->phases_info->stepbackPhaseCntr(2); // 2: because a next_order will follow imediatelly! 
 
 // Notify Client ====>
 	$this->msg_out='{"rows":[{"id":"199","data":[{"TIME":"'.$this->orders_cntr.'","PHASE":'.'"STEP_BACK"'.'}]}]}';
 	$this->sendMsgToMeteor(_ch_events,_msg_local);
// ===================> 
 }
 
/****************************************************************
*
*		PAUSE the Market
*
*****************************************************************/
function pause_trade($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;
$s="error";

print "pause trade ==".$data." ses=".$this->ses."\n";

$this->mkt_stat=$data;

$obj->write($socket_id, $channel_id, "ok");	
$this->getMeteorMsgs();
}

/****************************************************************
*
*		Set ACTIVE new ISIN
*
*****************************************************************/
function setIsinActive($obj, $socket_id, $channel_id,$data)
{
//global $delay,$mkt_stat;
 $s="error";

 $this->secInfo=$this->data_io->markets[$this->data_io->cfg->market]->setActiveSec($data);
 $this->isin = $this->secInfo->getIsinSec();
 $this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);
 
 print ("ActiveSec:".$this->isin."\n");
 $this->retransmitChannels($this->isin);
 
 $this->data_io->packHiLo($this->tableIT, true); // true: Send Orders/Trades totals also!		
 $this->sendMsgToMeteor(_ch_hilo);
	 
 $obj->write($socket_id, $channel_id, "ok");	
 }



function retransmitChannels($isin)
{
 foreach($this->channels as $id=>$channel)
 {
  if ($channel[_cache_len] > 0)
  {
    print ("retransmit Channel:".$id."\n");
   $this->sendMsgToMeteor($id,_reTransmit);
   }
  }
 }
 
/**
*		Set a new delay time
*/
function set_delay($obj, $socket_id, $channel_id,$data)
{
//global $delay;
print "set new delay at: ".$data."\n";

$this->delay=$data;
$obj->write($socket_id, $channel_id, "ok");	
}

/**
*		Read Current Orders
*/
function get_AllTraders($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 $this->data_io->getAllTraders($data);
// print($script);
// $out = "ADDMESSAGE "._ch_traders." ".$this->data_io->tradersScript."\r\n";
// fwrite($this->op, $out);
 $this->sendMsgToMeteor(_ch_traders);
 $obj->write($socket_id, $channel_id, "ok");	
}

/**
*		Read Current Orders
*/
function get_currentOrders($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$format,$posStart,$count,$phase,$trader)=explode(':',$data);
 $script = $this->data_io->getHistoryOrders($msgId, $format, $posStart, $count, $phase, $trader);
//print($script);
 $obj->write($socket_id, $channel_id, $script);	
}

/**
*		Read Traders
*/
function get_smartTraders($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$format,$posStart,$count,$results, $sort, $dir, $trader)=explode(':',$data);
 $script = $this->data_io->getTraders($msgId, $format, $posStart, $count,$results, $sort, $dir,$trader);
//print($script);
 $obj->write($socket_id, $channel_id, $script);	
}

/**
*		Query Securities from Ora
*/
function get_orasecs($obj, $socket_id, $channel_id, $data)
{
// global $data_io;
print("get_orasecs => " .$this->filter."\n");
 list($format,$posStart,$count,$results, $sort, $dir,$newRequest)=explode(':',$data);
 
 
 $script = $this->data_io->sourceObj->getOraSecs($format, $posStart, $count,$results, $sort, $dir, $newRequest,$this->filter);

 $obj->write($socket_id, $channel_id, "err=".$script.";");	
}

/**
*		set_SurvTrader
*/
function select_OraSec($obj, $socket_id, $channel_id, $data)
{
 list($fdate, $value)=explode(':',$data);
// print ("set_SurvTrader -".$trader."==".$value."\n");
 $ret=$this->data_io->setSurvTraders($trader, $value);
// print_r($this->data_io->survTraders);
 $obj->write($socket_id, $channel_id, (($ret==true) ?"ok" :"error"));	
 if($ret==true){
 	$this->msg_out ='{"rows":[{"id":"SURV","data":['.$this->secInfo->surv_str.']}]}';
 	$this->sendMsgToMeteor(_ch_mktdepthB, _msg_local);
	$this->secInfo->surv_str	= "";
 	}
 }
 
/**
*		Read Current OB
*/
function get_OrderBook($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$format,$posStart,$count,$results, $sort, $dir, $trader)=explode(':',$data);
 $script = $this->data_io->getOrderBook($msgId, $format, $posStart, $count,$results, $sort, $dir,$trader);
//print($script);
 $obj->write($socket_id, $channel_id, $script);	
}

/**
*		Search the Market
*/
function searchMarket($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($task,$keywords)=explode(':',$data);

 $script = $this->data_io->startSearchEngine($task, $keywords);
 
 if (count($script))
 {
  for($i=0; $i<count($script);$i++)
  {
   $this->msg_out =$script[$i];
 // print($this->msg_out."\n");
   $this->sendMsgToMeteor(_ch_mktsearch, _msg_local);
   }
  }
 $obj->write($socket_id, $channel_id, "ok");
}

/**
*		Search the Market
*/
function countSessions($obj, $socket_id, $channel_id, $data)
{
 global $super_market;
 
 $script="sessions=".json_encode($super_market).";";
 
 $obj->write($socket_id, $channel_id, $script);
}
 
/**
*		set_SurvTrader
*/
function set_SurvTrader($obj, $socket_id, $channel_id, $data)
{
 list($trader, $value)=explode(':',$data);
// print ("set_SurvTrader -".$trader."==".$value."\n");
 $ret=$this->data_io->setSurvTraders($trader, $value);
// print_r($this->data_io->survTraders);
 $obj->write($socket_id, $channel_id, (($ret==true) ?"ok" :"error"));	
 if($ret==true){
 	$this->msg_out ='{"rows":[{"id":"SURV","data":['.$this->secInfo->surv_str.']}]}';
 	$this->sendMsgToMeteor(_ch_mktdepthB, _msg_local);
	$this->secInfo->surv_str	= "";
 	}
 }

/**
*		Read Current Trades
*/
function get_currentTrades($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$format,$posStart,$count,$phase,$buyer,$seller)=explode(':',$data);
 $script = $this->data_io->getHistoryTrades($msgId, $format, $posStart, $count,$phase,$buyer,$seller);
//print($script);
 $obj->write($socket_id, $channel_id, $script);	
}

/**
*		Read mdTrades
*/
function get_mdTraders($obj, $socket_id, $channel_id, $data)
{
 list($side,$price)=explode(':',$data);
 print("mdTrades - ".$side."==".$price."\n");
 $script = $this->data_io->getMdTraders($side,$price);
 
 $obj->write($socket_id, $channel_id, $script);
 }

/**
*		set a new BookMark
*/
function get_Bmarks($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 $script = $this->data_io->getBmarks();
 //print("get_Bmarks - "."\n");
 
 $obj->write($socket_id, $channel_id, $script);	
}

function setBmark($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($code, $time, $side, $price, $volume, $trader, $type) = explode(':',$data);
 
 $ret = $this->data_io->setBmark($code, $time, $side, $price,$volume,$trader,$type);
 print("Bookmark set - id:".$ret."\n");
 
 $obj->write($socket_id, $channel_id, "ans=".($ret==false ?"error;" :"ok;"));	
}

function clearBmark($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($id, $all) = explode(':',$data);
 
 $ret = $this->data_io->clearBmark($id, ($all=="" ?false :true));
 print("clearBmark -".$ret."\n");
 
 $obj->write($socket_id, $channel_id, "ans=ok;");	
}

function copyBmark($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($id) = explode(':',$data);
 
 $ret = $this->data_io->copyBmark($id);
 print("Copy Bookmark set - id:".$ret."\n");
 
 $obj->write($socket_id, $channel_id, "ans=".($ret==false ?"error;" :"ok;"));	
}

/****************************************************************
*
*		Refresh Hour Trades
*
*****************************************************************/
function refreshHourTrades($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($trader,$investor)=explode(':',$data);
//print("refreshHourTrades -".$trader."==".$data."\n"); 
 $this->data_io->createHourGraphData(($trader=='' ?false :true), $trader,$investor);
 $this->createHourTradesGraph();

 $obj->write($socket_id, $channel_id, "ans=ok;");	
}

/****************************************************************
*
*		Read Current Trades
*
*****************************************************************/
function get_TradeOrders($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

print("get_TradeOrders : ".$data."\n");
 list($msgId,$mode,$time,$msg_num,$ask,$sort,$dir)=explode(':',$data);			// $ask in case of trade must be null !
 if ($msgId=='IS')
     $script = $this->data_io->getTradeDetails($msgId, $time, $msg_num,$sort,$dir);
	else 
	   $script = $this->data_io->getTradeOrders($msgId, $mode, $time, $msg_num, $ask,$sort,$dir);

 $obj->write($socket_id, $channel_id, $script);	
}

/****************************************************************
*
*		Read Market Stats
*
*****************************************************************/
function get_MarketStatistics($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

//print("get_MarketStatistics : ".$data."\n");
 list($id,$isin)=explode(':',$data);			// $ask in case of trade must be null !
 $script = $this->data_io->getStatistics($isin);
//print("get_MarketStatistics ".$script."\n");
 $obj->write($socket_id, $channel_id, $script);	
}

/****************************************************************
*
*		Read MarketDepth Details
*
*****************************************************************/
function get_MarketDepthDetails ($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

//print("get_MarketDepthDetails:".$data."\n");
 list($side,$time,$price)=explode(':',$data);
 $script = $this->data_io->getMarketDepthDetails($side, $time, $price);

 $obj->write($socket_id, $channel_id, $script);	
}

/****************************************************************
*
*		Save Notes
*
*****************************************************************/
function save_Notes($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$trade,$bid,$ask,$htmlText)=explode(':',$data);
 
 print("save_Notes ".$msgId."\n");
 
 $ret = $this->data_io->saveNotes($msgId, $trade,$bid,$ask,$htmlText);

 $obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
*
*		Save Notes
*
*****************************************************************/
function get_CRMinfo($obj, $socket_id, $channel_id, $data)
{
// global $data_io;

 list($msgId,$msg_num,$trader,$time)=explode(':',$data);
 
 print("get_CRMinfo ".$msgId."\n");
 
 $script = $this->data_io->getCRMinfo($msgId,$msg_num,$trader,$time);

 $obj->write($socket_id, $channel_id, $script);	
}

/****************************************************************
*
*		Read Open Price Details
*
*****************************************************************/
function get_OpenPriceDetails($obj, $socket_id, $channel_id, $data)
{
// global $data_io;
print("call of OpenPriceDetails..."."\n");
 $script = $this->data_io->OpenPriceDetails();

 $obj->write($socket_id, $channel_id, $script);	
}

/****************************************************************
*
*		STEP Forward the Market
*
*****************************************************************/
function step_fwrd($obj, $socket_id, $channel_id,$data)
{
$s="error";


 list($this->custom_start,$this->custom_stop)=explode(":",$data);
 $this->custom_stop=$this->custom_start;
 
 $this->mkt_action='fw_custom_period';
 $this->last_time= ($this->custom_start > $this->secInfo->lastOrders[0]->OASIS_TIME) ?$this->secInfo->lastOrders[0]->OASIS_TIME :"00000000";
 
 print "step_fwrd Last=".$this->last_time." custom_start=".$this->custom_start ." OASIS_TIME=". $this->secInfo->lastOrders[0]->OASIS_TIME."\n";
 
 $this->orders_cntr=0;
 $this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
 $this->phases_info = $this->secInfo->getPhase();
 $this->data_io->reset_some_things(); // set also setCustomPeriod to false!

 //do
 //{	
	if ($this->secInfo->totalOrders==0)
		continue;
	//$this->secInfo->resetStats();  // tradrs info

	$isin=$this->secInfo->getIsinSec();
    if ($this->last_time=="00000000")
	{
    	//$this->initGraph();
		$phases_info = $this->secInfo->getPhase();
		if (is_a($phases_info,'msg_info'))  {
  			$phases_info->restorePhases($isin);
			$phases_info->reset_currphase();
			}
	 }
	 		
	$this->createOB();
	print "step_fwrd isin ==".$isin."\n";	
	//}while($this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->setActiveNextSec());

$this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
$this->isin = $this->secInfo->getIsinSec();
$this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);
  
system ("killall -s SIGUSR1 meteord", $ret);
print "Clear message: ".$ret."\n";
	
$obj->write($socket_id, $channel_id, "ok");	
}

/****************************************************************
******************************************************************
*		Forward Custom Period Market
******************************************************************
*****************************************************************/
function custom_period($obj, $socket_id, $channel_id,$data)
{
//global $data_io,$delay,$mkt_stat,$fwrd_step,$mkt_action,$orders_cntr;
$s="error";

	print "custom_period ==".$data."\n";

 list($this->custom_start,$this->custom_stop)=explode(":",$data);
 $this->mkt_action='fw_custom_period';
 $this->last_time= ($this->custom_start > $this->secInfo->lastOrders[0]->OASIS_TIME) ?$this->secInfo->lastOrders[0]->OASIS_TIME :"00000000";
 
 $this->orders_cntr=0;
 $this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
 $this->phases_info = $this->secInfo->getPhase();
 $this->data_io->reset_some_things(); // set also setCustomPeriod to false!

 //do
 //{	
	$isin=$this->secInfo->getIsinSec();
	
	if ($this->secInfo->totalOrders==0)
		print "custom_period totalOrders 0 for isin ==".$isin."\n";
		else
			$this->secInfo->resetStats();  // tradrs info

    if ($this->last_time=="00000000")
	{
//	 $this->initGraph();
	 $phases_info = $this->secInfo->getPhase();
	 if (is_a($phases_info,'msg_info'))  {
  		 $phases_info->restorePhases($isin);
		 $phases_info->reset_currphase();
		 }
	}	
//	$this->last_time='00000000';
	$this->createOB();
	print "custom reset isin ==".$isin."\n";	
	//}while($this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->setActiveNextSec());

$this->secInfo = $this->data_io->markets[$this->data_io->cfg->market]->getActiveSec();
$this->isin = $this->secInfo->getIsinSec();
$this->phases_info = $this->data_io->markets[$this->data_io->cfg->market]->getSecPhase($this->isin);
  
system ("killall -s SIGUSR1 meteord", $ret);
print "Clear message: ".$ret."\n";

/*
$this->createFile("js/graphs/trades_data_".$this->ses.".txt","");
$this->createFile("js/graphs/trades_events_".$this->ses.".xml","xml");
$this->createFile("js/graphs/projected_data1_".$this->ses.".txt","");
$this->createFile("js/graphs/projected_data2_".$this->ses.".txt","");
$this->createFile("js/graphs/projected_events_".$this->ses.".xml","xml");
$this->createFile("js/graphs/bidask_events_".$this->ses.".xml","trade");

print "Clear Graph files.... \n";
*/


//$this->createFile("js/graphs/mdb_data.txt","");
//$this->createFile("js/graphs/mda_data.txt","");

//$this->createAskBidFile("js/graphs/bidask_data","xml");

//$this->createAskBidFile_csv("js/graphs/bidask_data","txt");

	
$obj->write($socket_id, $channel_id, "ok");	
}


//====================================================================================================================


/****************************************************************
*		addEventGraph
*****************************************************************/
function addEventGraph()
{
	$evs=$this->json_decode2($this->data_io->eventsScript, true);
			
	foreach($evs as $rows=>$row)
			foreach($row as $sub=>$data)
				foreach($data as $col=>$val)
						if ($col=='data')
						{
						if ($val[2])
							$this->addEventFile("js/graphs/trades_events_".$this->ses.".xml",date("Y/m/d",strtotime($this->data_io->getSessionDate())).' '.$val[0]."0", $val[2], $val[1]);
						}
 }
 

function addTradeGraph($trade)
{
    $open = $this->data_io->auctionPrice / (pow(10,2));
	$low  = ((int)$this->data_io->lowest) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$hi   = ((int)$this->data_io->highest) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$price= ((int)$trade->PRICE) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$lasttrade= ((int)$this->data_io->lastTrade->PRICE) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
    $milis= $this->data_io->formatTimeStr($trade->OASIS_TIME);
	$bprice= ((int)$this->secInfo->tr_orders[0]["bprice"]) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$aprice= ((int)$this->secInfo->tr_orders[0]["aprice"]) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
//	 print_r($this->data_io->tr_orders);
	$this->addDataFile("js/graphs/trades_data_".$this->ses.".txt",date("Y/m/d",strtotime($this->data_io->getSessionDate())).' '.$milis .",". $open .",".$trade->VOLUME .",". $price .",". $low.",". $hi);
/*	$this->addAskBidDataFile_csv("js/graphs/bidask_data", $trade->TRADE_NUMBER, $trade->OASIS_TIME, $trade->VOLUME,$price,$bprice,$aprice,
														$trade->BUY_TRADER_ID,$trade->BUY_CSD_ACCOUNT_ID,$trade->SELL_TRADER_ID,$trade->SELL_CSD_ACCOUNT_ID,
														$this->data_io->tr_orders[0]["border"],$this->data_io->tr_orders[0]["aorder"],
														$lasttrade,$hi,$low);
														*/
/*					
	$dat=$this->json_decode2 ($this->data_io->tradesScript, true);
	foreach($dat as $rows=>$row)
			foreach($row as $sub=>$data)
				foreach($data as $col=>$val)
					if ($col=='data')
					{
				//		print "_FETCH_RET_TRADES ==".$data_io->tradesScript."\n";
					$val[0].='0'; // time: miliseconds must be of 3 digits!
					$open = $this->data_io->auctionPrice / (pow(10,2));
					//	trades","{rows:[{id:'000229',data:['10:31:53:25','T','000229','11.56','978','ΕΘ00C','ΕΘ','00007016','PSM02','PS','00006325','11305.68','0','978','11.56','2000',11.54,11.56]
					//									  ['10:31:52:18','O','000201','11.54','196','MD001','MD','00000027','Î‘Î¦012','Î‘Î¦','00004319','2261.84','27','0','196','4319','0','1368',11.54,11.54]		 
			//		$this->addDataFile("js/graphs/trades_data.txt",date("Y/m/d",strtotime($this->data_io->get4Date())).' '.$val[0] .",". $open .",".$val[4] .",". $val[3] .",". $val[13] .",". $val[16] .",". $val[18].",". $val[19]);
					$this->addDataFile("js/graphs/trades_data.txt",date("Y/m/d",strtotime($this->data_io->getSessionDate())).' '.$val[0] .",". $open .",".$val[4] .",". $val[3] .",". $val[18].",". $val[19]);
					$this->addAskBidDataFile("js/graphs/bidask_data", date("Y/m/d",strtotime($this->data_io->getSessionDate())).' '.$val[0],$val[4],$val[13],$val[16],$val[8]);
					//$this->addDataFile("js/graphs/bidask_data.txt",date("Y/m/d",strtotime($this->data_io->getSessionDate())).' '.$val[0] .";". $val[4] .";". $val[13].";". $val[16].";". $val[8]);
					}
*/					
}

function addAskBidDataFile_csv($file,$tradeno,$oasis_time, $vol,$price,$bid,$ask,$b_trader,$b_account,$a_trader,$a_account,$border,$aorder, $last_trade,$hi,$low)
{
 $milis= $this->data_io->formatTimeStr($oasis_time);
 
 //$this->addDataFile($file."a.txt",$milis.",".$bid.",".$ask.",".$vol.",".$last_trade.",".$hi.",".$low);
 $this->addDataFile($file."a.txt",$milis.",".$bid.",".$ask.",".$vol.",".$last_trade.",".$hi.",".$low);
 $len=2;
 if (mb_check_encoding($b_trader,'UTF-8'))
	 $len=4;

 $url=$url_b."javascript:createTradeOrdersWindow_bid('" . $oasis_time ."','" . $tradeno ."','". $border ."','". $aorder."','Buy/Sell Detail2','order');";
 $this->addEventFile("js/graphs/bidask_events_".$this->ses.".xml",$milis,mb_substr($b_trader,0,$len),"Tr: ".$tradeno."<br><b>Pr:</b> ".$price."<br>B Acc: ".$b_account
 ."<br>S Acc:".$a_account
 ."<br>B: ".$border
 ."<br>S: ".$aorder, $url);
// print "addAskBidDataFile_csv: ".$b_trader."\n";
 //$this->addDataFile($file."b.txt",$date.",".$bid);
 }


function addAskBidDataFile_xml($file,$date,$vol,$bid,$ask,$b_trader,$a_trader)
{
 $str = '<value xid="'.$this->newSeries().'">'.$date.'</value>
 </series>';

 $fp1=fopen($file.".xml","w");
 		
 $fp=fopen($file."_s.xml","r+");
 $pt=ftell($fp);

 $flag=fseek($fp,-9,SEEK_END);
// print "addEventFile: ".$letter." = ".$pt."==".$flag."==".ftell($fp)."\n";
 fwrite($fp,$str,strlen($str));
// 
 fclose($fp);
 
 $graph_s=file_get_contents($file."_s.xml");
 fwrite($fp1,$graph_s,strlen($graph_s));
 fwrite($fp1,"<graphs>",8);
 unset($graph_s);
// $series=file_get_contents($file."_s.xml");
 
 //-------------------------------------------
 $str = '<value xid="'.$this->series.'">'.$vol.'</value>
 </graph>';
 
 $fp=fopen($file."_1.xml","r+");
 $pt=ftell($fp);

 $flag=fseek($fp,-8,SEEK_END);
// print "addEventFile: ".$letter." = ".$pt."==".$flag."==".ftell($fp)."\n";
 fwrite($fp,$str,strlen($str));
 fclose($fp);
 
 $graph_1=file_get_contents($file."_1.xml");
 fwrite($fp1,$graph_1,strlen($graph_1));
 unset($graph_1);
 //-------------------------------------------
 $str = '<value xid="'.$this->series.'" description="' .$b_trader. '" bullet="square" border_color="#002200" bullet_color="#009900" bullet_size="9"'.'>'.$bid.'</value>
 </graph>';
 
 $fp=fopen($file."_2.xml","r+");
 $pt=ftell($fp);

 $flag=fseek($fp,-8,SEEK_END);
// print "addEventFile: ".$letter." = ".$pt."==".$flag."==".ftell($fp)."\n";
 fwrite($fp,$str,strlen($str));
 fclose($fp);
 
 $graph_2=file_get_contents($file."_2.xml");
 fwrite($fp1,$graph_2,strlen($graph_2));
 unset($graph_2);
 //-------------------------------------------
 $str = '<value xid="'.$this->series.'" description="' .$a_trader. '" bullet_color="#990000" bullet_size="8"'.'>'.$ask.'</value>
 </graph>';
 
 $fp=fopen($file."_3.xml","r+");
 $pt=ftell($fp);

 $flag=fseek($fp,-8,SEEK_END);
// print "addEventFile: ".$letter." = ".$pt."==".$flag."==".ftell($fp)."\n";
 fwrite($fp,$str,strlen($str));
 fclose($fp);
 
 $graph_3=file_get_contents($file."_3.xml");
 fwrite($fp1,$graph_3,strlen($graph_3));
 fwrite($fp1,'</graphs>
</chart>',20);
 unset($graph_3);
 fclose($fp1);
 }

function addMDbidGraph()
{
 $file = "js/graphs/mdb_data.txt";
 
 $rows=$this->secInfo->md; 
 if (!$rows) {
	  		 return;
			 }

 $this->createFile($file,"txt"); // init every time
 $fp=fopen($file,"a");

 foreach($rows as $row)
 {
	$price= ((int)$row->price) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$vol=((int)$row->volume) * 1;
	$str = $price.";".$vol."\n";
	fputs($fp,$str,strlen($str));
	}
	
 fclose($fp);
}

function addMDaskGraph()
{
 $file = "js/graphs/mda_data.txt";
 
 $rows=$this->secInfo->md; 
 if (!$rows) {
	  		 return;
			 }

 $this->createFile($file,"txt"); // init every time
 $fp=fopen($file,"a");
 
 foreach($rows as $row)
 {
	$price= ((int)$row->price) / (pow(10,$this->tableIS['PRICE'][_IDX_DECIMALS]));
	$vol=((int)$row->volume) * 1;
	$str = $price.";".$vol."\n";
	fputs($fp,$str,strlen($str));
	}

 fclose($fp);	
}														
/******************************************************************************
 * 
******************************************************************************/
function createFile($file,$type) 
{
 
$fp=fopen($this->filesDir.$file,"w");
if ($type=='xml'){
/*$str="<events>
	<event>
    <graph_id>none</graph_id>
    <y_axis>right</y_axis>
    <bullet>horizontal_line_dashed</bullet>
    <border_color>000000</border_color>
    <border_alpha>50</border_alpha>
    <text_color>000000</text_color>    
    <letter>Bottom barrier</letter>
    <value>15</value>
    <percent_value>120</percent_value>
  </event>
  </events>";*/
	fwrite($fp,"<events></events>",17);
	}
if ($type=='trade')
	fwrite($fp,"<events></events>");	
//print "createFile =".$file."==".$type."\n";
fclose($fp);

return(fopen($this->filesDir.$file,"a"));
}

/******************************************************************************
 * 
******************************************************************************/
function createAskBidFile($file,$type) 
{
$this->series=0;

$fp=fopen($file.".".$type,"w");
fclose($fp);

$fp=fopen($file."_s.".$type,"w");
	fwrite($fp,'<?xml version="1.0" encoding="UTF-8"?>
	<chart>
	<series>
	</series>',73);
fclose($fp);
$fp=fopen($file."_1.".$type,"w");
	fwrite($fp,'<graph gid="1"></graph>',23);
fclose($fp);
$fp=fopen($file."_2.".$type,"w");
	fwrite($fp,'<graph gid="2"></graph>',23);
fclose($fp);
$fp=fopen($file."_3.".$type,"w");
	fwrite($fp,'<graph gid="3"></graph>',23);
fclose($fp);

}

function init_BidAskFile($file,$type)
{
  $fp=fopen($this->filesDir.$file,"w");
  fwrite($fp,'08:00:00:000;10;0;0;10',23);
  fclose($fp);
 }
 
function createAskBidFile_csv($file,$type)
{
 $fp=fopen($this->filesDir.$file,"w");
 fclose($fp);
// $fp=fopen($file."b.".$type,"w");
// fclose($fp);
 }
/******************************************************************************
 * 
******************************************************************************/
function addEventFile($file,$date,$letter,$descr,$url='',$trades=0, $price="", $flag="") 
{
//<url>javascript:openUrl(http://'www.amcharts.com', '_blank');</url>
$color=($letter=='P' ?'FDDB3E' :($letter=='T'?($trades>1 ?'F0F0F0' :'FD9C3E') :($letter=='B' ?'00FF00' :($letter=='S' ?'FF0000' :'f2d8c3'))));

$gid=="";
$axis="";
$bborder="";
$bullet="flag";
$size=10;

if ($letter=='B')
	$gid="<graph_id>bid</graph_id>";
   elseif 	($letter=='S')	
		$gid="<graph_id>ask</graph_id>"; 
		elseif ($letter=='T') 
			  if ($flag!=""){
			      if ($flag=='b')	
    					$bborder="<border_color>00ff00</border_color>";
					elseif ($flag=='s')	
    					  $bborder="<border_color>ff0000</border_color>";
				  }
		
if 	($flag=='X'|| $flag=='I'){
	$axis="<value>".$price."</value>"."<y_axis>left</y_axis>"; 
	$gid="<graph_id>cancel</graph_id>";
	$bullet="square_outlined";
	$size=7;
	//$color='ffbb00';
	}
	

if ($letter=="" && $price==-1) {
    $bullet="vertical_line_dashed";
	$gid="<graph_id>ghost</graph_id>";
	}
	
 elseif (($letter=='B' || $letter=='S') && !in_array($flag,array('X', 'I'))){
 	 	if ($price==0)
	    	$bullet="round_outline";
			else
			    $bullet="pin";
		  }	

// MC_FLAG or BOARD_ID
if ($flag == "Q") {
	$color="ffffff";
	$bullet="sign";
	$gid="<graph_id>preagreed</graph_id>";
	
	}
if ($flag == "B" && in_array($letter,array('B', 'S'))) {
	$color="00ffff";
	$letter="P";
	$bullet="square_outlined";
	$gid="<graph_id>preagreed</graph_id>";
	}	

		
$str = "<event>"
		 .$gid
		 .($url!='' ?"<url>".$url."</url>" :'')

		 ."<date>".$date."</date>
          <color>".$color."</color>"
		  .$axis
		  .(in_array($letter,array('B', 'S','X','I')) ?"" :"<letter>".$letter."</letter>")
		  ."<bullet>".$bullet."</bullet>"
		  . $bborder
          ."<description><![CDATA[".$descr."]]></description>"
          ."<size>". $size ."</size>"
        ."</event>
		 </events>";
		
$fp=fopen($this->filesDir.$file,"r+");
if (!$fp) {
    print "addEventFile: error in file: ".$file."\n";
	return;
	}
$pt=ftell($fp);

$flag=fseek($fp,-9,SEEK_END);
//print "addEventFile: ".$file."==".$letter." = ".$pt."==".$flag."==".ftell($fp)."\n";
fwrite($fp,$str,strlen($str));
fclose($fp);
}

/******************************************************************************
 * 
******************************************************************************/
function addMDdataFile($file,$price,$volume,$color) 
{
 $str = "";
 		
$fp=fopen($file,"r+");
fseek($fp,0,SEEK_END);

$pt=ftell($fp);
if ($pt > 0)    
	$flag=fseek($fp,-6,SEEK_END);
  else
	 $str ="<pie>"; 
	
$str .= "<slice title='".$price."' color='".$color."'>".$volume."</slice></pie>";

fwrite($fp,$str,strlen($str));
//print "addMDdataFile: ".$price." = pt=".$pt."== f=".$flag."== ftell".ftell($fp)."\n";
fclose($fp);
}	
/******************************************************************************
 * 
******************************************************************************/
function addDataFile($file,$data) 
{
$str = $data."\n";
//if ($file==$this->secInfo->graphBidAskFname)
//    print(strlen($str)."==".$str);		
//$this->secInfo->fp_data=fopen($this->filesDir.$file,"a");
fputs($fp,$str,strlen($str));
fclose($fp);
}

//=============================================================================
function json_decode2($content, $assoc=false)
{                
  if ( $assoc ){
       $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
                $json = new Services_JSON;
                }
   return $json->decode($content);
 }

} //class
?>