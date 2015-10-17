<?php
/** 
* Main source for TradeReplay project
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/
require_once dirname(__FILE__) . "/dp_database.php";
require_once dirname(__FILE__) . "/class.clientsocket.php";
require_once dirname(__FILE__) ."/class.msg_log.php";
require_once dirname(__FILE__) ."/class.messages.php";
require_once dirname(__FILE__) ."/class.markets.php";
require_once dirname(__FILE__) ."/class.sec_phases.php";
require_once dirname(__FILE__) ."/class.mysearch.php";
require_once dirname(__FILE__) ."/class.graphfile.php";
require_once dirname(__FILE__) ."/class.db_source.php";
//require_once(dirname(__FILE__) . "/Collection.class.php");
//require_once dirname(__FILE__) . '/mos_func.php';


require_once dirname(__FILE__) . '/class.marketplay.php';

DEFINE('_FIC','ficsnd');
DEFINE('_SUP','supsnd');

DEFINE('_MSG_IDX_DELISIN', 10);
DEFINE('_MSG_IDX_SHOWSRCHCOLS', 11);
DEFINE('_MSG_IDX_CRETAB', 9);
DEFINE('_MSG_IDX_FLAG', 8);
DEFINE('_MSG_IDX_INSCOLS', 7);
DEFINE('_MSG_IDX_INSERT', 1);
DEFINE('_MSG_IDX_FILTER', 2);
DEFINE('_MSG_IDX_ORA_TABNAME', 2);
DEFINE('_MSG_IDX_COLUMNS', 3);
DEFINE('_MSG_IDX_TABKEY', 0);

DEFINE('_CFG_LOG_SOURCE', 0);
DEFINE('_CFG_DB_SOURCE', 1);

DEFINE('_DO_NOT_INITIALIZE', 0);
DEFINE('_DO_INITIALIZATION', 1);
DEFINE('_DO_PARTIAL_INITIALIZATION', 2);
DEFINE('_DO_ERASE',4);

DEFINE('_NOT_RELEASED_ORDER',7);
DEFINE('_CANCEL_ORDER',8);
DEFINE('_SOFT_DELETE_ORDER',9);

DEFINE('_MARKET_RUNNING', 'r');
DEFINE('_MARKET_STOPPED', 's');
DEFINE('_MARKET_PAUSED', 'p');

DEFINE('_FETCH_RET_ERROR', -1);
DEFINE('_FETCH_RET_EVENT', 8);
DEFINE('_FETCH_RET_NO_EVENT', 0);
DEFINE('_TRADES_RET_ERROR', 2);
DEFINE('_FETCH_RET_TRADES', 1);
DEFINE('_FETCH_RET_NO_TRADES', 0);
DEFINE('_OBOOK_RET_NO_ORDER', 1);
DEFINE('_OBOOK_RET_ORDER', 2);
DEFINE('_OBOOK_RET_UPDATE_ORDER',4);
DEFINE('_OBOOK_RET_ERROR',-1);
DEFINE('_FETCH_RET_ORDERS', 4);

DEFINE('_PHASE_STAT', 0);
DEFINE('_PHASE_CNTR', 1);
DEFINE('_PHASE_SUBT', 2);  		// subtotal
DEFINE('_PHASE_OPEN_COMMENT',3);  	// During Open Instructions
DEFINE('_PHASE_RUN_COMMENT',4);  	// During Run  Instructions
DEFINE('_PHASE_DONE_COMMENT',5);  	// After  Done Instructions
DEFINE('_PHASE_TIME',6);
DEFINE('_PHASE_RECOUNT',7);
DEFINE('_PHASE_EMPTY',' ');
DEFINE('_PHASE_NEW',0);
DEFINE('_PHASE_OPEN',1);
DEFINE('_PHASE_DONE',9);
DEFINE('_PHASE_TIME_DONE',10);

DEFINE('_PHASE_CONTINUE',89);
DEFINE('_MARKET_CLOSED','MARKET_CLOSED');

DEFINE('_PHASE_PRECALL','P');
DEFINE('_PHASE_CONTINUOUS','T');
DEFINE('_PHASE_CLOSE','C');
DEFINE('_PHASE_OPENING','O');
DEFINE('_PHASE_CLOSING','A');
DEFINE('_PHASE_END','E');
DEFINE('_PHASE_STOP','S');

DEFINE('_DO_OPEN_PRICE','OPENPRICE');
DEFINE('_PRECALL_OPEN','PRECALLOPEN');
DEFINE('_CONTINUOUS_OPEN','CONTINUOUSOPEN');
DEFINE('_CONTINUOUS_CLOSE','CONTINUOUSCLOSE');
DEFINE('_ATC_OPEN','ATC_START');
DEFINE('_ATC_CLOSE','ATC_STOP');
DEFINE('_SEC_STOP','Security STOP');
DEFINE('_CLOSING_PHASE','Closing Phase');
DEFINE('_OPENING_PHASE','Opening Phase');
DEFINE('_DO_NOTHING','Transition');
DEFINE('_DO_MATCHING','MATCHING');
DEFINE('_DO_AUCTION','Auction Trades');
DEFINE('_MAX_READ',1);

DEFINE('_DO_MATCHING_PARALLEL',"P");
DEFINE('_DO_MATCHING_SETREADY',"S");
DEFINE('_DO_MATCHING_PROGRAM',"M");

DEFINE('_YTA',0);
DEFINE('_OE_B',1);
DEFINE('_OE_S',2);
DEFINE('_SOE_B',3);
DEFINE('_SOE_S',4);
DEFINE('_YO',5);

$board=array(
'M' => 'Main board', 
'S' => 'Special conditions board',
'B' => 'Report Only board',
'O' => 'Odd-lot board',
'F' => 'Forced Sale board',
);

$price_type=array(
'L' => 'Limit price',
'M' => 'MKT',
'O' => 'ATO',
'C' => 'ATC',
);

$ord_source=array(
'C' => 'CTCI',
'M' => 'ETW station',
'R' => 'EMRW station',
);

$order_status=array(
'N'	=>'Not Released',
'I'	 => 'Inactive',
'A'	 => 'Pending Approval',
'D'	 => 'Disapproval',
'O'	 => 'Open',
'M'	 => 'Match',
'X'	 => 'Cancel',
'RA' => 'Method 6-1 Report-only order',
'RD' => 'Spot-1 Report-only order',
'RU' => 'Spot-2 Report-only order',
'RR' => 'Settlement-Incomplete Buy Report-only order',
'RS' => 'Settlement-Incomplete Sell Report-only order',
'RO' => 'Other Report-only order',
'EP' => 'expired status',
);

$order_lifetime=array(
'D'	 => 'Day order',
'C'	 => 'Good until Canceled',
'E'	 => 'Good until Expiration date',
);

$sec_status=array(
'A'	 => 'Active',
'N'	 => 'Not active', 
'S'	 => 'suspended',
'H'	 => 'Halted',
'R'	 => 'resumed ',
);

$ord_special=array(
'N' => 'No Condition', 
'I' => 'Immediate Or Cancel',
'F' => 'Fill or Kill',
'S' => 'Stop on Security',
'D' => 'Stop on Index',
'A' => 'All Or None',
'M' => 'Minimum Fill',
'O' => 'Multiple of',
);

$trade_type=array(
'MB' => 'Main Board Trade', 
'MO' => 'Main Board Opening Trade',
'MC' => 'Main Board Closing Trade',
'ST' => 'Special Board Trade',
'OL' => 'Odd-lot Board Trade',
'FS' => 'Forced Sale Board Trade',   
'RO' => 'Agreed Price'
);

$mc_flag=array(
'C' => 'Client Account',
'M' => 'Short Sell Order',
'G' => 'Group of investors Account',
'Q' => 'Quote',
);

$phases=array(
'P' => 'Auction',
'O' => 'Opening',
'T' => 'Continuous',
'S' => 'Stop',
'E' => 'End',
'A' => 'At closing',
'C' => 'Close',
); 

$side=array(
'B' => 'Buy',
'S' => 'Sell',
);

$type=array(
'IT' => 'ORDER',
'IS' => 'TRADE',
'2' => 'TRADE',
); 

$trade_status=array(
'  ' =>	'Normal Completed Trade',
'DC' =>	'Depends on Contra Member Approval',
'DS' =>	'Depends on Stock Exchange Approval', 
'CD' =>	'Contra Member Disapproval',	
'SD' =>	'Stock Exchange Disapproval',	
'XC' =>	'Cancelled trade pending Approval from Contra Member',
'XS' =>	'Cancelled trade pending Approval from Stock Exchange',
'X' =>	'Cancelled trade',
);

$desc_r=array("Original price type"=>$price_type,
			 "Order status"=>$order_status,
			 "Order lifetime"=>$order_lifetime,
			 "Security status"=>$sec_status,
			 "Special conditions"=>$ord_special,
			 "Order source"=>$ord_source,
			 "M/C flag"=>$mc_flag,
			 "Buy M/C flag"=>$mc_flag,
			 "Sell M/C flag"=>$mc_flag,
			 "Trade type"=>$trade_type,
			 "Trade status"=>$trade_status,
			 "Phase ID"=>$phases,
			 "Buy/Sell"=>$phases,  // to catch phase id of trades on the same column SIDE
			 "Side"=>$side,
			 "SIDE"=>$side,  // to catch with ID also and not with tile
			 "Type"=>$type,
			 "Board ID"=>$board,
			 );

	

/**
* Supports traditional files i/o
* @package TradeReplay 
* @version 2.0.1
*/

class file_io 
{
public $fname;
public $fpCurrent;
public $fp;
public $files;
public $filesCounter;

	
	/**
	 * Class Constuctor 
	 * just assign the $ComH varaible and Init it
	 *
	 * @param Process $ComH
	 */
	function __construct($File_Path, $source,$fdate)
	{
	    if ($this->_is_valid_date(&$fdate))
		{
		$file = $File_Path . $source .$fdate . ".log";
		$this->fp=0;
		$this->fname=$file;
		
		$this->_Init();
		}
		else
		  return false;
	}
	
static	function checkFile($File_Path, $source,$fdate)
	{
	    if (file_io::_is_valid_date(&$fdate))
		{
		$file = $File_Path . $source .$fdate . ".log";
	//echo $file;		
		// chack only existance
		if (file_exists($file))
				return true;
		else
			  return false;
	    }
		else
			  return false;
	}
	/**
	 * Class Constuctor 
	 * just assign the $ComH varaible and Init it
	 *
	 * @param Process $ComH
	 */
	function _Init()
	{
	//echo "fname=".$this->fname;
		if (file_exists($this->fname)){
		
			$this->fp=fopen($this->fname,"ra");	
			}
			else
			{
			echo "Error with ". $this->fname;
				$this->files--;
			}
		}
	
	/**
	 * function _is_valid_date 
	 * just assign the $ComH varaible and Init it
	 *
	 * @param Process $ComH
	 */
	function _is_valid_date(&$fdate)
	{
	    if ($fdate=='')
		    return false;
			
		if (strstr($fdate,'-')) {
		    list($y,$m,$d) = explode('-',$fdate);
			if (!$y || !$m ||!$d )
			    return false;
			else
			   $fdate=$y.$m.$d;
			}  
		return true;
	}
	
	function getCurrent()
	{
	return ftell($this->fp);
	}
		
	function getNext()
	{
	 if(!feof($this->fp)) {
	    return fgets($this->fp);
		}
	  return false;	
	}
	
	function getPrevious()
	{
	}
	
	function setPosition($start, $offset_bytes)
	{
	if (fseek($this->fp,(int)$offset_bytes,$start)==0){ // success 
		$this->fpCurrent=ftell($this->fp);
		return true;
		}
		else
		 return false;
	}
	
	function reStart()
	{
		fseek($this->fp,0);
	}
	
	function _Close()
	{
	if ($this->fp)
	 	fclose($this->fp);
	}
	
	function __destruct()
	{
		$this->_Close();
	}
}

/**
* Maintain OASIS logs as input to tradereplay application
* @package TradeReplay 
* @version 2.0.1
*/
class logs_source
{
 public $context;
 public $rec;  		// current input buffer
 public $ficsnd;
 public $MSG_r;
 
 function __construct($parent)
 {
	$this->context = $parent;
	}
	
 function connect_to_source()
 {
  $parent=$this->context;
  
  $this->ficsnd= new file_io($parent->cfg->filepath, $parent->source, $parent->fdate);
  if (!$this->ficsnd){
			$parent->errorlog->addlog( 'file_io: - error = '.$parent->cfg->filepath. $parent->source. $parent->fdate);
		    return false;
			}
		//$this->supsnd= new file_io("supsnd".$fdate.".log");
  if ($this->ficsnd->files < 0) {
			$parent->errorlog->addlog( "File i/o error of ".$fname);
		    return false;
			}
  
  if ($parent->source==_FIC)
		$this->init_ficTables();
			
  if ($parent->source==_SUP) {
		$this->init_supTables();
		}
  
  $parent->MSG_r = $this->MSG_r;
  		
  return true;
  }
 
 function init_ficTables()
	{
	 $this->MSG_r=array ("IA"=>array("MARKET_ID","callbackget_messages_intoDB","","","","","","","",1),
						 "IB"=>array("MARKET_ID,BOARD_ID,ISIN_CODE","callbackget_messages_intoDB","","","","","BOARD_ID","Y","",1),
						 "IF"=>array("OASIS_TIME,MARKET_ID,BOARD_ID,STATUS","callbackget_messages_intoDB","","","","","","","",1),
						 "IC"=>array("INDEX_ID","callbackget_messages_intoDB","","","","","","","",1),
						 "IE"=>array("INDEX_ID","","","","","","","","",0),
						 "ID"=>array("INDEX_ID","","","","","","","","",0),
						 "IG"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN","","","","","","","","",0),
						 "II"=>array("INDEX_ID","","","","","","","","",0),
						 "IJ"=>array("INDEX_ID","","","","","","","","",0),				 
						 "IL"=>array("INDEX_ID","","","","","","","","",0),
						 "IM"=>array("INDEX_ID","","","","","","","","",0),
						 "IN"=>array("INDEX_ID","","","","","","","","",0),
						 "IH"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN,PHASE_ID","callbackget_messages_intoDB","callbackfilter_ISIT","","","","","","",1),
						);
  }
	
 function init_supTables()
 {
	 $this->MSG_r=array ("IS"=>array("TRADE_NUMBER","callbackget_messageIS_intoDB","callbackfilter_ISIT",$this->getColumns('IS'),"OASIS_TIME,BUY_ORDER_NUMBER,SELL_ORDER_NUMBER","","","","",1),
 						 "IT"=>array("ORDER_ID","callbackget_messages_intoDB","callbackfilter_ISIT",$this->getColumns('IT'),"ORDER_NUMBER,SECURITY_ISIN,OASIS_TIME,SIDE,ORDER_MOD,ORDER_STATUS,PRICE","LIMIT","","","",1),
						 "IK"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN","callbackget_messages_intoDB","callbackfilter_ISIT",$this->getColumns('IK'),"OASIS_TIME","","","","",1),
						 "OB"=>array("SESSION,ORDER_NUMBER","","","","","","","","",0),
						 "OP"=>array("ID","","","","","","","","",1),
						 "MF"=>array("ID","","","","OASIS_TIME,ISIN_CODE","","","","",1),
						 "ST"=>array("ID","","","","ID,TRADER","","","","",1),
						 "II"=>array("OASIS_TIME,MARKET_ID,ISIN_CODE","callbackget_messages_intoDB","callbackfilter_ISIT","","","","","","",1),
							);	
  }
  
 function getColumns($msgId)
	{
	 $parent=$this->context;
	
	 $table=$parent->Msgs_collection->getMsg($msgId);
		
		if( ($table instanceof Collection) == false)
		 	{
			 $parent->errorlog->addlog( 'getColumns: table is null - '.$msgId );
			 return '';
			}	
		
		$ar=array();
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				continue;
				}
														//  selected to appeared, header==1
			if ($val->get(_IDX_HEADER)) {
				if ($columns!='')
	    			$columns.=',';
				$columns .= ($msgId=='IS' ?'s.' :($msgId=='IT' ?'t.' :'')) . $fldName;
			
		    	if ($titles!='')
			    	$titles.=',';
				$titles .= $val->get(_IDX_TITLE);
				}
				//else
				 //$this->errorlog->addlog( 'getColumns: '.$msgId." - ".$fldName."=".$val->get(_IDX_HEADER) );
			}
		$ar['columns']=$columns;
		$ar['titles']=$titles;
		
	return $ar;	
	}
	
 function prepareMarket()
	{
	 $parent=$this->context;
	 			
	 if ($this->ficsnd->fp==''){
	 	$parent->errorlog->addlog( "prepareMarket: ficsnd->fp is null");
		return false;
	 	}
	 
	 $this->ficsnd->reStart();
	 
	 while(!feof($this->ficsnd->fp)) {
		
	    $this->rec = fgets($this->ficsnd->fp);
		$msg=$this->check_of_interest();
		$filter_passed=25;
// message of our interest!
	 	if ( $msg )  {
	 // ...then filter it more...!
			$func = $this->MSG_r[$msg][_MSG_IDX_FILTER];
			if ($func != "") {
				if (method_exists('logs_source', $func))
		 	    	$filter_passed = $this->$func($parent,$msg);
				//$func="messages_io::".$this->MSG_r[(string)$msg][_MSG_IDX_FILTER];	
				//$filter_passed = call_user_func($func,$this,$msg);
				}
		 	$func=$this->MSG_r[$msg][_MSG_IDX_INSERT];
			if ($filter_passed==25 && ($func!="")) {
				 $this->$func($parent,$msg);
		    	 //$func="messages_io::".$this->MSG_r[(string)$msg][_MSG_IDX_INSERT];				
			 	//call_user_func($func,$this,$msg);
			 	}
			}
		}
	// Rewind file	
	$this->ficsnd->reStart();
	if ($this->MSG_r['IT'][_MSG_IDX_COLUMNS] != '') {

		if (is_a($parent->markets[$parent->cfg->market],'mkt_info'))
		     print "market OK!".$parent->cfg->market."\n";
	    $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  		$isin=$secInfo->getIsinSec();
		$info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
		$info->savePhases();
		$info->reset_currphase();
		}
	$parent->errorlog->addlog( 'prepareMarket: - Trades witten='.$parent->Written_trades);
	unset($secInfo->marketGraphObj);	
	}	

	
	function check_of_user_interest()
	{
		$parent=$this->context;
		 
		$msg = substr($this->rec, _HEADER, _MSGLEN);
		$msg_table=$parent->Msgs_collection->getMsg($msg);
		
		if (array_key_exists((string)$msg, $this->MSG_r) && $msg_table->get(3)==1)
			 return($msg_table->get(0));
		  else
			 return '-1';
	}
	
	function check_of_interest()
	{
		$msg = substr($this->rec, _HEADER, _MSGLEN);

		if(array_key_exists((string)$msg, $this->MSG_r))
			 return($msg);
		  else
			 return '';
	 }
	

 function callbackfilter_ISIT($msgobj,$msgId)
 {
	 $table=$msgobj->Msgs_collection->getMsg($msgId);
	 if( empty($table) || $table==null || ($table instanceof Collection) == false) {
	 	 //$msgobj->errorlog->addlog( 'filterISITtable is null -'. $msgId);
	     return ;
		 }
		
	 $ret=false;
	 foreach($table as  $fldName=>$val) {
			if (is_numeric($fldName)) 
				continue;
				
			if (array_key_exists($fldName,$msgobj->filters)) {
				$valid_val = substr($this->rec, _HEADER+$val->get(0), $val->get(1));	

				if (is_array($msgobj->filters[$fldName])) 
					{
					if (!in_array($valid_val, $msgobj->filters[$fldName])) {				
						return 24;
						}
						else
						   $ret=25;
					}
				   else {
				   		if ($this->MSG_r[$msgId][6] != '' && $this->MSG_r[$msgId][6]==$fldName){
						//if ($msgId=='IB')
						
							if (substr($valid_val,0,1)!=$this->MSG_r[$msgId][7] ){
							    return 23;
								}
								else
							  $ret=25;
							  }
							 else 
								if ($valid_val!=$msgobj->filters[$fldName] ){

					 		return 22; 	
							}
							else{
							  $ret=25;
							  }
						}
				}				
			}
	 return $ret;	
	}
			
/*-------------------------------------------------------
*	getIABCF:
*--------------------------------------------------------*/	
	function callbackget_messages_intoDB($msgobj,$msgId)
	{
	    $q = "";
		$columns="";
		$values="";
		$sec="";
		$order='';
		$ostat='';
		$oprice=0;
		$oside='';
		$otime='';
		$msgobj->dp->debug=5;
		
		$table=$msgobj->Msgs_collection->getMsg($msgId);
		
		if( ($table instanceof Collection) == false)
		 	{
			 $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: table is null - '.$msgId );
			 return;
			}
								
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				$tabname=$table->get(0);
				$tabsize=$table->get(2);
				continue;
				}
			if ($q == "")
		  		$q = "INSERT INTO imk_$tabname ";
		 	  else {
		   		 $columns.= ",";
				 $values.= ",";
				 }
			$columns.= $fldName;
											//  selected to appeared, header==1
			if ($fldName == 'OASIS_POSITION') {
				$pos=$this->ficsnd->getCurrent();
				$ps=(string)($pos - ($tabsize+5));
				$values .= "\"" . $ps . "\"";
				}
				else if ($fldName == 'ORDER_MOD') {
				$values .= '0';
				}
			else {	
					$rec_field = substr($this->rec, _HEADER+$val->get(0), $val->get(1));

			    	$rec_field=mb_convert_encoding($rec_field,"UTF-8","ISO-8859-7");
					
					if ($msgId==='IT' ) {
					   if ($fldName==='PHASE_ID')
					    	$phase=$rec_field;
						elseif ($fldName==='ORDER_NUMBER')
					    	$order=$rec_field;	
						elseif ($fldName==='OASIS_TIME')
					    	$otime=$rec_field;	
						elseif ($fldName==='ORDER_STATUS')
					    	$ostat=$rec_field;
						elseif ($fldName==='SIDE')
					    	$oside=$rec_field;	
						elseif ($fldName=='SECURITY_ISIN')
							$sec=$rec_field;
						elseif ($fldName=='VOLUME')
							$ovol=(int)ltrim($rec_field,'0');	
						elseif ($fldName=='PRICE')
							$oprice=(int)ltrim($rec_field,'0');		
					    elseif ($fldName=='SECURITY_STATUS')
						{ 
						  //if ($rec_field=='R') $phase='P';
						 }
						}
							
					$values .= "\"" . str_replace('"', '', $rec_field) . "\"";	
					}
		 	} // Foreach
		
		if ($msgId=='IT') {	
			      $u_err = $this->changeExistedOrder($msgobj,$order,$otime,$tabname,&$ostat,$oprice,$oside);
				  if ($u_err>0)
				 	  $values=substr_replace($values, '1', 11,1);
				 				  
    			  $info = $msgobj->markets[$msgobj->cfg->market]->getSecPhase($sec); 
				  $info->set_phase((($phase==' ') ?'P' :$phase), $msgId, $otime,substr($ostat,0,1));
				  $msgobj->markets[$msgobj->cfg->market]->getSec($sec)->marketGraphObj->addDataFile($otime,$ovol,$oprice);
				  }
			
		$q.="( $columns ) VALUES ( $values )";	
		$msgobj->dp->setQuery($q);
		$msgobj->dp->query();

	
	  	$err=$msgobj->dp->getAffectedRows();	
		if ($err==-1)
		   {
		    $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: - '.$msgId. ' Q='.$q.' - err ='.$msgobj->dp->getErrorNum());
			}			
	}

 function changeExistedOrder($msgobj,$order,$otime,$tabname,&$ostat,$oprice,$oside)
 {
	 $err=0;
	 $stat=substr($ostat,0,1);
	 if ( $stat=='X') 
	      return;
	 $oid=-1;
	 $oprev_time='';
	 $dup=0;
	 if (array_key_exists($order,$msgobj->dup_orders)) {
			$msgobj->dup_orders[$order][0]+=1;
			$ostat='X';
			$first=false;
			$dup=1;
			$oid=$msgobj->dup_orders[$order][0];
			$oprev_time=$msgobj->dup_orders[$order][1];
			}
		  else	{
		    	$msgobj->dup_orders[$order]=array(0,$otime);
				$first=true;
				$oprev_time=$otime;
				}	
	 return $err;
	}		

/*-------------------------------------------------------
*	get IS Trades:
*--------------------------------------------------------*/	
	function callbackget_messageIS_intoDB($msgobj,$msgId)
	{
	    $q = "";
		$columns="";
		$values="";
		$sec="";
		$order='';
		$ostat='';
		$oprice=0;
		$oside='';
		$otime='';
		$msgobj->dp->debug=5;
		
		$table=$msgobj->Msgs_collection->getMsg($msgId);
		
		
		if( ($table instanceof Collection) == false)
		 	{
			 $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: table is null - '.$msgId );
			 return;
			}
							
		
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				$tabname=$table->get(0);
				$tabsize=$table->get(2);
				continue;
				}
			if ($q == "")
		  		$q = "INSERT INTO imk_$tabname ";
		 	  else {
		   		 $columns.= ",";
				 $values.= ",";
				 }
			$columns.= $fldName;
											//  selected to appeared, header==1
						//
			//
			if ($fldName == 'OASIS_POSITION') {
				$pos=$this->ficsnd->getCurrent();
				$ps=(string)($pos - ($tabsize+5));
				$values .= "\"" . $ps . "\"";
				}
				
			else {	
					$rec_field = substr($this->rec, _HEADER+$val->get(0), $val->get(1));
					//$pos = strpos($fldName,"HELLENIC");
					//if ($pos!==false)
			    	$rec_field=mb_convert_encoding($rec_field,"UTF-8","ISO-8859-7");
					
					if ($fldName==='TRADE_NUMBER')
					    	$trade=$rec_field;
					elseif ($fldName==='BUY_ORDER_NUMBER')
					    	$bid=$rec_field;
					elseif ($fldName==='SELL_ORDER_NUMBER')
					    	$ask=$rec_field;	
					elseif ($fldName==='OASIS_TIME') {
					    	$tr_time=$rec_field;
							if ($tr_time==$msgobj->prev_trade_time)     
		    					$rec_field .= ++$msgobj->trade_mils;
					 			else {	
						   				$msgobj->prev_trade_time = $tr_time;
			   			   				$msgobj->trade_mils=0;
			   			   				$rec_field .= '0';
			  			   				}
							}			
					elseif ($fldName==='PRICE')
					    	$oprice=$rec_field;	
					
							
					$values .= "\"" . str_replace('"', '', $rec_field) . "\"";	
					}
		 	} // Foreach
		
			
		$err=$this->markTradeOrders($tabname,$tr_time,$oprice,$trade,$bid,$ask);
					
		$q.="( $columns ) VALUES ( $values )";	
	//  $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: '. $q);			
 		//$info->set_hdr(true);
		$msgobj->dp->setQuery($q);
		$msgobj->dp->query();
	//  $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: '.$msgId. ' OrdId='.$order.' - msg ='. $msgobj->dp->getAffectedRows());	
	
	  	$err=$msgobj->dp->getAffectedRows();	
		if ($err==-1)
		   {
		    $msgobj->errorlog->addlog( 'callbackget_messageIS_intoDB: - '.$msgId. ' Q='.$q.' - err ='.$msgobj->dp->getErrorNum());
			}
		else
		  $msgobj->Written_trades++;
	}

 
 function markTradeOrders($tabname,$tr_time,$oprice,$trade,$bid,$ask)
 {
	$parent=$this->context;
	
	 if (array_key_exists($bid,$parent->dup_orders)) 
			$bid_time=$parent->dup_orders[$bid][1];
	 if (array_key_exists($ask,$parent->dup_orders)) 
			$ask_time=$parent->dup_orders[$ask][1];
	
	if ($bid_time < $ask_time) {
	   $otime =$ask_time;
	   $ord=$ask;
	   }
	   else {
	    $otime =$bid_time;
		$ord=$bid;
		}
				
	 $q="SELECT DISTINCT OASIS_TIME, SIDE, ORDER_ID, PRICE, VOLUME"
			. " FROM imk_IT"
			. " WHERE OASIS_TIME <= \'$tr_time\'"
			. " AND ORDER_NUMBER = \'$ord\'"
			. " AND ORDER_STATUS=\'O\'"
			. " AND (CASE WHEN side = \'S\'
					THEN PRICE <=$oprice
				WHEN side = \'B\'
					THEN PRICE >=$oprice
				END OR PRICE=0)" 
			. " HAVING max( OASIS_TIME )"; 
		
	//	$this->errorlog->addlog( 'tr_orders sql: '.$q." ===".$bid_time ."==". $ask_time);
		$parent->dp->setQuery(stripslashes($q)); 
		
		$parent->dp->loadObject($tr_order);
		if ($tr_order==false) {
			$parent->errorlog->addlog( 'tr_order empty '."==".$q);
			return 1;
			}
		 
			$q="UPDATE imk_IT SET ORDER_MOD=ORDER_MOD+".($tr_order->SIDE=='B' ?'100' :'1') . " WHERE ORDER_ID=\'$tr_order->ORDER_ID\'";
		
		$parent->dp->setQuery(stripslashes($q));
	 	$parent->dp->query();
	 	$err=$parent->dp->getAffectedRows();
	}
	
 } // CLASS
 

/** 
* Session info  CLASS
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/
class session_info
{
 public $sesid;
 public $ses_db;
 
 function __construct($session_id)
 {
  $this->sesid = $session_id;
  }
 }
 
/*============================================================
	MAIN CLASS
=============================================================*/	 		
/**
* Main Class. Establish the connections to/from databases: local/Oracle
* @package TradeReplay
* @author JL
* @version 2.0.1
*/

class messages_io
{
public $errorMessage;

public $sourceObj;
public $session;
public $ficsnd;
public $supsnd;
public $currPosition;

//public $projected;		//   array: last auction projected prices
//public $projected_orders; // array: projected orders
//public $md;				//   array: Last MD query
//public $lastTrades;		//   array: Last trade query
//public $lastTrade;		//   last trade, updated after the current trade has transmitted
//public $lastOrders;		//	 array: Last Fetched orders
//public $tr_orders;		//   array: matched orders of the last trade query
public $markets;		//   array: Hold markets,Secs info
//public $survTraders;	//   array: Hold traders on survailance
//public $TradersGraph; 	//	 array: Last query of traders vol totals
//public $statistics;		//   array: Hold statisticse
//public $ob_entries;		//   array: OB entries for matching


// During trade Creation
public $prev_trade_time;
public $trade_mils;
// Market Configuration
public $creOBStats;		//	 flag: 0/1, 1:first time during init, create OBS
public $cfg;
public $filter_sec;
public $filters;
private $Logfilters;
//----------------------

public  $projectedScript;
public  $eventsScript;
public  $ordersScript;
public  $tradesScript;
public  $tradersScript;
public  $hiloScript;
public  $OBScriptB;
public  $OBScriptS;
public  $mdScriptB;
public  $mdScriptS;
public  $accScript;
public  $accTradesScript;
public  $surv_str;

//public  $priceInRange;
public  $marketGraphObj;
//public  $nextOrderKey;
private $data_r;	 
private $msgKey;
private $msgOrder;
private $msgLimit;
public  $fdate;

public $source;
public $currLoc;
public $startTime;
public $stopTime;

//public $highest;
//public $lowest;
//public $tradePrice;
//public $auctionPrice;
//public $openAuctionPrice;  // Updated from Projected
//public $auctionPriceIndex;
//public $startPrice;
//public $bid_1;		// first level of MKT Depth
//public $ask_1;
//public $MDchange;
public $aprices;  // open price array
//public $totTradeVol; // calc on _Init
	 
public $files;
public $rec;
public $dp;
public $CRMdp;
public $init_flag;
public $marketTradeUnmatched;

public $msgHeader;
public $dup_orders;
public $Written_trades;
//public $Read_trades;
//public $trade_numbers;
public $MSG_r;
public $SUP_r;
public $Msgs_collection;
public $errorlog;
public $totalTrades;
public $orders;
	
public $total;
//public $posStart;
//public $count;
public $mode;
public $CustomPeriodstat;
public $client_socket;
public $host;
public $port; 
public $timeout;


	/** Start Object Creation
	 * messages_io main Class Constuctor 
	 * just assign the $ComH varaible and Init it
	 *
	 * @param Process $ComH
	 */
	function __construct($DBname,$source)
	{
	   	//echo "File_Path=".$this->File_Path."</br>";
		 $total=0;
		 $posStart=0;
		 $count=0;
		 $mode='';
		 $this->host="127.0.0.1" ;
		 $this->port=12346;  // auxiliary service aux_service.php
		 $this->timeout=30;
		
		 $result=shell_exec('ps ax |grep aux_service|grep -v "grep"');
		 $aux_pid = preg_split('/\s+/', $result);
		
			 if($aux_pid[0])
			 	echo "Aux services => ".$aux_pid[0]." \n";
				else
				{
		     echo "No aux services!!! \n";
			// proc_close (proc_open ("php /u01/imarket/marketplay/aux_service.php --foo=1 &", Array (), $foo));
			 }
		
		 
		 $this->client_socket = new ClientSocket($this->host, $this->port);
		 if ($this->client_socket) 
			 $this->client_socket->msConnectSocket();
		 //test...
		// $data = implode(':', array('IT',"test"));
		// $this->callAux($data);
		 
		$this->source=$source;
//	$this->errorlog->addlog( '__construct: - q = '.$fname);
		$this->dp = new dp_database( 'localhost', 'root', '', $DBname, '', 0 );
		if ($this->dp) 
		    $this->Msgs_collection = new oasis_messages($DBname,0); //,(($performInit==true) ?0 :1));
			else
			{
			  echo "error in DB=$DBname";
			  return false;
			  } 
		
		$this->CRMdp = new dp_database( 'localhost', 'root', '', 'CRM1', '', 0 );
		if (!$this->CRMdp) 
			{
			  echo "error in  DB=CRM1";
			  return false;
			  } 
	}

   function is_process_running($PID)
   {
       exec("ps $PID", $ProcessState);
       return(count($ProcessState) >= 2);
   }

	function callAux($data)
	{
	 if ($this->client_socket->connected) {
	
		$nw=socket_write($this->client_socket->_SOCK, "getConfig".",".$data);
		$nr=socket_recv($this->client_socket->_SOCK,$ans,20000,0);
	
		print("Aux ans=".$ans."\n");
		}
		else
		  print("Not Connected to Client...\n");
		//socket_close($socket->_SOCK);
	 }
		
		
  function setSession($ses)
  {
   $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
   $this->isin = $secInfo->getIsinSec();
   $q="INSERT into imk_SES (id,sesid,isin,order_rows,trade_rows,show_prj_graph) VALUES(0,".$ses.",\"".$secInfo->getIsinSec()."\"" .",5,5,1)";
   $this->dp->setQuery(stripslashes($q));
   $this->dp->query();
   $err=$this->dp->getAffectedRows();	
   if ($err < 1)
	    print( 'setSession: session='.$ses."==".$q."\n");
	
   $this->getSession($ses);
   }
   
  function getSession($ses)
  {
   $q="SELECT * FROM imk_SES WHERE id=".$ses;
   $this->dp->setQuery(stripslashes($q));
   $this->dp->loadObject($this->session->ses_db);
    print( 'New Session: session='.$this->session->ses_db->id."\n");
   return $this->session->sesid;
   }
/*-------------------------------------------------------
*	getIABCF:
*--------------------------------------------------------*/		
	static	function getNextIT($msgobj,$msgId)
	{
	$cols=array('PHASE_ID', 'SIDE', 'SECURITY_ISIN', 'ORDER_NUMBER', 'ORDER_ENTRY_TIME', 'VOLUME', 'DISCLOSED_VOLUME', 'MATCHED_VOLUME', 'PRICE', 'TRADER_ID', 'MEMBER_ID');
	 $data_cols="";
	 
	 $table=$msgobj->Msgs_collection->getMsg($msgId);
	 foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) 
				continue;
				
			if ($fldName == 'ORDER_NUMBER') {
				$id = substr($msgobj->rec, _HEADER+$val->get(0), $val->get(1));
				continue;
			}
			
			if (in_array($fldName, $cols)) {
			//$msgobj->errorlog->addlog( 'getISColumns: - '. $fldName);	
				if ($data_cols!="")
					$data_cols .= ",";
			   $data_cols .= substr($msgobj->rec, _HEADER+$val->get(0), $val->get(1));
			   }
			}
			//$msgobj->errorlog->addlog( 'getISColumns: - '. $data_cols);
	 return array($id,$data_cols);
	}
	
	
	
	
	/*-------------------------------------------------------------
	 * Called from Constuctor 
	 *
	 * @param DBname
	 *-------------------------------------------------------------*/
	function _Init($fdate,$ses)
	{
	 
	 $this->init_flag = 0;
	 
	 $q = "SELECT * FROM imk_imarket_cfg WHERE id=$ses"; //fdate=\'".$fdate."\'";
//$this->errorlog->addlog( 'createFilter: - q = '.$q);
	 $this->dp->setQuery(stripslashes($q));
	 
	 if ($this->dp->getErrorNum()){
	    print( '_Init  sql: '.$q."==".$this->dp->stderr()."\n");
		return false;
		}
	$this->dp->loadObject($this->cfg);
	if ($this->cfg==''){
		print( '_Init  sql: '.$q."==".$this->dp->stderr()."\n");
		return false;
		}
	 
	file_io::_is_valid_date(&$fdate);
	$this->fdate=$fdate;
	$this->errorlog = new msg_log;
	
	$this->empty_table("imk_imarket_log");
//	$this->empty_table("myproc");
//	$this->empty_table("imk_MD");
	$this->session = new session_info($ses);
	
	$fname=$this->cfg->filepath . $this->source .$fdate.".log";
	$this->init_flag = $this->cfg->init;

	if ($this->cfg->state == _CFG_DB_SOURCE)
		$this->sourceObj = new db_source($this);
		else
			$this->sourceObj = new logs_source($this);
	
	$SOURCE = $this->sourceObj;		
		
	if ( $SOURCE->connect_to_source() == false)
			 return false;
/**
* Init MSG_r array
*/	
	$SOURCE->_init();
/**
*	Create Markets array
*/	
	$this->markets = array();
	$this->markets[$this->cfg->market] = new mkt_info($this);
		$this->errorlog->addlog( ' _Init: - state = '.$this->cfg->state."==".$this->init_flag);
	
	$this->reset_some_things($performInit);
	
	$this->dup_orders=array();
		
//	    check loacal db; 		 

/*	$q="SELECT sum(VOLUME) total, count(*) trades FROM imk_IS";
	 $this->dp->setQuery(stripslashes($q)); 
	 $this->dp->loadObject($this->totTradeVol);
	 if ($this->totTradeVol)
		 $this->totalTrades=$this->totTradeVol->trades;

	$q = "SELECT count(*) FROM imk_IT";
//$this->errorlog->addlog( 'createFilter: - q = '.$q);
	$this->dp->setQuery($q);
	$rows=$this->dp->loadResult();
	if (count($rows) > 0) {
	   	$this->recreateInfo();  // just initialize phases
		}

	$q="SELECT trader FROM imk_ST";
   	 $this->dp->setQuery(stripslashes($q));
 	 $this->survTraders = $this->dp->loadObjectList();
	 if ($this->survTraders)
	 {
	 	$this->surv_str="";
	 	$i=0;
	 	foreach ($this->survTraders as $row)
 		{
 			if ($this->surv_str!="") 
	  			$this->surv_str .= ",";
	   		$this->surv_str .= '{"T":"'.$row->trader.'"}';
		  }	
		}		  
*/
	if ($this->cfg->init==_DO_INITIALIZATION){
		$this->empty_table("imk_OBS");
		$this->empty_table("imk_SS");
		$this->empty_table("imk_events");
		$this->empty_table("imk_MS");
		$this->empty_table("imk_MF");  // Market Statistics from Oracle
		$this->empty_table("imk_OP"); 
		
		}	 
	
	 return true;		    
	}

//===================================================
//	 Data store Initialization
//===================================================
	function reset_some_things($performInit=true)
	{
	 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
	 
	 $secInfo->trade_numbers= array();
	 $this->surv_traders = array();
//	 $this->empty_table("imk_imarket_log");

	// $this->empty_table("myproc");
	 //$this->empty_table("imk_MD");
	 //$this->empty_session_table("imk_MO");
	 $this->empty_session_table("imk_OB");

	 $secInfo->priceInRange='';
	 $secInfo->highest=0;
	 $secInfo->bid_1=0;
	 $secInfo->ask_1=0;
	 $secInfo->nextOrderKey='';
 	 $secInfo->lowest=9999;
	 unset($secInfo->lastTrades);		//   array: Last trade query
	 unset($secInfo->tr_orders);
	 unset($this->projected);
	 $this->prev_trade_time='000000000';
	 $this->trade_mils=0;
	 $this->CustomPeriodstat=false;
// Count trades
//	 $q = "SELECT COUNT(*) FROM imk_IS";
//	 $this->dp->setQuery(stripslashes($q)); 
//	 $this->totalTrades = $this->dp->loadResult();
	}
	
	function clear_beforeDelMkt()
	{
	 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
	 
	 $secInfo->trade_numbers= array();
	 $this->surv_traders = array();
	 unset($secInfo->lastTrades);		//   array: Last trade query
	 unset($secInfo->tr_orders);
	 unset($this->projected);
	 $this->empty_session_table("imk_OB");
	 }
	 
	function setCustomPeriod($stat=true)
	{
		$this->CustomPeriodstat=$stat;
	}
	
	function getCustomPeriod()
	{
		return $this->CustomPeriodstat;
	}
	
	function cfgInit($val)
	{
	 $q="UPDATE imk_imarket_cfg SET init = $val";
	 $this->dp->setQuery($q);
	 $this->dp->query();
	 $err=$this->dp->getAffectedRows();	
	
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'cfgInit error: '.$this->dp->getErrorNum());
		 return false;
		 }
	 }
	 
/****************************************** END ****************************************************/


//=============================================================================
//		Class property functions
//=============================================================================	
	function getSessionDate()
	{
		return $this->fdate;
	}
	
	function getSessionIsin()
	{
	 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  	 return ($secInfo->getIsinSec());
	 }
	 
//--------------------------------	
	function getFilter($fname)
	{
		return $this->filters[$fname];
	}
//--------------------------------		
	function getDB()
	{
		return $this->dp;
	}
//=============================================================================	
	
	
//---------------------------------------------------------------
// 		init_ficTables: 
//---------------------------------------------------------------
	
/*
	function init_ficORATables()
	{
	 $this->MSG_r=array ("IA"=>array("MARKET_ID","callbackget_markets_intoDB","","","","","","","",1),
						 "IB"=>array("MARKET_ID,BOARD_ID,ISIN_CODE","callbackget_securities_intoDB","mm_securities","","","","BOARD_ID","Y","",1),
						 "IF"=>array("OASIS_TIME,MARKET_ID,BOARD_ID,STATUS","callbackget_marketsphases_intoDB","","","","","","","",1),
						 "IC"=>array("INDEX_ID","callbackget_indexes_intoDB","mm_market_indexes","","","","","","",1),
						 "IE"=>array("INDEX_ID","","","","","","","","",0),
						 "ID"=>array("INDEX_ID","","","","","","","","",0),
						 "IG"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN","callbackget_halts_intoDB","mm_oasis_security_halts","","","","","","",1),
						 "II"=>array("INDEX_ID","","","","","","","","",0),
						 "IJ"=>array("INDEX_ID","","","","","","","","",0),				 
						 "IL"=>array("INDEX_ID","","","","","","","","",0),
						 "IM"=>array("INDEX_ID","","","","","","","","",0),
						 "IN"=>array("INDEX_ID","","","","","","","","",0),
						 "IH"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN,PHASE_ID","callbackget_secphases_intoDB","mm_oasis_security_phases","","","","","","",1),
						 
							);
	}
	function init_supORATables()
	{
	 $this->MSG_r=array ("IS"=>array("TRADE_NUMBER","callbackget_executions_intoDB","mm_oasis_executions",$this->getColumns('IS'),"OASIS_TIME,BUY_ORDER_NUMBER,SELL_ORDER_NUMBER","","","","",1),
 						 "IT"=>array("ORDER_ID","callbackget_orders_intoDB","mm_oasis_orders",$this->getColumns('IT'),"ORDER_NUMBER,SECURITY_ISIN,OASIS_TIME,SIDE,ORDER_MOD,ORDER_STATUS,PRICE","LIMIT","","","",1),
						 "IK"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN","callbackget_messages_intoDB","",$this->getColumns('IK'),"OASIS_TIME","","","","",1),
						 "OB"=>array("SESSION,ORDER_NUMBER","","","","","","","","",0),
						 "OP"=>array("ID","","","","","","","","",1),
						 "MF"=>array("ID","","","","OASIS_TIME,ISIN_CODE","","","","",1),
						 "II"=>array("OASIS_TIME,MARKET_ID,ISIN_CODE","callbackget_messages_intoDB","callbackfilter_ISIT","","","","","","",1),
							);	
	}
*/	
	function getColumns($msgId)
	{
	 $table=$this->Msgs_collection->getMsg($msgId);
		
		if( ($table instanceof Collection) == false)
		 	{
			 $this->errorlog->addlog( 'getColumns: table is null - '.$msgId );
			 return '';
			}	
		
		$ar=array();
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				continue;
				}
														//  selected to appeared, header==1
			if ($val->get(_IDX_HEADER)) {
				if ($columns!='')
	    			$columns.=',';
				$columns .= ($msgId=='IS' ?'s.' :($msgId=='IT' ?'t.' :'')) . $fldName;
			
		    	if ($titles!='')
			    	$titles.=',';
				$titles .= $val->get(_IDX_TITLE);
				}
				//else
				 //$this->errorlog->addlog( 'getColumns: '.$msgId." - ".$fldName."=".$val->get(_IDX_HEADER) );
			}
		$ar['columns']=$columns;
		$ar['titles']=$titles;
		
	return $ar;	
	}

//---------------------------------------------------------------
// 		createFilter: read cfg from db
//---------------------------------------------------------------		
	function createFilter()
	{
	//if ($this->init_flag)
	//    return;

//	$this->filter_sec = explode(",",$this->cfg->securities);
	$sec=$this->filter_sec[0];	
	
	$mkt=$this->markets[$this->cfg->market];
	if (is_a($mkt,'mkt_info'))
	    $secInfo = $mkt->getSec($sec);
 	   else {
		  print "Error mkt_info:".$this->cfg->market."\n";
		  print_r($this->markets);
		  }
	
	foreach($mkt->secs as $secObj)
  	{
     
	 if ($this->cfg->init!=_DO_ERASE)
	 	if ($secObj->sessionSecs==0)
   	 		{
	  		$mkt->qsec_missed .= ($mkt->qsec_missed!="" ?"," :"");
	  		$mkt->qsec_missed .= "'".$secObj->isin."'";
	  		}
	 print("createFilter - ".$mkt->qsec_missed."\n");
/*				
	if (is_a($secInfo,'sec_info'))
	    $secInfo->setActiveSec();
		else
		  print "Error getSec:".$this->cfg->market." - ".$sec;
*/					
	$this->filters=array("MARKET_ID"=>$this->cfg->market,"BOARD_ID"=>$this->cfg->board,"SECURITY_ISIN"=>$secObj->isin,"ISIN_CODE"=>$secObj->isin,"TRADE_DATE"=>$this->fdate,);
	$this->Logfilters=array("OASIS_TIME"=>0,"MARKET_ID"=>0,"BOARD_ID"=>0,"PHASE_ID"=>0,"STATUS"=>0,"HALT_SUSPEND_REASON_IN_ENGLISH"=>0,"TOP__BID_PRICES___1"=>0,"TOP__BID_VOLUMES___1"=>0,"PRICE"=>0,"VOLUME"=>0,"SIDE"=>0,"ORDER_LIFETIME"=>0,"START_OF_DAY_PRICE"=>0);
	
	//if ($this->cfg->state==0) // Log file source
	if (is_a($secObj,'sec_info')){
	    $secObj->startPrice = $this->updateStartPrice($secObj->getIsinSec());
		$this->errorMessage="createFilter: Security OP:".$secObj->startPrice;
		}
		else {
		$this->errorMessage="createFilter: Security Object Error!";
		return false;
		}
	} // foreach
		
	return true;	
	}
	
	function updateStartPrice($active_isin)
	{
	 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
	 
	 $q= "SELECT START_OF_DAY_PRICE FROM imk_II WHERE ISIN_CODE = '$active_isin' LIMIT 0,1";
	 $this->dp->setQuery($q);
	 return($this->dp->loadResult());
	 }
	 
	function recreateInfo()
	{
	 $qu = "UPDATE imk_MF SET CNTR=0,STATUS=0";
	 	//. " AND OASIS_TIME = \'$oprev_time\'";	
	 //$msgobj->errorlog->addlog('recreateInfo - '.$qu);
	 $this->dp->setQuery($qu);
	 $this->dp->query();
	 $this->empty_table("imk_OP");
	}
	
//---------------------------------------------------------------
// 		createSpecificTable: 
//---------------------------------------------------------------		
	function createSpecificTable($msgId, $keys)
	{
	if ($this->Msgs_collection==null)
	    return "error - $msgId - $keys";
	else {
		  $table=$this->Msgs_collection->getMsg($msgId);
		  return $this->createTable($table, $keys);
		  }
  	}

//---------------------------------------------------------------
// 		createTables: call recursivly createTable via MSG_r
//---------------------------------------------------------------
	function createTables()
	{
	 	 
	 if ($this->init_flag==_DO_NOT_INITIALIZE)
	    return false;
		
		foreach($this->MSG_r as $msg=>$info) {
		    if ($info[_MSG_IDX_CRETAB]){
				$this->errorlog->addlog( 'createTables = table Id=' .$msg);
			    $ans=$this->createTable($this->Msgs_collection->getMsg($msg), $info[0]);
				}
		}
	 return true;	
  	}
	

//---------------------------------------------------------------
// 		createTable: 
//---------------------------------------------------------------	
    function createTable($table, $key)
	{
	 $q = "";
	 if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'createTable = table is null - Key=' .$key);
	     return false;
		 }
	 
	//echo "[table not NULL====================]";	
	 $tb=$table->get(0);
	 $q = "SHOW TABLES LIKE \'imk_$tb\'";
	 $this->dp->setQuery(stripslashes($q));
	 $tabname=$this->dp->loadResult();
 
	 if ($tabname=='')   // table does not exists!
	 {
	 //$this->errorlog->addlog( 'check Table = '.$tabname.' - ' .$q);
	 $q='';
	 foreach($table as $fld=>$val) {
		
		if (is_numeric($fld)) {
			//$tabname=$table->get(0);
			continue;
			}
				
		if ($q == "")
		  	$q = "CREATE TABLE IF NOT EXISTS imk_$tb (";	
		   else
		   	$q.= ",";
			
		 //echo $fld;   
		//$field_name=str_replace(" ", "_", strtoupper($fld));
		$q.= $fld . (($val->get(3)==-11 || $val->get(3)==-1 || $val->get(3)>0) ? " int(" :" char(") .$val->get(1) . ")" . (($val->get(3)==-11) ?'NOT NULL auto_increment' :'');   // $fld=='OASIS_TIME' || 
		 }
		 
	if ($key )
		$q .= ",PRIMARY KEY ($key)";

//$this->errorlog->addlog( 'createTable  - [4]=' .$this->MSG_r[$tb][4]."==".$tb);
	
	if ($this->MSG_r[$tb][4])
	{
	 $indexes_r=explode(",",$this->MSG_r[$tb][4]);
 
	 foreach($indexes_r as $idx){
	 		$q .=",KEY `$idx` (`$idx`)";
	 		}
	}
	
	$q.= ") ENGINE=HEAP";
	
//$this->errorlog->addlog( 'createTable q - ' .$q); 				
	$this->dp->setQuery($q);
	$this->dp->query();
	$err=$this->dp->getAffectedRows();
	if ($err==-1) {
	    $this->errorlog->addlog( 'createTable err - ' .$this->dp->getErrorMsg()); 
    	return;
		}
	}
	
	$this->empty_table("imk_".$tb);		
	/*
	$q1 = "TRUNCATE TABLE imk_$tb";

	$this->dp->setQuery(stripslashes($q1));
	$this->dp->query();
	$err=$this->dp->getAffectedRows();
	if ($err==-1)
    	$this->errorlog->addlog( 'TRUNCATE err - ' .$this->dp->getErrorMsg()); 
	*/
	return;	
	}

    function empty_table($table_name)
	{
	 $q1 = "TRUNCATE TABLE $table_name";

	 $this->dp->setQuery(stripslashes($q1));
	 $this->dp->query();
	 $err=$this->dp->getAffectedRows();
	 if ($err==-1)
    	$this->errorlog->addlog( 'TRUNCATE err - ' .$this->dp->getErrorMsg()); 
		else
		$this->errorlog->addlog( 'TRUNCATE OK - ' .$table_name); 
	}
	
	function empty_session_table($table_name)
	{
	 $q1 = "DELETE FROM $table_name WHERE SECURITY_ISIN = \"". $this->getSessionIsin() . "\" AND SESSION=".$this->session->sesid;

	 $this->dp->setQuery(stripslashes($q1));
	 $this->dp->query();
	 $err=$this->dp->getAffectedRows();
	 if ($err==-1)
    	$this->errorlog->addlog( 'TRUNCATE by session err - ' .$this->dp->getErrorMsg()); 
		else
		$this->errorlog->addlog( 'TRUNCATE by session OK - ' .$table_name); 
	}
	
	
//---------------------------------------------------------------
// 		getFields: 
//---------------------------------------------------------------				
	function getFields()
	{
		$this->ficsnd->setPosition(SEEK_CUR, 10);
	}

//---------------------------------------------------------------
// 		createTable: 
//---------------------------------------------------------------	
	function prepareMarket()
	{
	 if ($this->init_flag==_DO_NOT_INITIALIZE)
	    return true;
	
	 if ($this->source==_SUP) {
 			$this->Written_trades=0;
		
			//$this->Read_trades=0;
		/*	$this->marketGraphObj = new market_graph($this);
			  if( ($this->marketGraphObj instanceof market_graph) == false) 
			  		$this->errorlog->addlog( 'Error creating market_graph! ');
					*/
		}
//	if(!$this->sourceObj->db)
//	    return false;

	if ($this->sourceObj->db == "")
		if ( $this->sourceObj->connect_to_source() == false)
			 return false;
			 
	 $this->sourceObj->prepareMarket();	
	 return true;	
 	 }
		
	function createMarketStats()
	{
	 	$this->sourceObj->createMarketStats();
	 }
		
	function check_time_of_interest()
	{
	
	  if (empty($this->startTime))
	      return 1;
//	$this->errorlog->addlog( 'check_time_of_interest: - =>'.$this->startTime.'<=');	  
		$oasis_time = substr($this->rec, _HEADER+_HEADER_TIME, _HEADER_TIME_LEN);

		if ($oasis_time >= $this->startTime && $oasis_time <= $this->stopTime)
			 return 1;
		if ($oasis_time < $this->startTime)	 
		     return -1;
		 if ($oasis_time > $this->stopTime)	 
		     return 2;
	}
	
			 
function prepareOrdersArray()
{
 $table=$msgobj->Msgs_collection->getMsg('IT');
 if( ($table instanceof Collection) == false)
		 	{
			 $msgobj->errorlog->addlog( 'callbackget_messages_intoDB: table is null - '.$msgId );
			 return;
			}
 foreach($table as $fldName=>$val) {
		if (is_numeric($fldName)) {
			$tabname=$table->get(0);
			$tabsize=$table->get(2);
			continue;
			}
		}	
}

function marketPhase()
{
 $info = $this->MSG_r['IT'][3];
 return $info->get_currphaseId();
}

function marketPhaseStat()
{
 $info = $this->MSG_r['IT'][3];
 return $info->get_currphaseStat();
}

//=========================================================
//		getPhaseOrders: Called by get_msgData
//=========================================================
function getPhaseOrders($msgId, $info, $cp, $phase )
{
  $msgKey = $this->MSG_r[$msgId][0];
  
  $this->msgOrder = $this->MSG_r[$msgId][4];
  $this->msgLimit = $this->MSG_r[$msgId][5];
  
  $start = $info->advancePhaseRecounter(); //$info->phases[$cp][_PHASE_SUBT] - $info->phases[$cp][$phase];
  $step  = $info->get_step();  //$info->phases[$cp][$phase];
  
 $sql = "SELECT "
	 . $this->MSG_r[$msgId][0] 
	 . " FROM imk_".$msgId
//	 . " WHERE substr( ORDER_NUMBER, 1, 1 ) = '0'"
	 . (($this->msgOrder!='') ?" Order by $this->msgOrder" :'')  
	 . (($this->msgLimit!='') ?" LIMIT ".$start.",".$step :"");
// $this->errorlog->addlog( 'getPhaseOrders = ' .$sql);
 $this->dp->setQuery($sql);
 
 $rows=$this->dp->loadObjectList();

 if ($this->dp->getErrorNum()){
    	 $this->errorlog->addlog('getPhaseOrders - err='. $this->dp->getErrorMsg());
		return null;
		}
 if ($rows==null)
	    return null;	
 				
 $script='';
 foreach($rows as $row){
        $okey=$row->ORDER_NUMBER."-".$row->OASIS_TIME;
		$data_r[]=array($okey=>$script);
		}

 return $data_r;
}


function getOrdersmsg()
{
 return ($this->ordersScript);
}

function getEventsmsg()
{
 return ($this->eventsScript);
}

function getTradesmsg()
{
 return ($this->tradesScript);
}

function formatTimeStr($time_str)
{
$str='';
    for ($n=0; $n<4; $n++) {
	    if ($str!='')
		    $str.=(($n==3) ?'.' :":");
	    $str .= substr($time_str,$n*2,(($n==3) ?3 :2));
		}
return $str;		
}


//********************************************************************************************
//		check phases and send phase Event (XML)
//********************************************************************************************
function checkPhases()
{
  $ret_trades=0;
  
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
  $phases_info = $this->markets[$this->cfg->market]->getSecPhase($isin);
  
  if (!is_a($phases_info,'msg_info'))  {
      $this->errorlog->addlog( 'checkPhases error msg_info - '.$isin); 
	  return _FETCH_RET_ERROR;
	  }
   
  $cp = $phases_info->get_currphase();
//$this->errorlog->addlog( 'checkPhases - '.$cp);

  if ($cp==-1 || !is_array($phases_info->phases[$cp])) 
	 {
		print("checkPhases - NOT array at:".$cp."\n");
		$this->eventId++;
		$this->eventsScript='{"rows":[{"id":'.$this->eventId.',"data":[{"TIME": "STOP","PHASE":"'._MARKET_CLOSED.'"}]}]}';
		$this->errorlog->addlog( 'checkPhases: - _MARKET_CLOSED - Trades read='.$secInfo->Read_trades);
		return _FETCH_RET_EVENT;	
		}
  
  
  $cphase_t = key($phases_info->phases[$cp]);
  $mf = $phases_info->phases[$cp];
  
  if ($phases_info->phases[$cp][_PHASE_STAT]==_PHASE_NEW)
  	 {
	  $phases_info->setPhaseStat(_PHASE_OPEN);
	  $this->eventId++;
	  $this->eventsScript='{"rows":[{"id":'.$this->eventId.',"data":[{"TIME":"'.$this->formatTimeStr($mf[_PHASE_TIME]).'","PHASE":"'.$mf[_PHASE_OPEN_COMMENT].'"}]}]}'; //.'","'.$phases_info->get_currphaseId()
	  return _FETCH_RET_EVENT;
	  }
   
  			
  if ($phases_info->phases[$cp][_PHASE_STAT]==_PHASE_DONE)
  {
  // $cp = $phases_info->get_currphase();
  // $mf = $phases_info->phases[$cp];
   
  		
   $cp = $phases_info->advancePhasePointer();
   if ($cp > 0)  {
	   $phases_info->setPhaseStat(_PHASE_OPEN);
	   $mf = $phases_info->phases[$cp];
	   $this->eventId++;
	   $this->eventsScript='{"rows":[{"id":'.$this->eventId.',"data":[{"TIME":"'.$this->formatTimeStr($mf[_PHASE_TIME]).'","PHASE":"'.$mf[_PHASE_OPEN_COMMENT].'"}]}]}';	//'","'.$phases_info->get_currphaseId().	
		}
	 else {
			$this->eventId++;
			$this->eventsScript='{"rows":[{"id":'.$this->eventId.',"data":[{"TIME":"'.$this->formatTimeStr($mf[_PHASE_TIME_DONE]).'","PHASE":"'._MARKET_CLOSED.'"}]}]}';					
			}

	$ret_trades=0;
	if ($mf[_PHASE_STAT]==_PHASE_OPEN && $mf[_PHASE_RUN_COMMENT]==_DO_AUCTION ) // _DO_OPEN_PRICE
	    {
		$ret_trades=$this->sendTrades();
		print ("sendTrades - ".$mf[_PHASE_RUN_COMMENT]." rows=".count($secInfo->lastTrades)."\n");
		}
		
	return $ret_trades | _FETCH_RET_EVENT;
   }
   
  if ($mf[_PHASE_CNTR]==$mf[$cphase_t]) 
  	 {
	 print ("After _PHASE_OPEN - ".$mf[_PHASE_CNTR]." TOT=".$mf[$cphase_t]."\n");
	/*    $ret_trades=0;
		if ($mf[_PHASE_DONE_COMMENT]==_DO_AUCTION ) // _DO_OPEN_PRICE
		{
		    $ret_trades=$this->sendTrades();
			print ("sendTrades -".$ret_trades." rows=".count($this->lastTrades)."\n");
			}
		*/
		$this->eventId++;
		$this->eventsScript='{"rows":[{"id":'.$this->eventId.',"data":[{"TIME":"'.$this->formatTimeStr($mf[_PHASE_TIME_DONE]).'","PHASE":"'.$mf[_PHASE_DONE_COMMENT]." +".$secInfo->Read_trades.'"}]}';
		
		$this->eventId++;

//	=======  Compute Open Price	and signal event ======
		if ($mf[_PHASE_DONE_COMMENT]==_DO_OPEN_PRICE ) // _DO_OPEN_PRICE
		{
			$this->computeOpenPrice($mf[_PHASE_TIME],$mf[_PHASE_TIME_DONE]);
		
			$openprice = (int)$secInfo->auctionPrice / (pow(10,2));
			$secInfo->startPrice=$secInfo->auctionPrice;
			$this->eventsScript.=',{"id":'.$this->eventId.',"data":[{"TIME":"'.$openprice.'","PHASE":"'."PRICE".'"}]}';			
			}
		$this->eventsScript.="]}";
			
	//------------------------------------------------>		
		$phases_info->setPhaseStat( _PHASE_DONE );
		
		 print ("_PHASE_DONE - ".$mf[_PHASE_STAT]." RUN=".$mf[_PHASE_RUN_COMMENT]."\n");	
	 	
     	
		
		return $ret_trades | _FETCH_RET_EVENT;
	 	} // ...more to read!

 return _FETCH_RET_NO_EVENT;
 }


//********************************************************************************************
//		Read next order for every phase and signal phase Event (XML)
//******************************************************************************************** 
function fetchNextOrder()
{
 $msgId='IT';
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 
 $phases_info = $this->markets[$this->cfg->market]->getSecPhase($isin);
 $cp = $phases_info->get_currphase();
 $mf = $phases_info->phases[$cp];
 $nextOrder=$secInfo->posStart+1;
 
 if ($mf[_PHASE_OPEN_COMMENT]!=_PRECALL_OPEN)
 	     
 	$q =  " SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns'] . ", OASIS_TIME prj_time, '0' prj_price"
			. " FROM imk_IT t USE INDEX (PRIM_ID)"
			. " WHERE SECURITY_ISIN like \"$isin\""
			. " AND TRADE_DATE = '". $this->getSessionDate()."'"
			. " ORDER BY ORDER_ID"  //OASIS_TIME
			. " LIMIT ".$secInfo->posStart.",".$secInfo->count;

  else			
    $q =  "SELECT a.*, k.oasis_time prj_time, k.PROJECTED_OPENING_PRICE prj_price FROM ("
 		    ." SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
			. " FROM imk_IT t USE INDEX (PRIM_ID)"
			. " WHERE SECURITY_ISIN like \"$isin\""
			. " AND TRADE_DATE = '". $this->getSessionDate()."'"
			. " ORDER BY ORDER_ID"  //OASIS_TIME
			. " LIMIT ".$secInfo->posStart.",".$secInfo->count
			. ") a"
			. " LEFT OUTER JOIN `imk_IK` k ON k.oasis_time >= a.oasis_time and k.oasis_time <= "
			. "(SELECT OASIS_TIME FROM imk_IT "
			. "	WHERE OASIS_TIME <='". $mf[_PHASE_TIME_DONE] ."'"
				. " ORDER BY ORDER_ID "
				. " LIMIT " . $nextOrder .",1)"			 
			. " WHERE a.oasis_time IS NOT NULL "
			. " LIMIT 0 , 1" ;
/*
(
SELECT `OASIS_TIME` , `PRICE` , "IT", order_id
FROM `imk_IT` t
ORDER BY OASIS_TIME, order_id
)
UNION (

SELECT `OASIS_TIME` , `PRICE` , "IS", trade_number
FROM `imk_IS` 
)
UNION (

SELECT DISTINCT oasis_time, PROJECTED_OPENING_PRICE, "IK", ""
FROM imk_IK
)
ORDER BY oasis_time  
*/
//$phases_info = $this->markets[$this->cfg->market]->getSecPhase($isin);
//$cp = $phases_info->get_currphase();
//$mf = $phases_info->phases[$cp];

//if ($mf[_PHASE_OPEN_COMMENT]==_PRECALL_OPEN)			
   //$this->errorlog->addlog( 'fetchNextOrder = '.$secInfo->posStart);
   // print( 'fetchNextOrder '." q=".stripslashes($q)."\n");
	
	$this->dp->setQuery(stripslashes($q));
	$rows = $this->dp->loadObjectList();
	if ($rows==false || count($rows)==0) {
	   	$this->errorlog->addlog( 'error = '.$this->dp->getErrorNum()." q=".stripslashes($q));
		print( 'fetchNextOrder '." q=".stripslashes($q)."\n");
		unset($secInfo->lastOrders);
		return _FETCH_RET_ERROR;   
		}
		
	$r=0;
	$this->ordersScript='';
	$secInfo->lastOrders=$rows;		

// $this->errorlog->addlog("script=".$script);	

 return _FETCH_RET_ORDERS;
 }
 
function createMarketStream()
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 $q="(
		SELECT OASIS_TIME , PRICE , 'IS' as TYPE, trade_number as ID
		FROM `imk_IS` 
		)
	UNION (
		SELECT OASIS_TIME , PRICE , 'IT' as TYPE, order_id as ID
		FROM `imk_IT` t
		ORDER BY order_id
		)
	UNION (
		SELECT DISTINCT oasis_time, PROJECTED_OPENING_PRICE, 'IK' as TYPE, '' as ID
		FROM imk_IK
		)
	ORDER BY oasis_time ";

	$this->dp->setQuery(stripslashes($q));
	$secInfo->market_stream = $this->dp->loadObjectList();
	if ($secInfo->market_stream==false || count($secInfo->market_stream)==0) {
	   	$this->errorlog->addlog( 'createMarketStream error = '.$this->dp->getErrorNum()." q=".stripslashes($q));
		print( 'createMarketStream '." q=".stripslashes($q)."\n");
		unset($secInfo->market_stream);
		return _FETCH_RET_ERROR;   
		}
 return _FETCH_RET_ORDERS;
 }


function fetchNextMarketStream()
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 if ($secInfo->cntr == count($secInfo->market_stream))
	 return false;
 return($secInfo->market_stream[$secInfo->cntr++]);
 }
 
  
function fetchOrderMarketStream()
{
 $msgId='IT';
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 
  
 $q =  " SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
			. " FROM imk_IT t"
			. " WHERE SECURITY_ISIN like \"$isin\""
			. " AND TRADE_DATE = '". $this->getSessionDate()."'"
			. " AND ORDER_ID = ".$secInfo->market_stream[$secInfo->cntr-1]->ID;

 $this->errorlog->addlog( 'fetchNextOrder = '.$secInfo->market_stream[$secInfo->cntr-1]->ID."==".$secInfo->market_stream[$secInfo->cntr-1]->TYPE);
 
 $this->dp->setQuery(stripslashes($q));
 $rows = $this->dp->loadObjectList();
 if ($rows==false || count($rows)==0) {
	   	$this->errorlog->addlog( 'error = '.$this->dp->getErrorNum()." q=".stripslashes($q));
		print( 'fetchNextOrder '." q=".stripslashes($q)."\n");
		unset($secInfo->lastOrders);
		return _FETCH_RET_ERROR;   
		}
		
 $secInfo->lastOrders=$rows;	
 
 return _FETCH_RET_ORDERS;
 }

function fetchTradesMarketStream ()
{
 $msgId='IS';
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 
 for($i=$secInfo->cntr-1; $secInfo->market_stream[$i]->TYPE=="IS"; $i++)
     $trades_str .= ($trades_str!="" ?"," :"") . $secInfo->market_stream[$i]->ID;
	 
 $q =  " SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
			. " FROM imk_IS s"
			. " WHERE SECURITY_ISIN like \"$isin\""
			. " AND TRADE_DATE = '". $this->getSessionDate()."'"
			. " AND TRADE_NUMBER IN (". $trades_str . ")";

 
 $this->dp->setQuery(stripslashes($q));
 $rows = $this->dp->loadObjectList();
 if ($rows==false || count($rows)==0) {
	   	$this->errorlog->addlog( 'fetchTradesMarketStream error = '.$this->dp->getErrorNum()." q=".stripslashes($q));
		print( 'fetchTradesMarketStream '." q=".stripslashes($q)."\n");
		unset($secInfo->lastOrders);
		return _FETCH_RET_NO_TRADES;   
		}

 $secInfo->cntr=$i;		
 $secInfo->lastTrades=$rows;	
 
 return _FETCH_RET_TRADES; 
 }
 
//********************************************************************************************
//		Pack an Order and send (XML)
//********************************************************************************************  
function packOrder($table, $row, &$r, $cu=false)
{
 global $desc_r;
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 if ($this->ordersScript=='') {
	  $this->ordersScript='{"rows":['; // :"<rows>"; // total_count=$count pos=$posStart //"{rows:[";
	  $this->ordersScript.="";
	  }
	  else
		$this->ordersScript.=",";	
		
 if ($this->cfg->market_depth == 0 ) //&& $mf[_PHASE_OPEN_COMMENT]==_CONTINUOUS_OPEN) 
  	 $secInfo->priceInRange = 0;
					
 
 $this->ordersScript.='{"id":"'.$row->ORDER_NUMBER.'","data":[{';	
 $i=0;
 $r--;
 $str='';
 $excols=array('ORDER_MOD','ORDER_ID','ORDER_STATUS');
 
 foreach($row as $col=>$val)
 		{
		 if ($cu=='sendOnlyTime' && $col!='OASIS_TIME')
		     continue;
		 if (in_array($col, $excols))
				continue;
				//print("pack ".$table[$col][_IDX_TITLE]."=H=".$table[$col][_IDX_HEADER]."=D=".$table[$col][_IDX_DECIMALS]."\n");
		 if ($table[$col][_IDX_HEADER] !=3)
		 	    continue;
		 if ( $i ) 
			 $this->ordersScript .= ',';
				  		
		// ===Format fields=== //
			if ($val=='')
				$val=' ';

					
			if ($table[$col][_IDX_ID]=="SIDE"){
			   // print("packOrder ".$col."==".$table[$col][_IDX_TITLE]."\n");
				$val = $desc_r[$table[$col][_IDX_ID]][$val];
				}
				
			elseif ($table[$col][_IDX_ID]=="VOLUME" && $row->ORDER_STATUS=='I')
			    $val.=" ($row->MATCHED_VOLUME)";
				
			elseif ($table[$col][_IDX_ID]=="TYPE")
			    $val = $desc_r[$table[$col][_IDX_TITLE]][$val];

			elseif ($table[$col][_IDX_ID]=="FLAGS")
			    $val .= $row->ORDER_STATUS;
				
			if ($table[$col][_IDX_DECIMALS] != 0)
				{
				
			 	if ($table[$col][_IDX_DECIMALS] > 0) 
			   		{
					$val = (int)$val * 1;
			    	$val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));
					}

				if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') 
			   		{
			    	/*
					for ($n=0; $n<4; $n++) 
				    	{
				     	if ($str!='')
					    	$str.=':';
				     	$str .= substr($val,$n*2,2);
					 	}*/
					$val = $this->formatTimeStr($val);
					}
				}
			
			$i++; 
			$mod_col="";
			if (in_array($table[$col][_IDX_ID],array("TRADER_ID" ,"ACCOUNT")))
			    if ($row->SIDE=="B") 
			        $mod_col= "BUY_";
				  else
				     $mod_col="SELL_";
				   
			$this->ordersScript.='"'.$mod_col.$table[$col][_IDX_ID].'":'.'"'.$val.'"';
						
			if ($col=='SIDE') 
				{	
				 $oside=$val;
				 }
							
			if ($col=='ORIGINAL_PRICE_TYPE')	
				$opricetype=$val;
			//$script .= "'$val'"; // :"<cell>$val</cell>"; 		  
			}
		$this->ordersScript.="}]}";
	
  if ($r == 0)		
      $this->ordersScript.="]}"; 
		  
 }
 
//=========================================================================================
//	GET MArket Depth
//=========================================================================================
function getMD($side, $price, $hasTrade=0)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $this->mdScriptB='{"rows":[';
// $script= $this->mdScriptB;
 /*
 if ($side =='B') {
 	 $this->mdScriptB='{"rows":[';
	 $script= $this->mdScriptB;
	 }	  	  
   else {
 	 	$this->mdScriptS='{"rows":[';
	 	$script= $this->mdScriptS;
	 	}
 */
 $oPrice =$price;
 $secInfo->priceInRange=1;
// print("getMD:".$price."==".$side."\n");
 if ($hasTrade==0) {  // Orders			
    $oPrice = ($price==0 ?($side=='B' ?"99999" :"0") :$price); 					
 	$q="SELECT count(*) FROM ("
	. " SELECT PRICE FROM imk_OB "
	. " USE INDEX (PRIM_ID)"
 	. " WHERE "
	. " SESSION = ". $this->session->sesid 
	. " AND SECURITY_ISIN = '". $this->getSessionIsin()."'" 
	. " AND FDATE = '". $this->getSessionDate()."'"  
	. " AND SIDE = \'". $side ."\' "
//	. " AND abs(PRICE) = " . abs($oPrice)
	. " AND STATUS < " ._NOT_RELEASED_ORDER 
	. " GROUP BY PRICE ORDER BY abs(price) " . (($side=='S') ?"ASC" :"DESC") . ",OASIS_TIME"
	. (($this->cfg->market_depth > 0) ?" LIMIT 0, ".$this->cfg->market_depth :"")
	. ")a"
	. " WHERE abs(PRICE) = ".abs($oPrice)
	. " GROUP BY PRICE";

print( 'addPriceMD  sql: '.$secInfo->bid_1."==".$secInfo->ask_1."\n");
 	$this->dp->setQuery(stripslashes($q)); 
 	$secInfo->priceInRange=$this->dp->loadResult();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'addPriceMD  sql: '.stripslashes($q)."==".$this->dp->stderr());
		print( 'getMD  sql: '.stripslashes($q)."==".$this->dp->stderr()."\n");
		$secInfo->priceInRange=0;
		$this->mdScriptB='';
		return;
		}
	}
//	$this->errorlog->addlog( 'getMD - priceInRange : '.$this->priceInRange);
// 	}
 
 if ($secInfo->priceInRange && $secInfo->priceInRange > 0) {
 	
	//$this->errorlog->addlog( 'addPriceMD  PRICE: '.$this->startPrice);
	 $q="SELECT OASIS_TIME,  abs(PRICE) price, sum( UNMATCHED_VOLUME ) volume, count(ENTRIES) entries"  //(CASE WHEN PRICE=99999 THEN 0 ELSE PRICE END)
		. " FROM imk_OB " 
		. " USE INDEX (MD)"
		. " WHERE "
		. " SESSION = ". $this->session->sesid 
		. " AND SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND FDATE = '". $this->getSessionDate()."'"  		
		. " AND SIDE = 'B'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " GROUP BY price"
		. " Having (price IS NOT NULL)"
		. " ORDER BY price DESC, OASIS_TIME"
		. (($this->cfg->market_depth>0) ?" LIMIT 0, ".$this->cfg->market_depth :"");
//print('getMD sql: '.$q."\n");
	$this->dp->setQuery($q); 
 	$mdbRows=$this->dp->loadObjectList();
	if ($this->dp->getErrorNum() || count($mdbRows)==0 || !$mdbRows){
	    $this->errorlog->addlog( 'getMD sql: '.$q."==".$this->dp->stderr());
		print('getMD sql: '.$q."==".$this->dp->stderr()."\n");
		$secInfo->priceInRange=0;
		$this->mdScriptB='';
		return;
		}
	
//	$other_side = ($side=='S' ?'B' :'S');
	
	$q="SELECT OASIS_TIME, abs( PRICE ) price, sum( UNMATCHED_VOLUME ) volume, count(ENTRIES) entries"
		. " FROM imk_OB" 
		. " USE INDEX (MD)"
		. " WHERE "
		. " SESSION = ". $this->session->sesid 
		. " AND SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND FDATE = '". $this->getSessionDate()."'"  
		. " AND SIDE = 'S'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " GROUP BY price"
		. " Having (price IS NOT NULL)"
		. " ORDER BY price ASC, OASIS_TIME"
		. (($this->cfg->market_depth>0) ?" LIMIT 0, ".$this->cfg->market_depth :"");

	$this->dp->setQuery($q); 
 	$mdsRows=$this->dp->loadObjectList();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'addPriceMD sql: '.$q."==".$this->dp->stderr());
		print('getMD sql: '.$q."==".$this->dp->stderr()."\n");
		$secInfo->priceInRange=0;
		$this->mdScriptB='';
		return;
		}
 	$r=0;
	
	$this->accScript="";
	$bid = ((int)$secInfo->bid_1) / pow(10,2);	
	$ask = ((int)$secInfo->ask_1) / pow(10,2);	
	
	$len = max(count($mdbRows),count($mdsRows));
	for($i=0; $i < $len; $i++) {
		list($mrow_key, $mrow) = @each($mdbRows);
		list($srow_key, $srow) = @each($mdsRows);
		
		$surv_script = $this->getSurvTraders( ($mrow!=null ?$i :0), $mrow->price, ($srow!=null ?$i :0), $srow->price );
		
		$this->getAccounts( $mrow->OASIS_TIME, $mrow->price, $srow->OASIS_TIME, $srow->price, $i );
		if (!$i){
				 $secInfo->MDchange=($secInfo->bid_1!=$mrow->price || $secInfo->ask_1!=$srow->price) ?1 :0;

				 $secInfo->bid_1=$mrow->price;
				 $secInfo->ask_1=$srow->price;
				 $bid = ((int)$secInfo->bid_1) / pow(10,2);	
				 $ask = ((int)$secInfo->ask_1) / pow(10,2);
				 }
//	foreach($mdbRows as $brow) {
//print("MD - ".$mrow->price."==".$mrow->volume."==".$srow->price."==".$srow->volume."\n");
		 $mprice_decimals = (int)$mrow->price / pow(10,2);
		 $sprice_decimals = (int)$srow->price / pow(10,2);
		 
		 $this->mdScriptB .= ($r > 0) ?"," :"";
		// if ($side=='B')			//'","'."A".'":"'."tradeOrders.php?task=md_details&side=$side&price=$price_decimals&time=$mrow->OASIS_TIME&session=$this->session"
		     $this->mdScriptB .= '{"id":"'.$mrow->price.'","data":[{"'."E".'":"'.$mrow->entries.'","'."B".'":"'.$mrow->volume.'","'."P".'":"'.$mprice_decimals.'","'."SP".'":"'.$sprice_decimals.'","'."A".'":"'.$srow->volume.'","'."SE".'":"'.$srow->entries.'","'."bid".'":"'.$bid.'","'."ask".'":"'.$ask.'","'."surv".'":['.$surv_script.']'.'}]}';
			//else 		//"A".'":"'."tradeOrders.php?task=md_details&side=$side&price=$price_decimals&time=$mrow->OASIS_TIME&session=$this->session".
			 //$script .= '{"id":"'.$mrow->price.'","data":[{"'.'"PRICE":"'.$mrow->price.'","ASK":"'.$mrow->volume.'","'."ENTRIES".'":"'.$mrow->entries.'"}]}';
		 $r++;
		 }
	$this->accScript .= ($this->accScript=="") ?"" :"]}";
 	$this->mdScriptB.="]}";
	
	$secInfo->md=$mdRows;
	
 //	if ($side =='B') { 
	 //	$this->mdScriptB=$script;
//		print ("MDscript: ".$this->mdScriptB."\n");
	// 	}	  
	//  else	  
	// 	 {
	// 	 $this->mdScriptS=$script;
	 //	 }
//	$script="";
	return;		
	}
	else
		$this->mdScriptB='';
// $this->accScript="";	
// $this->mdScriptS='';
// $this->mdScriptB='';
}

//=========================================================================================
//	GET MArket Depth 1st level only!  Used by CustomPeriod
//=========================================================================================
function getMD_1()
{
 $tabName = ($this->creOBStats == 1) ?"imk_OBS" :"imk_OB";
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
	 $q="SELECT abs(PRICE) price"  //(CASE WHEN PRICE=99999 THEN 0 ELSE PRICE END)
		. " FROM $tabName" 
		. " USE INDEX (MD) "
		. " WHERE "
		. " SESSION = ". $this->session->sesid 
		. " AND SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND FDATE = '". $this->getSessionDate()."'"  
		. " AND SIDE = 'B'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC, OASIS_TIME"
		. " LIMIT 0,1 ";

	$this->dp->setQuery(stripslashes($q)); 
 	$bprice=$this->dp->loadResult();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getPriceMD sql: '.$q."==".$this->dp->stderr());
		print( 'getPriceMD sql: '.$q."==".$this->dp->stderr()."\n");
		return false;
		}
	
//	$other_side = ($side=='S' ?'B' :'S');
	
	$q="SELECT abs( PRICE ) price"
		. " FROM $tabName" 
		. " USE INDEX (MD) "
		. " WHERE "
		. " SESSION = ". $this->session->sesid 
		. " AND SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND FDATE = '". $this->getSessionDate()."'"  		
		. " AND SIDE = 'S'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price ASC, OASIS_TIME"
		. " LIMIT 0, 1";

	$this->dp->setQuery($q); 
 	$aprice=$this->dp->loadResult();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getPriceMD sql: '.$q."==".$this->dp->stderr());
		print( 'getPriceMD sql: '.$q."==".$this->dp->stderr()."\n");
		return false;
		}
//if ((int)$bprice==1023)
  //print("getMD_1 ".$secInfo->bid_1."==".$bprice."==".$secInfo->ask_1."==".$aprice."\n");	
	$secInfo->MDchange=($secInfo->bid_1!=$bprice || $secInfo->ask_1!=$aprice) ?1 :0;
	//$this->errorlog->addlog( "getMD_1 ".$secInfo->MDchange."==".$bprice."==".$aprice);
	$secInfo->bid_1=$bprice;
	$secInfo->ask_1=$aprice;
 return true;
}

/*-------------------------------------------------------------
*			getSurvTraders
--------------------------------------------------------------*/
function getSurvTraders($bi, $oprice, $si, $oprice_s)
{ 
 $search_str = "";
 $script = "";
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 if (!$secInfo || count($secInfo->survTraders)==0 ){
    print ("survTraders is empty!"."\n"); 
 	return $script;
	}

 foreach ($secInfo->survTraders as $row)
 {
 	if ($search_str=="") 
		$search_str="(";
	  else	
	  	$search_str .= ",";
	   
	$search_str .= "'".$row->trader."'";
  }
  if ($search_str)
	  $search_str .= ")";
	  
// print ($search_str."\n"); 
 $q="SELECT otime, TID, price, sum( volume ) vol, entries "
	.	" FROM ( "
	.	" SELECT t.OASIS_TIME otime, concat( t.TRADER_ID, '-', t.CSD_ACCOUNT_ID ) TID, "
	.	" (CASE WHEN o.PRICE =99999 THEN 0 ELSE o.PRICE END )price, "
	.	" UNMATCHED_VOLUME volume, ENTRIES entries "
	.	" FROM imk_OB o, imk_IT t "
	.	" WHERE o.SIDE = 'B'"
	.   " AND SESSION = ". $this->session->sesid 
	.   " AND o.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
	.   " AND o.FDATE = '". $this->getSessionDate()."'" 
	.	" AND abs( o.PRICE ) = $oprice "
	.	" AND o.ORDER_ID = t.ORDER_ID "
	.	" AND STATUS <7 "
	.	" ORDER BY price DESC "
	.	" )m"
	.	" WHERE tid"
	.	" IN $search_str "
	.	" GROUP BY tid ";
//where
//tid in ('ΕΛ00C-92308','EB00C-307878')

 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.stripslashes($q)."==".$this->dp->stderr());
		print("failed - ".stripslashes($q)."\n");
		return null;
		}
	$mRows = $this->dp->loadObjectList();
//	if (count($mRows))
//	print("survTraders - ".stripslashes($q)."\n");
		//return;
	
	 $q="SELECT otime, TID, price, sum( volume ) vol, entries "
	.	" FROM ( "
	.	" SELECT t.OASIS_TIME otime, concat( t.TRADER_ID, '-', t.CSD_ACCOUNT_ID ) TID, "
	.	" (CASE WHEN o.PRICE =99999 THEN 0 ELSE o.PRICE END )price, "
	.	" UNMATCHED_VOLUME volume, ENTRIES entries "
	.	" FROM imk_OB o, imk_IT t "
	.	" WHERE o.SIDE = 'S'"
	.   " AND SESSION = ". $this->session->sesid 
	.   " AND o.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
	.   " AND o.FDATE = '". $this->getSessionDate()."'" 
	.	" AND abs( o.PRICE ) = $oprice_s "
	.	" AND o.ORDER_ID = t.ORDER_ID "
	.	" AND STATUS <7 "
	.	" ORDER BY price DESC "
	.	" )m"
	.	" WHERE tid"
	.	" IN $search_str"
	.	" GROUP BY tid ";

 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.$q."==".$this->dp->stderr());
		//print("failed - ".stripslashes($q)."\n");
		return null;
		}
	$sRows = $this->dp->loadObjectList();
	//if (count($sRows)==0)
		//return;
	
	$len = max(count($mRows),count($sRows),count($secInfo->survTraders));
	for($i=0; $i < $len; $i++) {
		list($mrow_key, $mrow) = @each($mRows);
		list($srow_key, $srow) = @each($sRows);
			
	 if ($i)
	    $script .= ",";
	 
	 $script .= "{";
	 
	// if ($mrow!=null)
		 $script .= '"SERB":"'.$this->searchSurvTrader($secInfo,$mrow->TID) .'",'.'"LB":"'.$bi .'",'.'"SBP":"'.$mrow->vol.'"';
//	 if (srow!=null) 
//	 	{
	 	// if ($mrow!=null) 
		     $script .= ",";
		 $script .= '"SERS":"'.$this->searchSurvTrader($secInfo,$srow->TID) .'",'.'"LS":"'.$si .'",'.'"SSP":"'.$srow->vol.'"';
//		 }
	 $script .= "}";	
	 }
//  print ("getSurvTraders -".$script."\n");
  return $script;
 }

function searchSurvTrader($secInfo,$tr)
{
 $v=0;
  
 foreach ($secInfo->survTraders as $row)
 {
	if ($row->trader==$tr) 
		return $v+1;
	$v++;	
  }
  return -1;
 } 


function getMdTraders($side,$price)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
// $totTradeValue=$this->totTradeVol;

	 
	$q="SELECT t.OASIS_TIME OASIS_TIME, " 
		. " CONCAT(t.TRADER_ID,'-',t.CSD_ACCOUNT_ID) TRADER, "
		. " t.ORDER_ID ORDER_ID, t.ORDER_NUMBER ORDER_NUMBER, "
		. " (CASE WHEN o.PRICE=99999 THEN 0 ELSE round((abs(o.PRICE)/100),2) END) PRICE,"
		. "	o.VOLUME VOLUME,UNMATCHED_VOLUME UNMATCHED "
		. " FROM imk_OB o, imk_IT t" 
		. " WHERE o.SIDE = '$side'"
		. " AND o.SESSION = ". $this->session->sesid 
		. " AND o.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND t.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND o.FDATE = '". $this->getSessionDate()."'" 
		. "	AND abs(o.PRICE)=$price "
		. " AND o.ORDER_ID = t.ORDER_ID"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC";
 

 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
 $nrows = count($rows);

 if ($this->dp->getErrorNum() || $nrows==0 || $rows==false){
	    $this->errorlog->addlog( 'getMdTraders sql: '.$this->dp->stderr()."==".stripslashes($q));
		print ('getMdTraders sql: '.$this->dp->stderr()."==".stripslashes($q)."\n");
		return _TRADES_RET_ERROR;
		}
 
  $posStart=1;
  $count=25;
  $returnValue = array(
        'recordsReturned'=>$nrows,
        'totalRecords'=>$nrows,
        'startIndex'=>$posStart,
        'sort'=>"id",
        'dir'=>"asc",
        'pageSize'=>$count,
        'records'=>$rows
    );
	
 require_once('JSON.php');
 $json = new Services_JSON();
 return ($json->encode($returnValue));
 }
 		 
/*----------------------------------------------------------
*		getAccounts
*-----------------------------------------------------------*/
function getAccounts($otime, $oprice, $otime_s, $oprice_s, $level)
{ 
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
// $totTradeValue=$this->totTradeVol;
 
 $q="SELECT t.OASIS_TIME, CONCAT(t.TRADER_ID,'-',t.CSD_ACCOUNT_ID) TID, (CASE WHEN o.PRICE=99999 THEN 0 ELSE o.PRICE END) price,UNMATCHED_VOLUME volume, ENTRIES entries"
		. " FROM imk_OB o, imk_IT t" 
		. " WHERE o.SIDE = \'B\'"
		. " AND o.SESSION = ". $this->session->sesid 
		. " AND o.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND t.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND o.FDATE = '". $this->getSessionDate()."'" 
		. "	AND abs(o.PRICE)=$oprice "
		. " AND o.ORDER_ID = t.ORDER_ID"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC";

/*			
 $q="SELECT ob.OASIS_TIME,TRADER_ID,CSD_ACCOUNT_ID,ob.VOLUME VOLUME"
 	. "  FROM imk_IT it, imk_OB ob"
	. " WHERE  "
	. " 	ob.OASIS_TIME<=\'$otime\' AND abs(ob.PRICE)=$oprice "
	. " AND ob.STATUS < 8"
	. " AND ob.SIDE = \"B\""
	. " AND ob.ORDER_ID = it.ORDER_ID"
	. " ORDER BY ob.OASIS_TIME";
*/
 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.stripslashes($q)."==".$this->dp->stderr());
		return null;
		}
	$mRows = $this->dp->loadObjectList();
	if (count($mRows)==0)
		print("getAccounts b- ".stripslashes($q)."\n");
	
/*	$q="SELECT ob.OASIS_TIME,CSD_ACCOUNT_ID,ob.VOLUME VOLUME"
 	. "  FROM imk_IT it, imk_OB ob"
	. " WHERE  "
	. " 	ob.OASIS_TIME<=\'$otime_s\' AND abs(ob.PRICE)=$oprice_s "
	. " AND ob.STATUS < 8"
	. " AND ob.SIDE = \"S\""
	. " AND ob.ORDER_ID = it.ORDER_ID"
	. " ORDER BY ob.OASIS_TIME";
*/	
	 $q="SELECT t.OASIS_TIME,CONCAT(t.TRADER_ID,'-',t.CSD_ACCOUNT_ID) TID, abs(o.PRICE) price,UNMATCHED_VOLUME volume, ENTRIES entries"
		. " FROM imk_OB o, imk_IT t" 
		. " WHERE o.SIDE = \'S\'"
		. " AND o.SESSION = ". $this->session->sesid 
		. " AND o.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND t.SECURITY_ISIN = '". $this->getSessionIsin()."'" 
		. " AND o.FDATE = '". $this->getSessionDate()."'" 
		. "	AND abs(o.PRICE)=$oprice_s "
		. " AND o.ORDER_ID = t.ORDER_ID"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC";


 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.$q."==".$this->dp->stderr());
		return null;
		}
	$sRows = $this->dp->loadObjectList();
	if (count($sRows)==0)
		print("getAccounts s- ".stripslashes($q)."\n");
	if (count($sRows)==0 && count($mRows))
		return;
	
	if ($this->accScript=="") 
		$this->accScript='{"rows":[';
	 else
		$this->accScript .= ",";
		
	$len = max(count($mRows),count($sRows));
	for($i=0; $i < $len; $i++) {
		list($mrow_key, $mrow) = @each($mRows);
		list($srow_key, $srow) = @each($sRows);
		
//	 $secInfo->bid_traders[$mrow->TID][1]+=$mrow->volume;
//	 $secInfo->bid_traders[$mrow->TID][2] = round( ((int)$secInfo->bid_traders[$mrow->TID][1] *100)/(int)$secInfo->bid_traders[$mrow->TID][0], 2);
//	 $secInfo->ask_traders[$srow->TID][1]+=$srow->volume;
//	 $secInfo->ask_traders[$srow->TID][2] = round( ((int)$secInfo->ask_traders[$srow->TID][1] *100)/(int)$secInfo->ask_traders[$srow->TID][0], 2);
	 
	 if ($i)
	    $this->accScript .= ",";
	 
	 $this->accScript .= '{"id":"'.$i.'","data":[{'.'"T":"'.$mrow->OASIS_TIME.'",'.'"ACC":"'.$mrow->TID.'",'.'"V":"'.$mrow->VOLUME.'",'.
												    '"ST":"'.$srow->OASIS_TIME.'",'.'"SACC":"'.$srow->TID.'",'.'"SV":"'.$srow->VOLUME.'",'.
													'"L":"'.$level.'"'.
							 '}]}';
	 }
//	$this->accScript .= "]}";
 }
 
 /*----------------------------------------------------------
*		getAccounts
*-----------------------------------------------------------*/
function getTopAccounts()
{ 
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
// $totTradeValue=$this->totTradeVol;
 /*
 $q=" SELECT * FROM ("
 		. " SELECT t.OASIS_TIME, CONCAT(t.TRADER_ID,'-',t.CSD_ACCOUNT_ID) TID, (CASE WHEN o.PRICE=99999 THEN 0 ELSE o.PRICE END) price,UNMATCHED_VOLUME volume, ENTRIES entries"
		. " FROM imk_OB o, imk_IT t" 
		. " WHERE o.SIDE = \'B\'"
		. "	AND abs(o.PRICE)=$oprice "
		. " AND o.ORDER_ID = t.ORDER_ID"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC)m"
		. " WHERE m.TID in (".$secInfo->bid_traders_str.")";
//print('getAccounts  sql: '.$q."\n");

 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.$q."==".$this->dp->stderr());
		return null;
		}
	$mRows = $this->dp->loadObjectList();
	
	 $q="SELECT * FROM ("
	 	. " SELECT t.OASIS_TIME,CONCAT(t.TRADER_ID,'-',t.CSD_ACCOUNT_ID) TID, abs(o.PRICE) price,UNMATCHED_VOLUME volume, ENTRIES entries"
		. " FROM imk_OB o, imk_IT t" 
		. " WHERE o.SIDE = \'S\'"
		. "	AND abs(o.PRICE)=$oprice_s "
		. " AND o.ORDER_ID = t.ORDER_ID"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " ORDER BY price DESC)m"
		. " WHERE m.TID in (".$secInfo->ask_traders_str.")";

 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getAccounts  sql: '.$q."==".$this->dp->stderr());
		return null;
		}
	
	$sRows = $this->dp->loadObjectList();
	if (count($sRows)==0 && count($mRows)==0)
		return;
	
	$this->accTradesScript='{"rows":[';
	
	$len = max(count($mRows),count($sRows));
	for($i=0; $i < $len; $i++) {
		list($mrow_key, $mrow) = @each($mRows);
		list($srow_key, $srow) = @each($sRows);
	// $this->totTradeVol		
//	 $secInfo->bid_traders[$mrow->TID][1]+=$mrow->volume;
	 $secInfo->bid_traders[$mrow->TID][2] = round( ((int)$secInfo->bid_traders[$mrow->TID][1] *100)/(int)$secInfo->bid_traders[$mrow->TID][0], 2);
//	 $secInfo->ask_traders[$srow->TID][1]+=$srow->volume;
	 $secInfo->ask_traders[$srow->TID][2] = round( ((int)$secInfo->ask_traders[$srow->TID][1] *100)/(int)$secInfo->ask_traders[$srow->TID][0], 2);
*/
	$this->accTradesScript='{"rows":[';
	
	reset ($secInfo->bid_traders);
	reset ($secInfo->ask_traders);
	 
	 $len = max(count($secInfo->bid_traders),count($secInfo->ask_traders));
//print("traders...............: ". $len."\n");		 
	 for($i=0; $i < $len; $i++) 
	 {
	  list($mrow_key, $mrow) = @each($secInfo->bid_traders);
	  list($srow_key, $srow) = @each($secInfo->ask_traders);
//print("mrow_key: ".$mrow_key."==".$mrow[1]."\n");
	  $mrow[2]=0;
	  $srow[2]=0;
	  if ((int)$mrow[0]>0)	  
		  $mrow[2]=round( ((int)$mrow[1] *100)/(int)$mrow[0], 2);
  	  if ((int)$srow[0]>0)	  
		  $srow[2]=round( ((int)$srow[1] *100)/(int)$srow[0], 2);
	  	  
	  if ($i)
	      $this->accTradesScript .= ",";
	 
 	  $this->accTradesScript .= '{"id":"'."T_".$i.'","data":[{'.'"T":"'.$mrow[0].'",'.'"ACC":"'.$mrow_key.'",'.'"V":"'.$mrow[1].'",'.'"BPCT":"'.$mrow[2].'",'.
												    '"ST":"'.$srow[0].'",'.'"SACC":"'.$srow_key.'",'.'"SV":"'.$srow[1].'",'.'"SPCT":"'.$srow[2].'"'.
							 '}]}';
	  }
	
	$this->accTradesScript .= "]}";
 }
 

//********************************************************************************************
//		Read next order for every phase and signal phase Event (XML)
//******************************************************************************************** 
function getProjected($last_time, $next_time)
{
 $msgId='IK';
 $tabName = ($this->creOBStats==1) ?"imk_OBS" :"imk_OB";
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  
 $q = "SELECT OASIS_TIME, ' ' as ORDER_NUMBER, ' ' as ORDER_ID, PROJECTED_OPENING_PRICE, 0 as VOLUME"
			. " FROM imk_IK"
			. " WHERE SECURITY_ISIN like \"".$this->getSessionIsin()."\""
			. " AND  OASIS_TIME >= \"$last_time\" AND OASIS_TIME <= \"$next_time\""
			. " UNION" 
			. " SELECT OASIS_TIME, ORDER_NUMBER, ORDER_ID, abs( PRICE ) price, VOLUME"
			. " FROM $tabName"
			. " WHERE SECURITY_ISIN LIKE \"".$this->getSessionIsin()."\""
			. " AND FDATE = \'".$this->getSessionDate(). "\'"
			. " AND  OASIS_TIME >= \"$last_time\" AND OASIS_TIME <= \"$next_time\""
			. " ORDER BY OASIS_TIME";

 $q1 = "SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns'] 
			. " FROM imk_$msgId"
			. " WHERE SECURITY_ISIN like \"".$this->getSessionIsin()."\""
			. " AND  OASIS_TIME >= \"$last_time\" AND OASIS_TIME <= \"$next_time\""
			. " ORDER BY OASIS_TIME";
			
//			. " LIMIT ".$this->posStart.",".$this->count;
			
//if ($mf[_PHASE_DONE_COMMENT]!=_DO_OPEN_PRICE)			
//   $this->errorlog->addlog( 'getProjected '." q=".$q);
	
	$this->dp->setQuery(stripslashes($q));
	$rows = $this->dp->loadObjectList();
	if ($rows==false || count($rows)==0) {
	   	$this->errorlog->addlog( 'getProjected error = '.$this->dp->getErrorNum()." q=".$q);
		print( 'getProjected error = '.$this->dp->getErrorNum()." q=".$q."\n");
		unset($secInfo->projected);
		$secInfo->projectedScript='';
		return _FETCH_RET_ERROR;   
		}
	$secInfo->projected=$rows;	
	
/*		 
	$q = "SELECT ob.OASIS_TIME otime, ob.ORDER_NUMBER onumber, ob.ORDER_ID oid, abs(ob.PRICE) oprice, ob.VOLUME ovolume" 
			. " FROM $tabName ob"
			. " WHERE "
			. " SECURITY_ISIN LIKE \"".$this->getSessionIsin()."\""
			. " AND FDATE = \'".$this->getSessionDate(). "\'"
			. " AND OASIS_TIME >= \"$last_time\" AND OASIS_TIME <= \"$next_time\""
			. " ORDER BY OASIS_TIME";
	
	$this->dp->setQuery(stripslashes($q));
	$orders = $this->dp->loadObjectList();
	if ($orders==false || count($orders)==0) {
	   	$this->errorlog->addlog( 'getProjected error = '.$this->dp->getErrorNum()." q=".$q);
		print( 'getProjected error = '.$this->dp->getErrorNum()." q=".$q."\n");
		unset($secInfo->projected_orders);
		$this->projectedScript='';
		return _FETCH_RET_ERROR;   
		}
	$secInfo->projected_orders=$orders;	
*/				
	$r=0;	

// $this->errorlog->addlog("script=".$script);	

 return _FETCH_RET_ORDERS;
 }
    

//========================================//======\\======================================//

function computeLimit($mf,$cphase_t)
{
 $cntr=0;
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 if ($this->total > 0)
		{
		 if ($this->total > _MAX_READ)
		 {
		  $this->total-=_MAX_READ;
		  $cntr=_MAX_READ;
		 }
		 else {
		 	if ( $this->total < $mf[$cphase_t]){
				 $cntr=$this->total;
			 	$this->total=0;
			 	}
				else {
				  $this->total -= $mf[$cphase_t];
				  $cntr=$mf[$cphase_t];
				  }	
			 }	  
	//	 print "stop_steps=$total;" . (($this->total == 0 && $this->mode!='step') ?"cmd_msg='PAUSE';" :"");	  
		 }
		
 $secInfo->posStart = ($mf[_PHASE_SUBT] - $mf[$cphase_t])+$mf[_PHASE_CNTR];

//		$count  = ($mf->OPEN_CMNT==_PRECALL_OPEN) ?$mf->PTOTAL :1;
 $secInfo->count  = ($cntr==0) ?(($mf[_PHASE_CNTR] < $mf[$cphase_t]) ?($mf[$cphase_t] - $mf[_PHASE_CNTR]) :$cntr) :$cntr; // :$mf->PTOTAL;
// $this->errorlog->addlog( 'computeLimit : count - '.$this->count."_PHASE_CNTR=".$mf[_PHASE_CNTR]." PHASE_TOTAL=".$mf[$cphase_t]." _PHASE_SUBT=".$mf[_PHASE_SUBT]);
 $secInfo->count  = ($secInfo->count > _MAX_READ) ?_MAX_READ :$secInfo->count;
}

//=========================================================================================
//	ADD MArket Depth
//=========================================================================================
function setOrderBook($ORDER_ID, $OASIS_TIME, $ORDER_NUMBER, $SIDE, $PRICE, $VOLUME, $DISCLOSED_VOLUME, $ORDER_STATUS)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 $tabName = ($this->creOBStats == 1) ?"imk_OBS" :"imk_OB";
 
 if ($ORDER_STATUS=='X')
 {
   	$q="UPDATE $tabName SET STATUS=8, OASIS_TIME=\"$OASIS_TIME\" WHERE ".$this->session->sesid
	   ." AND ORDER_NUMBER=\"$ORDER_NUMBER\""
	   ." AND SECURITY_ISIN = \"$isin\""
	   ." AND FDATE = \'".$this->getSessionDate()."\'";
   	$this->dp->setQuery(stripslashes($q));
 	$this->dp->query();
	$err=$this->dp->getAffectedRows();	
	
//print( 'UPDATE X  sql: '.stripslashes($q)."==".$err."\n");
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'setOrderBook OB: '.$this->dp->getErrorNum());
		 return _OBOOK_RET_ERROR;
		 }
//print "setOrderBook ".$ORDER_NUMBER."==". $ORDER_STATUS."\n"; 
    $secInfo->nextOrderKey='';
	return _OBOOK_RET_NO_ORDER;
	}

 $ord='';
 $order_exist = 0;
 $str = "";
 
 $q="SELECT 1 as flag, UNMATCHED_VOLUME AS VOLUME FROM $tabName WHERE SESSION=".$this->session->sesid 
 	." AND ORDER_NUMBER=\"$ORDER_NUMBER\""
	." AND SECURITY_ISIN = \"$isin\""
	." AND FDATE = \'".$this->getSessionDate()."\'";
 $this->dp->setQuery(stripslashes($q)); 
 if ($this->dp->loadObject($ord)==true)
// print( 'check OB: '.$this->dp->stderr()."\n");
/* if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'check OB  : '.$OASIS_TIME."==".$ORDER_NUMBER."==".$this->dp->stderr());
		return _OBOOK_RET_ERROR;
		}
 */ 
 {
  $order_exist=$ord->flag;
  $vol_exist = $ord->VOLUME;
  if ($ORDER_STATUS!='I')
      $str = ($vol_exist==$DISCLOSED_VOLUME) ?"" :" VOLUME=$DISCLOSED_VOLUME, UNMATCHED_VOLUME=$DISCLOSED_VOLUME,";
 //if ($ORDER_NUMBER=='')
  //print("check OB  found:Vol". $ord->VOLUME);
//  $this->errorlog->addlog( 'check OB  found: '.$OASIS_TIME."==".$ORDER_NUMBER."== exist:".$order_exist);
  }
//  else if ($ORDER_NUMBER=="1372")
//  print($q."\n");
     // $this->errorlog->addlog( 'check OB Missing : '.$OASIS_TIME."==".$ORDER_NUMBER."==".$order_exist);

 $status =  ($ORDER_STATUS=='I') ?_CANCEL_ORDER :(($ORDER_STATUS=='N') ?_NOT_RELEASED_ORDER :0);
 
 $oPRICE = $PRICE * (($SIDE=='S') ?-1 :1);
 $oPRICE = ($PRICE==0 ?($SIDE=='B' ?99999 :0) :$oPRICE);
  
 
 if ($order_exist==1)
 	{														//  UNMATCHED_VOLUME=$VOLUME,
	 $q="UPDATE $tabName SET SIDE=\"$SIDE\", STATUS=$status, OASIS_TIME=\"$OASIS_TIME\", ORDER_ID=$ORDER_ID, PRICE=$oPRICE, " 
	 	.$str 
		. "UPDATES=UPDATES+1 WHERE SESSION=".$this->session->sesid
		. " AND ORDER_NUMBER=\"$ORDER_NUMBER\""
		. " AND SECURITY_ISIN = \"$isin\""
		. " AND FDATE = \'".$this->getSessionDate()."\'";
   	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
	 
	 $err=$this->dp->getAffectedRows();	
	 //if ($ORDER_STATUS=='I')
  	 	//print( 'setOrderBook update : q='.stripslashes($q)." getAffectedRows=".$err."\n");
	 
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'setOrderBook update : '.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum());
		 return _OBOOK_RET_ERROR;
		 }
	 
	 $secInfo->nextOrderKey=($status==_CANCEL_ORDER || $status==_NOT_RELEASED_ORDER) ?'' :$ORDER_NUMBER.'-'.$OASIS_TIME."-".'L'."-".$SIDE."-".$PRICE."-".$DISCLOSED_VOLUME;
	 return ($status==_CANCEL_ORDER) ?_OBOOK_RET_NO_ORDER :_OBOOK_RET_UPDATE_ORDER | _OBOOK_RET_ORDER;
 	 }
 	
 $oVOL = ($DISCLOSED_VOLUME >0) ?$DISCLOSED_VOLUME :$VOLUME;
 $q="INSERT $tabName VALUES(" .$this->session->sesid . ",\'".$OASIS_TIME ."\',\'".$this->getSessionDate()."\',\'".$isin."',".$ORDER_ID.",\'".$ORDER_NUMBER."\',\'".$SIDE."\',".$oPRICE.",".$oVOL .",". $oVOL . ",0,".$status.",0,0" . ")"; 
// print $q."\n";
//	if ($ORDER_NUMBER=='40706')
//	$this->errorlog->addlog( 'setOrderBook add OB:'. stripslashes($q));
 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
// if ($DISCLOSED_VOLUME >0)
//print( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."==".$DISCLOSED_VOLUME." err=".$err."\n");
 if ($err!=1) {
   	$this->errorlog->addlog( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum());
	//print( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."==".stripslashes($q)." err=".$this->dp->getErrorNum()."\n");
	return _OBOOK_RET_ERROR;
	}

  $secInfo->nextOrderKey=$ORDER_NUMBER.'-'.$OASIS_TIME."-".'L'."-".$SIDE."-".$PRICE."-".$DISCLOSED_VOLUME;
  //if ($ORDER_NUMBER=='36008')
    // $this->errorlog->addlog( 'setOrderBook - 35928 '.$secInfo->nextOrderKey);
  return _OBOOK_RET_ORDER;
 }

//=========================================================================================
//	Create a new BookMark - Clear BookMarks - Copy a BookMark
//=========================================================================================

function getBmarks()
{
 $q="SELECT * FROM imk_BM "
	 . " WHERE "
	 . " FDATE='".$this->getSessionDate()."'"
	 . " AND SECURITY_ISIN='".$this->getSessionIsin()."'";
 
 $this->dp->setQuery(stripslashes($q));
 $bm = $this->dp->loadObjectList();
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getBmarks sql: '.$q."==".$this->dp->stderr());
		return false;
		} 
 
  $posStart=1;
  $count=25;
  $returnValue = array(
        'recordsReturned'=>count($bm),
        'totalRecords'=>count($bm),
        'startIndex'=>$posStart,
        'sort'=>"id",
        'dir'=>"asc",
        'pageSize'=>$count,
        'records'=>$bm
    );
	
 require_once('JSON.php');
 $json = new Services_JSON();
 return ($json->encode($returnValue));
 }
 
function setBmark($code, $time,$side,$price,$volume,$trader,$type)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 
 $q="INSERT imk_BM VALUES(0," .$this->session->sesid .",\'".$this->getSessionDate() . "',\'".$isin."',\'".$code ."\',\'".$time."',\'".$side."\','order',".$price.",".$volume .",\'". $type ."','". $trader ."'" . ")"; 
// print $q."\n";
//	if ($ORDER_NUMBER=='40706')
//	$this->errorlog->addlog( 'setOrderBook add OB:'. stripslashes($q));
 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
// if ($DISCLOSED_VOLUME >0)
//print( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."==".$DISCLOSED_VOLUME." err=".$err."\n");
 if ($err!=1) {
   	$this->errorlog->addlog( 'setBmark:'.$time."==".$trader."=="." err=".$this->dp->getErrorNum());
	print( 'setBookMark add OB:'.$time."==".$trader."==".$q." err=".$this->dp->getErrorNum()."\n");
	return false;
	}
 
 $id_new = $this->dp->insertid();
 $secInfo->BM[$id_new]=array($code, $time,$side,$price,$volume,$trader,$type);
 
 return $id_new;
 }

function clearBmark($id, $all=false)
{
 $q="DELETE FROM imk_BM WHERE SESION=".$this->session->sesid . ($all==true ?"" :" AND id=$id");
 
 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
 if ($err==-1) {
   	$this->errorlog->addlog( 'clearBmark: q='.$q." err=".$this->dp->getErrorNum());
	return false;
	}
 return true;	 
 }

function copyBmark($id)
{
 $q="SELECT * FROM imk_BM WHERE id=$id";
 
 $this->dp->setQuery(stripslashes($q));
 $this->dp->loadObject($bm);
 //print_r($bm);
 $bm->SESSION=$this->session->sesid;
 $bm->id=0;
 
 $ret=$this->dp->insertObject("imk_BM",$bm);
 
 if ($ret==false) {
   	$this->errorlog->addlog( 'copyBmark:failed for id='.$id." err=".$this->dp->getErrorNum());
	print( 'copyBmark:failed for id='.$id." err=".$this->dp->getErrorNum()."\n");
	return false;
	}

 $id_new = $this->dp->insertid();
 $secInfo->BM[$id_new]=array($bm->code, $bm->time,$bm->side,$bm->price,$bm->volume,$bm->trader,$bm->type);	
 
 return $id_new;
 }
   
//=========================================================================================
//	Set OrderBook Statistics
//=========================================================================================
function setOrderBookStats($ISIN,$ORDER_ID, $OASIS_TIME, $ORDER_NUMBER, $SIDE, $PRICE, $VOLUME, $DISCLOSED_VOLUME, $ORDER_STATUS)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
 
 if ($ORDER_STATUS=='X')
 {
   	$q="UPDATE imk_OB SET STATUS=8, OASIS_TIME=\"$OASIS_TIME\" WHERE SESSION=".$this->session->sesid." AND ORDER_NUMBER=\"$ORDER_NUMBER\"";
   	$this->dp->setQuery(stripslashes($q));
 	$this->dp->query();
	$err=$this->dp->getAffectedRows();	
	
//print( 'UPDATE X  sql: '.stripslashes($q)."==".$err."\n");
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'setOrderBook OB: '.$this->dp->getErrorNum());
		 return _OBOOK_RET_ERROR;
		 }
//print "setOrderBook ".$ORDER_NUMBER."==". $ORDER_STATUS."\n"; 
    $secInfo->nextOrderKey='';
	return _OBOOK_RET_NO_ORDER;
	}

 $ord='';
 $order_exist = 0;
 $str = "";
 
 $q="SELECT 1 as flag, SESSION, OASIS_TIME, SECURITY_ISIN, ORDER_ID, ORDER_NUMBER, SIDE, PRICE, VOLUME, UNMATCHED_VOLUME, STATUS, ENTRIES, UPDATES FROM imk_OB WHERE SESSION=".$this->session->sesid ." AND ORDER_NUMBER=\"$ORDER_NUMBER\"";
 $this->dp->setQuery(stripslashes($q)); 
 if ($this->dp->loadObject($ord)==true)
 {
  $order_exist=$ord->flag;
  $vol_exist = $ord->UNMATCHED_VOLUME;
  if ($ORDER_STATUS!='I')
      $str = ($vol_exist==$DISCLOSED_VOLUME) ?"" :" VOLUME=$DISCLOSED_VOLUME, UNMATCHED_VOLUME=$DISCLOSED_VOLUME,";
  }
 
 $status =  ($ORDER_STATUS=='I') ?_CANCEL_ORDER :0;
 
 $oPRICE = $PRICE * (($SIDE=='S') ?-1 :1);
 $oPRICE = ($PRICE==0 ?($SIDE=='B' ?99999 :0) :$oPRICE);
  
 
 if ($order_exist==1)
 	{														//  UNMATCHED_VOLUME=$VOLUME,
	 $q="UPDATE imk_OB SET SIDE=\"$SIDE\", STATUS=$status, OASIS_TIME=\"$OASIS_TIME\", ORDER_ID=$ORDER_ID, PRICE=$oPRICE, " .$str . "UPDATES=UPDATES+1 WHERE SESSION=".$this->session->sesid." AND ORDER_NUMBER=\"$ORDER_NUMBER\"";
   	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
	 
	 $err=$this->dp->getAffectedRows();	
	 //if ($ORDER_STATUS=='I')
  	 	//print( 'setOrderBook update : q='.stripslashes($q)." getAffectedRows=".$err."\n");
	 
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'setOrderBook update : '.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum());
		 return _OBOOK_RET_ERROR;
		 }

// INSERT OB STATS----- 	 
	$upds=$ord->UPDATES + 1;
	$q="INSERT imk_OBS VALUES("
	 ."0"
	 .",".$ord->SESSION
	 .",\'".$OASIS_TIME
	 ."\',\'".$ISIN
	 ."\',".$ord->ORDER_ID
	 .",\'".$ord->ORDER_NUMBER
	 ."\',\'".$SIDE
	 ."\',".$oPRICE
	 .",".$DISCLOSED_VOLUME
	 .",".$ord->UNMATCHED_VOLUME
	 .",".$status
	 .",".$ord->ENTRIES
	 .",".$upds 
	 . ")";
	 
	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
 	 $err=$this->dp->getAffectedRows();
 	 if ($err!=1) {
   		$this->errorlog->addlog( 'setOrderBookStat add OB:'.$ord->SESSION."==".$OASIS_TIME."==".$ISIN."==".$ORDER_ID."==".$SIDE."==".$oPRICE."==".$DISCLOSED_VOLUME."==".$ord->UNMATCHED_VOLUME."==".$status."==".$ord->ENTRIES."==".$ord->UPDATES);
		print( 'setOrderBook add OB:'.stripslashes($q)."==".$this->dp->getErrorNum()."\n");
		return _OBOOK_RET_ERROR;
		}
		
	 $secInfo->nextOrderKey=($status==_CANCEL_ORDER) ?'' :$ORDER_NUMBER.'-'.$OASIS_TIME."-".'L'."-".$SIDE."-".$PRICE;
	 return ($status==_CANCEL_ORDER) ?_OBOOK_RET_NO_ORDER :_OBOOK_RET_UPDATE_ORDER | _OBOOK_RET_ORDER;
 	 }
	 
// INSERT OB ----- 	
 $oVOL = ($DISCLOSED_VOLUME >0) ?$DISCLOSED_VOLUME :$VOLUME;
 $q="INSERT imk_OB VALUES(" .$this->session->sesid . ",\'".$OASIS_TIME ."\',\'".$ISIN."',".$ORDER_ID.",\'".$ORDER_NUMBER."\',\'".$SIDE."\',".$oPRICE.",".$oVOL .",". $oVOL . ",".$status.",0,0" . ")"; 

 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	

 if ($err!=1) {
   	$this->errorlog->addlog( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum());
	print( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum()."\n");
	return _OBOOK_RET_ERROR;
	}
 
 // INSERT OB STATS----- 	
 $q="INSERT imk_OBS VALUES(0," .$this->session->sesid . ",\'".$OASIS_TIME ."\',\'".$ISIN."',".$ORDER_ID.",\'".$ORDER_NUMBER."\',\'".$SIDE."\',".$oPRICE.",".$oVOL .",". $oVOL . ",".$status.",0,0" . ")"; 

 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	

 if ($err!=1) {
   	$this->errorlog->addlog( 'setOrderBookStats add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum());
	print( 'setOrderBook add OB:'.$OASIS_TIME."==".$ORDER_NUMBER."=="." err=".$this->dp->getErrorNum()."\n");
	return _OBOOK_RET_ERROR;
	}

  $secInfo->nextOrderKey=$ORDER_NUMBER.'-'.$OASIS_TIME."-".'L'."-".$SIDE."-".$PRICE;
  return _OBOOK_RET_ORDER;
 }

//=========================================================================================
//	ADD MArket Depth
//=========================================================================================
function addPriceMD($row)
{
 $script='';
 
 $val = $row->PRICE * (($row->SIDE=='S') ?-1 :1);
 //$sval = sprintf("%04l",$val);
 
 $q="SELECT 1 from imk_MD WHERE price = ". $val;
 $this->dp->setQuery($q); 
 $priceExist=$this->dp->loadResult();

 $mtime = microtime(true);
 
 if ($priceExist)
	 $q="UPDATE imk_MD SET entries=entries+1, volume=volume+" . (int)$row->VOLUME*1 . ",time=\'$mtime\'"
	 	. " WHERE price=" . $val;
	else
		$q="INSERT imk_MD VALUES(" . $val.",".(int)$row->VOLUME*1 .",". 1 .",\'". $row->SIDE . "\',\'$mtime\')"; 

//$this->errorlog->addlog( 'addPriceMD: q='.$q);

 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
 if ($err==-1) {
   	$this->errorlog->addlog( 'addPriceMD: q='.$q." err=".$this->dp->getErrorNum());
	return;
	}

// if ($priceExist==1) {
 	$q="SELECT 1 from imk_MD WHERE SIDE = \'". $row->SIDE ."\' AND time=\'$mtime\' ORDER BY price LIMIT 0, ".$this->cfg->market_depth;
 	$this->dp->setQuery(stripslashes($q)); 
 	$secInfo->priceInRange=$this->dp->loadResult();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'addPriceMD  sql: '.$q."==".$this->dp->stderr());
		$secInfo->priceInRange=0;
		return $script;
		}
//	$this->errorlog->addlog( 'priceInRange  sql: '.$q);
// 	}
 
 if ($secInfo->priceInRange == 1) {
 	//if ($priceExist==1) {
	// 	$q="SELECT price AS id, price,volume,entries from imk_MD WHERE SIDE = '". $row->SIDE ."' AND price=$val";
	//	$this->errorlog->addlog( "addPriceMD = priceExists"."==".$q);
	//	}
	//else
		$q="SELECT price AS id, price".(($row->SIDE=='S') ?"*-1 AS price" :"").", volume, entries from imk_MD WHERE SIDE = '". $row->SIDE ."' ORDER BY id DESC LIMIT 0, ".$this->cfg->market_depth;
 	 
	 $q="SELECT abs( PRICE ) price, sum( UNMATCHED_VOLUME ) vol"
		. " FROM imk_OB" 
		. " WHERE side = '$row->SIDE'"
		. " AND STATUS < ". _NOT_RELEASED_ORDER
		. " GROUP BY price"
		. " ORDER BY PRICE " . ($row->SIDE=='S') ?"ASC" :"DESC";

	$this->dp->setQuery($q); 
 	$mdRows=$this->dp->loadObjectList();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'addPriceMD sql: '.$q."==".$this->dp->stderr());
		$secInfo->priceInRange=0;
		return $script;
		}
 	$r=0;
	foreach($mdRows as $mrow) {
		 $script .= ($r>0) ?"," :"";
		 if ($row->SIDE=='B')
		     $script .= "{id:\'$mrow->id\',data:['tradeOrders.php?task=md_details&side=$row->SIDE&price=$mrow->price&time=$row->OASIS_TIME&session=$this->session->sesid',$mrow->entries,$mrow->volume,$mrow->price]}";
			else 
			 $script .= "{id:\'$mrow->id\',data:['tradeOrders.php?task=md_details&side=$row->SIDE&price=$mrow->price&time=$row->OASIS_TIME&session=$this->session->sesid',$mrow->price,$mrow->volume,$mrow->entries]}";
		 $r++;
		 }
	}
//$this->errorlog->addlog( "addPriceMD = InRange".$this->priceInRange."==".$this->cfg->market_depth."==".$script);	
 return $script;		
}

/**	UPDATE MArket Depth
*
*/
function updatePriceMD($trade_no,$side,$price,$volume)
{
 $val = $price * (($side=='S') ?-1 :1);
 //$sval = sprintf("%04l",$val);
 								// No need to filter with SIDE - if price < 0 then it is SELL   //
 $q="SELECT volume from imk_MD WHERE price = ". $val;
 $this->dp->setQuery($q); 
 $priceVol=$this->dp->loadResult();

 if ( ($priceVol - (int)$volume*1)==0)
 	$q="DELETE FROM imk_MD WHERE price=" . $val;
 else {		
 		$mtime = microtime(true);
 		if ($priceVol)
			$q="UPDATE imk_MD SET entries=entries-1, volume=volume-" . (int)$volume*1 . ",time=\'$mtime\'"
	 		. " WHERE price=" . $val;
		else {
	 		 $this->errorlog->addlog( 'updatePriceMD: q='.$q." PRICE DOES NOT EXIST=".$price); 
			 return;
			 }
	}
 
//$this->errorlog->addlog( 'addPriceMD: q='.$q);

 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
 if ($err==-1) {
   	$this->errorlog->addlog( 'updatePriceMD: q='.$q." err=".$this->dp->getErrorNum());
	return;
	} 
}

//=========================================================================================
//	RETURN MArket Depth Details
//=========================================================================================
function getMarketDepthDetails($side, $time, $price)
{
 $table=$this->Msgs_collection->getMsg('IT');
 if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'getMarketDepthDetails = table is null - IT');
	     return _FETCH_RET_ERROR;
		 }
		 
 $q="SELECT ob.ORDER_ID,ob.OASIS_TIME,TRADER_ID,MEMBER_ID,CSD_ACCOUNT_ID,ob.VOLUME"
 	. "  FROM imk_IT it, imk_OB ob"
	. " WHERE ob.SIDE = \'". $side ."\' "
	. " AND ob.OASIS_TIME<=\'$time\' AND abs(ob.PRICE)=$price "
	. " AND ob.STATUS < 9"
	. " AND ob.ORDER_ID = it.ORDER_ID"
//	. " GROUP BY TRADER_ID "
	. " ORDER BY ob.OASIS_TIME";

 	$this->dp->setQuery(stripslashes($q)); 
 	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getMarketDepthDetails  sql: '.$q."==".$this->dp->stderr());
		return $script;
		}
	$rows=$this->dp->loadObjectList();
	
//$this->errorlog->addlog( 'getMarketDepthDetails  sql: '.$q);
 $script='<rows><head>
        <column width="70" type="ro" align="right" color="white" sort="str">Time</column>
        <column width="50" type="ro" align="left" color="#d5f1ff" sort="str">Trader</column>
        <column width="60" type="ro" align="left" color="#d5f1ff" sort="int">Account</column>
		<column width="50" type="ro" align="left" color="white" sort="str">Vol</column>
        <settings>
            <colwidth>px</colwidth>
        </settings>
	    </head>'; 
		
 foreach($rows as $row)
 		{		
		 $script.='<row id="'.$row->ORDER_ID.'">';
		 $str='';
		 foreach($row as $col=>$val) 
		 		{
				 if ($col=='ORDER_ID')
				     continue;
				 elseif ($col=='MEMBER_ID')
				     $val.=$row->CSD_ACCOUNT_ID;
					elseif ($col=='CSD_ACCOUNT_ID')
							continue;
				 if ($table[$col][_IDX_DECIMALS] != 0)
					{
					if ($table[$col][_IDX_DECIMALS] > 0) {
						$val = (int)$val * 1;
			    		$val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));
						}
					if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') {
			    		for ($n=0; $n<4; $n++) {
				    		if ($str!='')
					    		$str.=':';
				    		$str .= substr($val,$n*2,2);
							}
						$val = $str;
						}
					}
				  $script.='<cell>'.$val.'</cell>';
		 		 }
		 $script.='</row>';
		 }
  $script.='</rows>';
  
  return $script;		
}

//================================================================================

function sendTrades()
{
 $msgId='IS';
 
 $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgData = table is null - ' .$msgId);
	     return _FETCH_RET_ERROR;
		 }
  
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
  $phases_info = $this->markets[$this->cfg->market]->getSecPhase($isin);
  if (!is_a($phases_info,'msg_info'))  {
      $this->errorlog->addlog( 'sendTrades error msg_info - '.$isin); 
	  return _FETCH_RET_ERROR;
	  }
   
  $cp = $phases_info->get_currphase();
  $cphase_t = key($phases_info->phases[$cp]);

  if ($cp==-1) 
	 {
		$this->errorlog->addlog("<script>cmd_msg='_MARKET_CLOSED';ev_time='STOP';");
		return _FETCH_RET_ERROR;	
		}
	
  $mf = $phases_info->phases[$cp];
  
 // $start=$mf[_PHASE_SUBT] - $mf[$cphase_t];
  $start = $phases_info->getPrevPhaseStartTime();
  $otime = $phases_info->getNextPhaseStartTime();

  $sql = "SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']   //. " t.oasis_time ti, t2.oasis_time ti2,"
  	. " FROM imk_IS s"
  	. " where OASIS_TIME >= \'".$start."\' AND OASIS_TIME <= \"$otime\""
	.	" AND SECURITY_ISIN = \'".$this->getSessionIsin()."\'"
	.	" AND TRADE_DATE = \'".$this->getSessionDate()."\'";
	// . " LIMIT ".$start .",".$mf->CNTR;
//$this->errorlog->addlog( 'sendTrades : sql - '.$sql	);	
  return $this->doTheMatching($sql );	
//  $this->errorlog->addlog( 'sendTrades : script - '.$script	);
}

//=========================================================
//		updateMatchedOrders
//=========================================================
function updateMatchedOrders($trade_time,$trade_number,$tr_orders)
{
  $q="";
 }
 

//=============================================================================
//		Reverse Matching routine
//-------------------------------------
//	
//	return: status
//-------------------------------------
//=============================================================================	 
function reverseMatching($orderNum)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $actSec=$this->getSessionIsin();

 $sql="SELECT abs(PRICE) price, ORDER_NUMBER, STATUS, ENTRIES "
 	.	" FROM imk_OB "
	.	" WHERE ORDER_NUMBER = $orderNum"
	.	" AND SESSION=".$this->session->sesid
	.	" AND SECURITY_ISIN = \'".$actSec."\'"
	.	" AND FDATE = \'".$this->getSessionDate()."\'";
	
	$this->dp->setQuery(stripslashes($sql)); 
	$this->dp->loadObject($obRec); 
	
	if ($obRec==false) {
			$this->errorlog->addlog( 'reverseMatching - orderRec empty '."==".stripslashes($q));
			return 1;
			}
	
	if ($obRec->STATUS=="X" ) //&& $obRec->ENTRIES==0
	{
	 if ($obRec->UPDATES==0)
	 	$this->reverseDeleteOB($actSec,$obRec->ORDER_NUMBER);
		else
		  $this->undoUpdateOB($actSec, $obRec);
	 return _FETCH_RET_NO_TRADES;
	 }
	 
	$sql="SELECT ORDER_STATUS, ORDER_NUMBER, OASIS_TIME, SIDE, PRICE, DISCLOSED_VOLUME,MATCHED_VOLUME "
 	.	" FROM imk_IT "
	.	" WHERE ORDER_ID = ". $obRec->ORDER_ID
	. 	" AND TRADE_DATE = '". $this->getSessionDate()."'"  
	.	" AND SECURITY_ISIN = \'".$actSec."\'";
	
	$this->dp->setQuery(stripslashes($sql)); 
	$this->dp->loadObject($orderRec);
	
	$status =  ($orderRec->ORDER_STATUS=='I') ?_CANCEL_ORDER :(($orderRec->ORDER_STATUS=='N') ?_NOT_RELEASED_ORDER :0);
	
	$secInfo->nextOrderKey=($status==_CANCEL_ORDER || $status==_NOT_RELEASED_ORDER) ?'' :$orderRec->ORDER_NUMBER.'-'.$orderRec->OASIS_TIME."-".'L'."-".$orderRec->SIDE."-".$orderRec->PRICE."-".$orderRec->DISCLOSED_VOLUME;
print ($secInfo->nextOrderKey."\n");

	if ($this->doTheMatching() == _FETCH_RET_TRADES)
		{		
		 $rows = $secInfo->lastTrades;
	
		 foreach($rows as $row)
		 {
	  	  $this->reverseUpdateOB($actSec, $row->ORDER_NUMBER, $row->VOLUME);
	  	  }
		}  
print ("reverseDeleteOB -".$actSec."==".$orderRec->ORDER_NUMBER."\n");	
	$this->reverseDeleteOB($actSec,$orderRec->ORDER_NUMBER); 
 }
 
function reverseDeleteOB($actSec,$orderNumber)
{
	$q="DELETE FROM imk_OB "
		 	." WHERE SESSION=".$this->session->sesid
	   		." AND ORDER_NUMBER=\"".$orderNumber."\""
		    ." AND SECURITY_ISIN = \"$actSec\""
		    ." AND FDATE = \'".$this->getSessionDate()."\'";
		 
	$this->dp->setQuery(stripslashes($q));	
	$this->dp->query();
	$err=$this->dp->getAffectedRows();	
	
	if ($err==-1) {
   			 $this->errorlog->addlog( 'reverseMatching - Cannot Delete from OB: '.$this->dp->getErrorNum());
			 }
 }

function reverseUpdateOB($actSec, $orderNumber, $vol)
{  
  $q="UPDATE imk_OB SET  STATUS=0, UNMATCHED_VOLUME=UNMATCHED_VOLUME + $trade->VOLUME, MATCHED_VOLUME=MATCHED_VOLUME-$trade->VOLUME, ENTRIES=ENTRIES-1";
  $q .= " WHERE SESSION=".$this->session->sesid." AND SECURITY_ISIN='".$actSec."' AND ORDER_NUMBER=\"$orderNumber\"";
  $this->dp->setQuery(stripslashes($q));	
  $this->dp->query();
  $err=$this->dp->getAffectedRows();	
	
  if ($err==-1) {
  		 $this->errorlog->addlog( 'reverseMatching - Cannot Update OB: '.$this->dp->getErrorNum());
		 } 
 }  

function undoUpdateOB($actSec, $obrec)
{  
  $q="UPDATE imk_OB SET  STATUS=0, ENTRIES=ENTRIES-1";
  $q .= " WHERE SESSION=".$this->session->sesid." AND SECURITY_ISIN='".$actSec."' AND ORDER_NUMBER=".$obrec->ORDER_NUMBER;
  $this->dp->setQuery(stripslashes($q));	
  $this->dp->query();
  $err=$this->dp->getAffectedRows();	
	
  if ($err==-1) {
  		 $this->errorlog->addlog( 'reverseMatching - Cannot Update OB: '.$this->dp->getErrorNum());
		 } 
 }     
//=============================================================================
//		Matching routine
//-------------------------------------
//	
//	return: status
//-------------------------------------
//=============================================================================	 
function doTheMatching($q='')
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 if ($q=='')
 {
  $msgId='IS';  
  list($orderId,$otime,$opricetype,$oside,$oprice,$ovol)=explode("-",$secInfo->nextOrderKey);
/*		
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $this_sec=$secInfo->getIsinSec();
  $phases_info = $this->markets[$this->cfg->market]->getSecPhase($this_sec);
  if (!is_a($phases_info,'msg_info'))  {
      $this->errorlog->addlog( 'get_msgTrades error msg_info - '.$this_sec); 
	  return _TRADES_RET_ERROR;
	  }
   
  $cp = $phases_info->get_currphase();
  $cphase_t = key($phases_info->phases[$cp]);
  $mf = $phases_info->phases[$cp];
  $last_time= $mf[_PHASE_TIME_DONE];
*/
   $actSec=$this->getSessionIsin();
//print ("doTheMatching:".  $actSec."\n"); 
   $tabName = ($this->creOBStats == 1) ?"imk_OBS" :"imk_OB";
   $qside=($oside=='B' ?'S' :'B');
   if ($oprice==0)
   {
//    $mkt_price = $this->lastTrade->PRICE;
	
    
    $sql="SELECT abs(PRICE) price, SUM(VOLUME) sum from $tabName"
	.   " USE INDEX (MD)"
	.	" WHERE STATUS < "._NOT_RELEASED_ORDER
	.	" AND SESSION=".$this->session->sesid
	.	" AND SECURITY_ISIN = \'".$actSec."\'"
	.	" AND SIDE = \'$qside\'"
	.	" GROUP BY price"
	. 	" ORDER BY abs(PRICE) " . ($qside == 'B' ?'DESC' :'ASC')
	.	" LIMIT 0,2";
	
	$this->dp->setQuery(stripslashes($sql)); 
	$ob_price = $this->dp->loadObjectList();
	$mkt_price = ($ob_price[0]->price==0 || $ob_price[0]->price==99999) ?$ob_price[1]->price :$ob_price[0]->price;
   	}

	if ($this->cfg->matching != _DO_MATCHING_SETREADY)
	{
	 $condition = ($oside=="B" ?"<=" :">=");
	 $sql="SELECT ORDER_ID, ORDER_NUMBER, abs(PRICE) price, UNMATCHED_VOLUME vol "
	 .	" FROM $tabName"
	 .   " USE INDEX (MD)"	 
	 .	" WHERE STATUS < "._NOT_RELEASED_ORDER
 	 .	" AND SESSION=".$this->session->sesid
	 .	" AND SECURITY_ISIN = \'".$actSec."\'"	 
	 .	" AND SIDE = '$qside'"
	 .	" AND ABS(price) " . $condition . ($oprice==0 ?$mkt_price :abs($oprice))
	 . 	" ORDER BY abs(PRICE) " . ($qside == 'B' ?'DESC' :'ASC');
//print($sql."\n"); 	
	 $this->dp->setQuery(stripslashes($sql)); 
	 $ob_entries = $this->dp->loadObjectList();
	 if (is_array($ob_entries)!=false || count($ob_entries)>0)
	    {
		 $secInfo->ob_entries=array();
		 $i=0;
		 $vol=$ovol;
		 foreach($ob_entries as $entry)
		 {
	 	  if ($vol<=0)
		 	 break;
		  $vol = $vol-$entry->vol;
		  $secInfo->ob_entries[$i][0]=$entry->ORDER_ID;
		  $secInfo->ob_entries[$i][1]=$entry->vol;
		  $secInfo->ob_entries[$i][2]=$entry->price;
		  $i++;
		  }
//		 print(count($this->ob_entries)."\n"); 
		}
		else
			unset($secInfo->ob_entries);
	  } // matching
/*	
	if ($orderId=='62661')
	{
	 foreach($ob_price as $ob)
	 {
	  print ("OB PRICE: ".$ob->price."\n");
	  }
	 }
*/	 
   $str1=($oside=='B') ?" t.order_number=s.buy_order_number " . ($oprice==0 ?" AND s.price=".$mkt_price :" and (abs(t.price) >= abs(t2.price))") 
	                   :" t.order_number=s.sell_order_number  " . ($oprice==0 ?" AND s.price=".$mkt_price :" and abs(t.price) <= abs(t2.price)");
   $str2=($oside=='B') ?" and t2.order_number=s.sell_order_number  " . ($oprice==0 ?" AND s.price=".$mkt_price :" and t.oasis_time >= t2.oasis_time") 
	                    :" and t2.order_number=s.buy_order_number  " . ($oprice==0 ?" AND s.price=".$mkt_price :" and t.oasis_time >= t2.oasis_time");	
						
	$sql="SELECT distinct " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']     //. " t.oasis_time ti, t2.oasis_time ti2,"
	. ", t.SIDE"
	. " FROM $tabName t	USE INDEX (MD), $tabName t2 USE INDEX (MD), imk_IS s"
	. " where "
	. $str1
	. " and t.order_number=\'$orderId\' and t.oasis_time=\'$otime\' "
	. " AND t.SESSION=".$this->session->sesid
	. " AND t2.SESSION=".$this->session->sesid
	. " AND t.SECURITY_ISIN = \'".$actSec."\'"
	. " AND t2.SECURITY_ISIN = \'".$actSec."\'"	
	. " AND s.SECURITY_ISIN = \'".$actSec."\'"	
	. " and t.STATUS < "._NOT_RELEASED_ORDER 
	. " and t2.STATUS < "._NOT_RELEASED_ORDER 
	. " and t.oasis_time<=substr(s.oasis_time,1,8) "
	. ($oside=='B' ?" AND t2.side = 'S'" :" AND t2.side = 'B'")
	. " and t2.oasis_time<=substr(s.oasis_time,1,8) "
	. $str2
	. " ORDER by s.trade_number";
	
if ($orderId=='29305')	
print( 'trades sql: '.stripslashes($sql)."\n");
 }
 else {
   $sql = $q;
   print ("doTheMatching -".$sql."\n");
   }
// print ("doTheMatching -".$sql."\n");  
    $this->dp->setQuery(stripslashes($sql)); 
	$rows=$this->dp->loadObjectList();
//if ($orderId==35928)	
  //  $this->errorlog->addlog( 'trades orderid='.$orderId .' - rows: '.count($rows));	
	
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'trades sql: '.$this->dp->stderr());
		unset($secInfo->lastTrades);
		$this->errorlog->addlog( 'trades : errorId= '.$orderId." sql=".stripslashes($sql));
		return _TRADES_RET_ERROR;
		}
	//if ($orderId=='00007438')		

	if (is_array($rows)==false || count($rows)==0){

		unset($secInfo->lastTrades);
		$this->errorlog->addlog( 'Notrades : '. ' Sec=' .$actSec. ' Id= '.$orderId." sql=".stripslashes($sql));
	    return _FETCH_RET_NO_TRADES;	
		}
 $this->errorlog->addlog( "trades : rows=".count($rows)."==".$actSec. "==".$orderId."==T:".$rows[0]->TRADE_NUMBER."==".$otime."==".$opricetype."==".$oside."==oP:".$oprice."==tP:".$rows[0]->PRICE);		
//if ($rows[0]->TRADE_NUMBER=='20954')
// print (stripslashes($sql)."\n");
//if ($orderId=='00007013')			
//$this->errorlog->addlog( 'trades found orderid='.$orderId.'-'.$otime.' =T='.$rows[0]->TRADE_NUMBER .' - rows: '.count($rows).' q='.$sql);	
//    print("<rows total_count='".$totalCount."' pos='".$posStart."'>");
	if ($this->reverseMatchingFlag)
	{
		if ( $secInfo->Read_trades >0)
			$secInfo->Read_trades-=count($rows);
	  }
	  else
		 $secInfo->Read_trades+=count($rows);
	
	$this->tradesScript = '';
	$secInfo->lastTrades=$rows;
	
	return _FETCH_RET_TRADES;
 }

//=============================================================================
//		get Matched Orders of a Trade
//-------------------------------------
//	
//	
//-------------------------------------
//=============================================================================	  
function getMatchedOrders($row)
{
// ---- get some info from the trading orders for graph Trading Chart ---- //
	    $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
		$actSec=$this->getSessionIsin();
		
		$tabName = ($this->creOBStats == 1) ?"imk_OBS" :"imk_OB";
	
		$q="SELECT DISTINCT itb.ORDER_ID bid,itb.SIDE bside,itb.OASIS_TIME btime,bid.ORDER_NUMBER border,bid.PRICE bprice,bid.UNMATCHED_VOLUME bvol,ita.ORDER_ID aid,ita.OASIS_TIME atime,ita.SIDE aside,ask.ORDER_NUMBER aorder,abs(ask.PRICE) aprice,ask.UNMATCHED_VOLUME avol"  
			. " FROM $tabName as bid, $tabName as ask, imk_IT as itb, imk_IT as ita " 
			. " WHERE bid.OASIS_TIME<=\'$row->OASIS_TIME\' AND ask.OASIS_TIME <= \'$row->OASIS_TIME\' AND bid.ORDER_NUMBER=\'$row->BUY_ORDER_NUMBER\'"
			. " AND bid.SESSION=".$this->session->sesid
			. " AND ask.SESSION=".$this->session->sesid	
			. " AND ita.SECURITY_ISIN = \'".$actSec."\'"
			. " AND itb.SECURITY_ISIN = \'".$actSec."\'"
			. " AND ask.SECURITY_ISIN = ita.SECURITY_ISIN "	
			. " AND bid.SECURITY_ISIN = itb.SECURITY_ISIN "	
			. " AND ask.ORDER_NUMBER=\'$row->SELL_ORDER_NUMBER\'"
			. " AND ask.ORDER_NUMBER=ita.ORDER_NUMBER"
			. " AND ask.OASIS_TIME=ita.OASIS_TIME"
			. " AND bid.OASIS_TIME=itb.OASIS_TIME"
			. " AND bid.ORDER_NUMBER=itb.ORDER_NUMBER";

			//. " AND bid.STATUS < 9"
			//. " AND ask.STATUS < 9";
		
		$this->dp->setQuery(stripslashes($q)); 
		
		$secInfo->tr_orders=$this->dp->loadAssocList();
	//	$this->errorlog->addlog( 'tr_orders sql: '.$q." ===".count($this->tr_orders));
		if (count($secInfo->tr_orders)==0)
			$this->errorlog->addlog( 'tr_orders empty '."==".stripslashes($q)."==".$this->dp->stderr());
		  else {		// if the order is BUY then  //
		  		if ($this->tr_orders->SIDE=='B') {
					 $price=$secInfo->tr_orders->aprice;
					 $volume=$secInfo->tr_orders->avol;
					 $side='S';
					 }
				else {		// if the order is SELL then  //
					  $price=$secInfo->tr_orders->bprice;
					  $volume=$secInfo->tr_orders->bvol;
					  $side='B';
					  }
		    	//$this->updatePriceMD($row->TRADE_NUMBER,$side,$price,$volume);
				//$this->updateMatchedOrders($row->OASIS_TIME,$row->TRADE_NUMBER,$tr_orders);
				}
 }

//=============================================================================
//		pack a Trade
//-------------------------------------
//	table: record description (IT,IS)
//	row: trade for packaging to XML
//-------------------------------------
//=============================================================================	 
function packTrade($table, $row, &$r)
{
 global $desc_r;
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 //print_r($secInfo->members);	
  
 if ($this->tradesScript=='') {
	  $this->tradesScript='{"rows":['; // :"<rows>"; // total_count=$count pos=$posStart //"{rows:[";
	  $this->tradesScript.="";
	  }
	  else
		$this->tradesScript.=",";	

   $excol=array('SIDE', 'TRADE_STATUS','SELL_M_C_FLAG');		
		
//		$this->tradesScript.=($r>0) ?"," :"";
		$this->tradesScript.='{"id":"'.addslashes($row->TRADE_NUMBER).'","data":[{';	
		$r--;	
		  //else	
		    // $script .= ",";

		foreach($row as $col=>$val)
		{
			if (in_array($col,$excol))
				continue;
			if ($table[$col][_IDX_HEADER] != 3)
				continue;
				
			if ( $i ) 
			    $this->tradesScript .= ',';			
		// ===Format fields=== //
			if ($val=='')
				$val=' ';
		
			if ($table[$col][_IDX_ID]=="FLAGS")
				$val.= $row->SELL_M_C_FLAG;
				
			  elseif ($table[$col][_IDX_ID]=="SIDE")
			    $val = $desc_r[$table[$col][_IDX_TITLE]][$val];
			
				elseif ($table[$col][_IDX_ID]=="BUY_TRADER_ID")
				{
			 	$bmemId=mb_substr($val,0,2, 'UTF-8');
					$secInfo->members["B"][$bmemId][1] += $row->VOLUME;
					$secInfo->members["B"][$bmemId][2] = round((float)(($secInfo->members["B"][$bmemId][1] * 100) / $secInfo->members["B"][$bmemId][0]),3);
					}

				elseif ($table[$col][_IDX_ID]=="SELL_TRADER_ID")
				{
				 $smemId=mb_substr($val,0,2, 'UTF-8');
					$secInfo->members["S"][$smemId][1] += $row->VOLUME;
					$secInfo->members["S"][$smemId][2] = round((float)(($secInfo->members["S"][$smemId][1] * 100) / $secInfo->members["S"][$smemId][0]),3);
//					 $this->errorlog->addlog("memberSEll: ".$smemId."==".$secInfo->members["S"][$smemId][0]."==".$secInfo->members["S"][$smemId][1]."==".$secInfo->members["S"][$smemId][2]);
					}		
			
				elseif ($table[$col][_IDX_ID]=="TYPE") {
			//print("packTrade ".$col."==".$table[$col][_IDX_TITLE]."\n");
			    	$val = $desc_r[$table[$col][_IDX_TITLE]][$val];
					}
				
			if ($table[$col][_IDX_DECIMALS] != 0)
			{
				$val = (int)$val * 1;

			if ($table[$col][_IDX_DECIMALS] > 0)
			    $val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));

			if ($table[$col][_IDX_DECIMALS] == -3 && $val!='')
				$val = $this->formatTimeStr($val);
			}		
			$i++;
			$this->tradesScript .= '"'.$table[$col][_IDX_ID].'":'.'"'.$val.'"'; // :"<cell>$val</cell>"; 		  
			}
		// Add Orders info for Graph Trading Chart//
/*		if (count($this->tr_orders)>0) 
		{
		foreach($this->tr_orders[0] as $col=>$val)
		{	
			$this->tradesScript .= ',';	
		//	$val = (int)$val * 1;
			if (substr($col,1)=='price' || substr($col,1)=='vol')
				$val = (int)($val *1) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
			$this->tradesScript .= "'$val'";
			}
		 }
		 else
		    $this->errorlog->addlog( 'packTrade - tr_orders empty '."==".$row->TRADE_NUMBER);
*/			
		// Add  Lowest and Highest prices
		//$this->tradesScript .= ',' .((int)$this->lowest) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
		//$this->tradesScript .= ',' .((int)$this->highest) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
		
		$this->tradesScript .= ',"' ."MBVAL".'":"'.$secInfo->members["B"][$bmemId][1].'"';
		$this->tradesScript .= ',"' ."MBPCT".'":"'.$secInfo->members["B"][$bmemId][2].'"';
		$this->tradesScript .= ',"' ."MSVAL".'":"'.$secInfo->members["S"][$smemId][1].'"';
		$this->tradesScript .= ',"' ."MSPCT".'":"'.$secInfo->members["S"][$smemId][2].'"';
		$this->tradesScript .= "}]}"; // :"</row>"; //']}';	
//    	}
	if ($r==0)
	    $this->tradesScript.="]}";
	
 }

//**************************************************************
//     pack Useful Prices to JSON Format
//--------------------------------------
//    $table: IS msg template
//--------------------------------------
//***************************************************************
function packHiLo($table, $sendTotals=false)
{
	$secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  	$isin=$secInfo->getIsinSec();
	$info = $this->markets[$this->cfg->market]->getSecPhase($isin);
		
	$this->hiloScript='{"rows":[{"id":"'.$isin.'","data":[{'; 
	$this->hiloScript .= '"lo":'.((int)$secInfo->lowest) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	$this->hiloScript .= ',' .'"hi":'.((int)$secInfo->highest) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	$this->hiloScript .= ',' .'"trade":'.((int)$secInfo->tradePrice) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	$this->hiloScript .= ',' .'"last":'.((int)$secInfo->lastTrade->PRICE) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));

	$this->hiloScript .= ',' .'"bprice":'.((int)$secInfo->tr_orders[0]["bprice"]) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	$this->hiloScript .= ',' .'"aprice":'.((int)$secInfo->tr_orders[0]["aprice"]) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	
	$this->hiloScript .= ',' .'"bid":'.((int)$secInfo->bid_1) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	$this->hiloScript .= ',' .'"ask":'.((int)$secInfo->ask_1) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));	
	
	if ($sendTotals==true) {
		$this->hiloScript .= ',' .'"rtrades":'.((int)$secInfo->Read_trades);
		$this->hiloScript .= ',' .'"rorders":'.((int)$info->countPhasesOrders());	
		$this->hiloScript .= ',' .'"price":'.((int)$secInfo->startPrice) / (pow(10,$table['PRICE'][_IDX_DECIMALS]));
	}
	
	$this->hiloScript.="}]}]}";
 }
 		
//***************************************************************
//	update Lo/Hi Price after a Trade
//-------------------------------------
//	PRICE: Trade price
//-------------------------------------
//*************************************************************** 
function updateLoHiPrice ( $PRICE )
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $secInfo->tradePrice = $PRICE;
 
 $secInfo->lowest = min((int)$PRICE, $this->lowest); 
 $secInfo->highest = max((int)$PRICE, $this->highest); 
 }
 
//***************************************************************
//		for every Trade Update OB
//-------------------------------------
//	row: Trade record
//-------------------------------------
//***************************************************************

function tradeUpdateOB(&$trade)
{
 $tabName = ($this->creOBStats == 1) ?"imk_OBS" :"imk_OB";
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 $q="SELECT b.OASIS_TIME b_time,b.ORDER_NUMBER b_order,b.UNMATCHED_VOLUME b_vol,b.PRICE bprice,s.OASIS_TIME s_time, s.ORDER_NUMBER s_order, s.UNMATCHED_VOLUME s_vol,s.PRICE sprice"
	. " FROM $tabName AS b, $tabName AS s"
	. " WHERE "
	. " b.SESSION=".$this->session->sesid." AND  s.SESSION=".$this->session->sesid
	. " AND b.SECURITY_ISIN=\'".$this->getSessionIsin()."\' AND  s.SECURITY_ISIN='".$this->getSessionIsin()."\'"	
	. " AND b.OASIS_TIME<=\'$trade->OASIS_TIME\' AND s.OASIS_TIME <= \'$trade->OASIS_TIME\' "
	. " AND b.ORDER_NUMBER='$trade->BUY_ORDER_NUMBER'"
	. " AND s.ORDER_NUMBER='$trade->SELL_ORDER_NUMBER'"
	. " AND b.STATUS < "._NOT_RELEASED_ORDER
	. " AND s.STATUS < "._NOT_RELEASED_ORDER;

//print(stripslashes($q)."\n");
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
 if (empty($rows) || count($rows)==0) {
     $this->errorlog->addlog( 'tradeUpdateOB: No more orders T='.$trade->OASIS_TIME." B=".$trade->BUY_ORDER_NUMBER." S=".$trade->SELL_ORDER_NUMBER."=e=".$this->dp->stderr());
	 //print( 'tradeUpdateOB: empty QUERY q='.stripslashes($q)."\n");
     return _OBOOK_RET_NO_ORDER;
	 }

 foreach($rows as $row)	
 {
  if ($secInfo->lastOrders[0]->ORDER_NUMBER == $row->b_order && $secInfo->lastOrders[0]->PRICE==0 && $trade->VOLUME < $secInfo->lastOrders[0]->VOLUME )
      $this->marketTradeUnmatched=$secInfo->lastOrders[0]->ORDER_NUMBER;
	 elseif ($secInfo->lastOrders[0]->ORDER_NUMBER == $row->s_order && $secInfo->lastOrders[0]->PRICE==0 &&  $trade->VOLUME < $secInfo->lastOrders[0]->VOLUME)
      		 $this->marketTradeUnmatched=$secInfo->lastOrders[0]->ORDER_NUMBER; 
		else
		   $this->marketTradeUnmatched=false;	 
 //if($row->b_order=='35928')
   // $this->errorlog->addlog( 'tradeUpdateOB: B='.$row->b_order.' sV='.$row->s_vol.' bUnMv='.$row->b_vol." == ".$trade->VOLUME. "== bT:".$row->bprice. "== sT:".$row->s_time);	 
//OASIS_TIME=$trade->OASIS_TIME,	OASIS_TIME=$trade->OASIS_TIME,
   $q_b="UPDATE $tabName SET  UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME, MATCHED_VOLUME=MATCHED_VOLUME+$trade->VOLUME, ENTRIES=ENTRIES+1";
   $q_s="UPDATE $tabName SET  UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME, MATCHED_VOLUME=MATCHED_VOLUME+$trade->VOLUME, ENTRIES=ENTRIES+1";
   
   
   if ($row->b_vol - $trade->VOLUME == 0)
   		 $q_b .= ", STATUS=9, ORDER_ID=".$secInfo->tr_orders[0]["bid"];  //, OASIS_TIME=\"$trade->OASIS_TIME\"
   $q_b .= " WHERE SESSION=".$this->session->sesid." AND SECURITY_ISIN='".$this->getSessionIsin()."' AND ORDER_NUMBER=\"$row->b_order\"";
   
   if ($row->s_vol - $trade->VOLUME == 0)
	   	 $q_s .= ", STATUS=9, ORDER_ID=".$secInfo->tr_orders[0]["aid"];	 //, OASIS_TIME=\"$trade->OASIS_TIME\" 
   $q_s .= " WHERE SESSION=".$this->session->sesid." AND SECURITY_ISIN='".$this->getSessionIsin()."' AND ORDER_NUMBER=\"$row->s_order\"";
/*		
   if ($row->b_vol < $row->s_vol) {
        $q ="UPDATE imk_OB SET STATUS=9, OASIS_TIME=\"$row->b_time\" WHERE SESSION=$this->session AND ORDER_NUMBER=\"$row->b_order\"";
		$q2="UPDATE imk_OB SET UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME WHERE SESSION=$this->session AND ORDER_NUMBER=\"$row->s_order\"";
		}

   if ($row->b_vol > $row->s_vol) {
        $q="UPDATE imk_OB SET STATUS=9, OASIS_TIME=\"$row->s_time\" WHERE SESSION=$this->session AND ORDER_NUMBER=\"$row->s_order\"";   
		$q2="UPDATE imk_OB SET UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME WHERE SESSION=$this->session AND ORDER_NUMBER=\"$row->b_order\"";
		}

   if ($row->b_vol == $row->s_vol)
        $q="UPDATE imk_OB SET STATUS=9, UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME, OASIS_TIME=\"$trade->OASIS_TIME\""
			. " WHERE SESSION=$this->session AND (ORDER_NUMBER=\'$row->b_order\' || ORDER_NUMBER=\'$row->s_order\')";   
*/   
   
   $this->dp->setQuery(stripslashes($q_b)); 
   $this->dp->query(); 

   $err=$this->dp->getAffectedRows();	
   if ($err!=1) {
   		$this->errorlog->addlog( 'tradeUpdateOB: q='.stripslashes($q_b)." err=".$this->dp->getErrorNum());
		print( 'tradeUpdateOB: q='.stripslashes($q_b)." err=".$this->dp->getErrorNum()."\n");
		return _OBOOK_RET_UPDATE_ORDER;
		} 
	
  $this->dp->setQuery(stripslashes($q_s)); 
  $this->dp->query(); 

  $err=$this->dp->getAffectedRows();	
  if ($err!=1) {
   	  $this->errorlog->addlog( 'tradeUpdateOB: q2='.stripslashes($q_s)." err=".$this->dp->getErrorNum());

	  return _OBOOK_RET_UPDATE_ORDER;
	  }
  $bp = new stdClass;
  $bp->BID=($row->bprice==99999 ?0 :$row->bprice);
  $sp=new stdClass;
  $sp->ASK=($row->sprice * -1);
  $trade=(object)array_merge((array)$trade,(array)$bp,(array)$sp);	  
//print_r($trade);
//print "\n";
  unset($bp,$sp);
  }
  
  return _OBOOK_RET_ORDER;	
//   $this->errorlog->addlog( 'tradeUpdateOB = ' .$row->ORDER_NUMBER . "==".$row->UNMATCHED_VOLUME);
  
  
/*  
. ($row->SIDE=='B' ?"b" :"s"). ".ORDER_NUMBER=\"$ORDER_NUMBER\""

 
			
 $q="UPDATE imk_OB SET entries=entries+1, volume=volume+" . (int)$row->VOLUME*1 . ",time=\'$mtime\'"
	 	. " WHERE price=" . $val;
	else
		$q="INSERT imk_MD VALUES(" . $val.",".(int)$row->VOLUME*1 .",". 1 .",\'". $row->SIDE . "\',\'$mtime\')"; 

//$this->errorlog->addlog( 'addPriceMD: q='.$q);

 $this->dp->setQuery(stripslashes($q));
 $this->dp->query();
 $err=$this->dp->getAffectedRows();	
 if ($err==-1) {
   	$this->errorlog->addlog( 'addPriceMD: q='.$q." err=".$this->dp->getErrorNum());
	return;
	}
*/	
 }

//***************************************************************
//		for every Trade Update OB and OB Stats
//-------------------------------------
//	row: Trade record
//-------------------------------------
//***************************************************************

function tradeUpdateOBStats($trade)
{
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
  
 $q="SELECT b.OASIS_TIME b_time,b.STATUS b_status,b.ORDER_NUMBER b_order,b.ORDER_ID b_order_id,b.UNMATCHED_VOLUME b_vol,b.ENTRIES b_entries,b.UPDATES b_updates,s.OASIS_TIME s_time,s.STATUS s_status,s.ORDER_NUMBER s_order,s.ORDER_ID s_order_id, s.UNMATCHED_VOLUME s_vol,s.UPDATES s_updates, s.ENTRIES s_entries"
	. " FROM imk_OB AS b, imk_OB AS s"
	. " WHERE b.SESSION=".$this->session->sesid." AND  s.SESSION=".$this->session->sesid
	. " AND b.OASIS_TIME<=\'$trade->OASIS_TIME\' AND s.OASIS_TIME <= \'$trade->OASIS_TIME\' "
	. " AND b.ORDER_NUMBER='$trade->BUY_ORDER_NUMBER'"
	. " AND s.ORDER_NUMBER='$trade->SELL_ORDER_NUMBER'"
	. " AND b.STATUS < "._NOT_RELEASED_ORDER
	. " AND s.STATUS < "._NOT_RELEASED_ORDER;

//print(stripslashes($q)."\n");
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
 if (empty($rows) || count($rows)==0) {
     $this->errorlog->addlog( 'tradeUpdateOB: No trades q='.stripslashes($q));
//	 print( 'tradeUpdateOB: empty QUERY q='.stripslashes($q)."\n");
     return _OBOOK_RET_NO_ORDER;
	 }

 foreach($rows as $row)	
 {

   $q_b="UPDATE imk_OB SET  UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME, ENTRIES=ENTRIES+1";
   $q_s="UPDATE imk_OB SET  UNMATCHED_VOLUME=UNMATCHED_VOLUME - $trade->VOLUME, ENTRIES=ENTRIES+1";
   
   
   if ($row->b_vol - $trade->VOLUME == 0)
   		 $q_b .= ", STATUS=9, ORDER_ID=".$secInfo->tr_orders[0]["bid"];  //, OASIS_TIME=\"$trade->OASIS_TIME\"
   $q_b .= " WHERE SESSION=".$this->session->sesid." AND ORDER_NUMBER=\"$row->b_order\"";
   
   if ($row->s_vol - $trade->VOLUME == 0)
	   	 $q_s .= ", STATUS=9, ORDER_ID=".$secInfo->tr_orders[0]["aid"];	 //, OASIS_TIME=\"$trade->OASIS_TIME\" 
   $q_s .= " WHERE SESSION=".$this->session->sesid." AND ORDER_NUMBER=\"$row->s_order\"";

   $this->dp->setQuery(stripslashes($q_b)); 
   $this->dp->query(); 

   $err=$this->dp->getAffectedRows();	
   if ($err!=1) {
   		$this->errorlog->addlog( 'tradeUpdateOB: q='.stripslashes($q_b)." err=".$this->dp->getErrorNum());
		
		return _OBOOK_RET_UPDATE_ORDER;
		} 
	
  $this->dp->setQuery(stripslashes($q_s)); 
  $this->dp->query(); 

  $err=$this->dp->getAffectedRows();	
  if ($err!=1) {
   	  $this->errorlog->addlog( 'tradeUpdateOB: q2='.stripslashes($q_s)." err=".$this->dp->getErrorNum());

	  return _OBOOK_RET_UPDATE_ORDER;
	  }
  $vol=$row->b_vol - $trade->VOLUME;
  $stat=(($vol == 0) ?'9' :$row->b_status);
  $entries=$row->b_entries+1;
 
  $q="INSERT imk_OBS VALUES("
	 ."0"
	 .",".$this->session->sesid
	 .",\'".$row->b_time."\',\'".$isin
	 ."\',".$row->b_order_id.",\'".$row->b_order
	 ."\',\'"."B"
	 ."\',".$trade->PRICE
	 .",".$row->b_vol
	 .",".$vol
	 .",".$stat 
	 .",".$entries 
	 .",".$row->b_updates 
	 . ")";

	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
 	 $err=$this->dp->getAffectedRows();
	 if ($err!=1){ 
	  //    print( 'tradeInsOBS: qB='.stripslashes($q)." err=".$this->dp->getErrorNum()."\n");
   	      $this->errorlog->addlog( 'tradeInsOBS: qB='.stripslashes($q)." err=".$this->dp->getErrorNum());
		   return _OBOOK_RET_UPDATE_ORDER;
		  }
	  
  $vol=$row->s_vol - $trade->VOLUME;
  $stat=(($vol == 0) ?'9' :$row->s_status);
  $entries=$row->s_entries+1;
   $q="INSERT imk_OBS VALUES("
	 ."0"
	 .",".$this->session->sesid
	 .",\'".$row->s_time."\',\'".$isin
	 ."\',".$row->s_order_id.",\'".$row->s_order
	 ."\',\'"."S"
	 ."\',".$trade->PRICE*-1
	 .",".$row->s_vol
	 .",".$vol
	 .",".$stat 
	 .",".$entries 
	 .",".$row->s_updates 
	 . ")";
	 
	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
 	 $err=$this->dp->getAffectedRows();
	 if ($err!=1){ 
	//	  print( 'tradeInsOBS: qS='.stripslashes($q)." err=".$this->dp->getErrorNum()."\n");
   	      $this->errorlog->addlog( 'tradeInsOBS: qS='.stripslashes($q)." err=".$this->dp->getErrorNum());
		   return _OBOOK_RET_UPDATE_ORDER;
		  }
  }	 
	 
  return _OBOOK_RET_ORDER;	
 }
 

	 
function getPhasesArray()
{
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $this_sec=$secInfo->getIsinSec();
//  print "getPhasesArray=".$this_sec."\n";
  return $this->markets[$this->cfg->market]->getSecPhase($this_sec);
  }
  
function getTradersGraph($side)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 $q="SELECT concat( t.trader_ID, '-', t.csd_account_id ) acc, sum( o.VOLUME - o.UNMATCHED_VOLUME ) ovol"
	.	" FROM imk_IT t, imk_OBS o"
	.	" WHERE o.ORDER_ID = t.ORDER_ID"
	.	" AND o.SECURITY_ISIN='".$this->getSessionIsin()."'"
	.	" AND o.FDATE='".$this->getSessionDate()."'"
	.	" AND STATUS !=10"
	.	" AND o.side = \"$side\""
//	.	" AND ORDER_MOD >0"
	.	" AND t.order_status != 'X'"
	.	" AND o.MATCHED_VOLUME > 0"
//	.	" AND abs( o.price ) = t.price"
	.	" GROUP BY 1 "
	.	" ORDER BY 2 DESC";
	
 $this->dp->setQuery(stripslashes($q)); 
 $secInfo->TradersGraph=$this->dp->loadObjectList();
//print(count($this->TradersGraph)."==".$q."\n");	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getHistoryTrades sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 return true;
 }
 
 function indexOf($haystack,$needle)
 {
  for($i=0,$z=count($haystack);$i<$z;$i+=1)
	  if ($haystack[$i] == $needle)        // a value and returns the index of the *first*
          return $i; 
   return false;
  }
   
/*
* Get All trades plus marked for Survailance
* @param	string msgId record type (IT,IS), int posStart: next record position ( for multiple ajax calls), int msg_num: number of limit records
* @version 2.0.1
* @author JL
*/
function getTraders($msgId='IT', $format, $posStart, $count, $results, $sort, $dir, $trader)
{
   $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
   $isin=$secInfo->getIsinSec();
	 
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgOrders = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
	 $new_posStart = $posStart;
	 //print("results=".$results."\n");
	 if(results==0) {
		$sql = "SELECT count( * ) "
			.	" FROM ("
			.	" SELECT concat( t.trader_ID, \'-\', CSD_ACCOUNT_ID ) Trader,"
			.	"  sum(CASE WHEN o.side = \'B\' THEN o.MATCHED_VOLUME ELSE 0 END ) bvol,"
			.	"  sum(CASE WHEN o.side = \'S\' THEN o.MATCHED_VOLUME ELSE 0 END ) svol"
			.	" FROM imk_IT t, imk_OBS o"
			.	" WHERE o.ORDER_ID = t.ORDER_ID"
			.	" AND STATUS !=10"
			.	" AND o.SECURITY_ISIN = \"$isin\" "
			.	" AND o.FDATE='".$this->getSessionDate()."'"
			.	" AND t.order_status != \'X\'"
			.	" AND ("
			.	" CASE WHEN abs( o.price ) =99999  THEN 0 ELSE abs( o.price ) END ) = t.price"
			.	" GROUP BY 1 "
			.	" )m"
			.	" WHERE (bvol >0 OR svol >0)";
			
		 
		$this->dp->setQuery(stripslashes($sql)); 
 		$totalCount = $this->dp->loadResult();
	
		//$count = $totalCount;
		//$posStart = 0;	
		}
		
		$limitStr = " LIMIT ".$new_posStart . "," . $count;
	// }
/* 						-------> DURING TRADING ORDERS
 $sql = "SELECT ob.status status, ob.VOLUME - ob.UNMATCHED_VOLUME vol," . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
		. " FROM imk_$msgId t, imk_OB ob "
		. " WHERE ob.ORDER_ID=t.ORDER_ID AND t.SECURITY_ISIN = ob.SECURITY_ISIN "
 		. " LIMIT ".$posStart . "," . $count;
*/		
		$sql = "SELECT ("
			.	" CASE WHEN st.trader IS NULL THEN '' ELSE 'checked' END )surv,"
			.	" m.Trader, m.bvol, m.svol, m.bvol + m.svol tot, sum( ms.volume ) mktvol, "
			.	" ((m.bvol + m.svol) *100) / sum( ms.volume ) mktpct"
			.	" FROM ("
			.	" SELECT concat( t.trader_ID, '-', CSD_ACCOUNT_ID ) Trader, "
			.	" sum(CASE WHEN o.side = 'B' THEN o.MATCHED_VOLUME ELSE 0 END ) bvol, "
			.	" sum(CASE WHEN o.side = 'S' THEN o.MATCHED_VOLUME ELSE 0 END ) svol, o.side"
			.	" FROM imk_IT t, imk_OBS o"
			.	" WHERE o.ORDER_ID = t.ORDER_ID"
			.	" AND STATUS !=10"
			.	" AND o.SECURITY_ISIN = \"$isin\" "
			.	" AND o.FDATE='".$this->getSessionDate()."'"
			.	" AND t.order_status != 'X'"
			.	" AND (CASE WHEN abs( o.price ) =99999 THEN 0 ELSE abs( o.price ) END ) = t.price"
			.	" GROUP BY 1 "
			.	" )m"
			.	" LEFT OUTER JOIN imk_ST AS st ON st.trader = m.Trader"
			.	" LEFT OUTER JOIN imk_MS AS ms ON ms.trader_id = m.Trader"
			.	" WHERE ( m.bvol >0 OR m.svol >0)"
			.	" GROUP BY trader"
			.	" ORDER BY ".$sort . " " . $dir
	 		. 	$limitStr;
	
//print( 'getHistoryTrades sql: '.$sql); 

 $script = "<rows total_count='".$totalCount."' pos='".$posStart."'>";
 
 $this->dp->setQuery(stripslashes($sql)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getHistoryTrades sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 
  /** JSON   *@version --------------------------------------------------------------*/
 if ($format=='JSON') {
     // Invalid start value
    if(is_null($posStart) || !is_numeric($posStart) || ($posStart < 0)) {
        // Default is zero
        $posStart = 0;
    }
    // Valid start value
    else {
        // Convert to number
        $posStart += 0;
    }

    // Invalid results value
    if(is_null($count) || !is_numeric($count) ||
            ($count < 1) || ($results >= $totalCount)) {
        // Default is all
        $count = $totalCount;
    }
    // Valid results value
    else {
        // Convert to number
        $count += 0;
    }
    
    // Create return value
    $returnValue = array(
        'recordsReturned'=>count($rows),
        'totalRecords'=>$totalCount,
        'startIndex'=>$posStart,
        'sort'=>$sort,
        'dir'=>$dir,
        'pageSize'=>$count,
        'records'=>$rows
    );

    // JSONify
    //print json_encode($returnValue);

    // Use Services_JSON

    require_once('JSON.php');
    $json = new Services_JSON();
    return ($json->encode($returnValue)); 
	}
//------JSON------------END----------------------
}

/***************************************************************
*		Get All trades plus marked for Survailance
*-------------------------------------
*	msgId: record type (IT,IS)
*	posStart: next record position ( for multiple ajax calls)
*	msg_num: number of limit records
*-------------------------------------
***************************************************************/
function getOrderBook($msgId='IT', $format, $posStart, $count, $results, $sort, $dir, $trader)
{
   $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
   $isin=$secInfo->getIsinSec();
	 
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'getOrderBook = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
	 $new_posStart = $posStart;
	 if(results==0) {
	 	
		
		$sql = "SELECT count( * )" 
		. " FROM ("
		. " SELECT count( * ) "
		. " FROM `imk_OB` "
		. " WHERE STATUS <8 "
		. " AND fdate='".$this->getSessionDate()."'"
 	 	. " AND security_isin='".$isin."'"
		. " GROUP BY price"
		. " )m ";
			
		 
		$this->dp->setQuery(stripslashes($sql)); 
 		$totalCount = $this->dp->loadResult();
	
		//$count = $totalCount;
		//$posStart = 0;	
		}
		
	$limitStr = " LIMIT ".$new_posStart . "," . $count;
		
	$q="SELECT OASIS_TIME BTIME, count(ENTRIES) BENTRIES, sum( UNMATCHED_VOLUME ) BVOL,  abs(PRICE) BPRICE"  //(CASE WHEN PRICE=99999 THEN 0 ELSE PRICE END)
		. " FROM imk_OB" 
		. " WHERE SIDE = 'B'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " AND fdate='".$this->getSessionDate()."'"
 	 	. " AND security_isin='".$isin."'"
		. " GROUP BY price"
		. " ORDER BY price DESC, OASIS_TIME"
		. $limitStr;

	$this->dp->setQuery($q); 
 	$mdbRows=$this->dp->loadObjectList();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getOrderBook sql: '.$q."==".$this->dp->stderr());
		print('getOrderBook sql: '.$q."==".$this->dp->stderr()."\n");
		return;
		}
	
//	$other_side = ($side=='S' ?'B' :'S');
	
	$q="SELECT  abs( PRICE ) SPRICE, sum( UNMATCHED_VOLUME ) SVOL, count(ENTRIES) SENTRIES,OASIS_TIME STIME"
		. " FROM imk_OB" 
		. " WHERE SIDE = 'S'"
		. " AND STATUS < "._NOT_RELEASED_ORDER
		. " AND fdate='".$this->getSessionDate()."'"
 	 	. " AND security_isin='".$isin."'"
		. " GROUP BY price"
		. " ORDER BY price DESC, OASIS_TIME"
		. $limitStr;

	$this->dp->setQuery($q); 
 	$mdsRows=$this->dp->loadObjectList();
	if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getOrderBook sql: '.$q."==".$this->dp->stderr());
		print('getOrderBook sql: '.$q."==".$this->dp->stderr()."\n");
		return;
		}
		
    //$rows = array_combine($mdbRows,$mdsRows);
	$len = max(count($mdbRows),count($mdsRows));
	$rows=array();
/*	$bp = new stdClass;
  $bp->BID=($row->bprice==99999 ?0 :$row->bprice);
  $sp=new stdClass;
  $sp->ASK=($row->sprice * -1);
  $trade=(object)array_merge((array)$trade,(array)$bp,(array)$sp);	
*/  
//	print_r((array)$mdsRows);
	for($i=0; $i < $len; $i++) {
		list($mrow_key, $mrow) = @each($mdbRows);
		list($srow_key, $srow) = @each($mdsRows);
$obj=(object)array_merge((array)$mrow,(array)$srow);

		$cnt=array_push($rows,$obj); //,$mrow->BTIME,$mrow->BENTRIES,$mrow->BVOL,$mrow->BPRICE,$srow->SPRICE,$srow->SVOL,$srow->SENTRIES,$srow->STIME);
		//print($obj->BPRICE."==".$obj->SPRICE."==".$cnt."\n");
		
		}
// print_r($mdbRows);
// print_r($mdsRows);
  /// JSON  version--------------------------------------------------------------
 if ($format=='JSON') {
     // Invalid start value
    if(is_null($posStart) || !is_numeric($posStart) || ($posStart < 0)) {
        // Default is zero
        $posStart = 0;
    }
    // Valid start value
    else {
        // Convert to number
        $posStart += 0;
    }

    // Invalid results value
    if(is_null($count) || !is_numeric($count) ||
            ($count < 1) || ($results >= $totalCount)) {
        // Default is all
        $count = $totalCount;
    }
    // Valid results value
    else {
        // Convert to number
        $count += 0;
    }
    
    // Create return value
    $returnValue = array(
        'recordsReturned'=>count($rows),
        'totalRecords'=>$totalCount,
        'startIndex'=>$posStart,
        'sort'=>$sort,
        'dir'=>$dir,
        'pageSize'=>$count,
        'records'=>$rows
    );

    // JSONify
    //print json_encode($returnValue);

    // Use Services_JSON

    require_once('JSON.php');
    $json = new Services_JSON();
    return ($json->encode($returnValue)); 
	}
//------JSON------------END----------------------
 }
		
function readSecStatistics()
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $isin=$secInfo->getIsinSec();
	
 $q="SELECT * FROM imk_SS WHERE security_isin='".$isin."' AND fdate='".$this->getSessionDate()."'";
// print("getMktStatistics - ".$q."\n");
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum() || !$rows || count($rows)==0){
	    $this->errorlog->addlog( 'getMktStatistics '.$isin. ' sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 $row = $rows[0];
 
 foreach($row as $col=>&$val)
 {
  if ($col=='id' || $col=='security_isin' || $col=='fdate')
      continue;
  $secInfo->$col=(int)$val;
//  if ($col=='totTradeVol')
//      print("getMktStatistics - ".$secInfo->$col."==".$val."\n");
  if (strpos(strtolower($col),'price') && strpos(strtolower($col),'trader')==false)
  	  $val= (int)$val / pow(10,2);
  }
 
 $secInfo->statistics=$rows;
 }
 
 
function getStatistics($isin)
{
 require_once('JSON.php');
 $json = new Services_JSON();
 $secInfo = $this->markets[$this->cfg->market]->getSec($isin);
 return ($json->encode($secInfo->statistics)); 
 }
  
function setSurvTraders($trader, $value)
{  
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 $secInfo->surv_str="";
 
 if ($value=='false') {
     $q="DELETE FROM imk_ST WHERE trader='".$trader."'"
//	 . " AND session=".$this->session->sesid
	 . " AND fdate='".$this->getSessionDate()."'"
 	 . " AND security_isin='".$this->getSessionIsin()."'";
   	 $this->dp->setQuery(stripslashes($q));
 	 $this->dp->query();
	 $err=$this->dp->getAffectedRows();	
	 
	 if ($err==-1) {
   		 $this->errorlog->addlog( 'setSurvTraders delete : '.$trader."=="." err=".$this->dp->getErrorNum());
		 return false;
		 }
 	 } 	
  else {
        $q="SELECT count(*) FROM imk_ST WHERE fdate='".$this->getSessionDate()."' AND SECURITY_ISIN= '".$this->getSessionIsin()."'";
   	 	$this->dp->setQuery(stripslashes($q));
		$tot_survs = $this->dp->loadResult();
		if ($tot_survs==5)
			return false;
			
	 	$q="INSERT imk_ST VALUES(0," . $this->session->sesid.",'".$this->getSessionDate()."','".$this->getSessionIsin()."','".$trader . "',0,0,0" . ")"; 
		$this->dp->setQuery(stripslashes($q));
		$this->dp->query();
		$err=$this->dp->getAffectedRows();	

		if ($err!=1) {
			$this->errorlog->addlog( 'setSurvTraders INSERT : '.$trader."=="." err=".$this->dp->getErrorNum());
		 	return false;
		 	}
		}
		
//	Save all into mem array			
		$q="SELECT trader FROM imk_ST WHERE fdate='".$this->getSessionDate()."' AND SECURITY_ISIN= '".$this->getSessionIsin()."'";
   	 	$this->dp->setQuery(stripslashes($q));
		if (isset($secInfo->survTraders))
		    $secInfo->survTraders='';
 	 	$secInfo->survTraders = $this->dp->loadObjectList();
		
		foreach ($secInfo->survTraders as $row)
 		{
 			if ($secInfo->surv_str!="") 
	  			$secInfo->surv_str .= ",";
	   		$secInfo->surv_str .= '{"T":"'.$row->trader.'"}';
		  }	
		
 return true;
 }
/*
SELECT tim, 
sum(CASE WHEN side = 'B' THEN vol ELSE 0 END ) bvol, 
sum(CASE WHEN side = 'S' THEN vol ELSE 0 END ) svol,
sum(CASE WHEN side = 'B' THEN p ELSE 0 END ) bp, 
sum(CASE WHEN side = 'S' THEN p ELSE 0 END ) sp
FROM (
SELECT substr( `OASIS_TIME` , 1, 2 ) tim, side, 
sum( `MATCHED_VOLUME` ) vol,
round( sum( price /100 ) / count( * ) , 2 ) p
AVG( (CASE WHEN price=99999 THEN 0 ELSE ABS( price ) END ) )/100  p
FROM `imk_OBS` 
WHERE MATCHED_VOLUME >0
GROUP BY substr( `OASIS_TIME` , 1, 2 ) , side
)m
GROUP BY tim
*/
/*
SELECT tim, 
sum(CASE WHEN trader = 'M' THEN bv ELSE 0 END ) bv, 
sum(CASE WHEN trader = 'M' THEN sv ELSE 0 END ) sv, 
sum(CASE WHEN trader = 'M' THEN bp ELSE 0 END ) bp, 
sum(CASE WHEN trader = 'M' THEN sp ELSE 0 END ) sp, 
sum(CASE WHEN trader = 't0' THEN bv ELSE 0 END ) v0, 
sum(CASE WHEN trader = 't0' THEN bp ELSE 0 END ) p0, 
sum(CASE WHEN trader = 't1' THEN bv ELSE 0 END ) v1, 
sum(CASE WHEN trader = 't1' THEN bp ELSE 0 END ) p1 
FROM ( 

"SELECT trader, tim, "
				. " sum(CASE WHEN side = 'B' THEN vol ELSE 0 END ) bv, "
				. " sum(CASE WHEN side = 'S' THEN vol ELSE 0 END ) sv, "
				. " sum(CASE WHEN side = 'B' THEN p ELSE 0 END ) bp, "
				. " sum(CASE WHEN side = 'S' THEN p ELSE 0 END ) sp "
	//			. " 0 v0, 0 v1,0 p0, 0 p1 "
				. " FROM ( "
				. " SELECT 'M' trader, substr( OASIS_TIME , 1, 2 ) tim, side, "
				. " sum( MATCHED_VOLUME ) vol,"
				. " round( sum(CASE WHEN price =99999 THEN 0 ELSE ABS( price ) END /100 ) / count( * ) , 2 ) p"
				. " FROM imk_OBS" 
				. " WHERE MATCHED_VOLUME >0"
				. " GROUP BY substr( OASIS_TIME , 1, 2 ) , side"
				. " )m"
				. " GROUP BY tim ";
UNION
 
SELECT 't0' trader, substr( OASIS_TIME, 1, 2 ) tim, 
sum( volume ) bv, 
0 sv,
round( sum( price /100 ) / count( * ) , 2 ) bp, 
0 sp 
FROM imk_IS WHERE buy_trader_ID = 'ML00C' AND buy_csd_account_id = '41614' 
GROUP BY substr( OASIS_TIME, 1, 2 ) )m
GROUP BY tim;
*/ 
function createHourGraphData($withTrader=false, $trader='',$investor='')
{
	$secInfo=$this->markets[$this->cfg->market]->getActiveSec();
	
	$mkt_str = "SELECT 'M' trader, substr( OASIS_TIME, 1, 2 ) tim, sum( volume ) v, round( avg( price /100 ) , 2 ) p "
				. "FROM imk_IS  "
				. " WHERE "
				. " trade_date=\"".$this->getSessionDate()."\""
				. " AND SECURITY_ISIN = \"".$this->getSessionIsin()."\""
				. " GROUP BY substr( OASIS_TIME, 1, 2 ) ";
				
	if ($withTrader==true)
	{
		$str ='';
		$str_sum ='';
		$tar=explode('^',$trader);
		if ($investor!='')
			$iar=explode('^',$investor);
//print_r($tar);	
		for ($i=0; $i<count($tar); $i++)
		{
	 		list($side,$trad)=explode('@',$tar[$i]);
			//--- In case string hasn't Side char
			$trad=($trad=='' ?$side :$trad);
			$side=($trad==$side ?'B' :$side);
			$m_str=(strlen(utf8_decode($trad))==2 ?"_member" :"_trader");
//print("\n".$trader."==".$trad."==".strlen(utf8_decode($trad))."\n");			
	 		$s_str = (($side=='B' || $side=='') ?"buy" :"sell");
			$flip_side=($side=='B' ?"S" :"B");
			
	 		$q_str .= " UNION  "
	 			. "SELECT 't".$i."' trader, substr( OASIS_TIME, 1, 2 ) tim, "
					. "sum( volume ) v,"
					. "round( avg( price /100 ) , 2 ) p "
	 			. " FROM imk_IS " 
	 			. " WHERE "
				. $s_str.$m_str."_ID = '" . $trad."' "
	 			. (($investor!='' && $iar[$i]!='') ?"AND ".$s_str."_csd_account_id = '".$iar[$i]."' " :"")
				. " AND trade_date=\"".$this->getSessionDate()."\""
				. " AND SECURITY_ISIN = \"".$this->getSessionIsin()."\""
	 			. " GROUP BY substr( OASIS_TIME, 1, 2 ) ";
	  		$str_sum .=
				  ", sum(CASE WHEN trader = '".'t'.$i."' THEN v ELSE 0 END ) v".$i.", " 
				. " sum(CASE WHEN trader = '".'t'.$i."' THEN p ELSE 0 END ) p".$i;
			}	
	
		 $mkt_str__ = " SELECT 'm' AS trader, substr( OASIS_TIME, 1, 2 ) tim, sum( volume ) v, round( sum( price /100 ) / count( * ) , 2 ) p, count( * ) c "
			. " FROM imk_IS  "
			. " WHERE "
			. " trade_date=\"".$this->getSessionDate()."\""
			. " AND SECURITY_ISIN = \"".$this->getSessionIsin()."\""
			. " GROUP BY substr( OASIS_TIME, 1, 2 ) " ;			
		
		$q= " SELECT tim,"
			. "sum(CASE WHEN trader = 'M' THEN v ELSE 0 END ) v, "
			. "sum(CASE WHEN trader = 'M' THEN p ELSE 0 END ) p "

			. $str_sum 
		//	. "sum(CASE WHEN trader != 'M' THEN v ELSE 0 END ) tv, " 
		//	. "sum(CASE WHEN trader != 'M' THEN p ELSE 0 END ) tp "
			. " FROM ( "
			. $mkt_str
			. $q_str 
			. ")m "
			. " GROUP BY tim ";
		}
		else
			$q=$mkt_str;
			$q__ = "SELECT substr( OASIS_TIME, 1, 2 ) tim, sum( volume ) v, 0 v0, 0 v1, round( avg( price /100 ) , 2 ) p, 0 p0, 0 p1, count( * ) c "
				. " FROM imk_IS  "
				. " trade_date=\"".$this->getSessionDate()."\""
				. " AND SECURITY_ISIN = \"".$this->getSessionIsin()."\""
				. " GROUP BY substr( OASIS_TIME, 1, 2 ) ";	

	$this->dp->setQuery(stripslashes( $q ));
	$secInfo->hourGraphData = $this->dp->loadObjectList();
//	print("hourGraphData - ".count($secInfo->hourGraphData)."\n");	
	if ($this->dp->getErrorNum() || count($secInfo->hourGraphData)==0){
	    $this->errorlog->addlog( 'getHourGraphData sql: '.$this->dp->stderr());
		$secInfo->hourGraphData='';
		return _TRADES_RET_ERROR;
		}
 }
  
/** 
*		All trades before last trade
* -------------------------------------
*	msgId: record type (IT,IS)
*	posStart: next record position ( for multiple ajax calls)
*	msg_num: number of limit records
*   @version 2.0.1
* -------------------------------------
*/
function getHistoryOrders($msgId='IT',$format,$posStart, $count, $phase, $trader)
{
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgOrders = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
 //if($posStart==0){
	 //$q = "SELECT COUNT(*) FROM imk_$msgId"; // WHERE TRADE_NUMBER <= ".end($this->trade_numbers);
	 //$this->dp->setQuery(stripslashes($q)); 
	 //$totalCount = $this->dp->loadResult();
	 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
     $isin=$secInfo->getIsinSec();
	 
	 $new_posStart = $posStart;
	 
	 if(strlen($phase)) {
	 	$totals_ar = $this->getPhasesArray()->countPhaseOrders($phase);
		$totalCount=$totals_ar[0];
		$phaseStart=$totals_ar[1];  // Subtot
		$new_posStart = ($phaseStart - $totalCount) + $posStart;
//	$this->errorlog->addlog( 'getHistoryOrders : '.$totalCount."==".$phaseStart."==".$posStart); 
		}
	   else	
		  $totalCount = $this->getPhasesArray()->countAllPhasesOrders();

		  
	 
	 if(strlen($trader)) {
	 	$totals_ar = $this->getPhasesArray()->timingPhaseOrders($phase);
		$startPhase=$totals_ar[0];
		$stopPhase=$totals_ar[1];
		$limitStr="";
		$traderStr=" AND TRADER_ID LIKE \"%$trader%\" ";
		$timeStr=" AND (OASIS_TIME>=\"$startPhase\" AND OASIS_TIME<=\"$stopPhase\") ";
		$new_posStart = $posStart;
		
		$sql = "SELECT COUNT(*)"
			. " FROM imk_$msgId t "
			. " WHERE t.SECURITY_ISIN = \"$isin\" "
			. " AND t.TRADE_DATE = \"".$this->getSessionDate()."\""
			. $traderStr
			. $timeStr;
 
		$this->dp->setQuery(stripslashes($sql)); 
 		$totalCount = $this->dp->loadResult();	
		//$count = $totalCount;
		//$posStart = 0;	
		}
		
		$limitStr = " LIMIT ".$new_posStart . "," . $count;
	// }
/* 						-------> DURING TRADING ORDERS
 $sql = "SELECT ob.status status, ob.VOLUME - ob.UNMATCHED_VOLUME vol," . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
		. " FROM imk_$msgId t, imk_OB ob "
		. " WHERE ob.ORDER_ID=t.ORDER_ID AND t.SECURITY_ISIN = ob.SECURITY_ISIN "
 		. " LIMIT ".$posStart . "," . $count;
*/		
  $sql = "SELECT " .$this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
		. " FROM imk_$msgId t "
		. " WHERE t.SECURITY_ISIN = \"$isin\" "
		. $traderStr
		. $timeStr
		. " ORDER BY OASIS_TIME "
 		. $limitStr;
		
//$this->errorlog->addlog( 'getHistoryTrades sql: '.$sql); 

 $script = "<rows total_count='".$totalCount."' pos='".$posStart."'>";
 
 $this->dp->setQuery(stripslashes($sql)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getHistoryTrades sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 
  /// JSON  version--------------------------------------------------------------
 if ($format=='JSON') {
     // Invalid start value
    if(is_null($posStart) || !is_numeric($posStart) || ($posStart < 0)) {
        // Default is zero
        $posStart = 0;
    }
    // Valid start value
    else {
        // Convert to number
        $posStart += 0;
    }

    // Invalid results value
    if(is_null($count) || !is_numeric($count) ||
            ($count < 1) || ($results >= $totalCount)) {
        // Default is all
        $count = $totalCount;
    }
    // Valid results value
    else {
        // Convert to number
        $count += 0;
    }
    
    // Create return value
    $returnValue = array(
        'recordsReturned'=>count($rows),
        'totalRecords'=>$totalCount,
        'startIndex'=>$posStart,
        'sort'=>$sort,
        'dir'=>$dir,
        'pageSize'=>$count,
        'records'=>$rows
    );

    // JSONify
    //print json_encode($returnValue);

    // Use Services_JSON
    require_once('JSON.php');
    $json = new Services_JSON();
    return ($json->encode($returnValue)); 
	}
//------JSON------------END----------------------

 $r=0;
 foreach($rows as $row)	
 {
 	$script.="<row id='".$row->ORDER_NUMBER."'>";	
	$color="";
	foreach($row as $col=>$val)
		{
		 if ($col=='ORDER_MOD' || $col=='ORDER_ID')
				continue;
		 elseif  ($col=='status') {
			 $color=($row->status==9) ?"#D58A78" :(($row->status==8) ?"#EFDD49" :(($row->vol > 0) ?"#2CBDF3" :''));
			 continue;
			 }
		elseif  ($col=='vol') {			 
			 continue;
			 }
			 	
		 if ( $i ) 
			    $script .= ',';			
		// ===Format fields=== //
		 if ($val=='')
				$val=' ';
		
		 if ($table[$col][_IDX_DECIMALS] != 0)
			{
				$val = (int)$val * 1;

		 if ($table[$col][_IDX_DECIMALS] > 0)
			    $val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));

		 if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') {
			    $str='';
			    for ($n=0; $n<4; $n++) {
				    if ($str!='')
					    $str.=':';
				    $str .= substr($val,$n*2,2);
					}
				$val = $str;
				}
		}	
			
		$i++;
		
		
		
		if ($color!="")
		   //$val='<![CDATA[<font color="'.$color.'">'.$val."</font>]]>";
		   $script .='<cell style="background-color: '. $color.'">'.$val."</cell>";
		  else 
			$script .="<cell>$val</cell>"; 		  
		
		}
		$script .= "</row>"; 	
    	}
	
	$script.="</rows>";

	return $script;
}

//***************************************************************
//		All trades before last trade
//-------------------------------------
//	msgId: record type (IT,IS)
//	posStart: next record position ( for multiple ajax calls)
//	msg_num: number of limit records
//-------------------------------------
//***************************************************************
function getHistoryTrades($msgId='IS',$format, $posStart, $count, $phase,$buyer,$seller)
{
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgTrades = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
  $buyerStr="";
  $sellerStr="";
  
  $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
	 
  $new_posStart = $posStart;
  
  if(strlen($buyer))
  	 $buyerStr=" AND BUY_TRADER_ID LIKE \"%$buyer%\" ";
  if(strlen($seller))
  	 $sellerStr=" AND SELL_TRADER_ID LIKE \"%$seller%\" ";
	 
  if(strlen($phase)) {
	 	$totals_ar = $this->getPhasesArray()->timingPhaseOrders($phase);
		$startPhase=$totals_ar[0];
		$stopPhase=$totals_ar[1];
				
		$timeStr=" AND (OASIS_TIME>=\"$startPhase\" AND OASIS_TIME<=\"$stopPhase\") ";
		$new_posStart = $posStart;
		
		$sql = "SELECT COUNT(*)"
			. " FROM imk_$msgId t "
			. " WHERE t.SECURITY_ISIN = \"$isin\" "
			. $buyerStr
			. $sellerStr
			. $timeStr;
 
		$this->dp->setQuery(stripslashes($sql)); 
 		$totalCount = $this->dp->loadResult();	
		
		$new_posStart =  $posStart;
		$this->errorlog->addlog( 'getHistoryTrades : '.$totalCount."==".$phaseStart."==".$posStart); 
		}
	   else	
		  $totalCount = $this->totalTrades;
 
 $limitStr = " LIMIT ".$new_posStart . "," . $count;

 $sql = "SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns']
		. " FROM imk_IS s "
		. " WHERE s.SECURITY_ISIN = \"$isin\" "
		. $buyerStr
		. $sellerStr
		. $timeStr
		. " ORDER BY OASIS_TIME "
 		. $limitStr;
$this->errorlog->addlog( 'getHistoryTrades sql: '.$sql);
  
 $script = "<rows total_count='".$this->totalTrades."' pos='".$posStart."'>";
 
 $this->dp->setQuery(stripslashes($sql)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getHistoryTrades sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 
 /// JSON  version--------------------------------------------------------------
 if ($format=='JSON') {
     // Invalid start value
    if(is_null($posStart) || !is_numeric($posStart) || ($posStart < 0)) {
        // Default is zero
        $posStart = 0;
    }
    // Valid start value
    else {
        // Convert to number
        $posStart += 0;
    }

    // Invalid results value
    if(is_null($count) || !is_numeric($count) ||
            ($count < 1) || ($results >= $totalCount)) {
        // Default is all
        $count = $totalCount;
    }
    // Valid results value
    else {
        // Convert to number
        $count += 0;
    }
    
    // Create return value
    $returnValue = array(
        'recordsReturned'=>count($rows),
        'totalRecords'=>$totalCount,
        'startIndex'=>$posStart,
        'sort'=>$sort,
        'dir'=>$dir,
        'pageSize'=>$count,
        'records'=>$rows
    );

    // JSONify
    //print json_encode($returnValue);

    // Use Services_JSON
    require_once('JSON.php');
    $json = new Services_JSON();
    return ($json->encode($returnValue)); 
	}
//------JSON------------END----------------------

 $r=0;
 foreach($rows as $row)	
 {
 	$script.="<row id='".$row->TRADE_NUMBER."'>";	
	
	foreach($row as $col=>$val)
		{
		 
		 if ( $i ) 
			    $script .= ',';			
		// ===Format fields=== //
		 if ($val=='')
				$val=' ';
		
		 if ($table[$col][_IDX_DECIMALS] != 0)
			{
				$val = (int)$val * 1;

		 if ($table[$col][_IDX_DECIMALS] > 0)
			    $val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));

		 if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') {
			    $str='';
			    for ($n=0; $n<4; $n++) {
				    if ($str!='')
					    $str.=':';
				    $str .= substr($val,$n*2,2);
					}
				$val = $str;
				}
			}		
			$i++;
			$script .="<cell>$val</cell>"; 		  
			}
		$script .= "</row>"; 	
    	}
	
	$script.="</rows>";

	return $script;
}

//=============================================================================
//		fetch Msg IT/IS Data Details for a specific ID
//-------------------------------------
//	msgId: record type (IT,IS)
//	time: transaction time
//	msg_num: record ID
//-------------------------------------
//=============================================================================			
 function getTradeDetails($msgId,$time, $msg_num)
 {
  global $desc_r;
  $ar_traders=array("TRADER_ID","BUY_TRADER_ID","SELL_TRADER_ID");
  
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgTrades = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
  $q="SELECT DISTINCT * "  
			. " FROM imk_IS" 
			. " WHERE TRADE_NUMBER=\'$msg_num\'";
 
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'getTradeOrders sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 
 $r=0;
 $script='<rows><head>'
	 	. '<column width="200" type="ro" align="left" color="#D4D0C8" sort="na">Name</column>'
		. '<column width="200" type="co" align="left" sort="na">Trading</column>'
		. '</head>';
		
 foreach($rows as $row)	
 {
	  
	  $i=0;
	  foreach($row as $col=>$val)
		{
		 if ( $i ) 
			    $script .= ',';			
		// ===Format fields=== //
		 if ($val=='')
				$val=' ';
		
		 if ($table[$col][_IDX_DECIMALS] != 0)
			{
				$val = (int)$val * 1;

		 if ($table[$col][_IDX_DECIMALS] > 0)
			    $val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));
		 
		 if ($table[$col][_IDX_DECIMALS] == -6 && $val!='')	
			 $val = date("Y/m/d",strtotime($val));
			 
		 if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') {
			    $str='';
			    for ($n=0; $n<4; $n++) {
				    if ($str!='')
					    $str.=':';
				    $str .= substr($val,$n*2,2);
					}
				$val = $str;
				}
			}
		  else
			{
			//print($table[$col][_IDX_TITLE]."=== $val \n");
			if ($desc_r[$table[$col][_IDX_TITLE]][$val])
			    $val = '<![CDATA['.$val .' <span style="color:red;font-size:11px">'.$desc_r[$table[$col][_IDX_TITLE]][$val].'</span>]]>'; 
			}
			
			$i++;

			if (in_array($col,$ar_traders))
			     $cell='<cell type="link">'.$val.'^javascript:App.memberInfo("'.$msgId.'","'.$time.'","'.$msg_num.'","'.$val.'");^_self' .'</cell>';
			else
			   	$cell='<cell type="ro">'.$val.'</cell>';	
			$script.='<row id="'.$i.'"><cell>'.(($col=='ORDER_MOD') ?"Trades" :$table[$col][_IDX_TITLE]).'</cell>'.$cell.'</row>';	  
			}
    	}
	
	$script.="</rows>";
			
	return $script;
	}

function getTradeOrders($msgId, $mode, $time, $msg_num, $ask, $sort, $dir)
 {
  global $desc_r;
  $ar_traders=array("TRADER_ID","BUY_TRADER_ID","SELL_TRADER_ID");
  
  $table=$this->Msgs_collection->getMsg($msgId);
  if( ($table instanceof Collection) == false) {
	 	 $this->errorlog->addlog( 'get_msgTrades = table is null - ' .$msgId);
	     return _TRADES_RET_ERROR;
		 }
 
  if ($ask=='-1')
  {
   $q="SELECT BUY_ORDER_NUMBER,SELL_ORDER_NUMBER FROM imk_IS WHERE TRADE_NUMBER=$msg_num";
   $this->dp->setQuery(stripslashes($q)); 
   $this->dp->loadObject($trade_orders);
   
   $ord_nums = "\"".$trade_orders->BUY_ORDER_NUMBER."\",\"". $trade_orders->SELL_ORDER_NUMBER."\"";
   }
  else 
	  $ord_nums = "\"$msg_num\",\"$ask\"";
  
  if ($mode=='noOB')
/*  	$q="SELECT DISTINCT it.* "  
			. " FROM imk_IT AS it" 
			. " WHERE it.ORDER_NUMBER IN ($ord_nums)"
			. " HAVING max( OASIS_TIME ) "; */
	$q="SELECT DISTINCT it.* "  
			. " FROM imk_IT AS it, imk_OBS AS ob" 
			. " WHERE it.ORDER_NUMBER IN ($ord_nums)"
			. " AND it.TRADE_DATE = \"".$this->getSessionDate()."\""
			. " AND it.SECURITY_ISIN = \"".$this->getSessionIsin()."\""
			. " AND it.ORDER_ID=ob.ORDER_ID"
			. " AND ob.FDATE = it.TRADE_DATE "
			. " AND ob.SECURITY_ISIN = it.SECURITY_ISIN ";
//			. " AND ob.session = ".$this->session->sesid;
 else			
  $q="SELECT DISTINCT it.* "  
			. " FROM imk_IT AS it, imk_OB AS ob" 
			. " WHERE it.ORDER_NUMBER IN ($ord_nums)"
			. " AND it.ORDER_ID=ob.ORDER_ID";
//			. " AND ORDER_STATUS NOT IN ('I','X')"
//			. " AND it.OASIS_TIME<=ob.OASIS_TIME";
//			. " HAVING max( OASIS_TIME ) "; 

 

 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
 $nrows = count($rows);


 if ($this->dp->getErrorNum() || $nrows==0 || $rows==false){
	    $this->errorlog->addlog( 'getTradeOrders sql: '.$this->dp->stderr()."==".$q);
		return _TRADES_RET_ERROR;
		}

 $r=0;
 $script='<rows><head>'
	 	. '<column width="200" type="ro" align="left" color="#D4D0C8" sort="na">Name</column>'
		. '<column width="180" type="co" align="left" sort="na">Buyer</column>'
		. '<column width="180" type="co" align="left" sort="na">Seller</column>'
		. '</head>';


  if ($rows[0]->ORDER_MOD > 0)
     {
	    $q="SELECT OASIS_TIME,TRADE_NUMBER "  
			. " FROM imk_IS" 
			. " WHERE ".( $rows[0]->SIDE=='B' ?"BUY_ORDER_NUMBER" :"SELL_ORDER_NUMBER")."="
			."\'".$rows[0]->ORDER_NUMBER."\'"
			. " ORDER BY  OASIS_TIME";
 
		 $this->dp->setQuery(stripslashes($q)); 
		 $trades=$this->dp->loadObjectList();
	
		 if ($this->dp->getErrorNum() || count($trades)==0 || $trades==false)
		    {
	    		$this->errorlog->addlog( 'getTradeOrders sql: '.$this->dp->stderr());
				return _TRADES_RET_ERROR;
			    }
	  	
	   }
 
  if ($nrows > 1 && $rows[1]->ORDER_MOD > 0)
     {   
	   $q="SELECT OASIS_TIME,TRADE_NUMBER "  
			. " FROM imk_IS" 
			. " WHERE ".( $rows[1]->SIDE=='B' ?"BUY_ORDER_NUMBER" :"SELL_ORDER_NUMBER")	."="
			. "\'".$rows[1]->ORDER_NUMBER."\'"
			. " ORDER BY  ".($sort ?$sort." ".$dir :"OASIS_TIME");
 
		 $this->dp->setQuery(stripslashes($q)); 
		 $trades_2=$this->dp->loadObjectList();
	
		 if ($this->dp->getErrorNum() || count($trades_2)==0 || $trades_2==false)
		 	{
	    		$this->errorlog->addlog( 'getTradeOrders sql: '.$this->dp->stderr());
				return _TRADES_RET_ERROR;
				}
	   }	   
	  
	  $i=0;

	  while (list($col, $val) = each($rows[0])) 
		{
		 if (count($rows)==2)
		     list($col_2, $val_2) = each($rows[1]);
		 if ( $i ) 
			    $script .= ',';			
		// ===Format fields=== //
		 if ($val=='')
				$val=' ';
		 if ($val_2=='')
				$val_2=' ';
		 if ($table[$col][_IDX_DECIMALS] != 0)
			{
				$val = (int)$val * 1;
				$val_2 = (int)$val_2 * 1;

		 	if ($table[$col][_IDX_DECIMALS] > 0)
			   {
				$val = (int)$val / (pow(10,$table[$col][_IDX_DECIMALS]));
				$val_2 = (int)$val_2 / (pow(10,$table[$col][_IDX_DECIMALS]));
				}
		 
		 	if ($table[$col][_IDX_DECIMALS] == -6 && $val!='')	
				{ 
			 	$val = date("Y/m/d",strtotime($val));
			 	$val_2 = date("Y/m/d",strtotime($val_2));
				 }
			 
		 	if ($table[$col][_IDX_DECIMALS] == -3 && $val!='') 
			    $val = $this->formatTimeStr($val);
		 	if ($table[$col_2][_IDX_DECIMALS] == -3 && $val_2!='') 
			    $val_2 = $this->formatTimeStr($val_2);		
		 	}
		 else
			{
			//print($table[$col][_IDX_TITLE]."=== $val \n");
			if ($desc_r[$table[$col][_IDX_TITLE]][$val])
			    //$val = $val .' ('.$desc_r[$table[$col][_IDX_TITLE]][$val].')'; 
				$val = '<![CDATA['.$val .' <span style="color:red;font-size:11px">'.$desc_r[$table[$col][_IDX_TITLE]][$val].'</span>]]>'; 
			if ($desc_r[$table[$col_2][_IDX_TITLE]][$val_2])
			    //$val_2 = $val_2 .' ('.$desc_r[$table[$col_2][_IDX_TITLE]][$val_2].')';
				$val_2 = '<![CDATA['.$val_2 .' <span style="color:red;font-size:11px">'.$desc_r[$table[$col_2][_IDX_TITLE]][$val_2].'</span>]]>';
			}
			
			$i++;
			if ($col=='ORDER_MOD' && $trades) 
				{
				 $cell='<cell xmlcontent="1" editable="0">';
				 $cell.=$val;
				 foreach($trades as $trade)
					  	$cell.='<option value="' . $trade->TRADE_NUMBER. '">' .$this->formatTimeStr($trade->OASIS_TIME)." - ".$trade->TRADE_NUMBER.'</option>';
			   	 $cell.='</cell>';
				 }
			if ($col=='ORDER_MOD' && $trades_2) 
				{
				 $cell_2='<cell xmlcontent="1" editable="0">';
				 $cell_2.=$val;
				 foreach($trades_2 as $trade)
					  	$cell_2.='<option value="' . $trade->TRADE_NUMBER. '">' .$this->formatTimeStr($trade->OASIS_TIME)." - ".$trade->TRADE_NUMBER.'</option>';
			   	 $cell_2.='</cell>';
	  			}
			elseif (in_array($col,$ar_traders))
				{
			     $cell='<cell type="link">'.$val.'^javascript:App.memberInfo("'.$msgId.'","'.$time.'","'.$msg_num.'","'.$val.'");^_self' .'</cell>';
				 $cell_2='<cell type="link">'.$val_2.'^javascript:App.memberInfo("'.$msgId.'","'.$time.'","'.$ask.'","'.$val_2.'");^_self' .'</cell>';
				 }
			else
			    {
			   	 $cell='<cell type="ro">'.$val.'</cell>';	
				 $cell_2='<cell type="ro">'.$val_2.'</cell>';	
				 }
			$script.='<row id="'.$i.'"><cell>'.(($col=='ORDER_MOD') ?"Trades" :$table[$col][_IDX_TITLE]).'</cell>'.$cell.((count($rows)==2) ?$cell_2 :'').'</row>';	  
			}
    		
	$script.="</rows>";
			
	return $script;
	}
//=====================================================================
//			Compute Open Price after Auction
//---------------------------
//	otime: start of Auction
//	ctime: end of Auction
//--------------------------
//=====================================================================
function computeOpenPrice($otime,$ctime)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
 
 $q="SELECT bp, sum( bvol ) OE_B, sum( svol ) OE_S"
	. " FROM ("
	. " SELECT PRICE bp, "
	. " SUM(CASE WHEN SIDE = \'B\' THEN VOLUME ELSE 0 END ) AS bvol,"
	. " SUM(CASE WHEN SIDE = \'S\' THEN VOLUME ELSE 0 END ) AS svol"
	. " FROM ("
	. " SELECT SIDE, IF((SIDE=\'B\' AND PRICE=0),9999,PRICE) PRICE,DISCLOSED_VOLUME AS VOLUME "
	. " FROM imk_IT b"
	. " WHERE (OASIS_TIME >= \'$otime\' AND OASIS_TIME <= \'$ctime\') AND ORDER_STATUS = \'O\' AND SECURITY_ISIN=\'".$this->getSessionIsin()."\'"." AND LAST_ORDER_UPDATE_DATE=\'".$this->getSessionDate()."\'"
	. " AND oasis_time = (SELECT max( oasis_time ) FROM imk_IT WHERE order_number = b.ORDER_NUMBER "." AND SECURITY_ISIN=\'".$this->getSessionIsin()."\'"
	. " AND LAST_ORDER_UPDATE_DATE=\'".$this->getSessionDate()."\'"
	. " AND oasis_time >= \'$otime\' AND OASIS_TIME <= \'$ctime\' )"
	. "   ORDER BY order_id) P"   //10312635
	. " GROUP BY PRICE"
	. " )A"
	. " GROUP BY bp"
	. " ORDER BY bp ASC";
	/*
 SELECT ORDER_NUMBER, OASIS_TIME, SIDE, IF( (
SIDE = 'B'
AND PRICE =0
), 9999, PRICE ) PRICE, VOLUME
FROM imk_IT a
WHERE (
OASIS_TIME >= '00000000'
AND OASIS_TIME <= '10312635'
)
AND oasis_time = (SELECT max( oasis_time ) FROM imk_IT
WHERE order_number = a.ORDER_NUMBER
AND oasis_time >= '00000000'
AND OASIS_TIME <= '10312635' )
AND order_status = 'O'
ORDER BY a.OASIS_TIME ASC 
*/
  $this->dp->setQuery(stripslashes($q)); 
  $rows=$this->dp->loadObjectList();

//print( 'computeOpenPrice : '.stripslashes($q)."\n");
  if ($this->dp->getErrorNum()){
   		$this->errorlog->addlog( 'computeOpenPrice sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
  if (count($rows)==0) {
  	  $this->errorlog->addlog( 'computeOpenPrice No Rows Selected: '.stripslashes($q));	
	  print( 'computeOpenPrice No Rows Selected: '.stripslashes($q)."\n");
	  return 0;
	  }
	  
  $this->aprices=array();
  
  $i=0;		
  foreach($rows as $row)	
  {
   $this->aprices[$i++]=array($row->bp,$row->OE_B,$row->OE_S,0,0,0);
  // if ($this->aprices[$i-1][0]==9999)
  // 		$this->aprices[$i-1][0]=0;
   }
   
   $this->errorlog->addlog( 'computeOpenPrice items: '.count($this->aprices)." Start:".$secInfo->startPrice);
   
   for($i=0; $i<count($this->aprices) && $this->aprices[$i][_YTA] < $secInfo->startPrice; $i++) ;
   
   if ($this->aprices[$i][_YTA] > $secInfo->startPrice)
	   array_splice($this->aprices,$i-1,0,array($secInfo->startPrice,0,0,0,0,0));
	
   $alen=count($this->aprices);
   $this->errorlog->addlog( 'computeOpenPrice items: '.$alen);  

   for($i=0; $i<$alen; $i++)
   {
    if(is_array($this->aprices[$i]))
		$this->aprices[$i][_SOE_B]=
							$this->computeAgregateBuyVol($this->aprices[$i][_YTA], $i, $alen);
	//if ($this->aprices[$i][_OE_B]==0 && $i)
	//		$this->aprices[$i][_SOE_B]=$this->aprices[$i-1][_SOE_B];
	}	
   
  for($i=$alen-1; $i>=0; $i--)
   {	
	if(is_array($this->aprices[$i]))
		$this->aprices[$i][_SOE_S]=
					$this->computeAgregateSellVol($this->aprices[$i][_YTA],$i, $alen);
 	// if($this->aprices[$i][_OE_S]==0 && $i < $alen-1) 
		//	$this->aprices[$i][_SOE_S]=$this->aprices[$i+1][_SOE_S];
	}
	
//	find MIN between _SOE_B and _SOE_S Volumes	
  for($i=0; $i<$alen && $this->aprices[$i]; $i++)
	  $this->aprices[$i][_YO] = min($this->aprices[$i][_SOE_B],$this->aprices[$i][_SOE_S]);
   

//	FINALLY, find the fucking Open Price...	
  $vmax=0;
  $secInfo->auctionPrice=0;
  
  for($i=0; $i<$alen; $i++)
    if ($this->aprices[$i][_YO] >= $vmax) {
	    $vmax=$this->aprices[$i][_YO];
		$secInfo->auctionPrice=$this->aprices[$i][_YTA];
		$secInfo->startPrice=$this->auctionPrice;
		$secInfo->auctionPriceIndex=$i;
		}

  $this->saveOpenPrices($otime,$alen);
  unset($this->aprices);	
}

//==============  Open Price Functions ==================================
function computeAgregateBuyVol($price, $start, $alen)
{
  $tot=0; 
   for($i=$start; $i<$alen && $this->aprices[$i][_YTA] >= $price; $i++)
   {
    $tot+=$this->aprices[$i][_OE_B];
    }
	
 return $tot;
 }	
 
function computeAgregateSellVol($price, $start, $alen)
{
  $tot=0;
  
   for($i=$start; $i>=0 && $this->aprices[$i][_YTA] <= $price; $i--)
   {
    $tot+=$this->aprices[$i][_OE_S];
    }
//	$this->errorlog->addlog( 'computeAgregateBuyVol: tot='.$tot."==".$price."==".$start);
 return $tot;
 }	 

//============================================================
//			Save Open price data into MySql imk_OP
//-----------------------------
//	alen: number of array items
//-----------------------------
//===========================================================
function saveOpenPrices($otime,$alen)
{

	for($i=0; $i<$alen && $this->aprices[$i]; $i++)
	{
		$q = "INSERT INTO imk_OP VALUES (";	
		$q.= $i+1
			. '\,'.$this->aprices[$i][_YTA]
			. '\,'.$this->aprices[$i][_OE_B]
			. '\,'.$this->aprices[$i][_OE_S]
			. '\,'.$this->aprices[$i][_SOE_B]
			. '\,'.$this->aprices[$i][_SOE_S]
			. '\,'.$this->aprices[$i][_YO]
			. "\,'".$this->getSessionDate()."'"
			. "\,'".$this->getSessionIsin()."'"
			. "\,'".$otime."'"
			.')';		

		$this->dp->setQuery(stripslashes($q));
		$this->dp->query();
		$err=$this->dp->getAffectedRows();	
		if ($err==-1) {
	    	$this->errorlog->addlog( 'saveOpenPrices: err='.$this->dp->getErrorNum().' q='.stripslashes($q));
			print( 'saveOpenPrices: err='.$this->dp->getErrorNum().' q='.stripslashes($q)."\n");
		   	return;
			}
		}
}
	
//====================================================================
//		XML list of Open Price Details read from imk_OP
//====================================================================
function OpenPriceDetails()
{
$secInfo = $this->markets[$this->cfg->market]->getActiveSec();

$script = '<rows><head>';
$script .= '<column width="30" type="ro" align="left" color="#D4D0C8" sort="na">No</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">SOE_B</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">OE_B</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">YTA</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">OE_S</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">SOE_S</column>';
$script .= '<column width="70" type="ro" align="left" color="#D4D0C8" sort="na">YO</column>';
$script .= '</head>';
 
 $q="SELECT  ID,SOE_B,OE_B,YTA,OE_S,SOE_S,YO "  
			. " FROM imk_OP" 
			. " ORDER BY ID";
 
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
	
 if ($this->dp->getErrorNum()){
	    $this->errorlog->addlog( 'get_OpenPriceDetails sql: '.$this->dp->stderr());
		return 0;
		}
 
 $r=0;
 
  

 if(is_null($startIndex) || !is_numeric($startIndex) || ($startIndex < 0)) {
        // Default is zero
        $startIndex = 0;
    }
    // Valid start value
    else {
        // Convert to number
        $startIndex += 0;
    }
if(is_null($results) || !is_numeric($results) ||
            ($results < 1) || ($results >= count($rows))) {
        // Default is all
        $results = count($rows);
    }
    // Valid results value
    else {
        // Convert to number
        $results += 0;
    }
 
 $returnValue = array(
        'recordsReturned'=>count($rows),
        'totalRecords'=>count($rows),
        'startIndex'=>$startIndex,
        'sort'=>0,
        'dir'=>1,
        'pageSize'=>$results,
        'records'=>$rows
    );

    // JSONify
    //print json_encode($returnValue);

    // Use Services_JSON
    require_once('JSON.php');
    $json = new Services_JSON();
    return($json->encode($returnValue)); 
			
 foreach($rows as $row)
 {
   $script .= '<row id="' . $row->ID . '"'.(($secInfo->auctionPriceIndex==$row->ID-1) ?' bgColor="#f0e0e0"' :'').'>';
   foreach($row as $name=>$col)
    	{
		 $col= ($name=='YTA') ?(int)$col / (pow(10,2)) :$col;	
		 $val = (($name=='OE_S' || $name=='SOE_S') ?'<![CDATA[<span style="color:red">'.$col.'</span>]]>' :$col); 
     	 $script .= '<cell>'. $val.'</cell>';
	 	}
   $script .= 	'</row>';
   }
$script .= '</rows>';

return $script;	
}

//====================================================================
//		Read all traders - Top 10
//====================================================================
function getAllTraders($side)
{
 $tabName = "imk_OBS";
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();
// $totTradeValue=$this->totTradeVol;

 //$gtot = (count($totTradeValue)>0 ?$totTradeValue->total :1);
	 
	 $q="SELECT concat(TRADER_ID,'-',CSD_ACCOUNT_ID) AID, (sum( o.VOLUME - o.UNMATCHED_VOLUME ) *100) / ".$secInfo->totTradeVol." AS PERC,"
		.	" sum( o.VOLUME - o.UNMATCHED_VOLUME ) VOL, sum( o.VOLUME ) WANT"
		.	" FROM imk_IT t, $tabName o"
		.	" WHERE o.ORDER_ID = t.ORDER_ID"
		.	" AND o.SECURITY_ISIN=t.SECURITY_ISIN"
		.	" AND STATUS !=10"
		.	" AND o.FDATE='".$this->getSessionDate()."'"
		.	" AND o.SECURITY_ISIN='".$this->getSessionIsin()."'"
		.	" AND o.side = \'$side\'"
		.	" AND t.order_status != 'X'"
//		.	" AND abs( o.price ) = t.price"
		.	" GROUP BY 1 "
		.	" ORDER BY 3 DESC "
		.	" LIMIT 10 ";
print("getAllTraders ".$side." -> ".$secInfo->totTradeVol."\n");
 /*    $q="SELECT DISTINCT MEMBER_ID MID,CSD_ACCOUNT_ID AID, TRADER_ID TID"  
			. " FROM imk_IT" 
			. " ORDER BY MEMBER_ID, CSD_ACCOUNT_ID";
	else
		$q="SELECT DISTINCT MEMBER_ID MID, CSD_ACCOUNT_ID AID, TRADER_ID TID"  
			. " FROM imk_IT" 
			. " ORDER BY MEMBER_ID, CSD_ACCOUNT_ID";*/

 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
 $nrows = count($rows);


 if ($this->dp->getErrorNum() || $nrows==0 || $rows==false){
	    $this->errorlog->addlog( 'getAllTraders sql: '.$this->dp->stderr()."==".stripslashes($q));
		print ('getAllTraders sql: '.$this->dp->stderr()."==".stripslashes($q)."\n");
		return _TRADES_RET_ERROR;
		}
 
 $this->tradersScript='{"rows":['; // :"<rows>"; // total_count=$count pos=$posStart //"{rows:[";
		
 $r=0; 
 foreach($rows as $row)
 {   
   if ($side=='B') {
	   $secInfo->bid_traders[$row->AID][0]=$row->VOL;
	   $secInfo->bid_traders[$row->AID][1]=0;
	   $secInfo->bid_traders_str.= ($r ?"," :"") . "\"".$row->AID."\"";
	   }
	   else {
	   	$secInfo->ask_traders[$row->AID][0]=$row->VOL;
	   	$secInfo->ask_traders[$row->AID][1]=0;
		$secInfo->ask_traders_str.= ($r ?"," :"") . "\"".$row->AID."\"";
	   }
   
   if ($r)
       $this->tradersScript.=',';
	  else
	   	 $r=1; 
		 
   $this->tradersScript.='{"id":"'.$row->AID.'","data":[{"SIDE":"'.$side.'"'; // '"TIME": "STOP","PHASE":"'._MARKET_CLOSED.'"}]}]}'

   foreach($row as $col=>$val)
    	{
		 $this->tradersScript.=',';
		 if ($col=="PERC") $val=round((float)$val,2);
		 $this->tradersScript.='"'.$col.'":'.'"'.$val.'"';
		 
	 	}
   $this->tradersScript .= 	'}]}';
   }

//print_r ($secInfo->bid_traders);

//print ("traders - ".$side."==".$secInfo->bid_traders_str."==".$secInfo->ask_traders_str."\n");
 $q="SELECT MEMBER_ID AID, (sum( o.VOLUME - o.UNMATCHED_VOLUME ) *100) / ".$secInfo->totTradeVol." AS PERC,"
		.	" sum( o.VOLUME - o.UNMATCHED_VOLUME ) VOL, sum( o.VOLUME ) WANT"
		.	" FROM imk_IT t, $tabName o"
		.	" WHERE o.ORDER_ID = t.ORDER_ID"
		.	" AND o.SECURITY_ISIN=t.SECURITY_ISIN"
		.	" AND o.FDATE='".$this->getSessionDate()."'"
		.	" AND o.SECURITY_ISIN='".$this->getSessionIsin()."'"		
		.	" AND STATUS !=10"
		.	" AND o.side = \'$side\'"
		.	" AND t.order_status != 'X'"
//		.	" AND abs( o.price ) = t.price"
		.	" GROUP BY 1 "
		.	" ORDER BY 3 DESC "
		.	" LIMIT 10 ";
 $this->dp->setQuery(stripslashes($q)); 
 $rows=$this->dp->loadObjectList();
		
 foreach($rows as $row)
 {
   if ($r)
       $this->tradersScript.=',';
	  else
	   	 $r=1; 
   
   if ($side=='B') {
	   $secInfo->bid_members[$row->AID][0]=$row->VOL;
	   $secInfo->bid_members[$row->AID][1]=0;
	   }
	   else {
	   	$secInfo->ask_members[$row->AID][0]=$row->VOL;
	   	$secInfo->ask_members[$row->AID][1]=0;
	   }
   
   $this->tradersScript.='{"id":"'.$row->AID.'","data":[{"SIDE":"'.'M'.$side.'"'; // '"TIME": "STOP","PHASE":"'._MARKET_CLOSED.'"}]}]}'

   foreach($row as $col=>$val)
    	{
		 $this->tradersScript.=',';
		 if ($col=="PERC") $val=round((float)$val,2);
		 $this->tradersScript.='"'.$col.'":'.'"'.$val.'"';
		 
	 	}
   $this->tradersScript .= 	'}]}';
   }
   
  $this->tradersScript .= ']}';
 }


  
//====================================================================
//		SAVE IT/IS Notes
//====================================================================
function saveNotes($msgId, $trade,$bid,$ask,$htmlText)
{
 $string=mysql_real_escape_string($htmlText);

	$q = "INSERT INTO imk_Notes VALUES(0,\'$msgId\', \'$trade\',\'$bid\',\'$ask\',\'$htmlText\')";		
	//	$msgobj->errorlog->addlog($q);
		
		$this->dp->setQuery(stripslashes($q));
		$this->dp->query();
		$err=$this->dp->getAffectedRows();	
		if ($err==-1) {
	    	$this->errorlog->addlog( 'saveNotes: msgId='.$q." err=".$this->dp->getErrorNum());
		   		return;
			}
	return "ok";
}

//====================================================================
//		XML CRM Trader Info
//====================================================================
function getCRMinfo($msgId,$msg_num,$trader,$time)
{
   $invalid_chars = array(' ','-','/','%',"'",'#',"’","&");
 
	$q = "SELECT DISTINCT t.ID, m.name as mem_name, mt.name, t.activ_date, t.oper_type, t.member_id, groupinvestors"
		. " FROM mbr_mb_stocks_traders t"
		. " LEFT OUTER JOIN mbr_mb_tradocks_traders_c lmt ON mbr_mb_tra3d15traders_idb = t.id"
		. " LEFT OUTER JOIN mbr_mb_traders mt ON mt.id = lmt.mbr_mb_tra6ccftraders_ida"
		. " LEFT OUTER JOIN mbr_mb_member m ON m.member_id = t.member_id"
		. " WHERE t.user_id = \'$trader\'";
		
	$this->CRMdp->setQuery(stripslashes($q)); 
 	$rows=$this->CRMdp->loadObjectList();
	
 	if ($this->CRMdp->getErrorNum()){
	    $this->errorlog->addlog( 'getCRMinfo sql: '.$this->CRMdp->stderr());
		return $q;
		}

$script .= '<rows><head>';
$script .= '<column width="100" type="ro" align="left" color="#D4D0C8" sort="na">Title</column>';
$script .= '<column width="250" type="ro" align="center"  sort="na">Value</column>';

$script .= '</head>';
	
	foreach($rows as $row)
 	{
	 	$script .= ($i > 0) ?',' :'';
		$script .= '<row id="1"><cell>Name</cell><cell type="ro">'.$row->name.'</cell></row>,';
		$script .= '<row id="2"><cell>Member</cell><cell type="ro">'.str_replace($invalid_chars, "_", (($row->mem_name==null) ?'----' :$row->mem_name)).'</cell></row>,';
		$script .= '<row id="3"><cell>Member ID</cell><cell type="ro">'.(($row->member_id==null) ?'----' :$row->member_id).'</cell></row>,';		
	   	$script .= '<row id="4"><cell>Active Date</cell><cell type="ro">'.$row->activ_date.'</cell></row>,';
		$script .= '<row id="5"><cell>Operator</cell><cell type="ro">'.$row->oper_type.'</cell></row>,';
		$script .= '<row id="6"><cell>Group Investors</cell><cell type="ro">'.$row->groupinvestors.'</cell></row>,';
		$script .= '<row id="7"><cell>Item ID</cell><cell type="ro">'.$msg_num.'</cell></row>,';
		$script .= '<row id="8"><cell>Time</cell><cell type="ro">'.$this->formatTimeStr($time).'</cell></row>,';
		$script .= '<row id="9"><cell>---</cell><cell type="ro">----'.'</cell></row>';
		$i++;
   }

	$script .= '</rows>';
 
 return $script;		
}

function startSearchEngine($task, $keywords,$which_tables="",$zoom_and="")
{ 
 
 define("SERVERNAME","localhost");
 define("USERNAME","root");
 define("PASSWORD","");
 
 $search = new mysearch("imk_mplay",$this); 
 $tables = $this->sourceObj->MSG_r;
//$search->kwd_only("on");
	
 if ($zoom_and && $zoom_and=="1")
	$search->and_or_kwd("and");	
	else
	 $search->and_or_kwd("or");

 if ($which_tables && $which_tables!="EVERY")
    $search->tables(array($which_tables => ""));
  else
	foreach($tables as $msg=>$info) {
		    if ($info[_MSG_IDX_CRETAB]==0 || $info[_MSG_IDX_SHOWSRCHCOLS]=="")
			    continue;
			$search->table2use[]="imk_".$msg;
			$search->table2use["imk_".$msg]="";
			$search->table2show[]="imk_".$msg;
			$search->table2show["imk_".$msg]=explode(",",$info[_MSG_IDX_SHOWSRCHCOLS]);
			$search->res_urls[]="imk_".$msg;
			$search->res_urls["imk_".$msg]=  "search.php?task=show&amp;t=%%TABLE&amp;f=%%FIELD";
    }

 if ($task=="show")
    $this->funcTables();
   else	
	  $search->start($keywords); 

 return($this->drawPage($search,$task,$keywords));
 }

function drawPage($search,$task,$keywords)
{
 if ($task!='show')
 {
 $i=0;
 $section=0;
 $html=array();
 $html[$section] ='{"rows":['; 
           $html[$section] .=  '{"id":"'.$i++.'","data":[{"'."dres".'":"'.'<b>Totaling there are '.$search->results." Results in ".$search->fields_count.' Fields found for keywords=>'.$keywords.'</b>"'."}]}";
		   $html[$section++] .= ']}';	
		   while($search->out()){
		   $ar=$search->r("content");
    
           foreach($ar as $tab=>$fld){
		   	$html[$section] ='{"rows":['; 
			$html[$section++].= '{"id":"'.$tab.'","data":[{"'."dres".'":"'.$search->r("result")." Results for -".$search->r("keywords")." in Table ".$search->r("table")." => Field ".$search->r("field").'"}]}'.']}';
		   	$html[$section] ='{"rows":[';
			$sepcntr=0;
//			print_r($fld);
			foreach($fld as $lines){
				foreach($lines as $name=>$res){
					//print($name."==".$res."\n");
					$html[$section].=($sepcntr ?',' :'') . '{"id":"'.$name.'","data":[{"'."dres".'":"'.$res.'"}]}';
					$sepcntr=1;
					}
			}
			$html[$section++] .= ']}';
		   }
		 //  $search->r("content").'"}]}';
          }
 //$html .= ']}';		   
 }
 print("drawPage - ".count($html)."\n");
 return $html;
 }
 
function imk_IT()
{
 print("imk_IT"."\n"); 
 }

function imk_IS()
{ 
 print("imk_IS"."\n"); 
 }

function imk_OBS()
{ 
 print("imk_OBS"."\n"); 
 }
 
function funcTables()
{
 $f=$_REQUEST['t'];
 if (function_exists($f))
     $f();
 }	
//=====================================================================	 
function updateOB()
{
global $colsOB_r;

$sql="SELECT  count(*)"
	. " FROM imk_OB"
	. " where ";  

$n=count($colsOB_r)-1;

$w  = "SECURITY_ISIN =" . $colsOB_r["SECURITY_ISIN"];
$w .= " AND PRICE =" . $colsOB_r["PRICE"];

 
$sql .= $w;


  $this->dp->setQuery($sql);
  $rows=$this->dp->loadResult();
  $this->errorlog->addlog( 'updateOB: '.$sql."==".$rows);
/*
if ($rows>0) {
    for ($i=0; $i< count($val_r); $i++) {
		 $u . $col_r[$i] . "=" . $val_r[$i]; 
		 $u . ($i<(count($val_r)-1)) ?", " :"";
	 }
	$insert_ob="UPDATE imk_OB SET " . $u . $w;
	}
else	
 $insert_ob="INSERT INTO imk_OB VALUES(" . $val .")";
$this->errorlog->addlog( 'updateOB: '.$insert_ob);
 $this->dp->setQuery($insert_ob);
*/ 
}


function countEntries($from, $to)
{
 $secInfo = $this->markets[$this->cfg->market]->getActiveSec();

 $sql="SELECT  count(*)"
	. " FROM imk_IT USE INDEX (PRIM_ID)"
	. " WHERE "
	. " SECURITY_ISIN like '".$secInfo->getIsinSec()."'"
	. " AND TRADE_DATE = '". $this->getSessionDate()."'"
	. " AND OASIS_TIME >='".$from."' AND OASIS_TIME <= '".$to."'";  

 print($sql."\n");
 
  $this->dp->setQuery($sql);
  return ($this->dp->loadResult());
  }
  	
function __destruct ()
	{
	echo "Thread is getting down: " . $this->ficsnd->fname . "\n";
	unset ($this->errorlog);
	unset ($this->ficsnd);
	unset ($this->Msgs_collection);
	unset ($this);
	}
}

class oradb_messages extends messages_io
{
 }
/*
SELECT DISTINCT bid.price bid, ask.price ask, sum( 
CASE WHEN bid.side = 'B'
THEN bid.volume
END ) bv, sum( 
CASE WHEN ask.side = 'S'
THEN ask.volume
END ) av
FROM `imk_OB` bid, imk_OB ask
WHERE bid.STATUS <7
AND ask.STATUS <7
AND bid.side = 'B'
AND ask.side = 'S'
AND bid.SECURITY_ISIN = 'GRS260333000'
AND bid.SECURITY_ISIN = ask.SECURITY_ISIN
GROUP BY bid.price, abs( ask.price ) 
ORDER BY bid.price DESC , ask.price DESC
*/

/* Read next line from a log
$handle = popen("tail -f /etc/httpd/logs/access.log 2>&1", 'r');
while(!feof($handle)) {
    $buffer = fgets($handle);
    echo "$buffer<br/>\n";
    ob_flush();
    flush();
}
pclose($handle);*/
?>