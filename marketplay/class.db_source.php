<?php
require_once dirname(__FILE__) . '/class.ora_db.php';
/** 
* Maintains the mechanism of downloading data from Oracle DB
* @package db_source 
* @version 2.0.1
* @author JL
*/

class db_source extends ora_db
{
/*
 public $context;
 public $MSG_r;
 public $db;
 public $oraConn;
 public $dsn = array(
    'phptype'   => 'oci8',
    'username'  => 'irene',
    'password'  => 'pilot',
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
 // if ($parent->source==_FIC)
	//	$this->init_ficORATables();
			
  if ($parent->source==_SUP) {
		$this->init_supORATables();
		}

  $parent->MSG_r = $this->MSG_r;	
  return true;	
  }
*/
function _init()
 {
  $parent=$this->context;
  
  if ($parent->source==_SUP) {
		$this->init_supORATables();
		}

  $parent->MSG_r = $this->MSG_r;	
  return true;	
  }
/** 
* Sets the parameters of MySql tables filled from Oracle DB
* @version 2.0.1
* @author JL
*/  
 function init_supORATables()
 {
 	 $this->MSG_r=array ("IA"=>array("MARKET_ID","callbackget_markets_intoDB","select MARKET_ID, exchange_id,GET_OASIS_DESC.Get_Market_Desc(MARKET_ID) AS MKT_NAME from mm_markets WHERE exchange_id='ASE'","MARKET_ID,EXCHANGE_ID,HELLENIC_MARKET_NAME","","","","","Q",2,"",""),
						 "IB"=>array("MARKET_ID,BOARD_ID,ISIN_CODE","callbackget_securities_intoDB","mm_securities","MARKET_ID,BOARD_ID,ISIN_CODE,SECURITY_ENGLISH_SYMBOL,SECURITY_HELLENIC_SYMBOL,SECURITY_ENGLISH_NAME,SECURITY_HELLENIC_NAME,SECURITY_TYPE,ENGLISH_CURRENCY_SYMBOL,ENTITLEMENT_CODE,ENTITLEMENT_VALUE,PRICE_TYPE,PAR_VALUE,SECTOR_ID","","","VALID_BOARD_ID","Y","",2,"",""),
						
						 "IF"=>array("OASIS_TIME,MARKET_ID,BOARD_ID,STATUS","callbackget_marketsphases_intoDB","mm_market_phases","","","","",$this->getInsColumns('IF'),"",0,"",""),
						 "IC"=>array("INDEX_ID","callbackget_indexes_intoDB","SELECT ase_symbol,idx_code,isin_code,name FROM mm_market_indexes","HELLENIC_INDEX_SYMBOL,INDEX_ID,INDEX_ISIN,HELLENIC_INDEX_NAME","","","","","Q",2,"",""),
						 "IE"=>array("INDEX_ID","","","","","","","","",0,"",""),
						 "ID"=>array("INDEX_ID","","","","","","","","",0,"",""),
						 "IG"=>array("OASIS_TIME,MARKET_ID,SECURITY_ISIN","callbackget_halts_intoDB","mm_oasis_security_halts","OASIS_TIME,SECURITY_ISIN,HALT_SUSPEND_REASON_IN_ENGLISH,HALT_SUSPEND_REASON_IN_HELLENIC,HALT_SUSPEND_REASON_CODE","","","","","Q",1,"IG_callback_erase_isin",""),
						 "MF"=>array("ID","","","","","","","","",1,"MF_callback_erase_isin",""),						 
						 "II"=>array("OASIS_TIME,MARKET_ID,TRADE_DATE,ISIN_CODE","callbackget_openprice_intoDB","mm_stock_day_trans",$this->getColumns('II'),"","","",$this->getInsColumns('II'),"",1,"II_callback_erase_isin",""),
						 "SES"=>array("ID","","","","ID","","",$this->getInsColumns('SES'),"",0,"",""),
						 "SS"=>array("ID","","","","ID,TRADER","","",$this->getInsColumns('SS'),"",1,"SS_callback_erase_isin",""),
						 "ST"=>array("ID","","","","fdate,security_isin","","",$this->getInsColumns('ST'),"",1,"ST_callback_erase_isin",""),
						 "IH"=>array("OASIS_TIME,MARKET_ID,TRADE_DATE,SECURITY_ISIN,PHASE_ID","callbackget_securityphases_intoDB","mm_oasis_security_phases",$this->getColumns('IH'),"","","",$this->getInsColumns('IH'),"",1,"IH_callback_erase_isin",""),
						 
						 "IJ"=>array("trade_date, security_isin","callbackget_bbo_intoDB","mm_oasis_bbo",$this->getColumns('IJ'),"","","",$this->getInsColumns('IJ'),"",1,"IJ_callback_erase_isin",""),				 
						 "IL"=>array("INDEX_ID","","","","","","","","",0,"",""),
						 "IM"=>array("INDEX_ID","","","","","","","","",0,"",""),
						 "IN"=>array("INDEX_ID","","","","","","","","",0,"",""),
						 "IT"=>array("ORDER_ID,TRADE_DATE,SECURITY_ISIN","callbackget_orders_intoDB","mm_oasis_orders",$this->getColumns('IT'),"ORDER_NUMBER,SECURITY_ISIN,OASIS_TIME,SIDE,ORDER_MOD,ORDER_STATUS,PRICE","LIMIT","",$this->getInsColumns('IT'),"",1,"IT_callback_erase_isin","OASIS_TIME,SECURITY_ISIN,ORDER_ID,ORDER_NUMBER,TRADER_ID,SUPERVISOR_ID"),
						 "IS"=>array("TRADE_NUMBER","callbackget_executions_intoDB","mm_oasis_executions",$this->getColumns('IS'),"OASIS_TIME,BUY_ORDER_NUMBER,SELL_ORDER_NUMBER","","",$this->getInsColumns('IS'),"",1,"IS_callback_erase_isin","OASIS_TIME,SECURITY_ISIN,TRADE_NUMBER,BUY_TRADER_ID,SELL_TRADER_ID"),
						 "IK"=>array("OASIS_TIME,MARKET_ID,TRADE_DATE,SECURITY_ISIN","callbackget_projected_intoDB","mm_oasis_projected_price",$this->getColumns('IK'),"OASIS_TIME,SECURITY_ISIN","","",$this->getInsColumns('IK'),"",1,"IK_callback_erase_isin",""),
						 "OB"=>array("SESSION,ORDER_NUMBER,FDATE,SECURITY_ISIN","","",$this->getColumns('OB'),"","","",$this->getInsColumns('OB'),"",1,"OB_callback_erase_isin",""),
						 "OBS"=>array("SESSION,ORDER_NUMBER,FDATE,SECURITY_ISIN","","",$this->getColumns('OBS'),"","","",$this->getInsColumns('OBS'),"",1,"OBS_callback_erase_isin","OASIS_TIME,SECURITY_ISIN,ORDER_ID,ORDER_NUMBER"),
						 "OP"=>array("ID","","","","","","","","",1,"OP_callback_erase_isin",""),
						 "events"=>array("ID","","","","","","","","",1,"EVENTS_callback_erase_isin",""),
						 
						 );					
	}


/** 
* Get the Columns details of a specific table
* @version 2.0.1
* @author JL
* @param string $msgId (OASIS msg id)
* @return array -of arrays: Column names/titles-
*/  	
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
		$i=0;
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				continue;
				}
														//  selected to appeared, header==1
			if ($val->get(_IDX_HEADER)) {
				if ($columns!='')
	    			$columns.=',';
				$columns .= ($msgId=='IS' ?'s.' :($msgId=='IT' ?'t.' :'')) . $fldName;
				$i++;
		    	if ($titles!='')
			    	$titles.=',';
				$titles .= $val->get(_IDX_TITLE);
				}
				//else
				 //$this->errorlog->addlog( 'getColumns: '.$msgId." - ".$fldName."=".$val->get(_IDX_HEADER) );
			}
		$ar['columns']=$columns;
		$ar['titles']=$titles;
		$ar['count']=$i;
		
	return $ar;	
	}

/** 
* Get the Columns details of a specific table
* @version 2.0.1
* @author JL
* @param string $msgId (OASIS msg id)
* @link oasis_messages 
* @return array -of arrays: Column names/titles-
*/
function getInsColumns($msgId)
	{
	 $parent=$this->context;
	
	 $table=$parent->Msgs_collection->getMsg($msgId);
		
		if( ($table instanceof Collection) == false)
		 	{
			 $parent->errorlog->addlog( 'getInsColumns: table is null - '.$msgId );
			 return '';
			}	
		
		$ar=array();
 		foreach($table as $fldName=>$val) {
			if (is_numeric($fldName)) {
				continue;
				}
			
				if ($columns!='')
	    			$columns.=',';
				$columns .= $fldName;
			
			}
		$ar['columns']=$columns;
		
	return $ar;	
	}
		
/**
* Called by Service3::deleteMarket in order to delete records by session date
*/
public function clearSessionData()
{
  $parent=$this->context;
	 if (DB::isError($this->oraConn)) {			
	 //if (!$this->oraConn){
	 	$parent->errorlog->addlog( "prepareMarket: no connection to ORA - " );
		return false;
	 	}
		
  foreach($this->MSG_r as $msg=>$info) 
  {
    if ($info[_MSG_IDX_CRETAB]==0)
	    continue;

	$func=$this->MSG_r[$msg][_MSG_IDX_DELISIN];
	if ( $func!="" ) 
	{
	  if (method_exists('db_source', $func))
	  {
			     		//print( "prepareMarket: exec: ".$func."\n");
		  $parent->errorlog->addlog( "prepareMarket: exec: ".$func);
	 	  $this->$func($parent,$msg);
		}
	}
   }
 return true;
 }
 				
				
 function prepareMarket()
	{
	 $parent=$this->context;
	 if (DB::isError($this->oraConn)) {			
	 //if (!$this->oraConn){
	 	$parent->errorlog->addlog( "prepareMarket: no connection to ORA - " );
		return false;
	 	}

	 foreach($this->MSG_r as $msg=>$info) {
		    if ($info[_MSG_IDX_CRETAB]==0)
			    continue;
		print ("PrepareMarket - $msg:".$parent->init_flag."\n");	 
		 	if (($parent->init_flag==_DO_PARTIAL_INITIALIZATION || $parent->init_flag==_DO_ERASE) && $info[_MSG_IDX_CRETAB]!=2)	{
				 $func=$this->MSG_r[$msg][_MSG_IDX_DELISIN];
				 if ( $func!="" ) {
			    	if (method_exists('db_source', $func)){
			       		print( "prepareMarket: exec: ".$func."\n");
				   		$parent->errorlog->addlog( "prepareMarket: exec: ".$func);
				 		$this->$func($parent,$msg);
						}
			 		}
				}
			
			if ($parent->init_flag==_DO_ERASE)
				continue;
			
			if ($info[_MSG_IDX_CRETAB]==2 && $parent->init_flag==_DO_PARTIAL_INITIALIZATION)
				continue;
			
			$func=$this->MSG_r[$msg][_MSG_IDX_INSERT];
			if ( $func!="" ) {
			    if (method_exists('db_source', $func)){
				   		print( "prepareMarket: exec: ".$func."\n");
				   		$parent->errorlog->addlog( "prepareMarket: exec: ".$func);
					    $this->$func($parent,$msg);
					}
			 	}
			}
/*		
	if ($this->MSG_r['IT'][_MSG_IDX_COLUMNS] != '') {

		if (is_a($parent->markets[$parent->cfg->market],'mkt_info')){
		     print "market OK!".$parent->cfg->market."\n";
			 
		    $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  			$isin=$secInfo->getIsinSec();
$parent->errorlog->addlog( 'prepareMarket: - isin='.$isin);			
			$info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
			$info->savePhases();
			$info->reset_currphase();
			}
		   else
			 	print "prepareMarket problem market! ".$parent->cfg->market. "ISIN:".$isin."\n";
		}
		*/
//	$parent->errorlog->addlog( 'prepareMarket: - Trades witten='.$parent->Written_trades);
//	unset($parent->marketGraphObj);	
	}


 function disconnectORA()
 {
  $ret=$this->db->disconnect();
  $this->context->errorMessage="Oracle user:".$this->dsn['username']." Disconnected! ".$ret;
  print ("Oracle user:".$this->dsn['username']." Disconnected! ".$ret."\n");
  
  return $ret;
  }
 	


/** 
* SQL Callback Functions to clean up local tables of specific SECURITY/Date, based on market obj getSecQstr() and getSessionDate
* @version 2.0.1
* @author JL
* @param messages_io $parent, $msgId (OASIS msg id)
*/  
 public function II_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE ISIN_CODE in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'II_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------  
 public function IT_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IT_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------  
 public function IS_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IS_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------
 public function SS_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND fdate=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'SS_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------
 public function ST_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND fdate=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'ST_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------
 public function MF_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE ISIN_CODE in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND FDATE=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'MF_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------
 public function OP_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND fdate=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'OP_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//------------------------------------------------------- 
 public function IK_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";
  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IK_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------
 public function IG_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IG_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------  
 public function IH_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IH_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }  
//-------------------------------------------------------  
 public function IJ_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND TRADE_DATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'IJ_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }    
//-------------------------------------------------------  
 public function OB_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND FDATE=\"".$parent->getSessionDate()."\"";

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'OB_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }
//-------------------------------------------------------    
 public function OBS_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND FDATE=\"".$parent->getSessionDate()."\"";  

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'OBS_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  } 
//-------------------------------------------------------    
 public function EVENTS_callback_erase_isin($parent,$msgId)	
 {
  $q1 = "DELETE FROM imk_$msgId WHERE SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" . " AND FDATE=\"".$parent->getSessionDate()."\"";  

  $parent->dp->setQuery(stripslashes($q1));
  $parent->dp->query();
  
  $err=$parent->dp->getAffectedRows();	
  if ($err < 1)
       print( 'EVENTS_callback_erase_isin: err='.$parent->dp->stderr()."==".$q."\n");
  }                    
//-------------------------------------------------------
//	DELETE Partial.......
//-------------------------------------------------------


/** 
* SQL Callback Functions to Insert into tables, based on function internal query to Oracle
* @version 2.0.1
* @author JL
* @param messages_io $parent, $msgId (OASIS msg id)
*/ 
 public function callbackget_markets_intoDB($parent,$msgId)
 {
  	
	if ($this->MSG_r[$msgId][_MSG_IDX_FLAG]=='Q')
	    $this->query = $this->MSG_r[$msgId][_MSG_IDX_ORA_TABNAME];
	else
	 $this->query = "SELECT " . $this->MSG_r[$msgId][_MSG_IDX_COLUMNS]['columns'] 
			. " FROM ". $this->MSG_r[$msgId][_MSG_IDX_ORA_TABNAME];
//			. " WHERE SECURITY_ISIN like \"$isin\"";
//	 print ('markets_intoDB query='.$this->query."\n");
	$stmt = $this->db_query();
//  $stmt=$this->db->simpleQuery($this->query);
//  $parent->errorlog->addlog( 'simpleQuery: -Execute OK! '.$stmt);

	if (DB::isError($stmt))
	{
			$parent->errorlog->addlog( 'callbackget_securities_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
	$values='';
    $columns='';
 
  $cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
  // $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "markets_intoDB - ".$err."==".$this->query."\n");
	   }
    }
 }
 
 public function callbackget_securities_intoDB($parent,$msgId)
 {
  $parent=$this->context;
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();

  $this->query = "SELECT market_id,valid_board_id,isin,english_symbol,hellenic_symbol,english_name,hellenic_name,"
  	.	" security_type_sub_type,currency_code,entitlement_code,entitlement_value,price_type,par_value,sector_id"     
  	. 	" FROM mm_dba.mm_securities"
	.	" where"
  	.	" substr(valid_board_id,0,1)='Y'"
	.	" and market_id='".$parent->cfg->market."'"
	.	" and trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')";

	$stmt = $this->db_query();
 
	if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_securities_intoDB: -err in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
  $values='';
 
  $cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
   
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';
	//if ($row->ISIN==$isin)
	if (in_array($row->ISIN,$parent->filter_sec))
	{
	 $secInfo = $parent->markets[$parent->cfg->market]->getSec($row->ISIN);
	 $secInfo->gr_symbol=mb_convert_encoding($row->HELLENIC_SYMBOL, "UTF-8", "ISO-8859-7");
     $secInfo->en_symbol=mb_convert_encoding($row->ENGLISH_SYMBOL, "UTF-8", "ISO-8859-7");
	 
	 $q="update imk_imarket_cfg set gr_symbol=\"$secInfo->gr_symbol\" where id=".$parent->session->sesid;
	 $parent->dp->setQuery(stripslashes($q));
   	 $parent->dp->query();
     $err=$parent->dp->getAffectedRows();	
     if ($err < 1)
	     print( 'symbol: err='.$parent->dp->stderr()."==".$q."==".$secInfo->gr_symbol."\n");
	 }
	
	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    }
 }

 public function callbackget_indexes_intoDB($parent,$msgId)
 {
 
  $this->query = '';
  if ($this->MSG_r[$msgId][_MSG_IDX_FLAG]=='Q')
	    $this->query = $this->MSG_r[$msgId][_MSG_IDX_ORA_TABNAME];
  
  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_indexes_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  $cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
 //$cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    }
 }
 
 public function callbackget_securityphases_intoDB($parent,$msgId)
 { 
  $secInfo = $parent->markets[$parent->cfg->market]->setActiveFirstSec();
  $isin=$secInfo->getIsinSec();
  $info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
  $thisIsin='';
  
  if( ($info instanceof msg_info) == false)
     print ("callbackget_securityphases_intoDB - info phases error!\n");
	 
  $this->query = "SELECT  a.trade_time as OASIS_TIME, '". $msgId . "' AS msg_id, a.market_id, to_char(a.trade_date,'YYYYMMDD') as trade_date, a.isin, a.phase_id"
  		.	" FROM mm_oasis_security_phases a"
  		.	" where"
  		.	" a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" and a.market_id='".$parent->markets[$parent->cfg->market]->getMarket()."'"
		.	" and a.isin in (".$parent->markets[$parent->cfg->market]->getSecQstr().")"
		.	" ORDER BY isin, trade_time";
//print($this->query."\n");
  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_securityphases_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
 //print_r( $cols);
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	if ($row->ISIN != $thisIsin) {
	    // Update current Sec Phase
		if ( $thisIsin!='') {
			$info->fixEndPhase();
			$info->reset_currphase(); 
			}
		// Get another Sec Object & its Phase
		//$secInfo = $parent->markets[$parent->cfg->market]->setActiveSec($row->ISIN);
		$secInfo = $parent->markets[$parent->cfg->market]->getSec($row->ISIN);
		$isin=$secInfo->getIsinSec();
	    $info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
		$thisIsin=$row->ISIN;
		}
		
	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$ophase=mb_convert_encoding($row->PHASE_ID, "UTF-8", "ISO-8859-7");
//	print("callbackget_securityphases_intoDB - CALL set_IFphase - phase:".$ophase."\n");
	
	print("callbackget_securityphases_intoDB - set_IFphase - ".$secInfo->getIsinSec() ." phase:".$ophase."==".$info->get_currphase()."\n");
	if ( $info->set_IFphase((($ophase=='') ?'P' :$ophase),$msgId,$row->OASIS_TIME) != true)
	    print("callbackget_securityphases_intoDB - set_IFphase err - ".$secInfo->getIsinSec() ." phase:".$ophase."==".$info->get_currphase()."\n");
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
//print($this->query."\n");
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
			//echo $this->query."\n";
		  $parent->errorlog->addlog( "securityphases_intoDB - ".$err."==".$this->query."\n");
	   }
    }
	// Update current Sec Phase
	
	
	$secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
	do
	{
     if ($secInfo->sessionSecs == 0)
	 {
	   $info= $secInfo->getPhase();
	   $info->fixEndPhase();
	   $info->reset_currphase(); 
	  }
	 }while(($secInfo = $parent->markets[$parent->cfg->market]->setActiveNextSec()));	
	
	$secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
	$info= $secInfo->getPhase();
	$info->reset_currphase(); 
  }

  
 public function callbackget_marketsphases_intoDB($parent,$msgId)
 {
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
  $info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
  
  $this->query = "SELECT  a.trade_time as OASIS_TIME, '". $msgId . "' AS msg_id,  a.market_id,".$parent->markets[$parent->cfg->market]->getBoard()." AS BOARD_ID, ".  "a.phase_id"
  		.	" FROM mm_market_phases a,mm_securities b"
  		.	" where"
  		.	" a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" and a.market='".$parent->markets[$parent->cfg->market]->getMarket()."'"
		.	" and a.ase_symbol=b.HELLENIC_SYMBOL"
		.	" and b.isin='".$isin."'"
  		.	" and b.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" ORDER BY isin, PHASE_TIME";
  
  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_halts_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  $cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$ophase=mb_convert_encoding($row->PHASE_ID, "UTF-8", "ISO-8859-7");
	$info->set_IFphase((($ophase=='') ?'P' :$ophase),$msgId,$row->OASIS_TIME);
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    } 
  }
  
 public function callbackget_halts_intoDB($parent,$msgId)
 {
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $symbol=mb_convert_encoding($secInfo->gr_symbol, "ISO-8859-7", "UTF-8") ;   
  
  $this->query = "SELECT a.HALT_TIME as OASIS_TIME, '".$secInfo->getIsinSec()."' as isin,a.description, a.description_greek,a.reject_code"
  		.	" FROM mm_oasis_security_halts a" // a,mm_securities b"
  		.	" where"
  		.	" a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
  		.	" and a.ase_symbol='".$symbol. "'"//b.HELLENIC_SYMBOL" $secInfo->gr_symbol
//		.	" and b.isin='".$secInfo->getIsinSec()."'"
//  		.	" and b.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" ORDER BY HALT_TIME";

  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_halts_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  //$cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    } 
  }

 
 public function callbackget_bbo_intoDB($parent,$msgId)
 {
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $symbol=mb_convert_encoding($secInfo->gr_symbol, "ISO-8859-7", "UTF-8") ;  
  
   $this->query = "SELECT MSG_TIME as OASIS_TIME,'". $msgId ."' AS MESSAGE_TYPE, MARKET_ID, to_char(a.trade_date,'YYYYMMDD') as trade_date, a.security_isin, a.num_of_bbo_levels,"
	.	" a.volume_type, a.ato_mkt_atc_buy_volume, a.ato_mkt_atc_buy_orders, a.sell_ato_mkt_atc_volume, a.ato_mkt_atc_sell_orders, a.top_bid_prices_1, a.irr_bid_price_1,"
    .	" a.top_bid_volumes_1, a.num_bid_orders_1, a.top_offer_prices_1, a.irr_offer_price_1, a.top_offer_volumes_1, a.num_offer_orders_1, a.top_bid_prices_2, a.iir_bid_price_2, a.top_bid_volumes_2,"
	.	" a.num_bid_orders_2, a.top_offer_prices_2, a.irr_offer_price_2, a.top_offer_volumes_2, a.num_offer_orders_2, a.top_bid_prices_3, a.iir_bid_price_3, a.top_bid_volumes_3, a.num_bid_orders_3,"
	.	" a.top_offer_prices_3, a.irr_offer_price_3, a.top_offer_volumes_3, a.num_offer_orders_3, a.top_bid_prices_4, a.iir_bid_price_4, a.top_bid_volumes_4, a.num_bid_orders_4, a.top_offer_prices_4,"
	.	" a.irr_offer_price_4, a.top_offer_volumes_4, a.num_offer_orders_4, a.top_bid_prices_5, a.iir_bid_price_5, a.top_bid_volumes_5, a.num_bid_orders_5, a.top_offer_prices_5, a.irr_offer_price_5,"
	.	" a.top_offer_volumes_5, a.num_offer_orders_5, ETS_SERIAL_NBR"
  		.	" FROM mm_oasis_bbo a"
  		.	" where"
  		.	" a.market_id='".$parent->markets[$parent->cfg->market]->getMarket()."'"
		.	" and a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
  		.	" and a.ASE_SYMBOL='".$symbol. "'"//b.HELLENIC_SYMBOL" $secInfo->gr_symbol
		.	" ORDER BY MSG_TIME";
 
  $stmt = $this->db_query();
 // print ($this->query);
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_bbo_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			//$this->disconnectORA();
			return;
			}	
   
  //$cols = $this->MSG_r[$msgId][_MSG_IDX_COLUMNS];
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    } 
  }
  
   
 public function callbackget_orders_intoDB($parent,$msgId)
 {
    $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
	$isin=$secInfo->getIsinSec();
    $info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
	$stime = $info->get_currphaseTime();
	if ($stime==""){
		$info->advancePhasePointer();
		$stime = $info->get_currphaseTime();
		}
		
		//$parent->errorlog->addlog(  '_orders_intoDB: curPhase='. $info->get_currphase()."==".$stime);	
	$ostat='';
	$thisIsin='';
/*
	$this->query = "select HELLENIC_SYMBOL from mm_oasis_security_phases a,mm_securities b"
        .	" where a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" and a.ase_symbol=b.HELLENIC_SYMBOL "
		.	" and b.isin='".$secInfo->getIsinSec()."'" ;
//	print( '_orders_intoDB: sub_q='. stripslashes($this->query)."\n");	
	$stmt = $this->db_query();
   
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_orders_intoDB: -err:'.$stmt->getCode().' in: q='. stripslashes($this->query));
			$this->disconnectORA();
			return;
			}
  $row = oci_fetch_object($stmt);
//  print_r($row);
  $stime=$row->TRADE_TIME;
*/
  $this->query = "SELECT "
  		.	" CASE WHEN (a.LAST_ORDER_UPDATE_DATE < a.trade_date) THEN " . "'". $stime . "'"
		.	"         ELSE a.last_order_update_time END as OASIS_TIME,"
		.	" 0 as ORDER_MOD, a.order_seq, a.record_type, a.market_id, a.board_id, a.phase_id,"
       .	" a.side, to_char(a.trade_date,'YYYYMMDD') as trade_date, a.security_isin, a.security_status, a.order_number,"
       .	" a.member_order_number, to_char(a.order_entry_date,'YYYYMMDD') as order_entry_date, a.order_entry_time,"
       .	" to_char(a.order_release_date,'YYYYMMDD') as order_release_date, a.order_release_time,"
       .	" a.last_update_user_id, to_char(a.trade_date,'YYYYMMDD') as last_order_update_date,"
       .	" a.last_order_update_time, a.volume, a.disclosed_volume,"
       .	" a.matched_volume, a.autodisclose_quantity, a.condition_volume,"
       .	" a.price, a.original_price_type, a.special_conditions,"
       .	" to_char(a.expiration_date,'YYYYMMDD') as expiration_date, a.order_lifetime, a.trade_id, a.member_id,"
       .	" a.supervisor_id, a.csd_account_id, a.order_sourse,"
       .	" a.order_status, a.m_c_flag, a.stop_loss_value, a.stop_market_id,"
       .	" a.stop_loss_symbol, a.order_remark, a.clearing_member_id,"
       .	" a.ip_address, 0 as IRR"
  		.	" FROM mm_oasis_orders a"
		.	"   where"
		. 	"   a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
  		.	"   and SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")" //='".$secInfo->getIsinSec()."'"
		.	" ORDER BY security_isin, order_seq"; //OASIS_TIME
//		.	" ORDER BY OASIS_TIME,order_entry_date,order_number";
	
//			print( '_orders_intoDB: q='. stripslashes($this->query)."\n");	
  $stmt = $this->db_query();
  
  if (DB::isError($stmt) || $stmt==false)
		{
			$parent->errorlog->addlog( 'callbackget_orders_intoDB: -err:'.$stmt->getCode().' in: q='. stripslashes($this->query));
			$this->disconnectORA();
			return;
			}	
 
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
/*  
  if (!($parent->markets[$parent->cfg->market] instanceof mkt_info))	
	  		$parent->errorlog->addlog( "orders_intoDB active ISIN EROR!: ".$parent->cfg->market);			   
 
  $info = $parent->markets[$parent->cfg->market]->getSecPhase($parent->markets[$parent->cfg->market]->getActiveSec()->isin); 
  if (!($info instanceof msg_info))
  {
	$parent->errorlog->addlog( "orders_intoDB get phase error: ".$parent->cfg->market."==".$parent->markets[$parent->cfg->market]->getActiveSec()->isin);
	return;
	}
	*/  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	if ($row->SECURITY_ISIN != $thisIsin) {
	   
		// Get another Sec Object & its Phase
		//$secInfo = $parent->markets[$parent->cfg->market]->setActiveSec($row->SECURITY_ISIN);
		$secInfo = $parent->markets[$parent->cfg->market]->getSec($row->SECURITY_ISIN);
		$isin=$secInfo->getIsinSec();
	    $info = $parent->markets[$parent->cfg->market]->getSecPhase($isin);
		$thisIsin=$row->SECURITY_ISIN;
		}
		
	$secInfo->totalOrders++;
	
	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($col=='VOLUME')
	{
		//$secInfo->maxVolTrader=($secInfo->maxVol < $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->maxVolTrader;
		//$secInfo->minVolTrader=($secInfo->minVol > $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->minVolTrader;
		if ($row->SIDE=='B'){
		    $secInfo->maxVolTrader=(($secInfo->maxVol < $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->maxVolTrader);
			$secInfo->minVolTrader=(($secInfo->minVol > $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->minVolTrader);
			$secInfo->minVol=min($secInfo->minVol,$val*1);
			$secInfo->maxVol=max($secInfo->maxVol,$val*1);
			}
			else {
					$secInfo->maxVolSellTrader=(($secInfo->maxSellVol < $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->maxVolSellTrader);
					$secInfo->minVolSellTrader=(($secInfo->minSellVol > $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->minVolSellTrader);
					$secInfo->minSellVol=min($secInfo->minSellVol,$val*1);
					$secInfo->maxSellVol=max($secInfo->maxSellVol,$val*1);
					}	
				
	}
	elseif ($col=='PRICE')
	{
		
		
		 // market price
			 if ($row->SIDE=='B'){
			 	$secInfo->maxPriceTrader=(($secInfo->maxPrice < $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->maxPriceTrader);
				$secInfo->maxPrice=max($secInfo->maxPrice,$val*1);
				if ((int)$val > 0){ 
					$secInfo->minPriceTrader=(($secInfo->minPrice > $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->minPriceTrader);
					$secInfo->minPrice=min($secInfo->minPrice,$val*1);
					}
			 	}
			 else {
			 		$secInfo->maxPriceSellTrader=(($secInfo->maxSellPrice < $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->maxPriceSellTrader);
					$secInfo->maxSellPrice=max($secInfo->maxSellPrice,$val*1);
					if ((int)$val > 0) {
			     		$secInfo->minPriceSellTrader=(($secInfo->minSellPrice > $val*1) ?$row->TRADE_ID.'-'.$row->CSD_ACCOUNT_ID :$secInfo->minPriceSellTrader);
						$secInfo->minSellPrice=min($secInfo->minSellPrice,$val*1);
						}
			 		}	 
		
	}
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
//	$ophase=mb_convert_encoding($row->PHASE_ID, "UTF-8", "ISO-8859-7");
//	$ostat=mb_convert_encoding($row->ORDER_STATUS, "UTF-8", "ISO-8859-7");
//	$oside=mb_convert_encoding($row->SIDE, "UTF-8", "ISO-8859-7");
	 
	$u_err = $this->changeExistedOrder($parent,$row->ORDER_NUMBER,$row->OASIS_TIME,$msgId,&$ostat,$row->PRICE,$oside);
	
//	$info->set_phase((($ophase=='') ?'P' :$ophase), $msgId, $row->OASIS_TIME, $ostat);
	$secInfo->marketGraphObj->addDataFile($row->OASIS_TIME,$row->VOLUME,$row->PRICE);	
			 
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "orders_intoDB - ".$err."==".$this->query."\n");
		  break;
	   }
    }
	
	$secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
	do
	{
	  print("orders_intoDB ".$secInfo->isin."==".$secInfo->sessionSecs."\n");
	  if ($secInfo->sessionSecs == 0)
	  {
	   $info= $secInfo->getPhase();
	   $info->setPhasesOrders();
	   $info->savePhases();
	   $info->reset_currphase();
	  }
	 }while(($secInfo = $parent->markets[$parent->cfg->market]->setActiveNextSec()));	
  } 
  
 
 function changeExistedOrder($msgobj,$order,$otime,$tabname,&$ostat,$oprice,$oside)
 {
	 $err=0;
	 $stat=$ostat;
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
	
 public function callbackget_executions_intoDB($parent,$msgId)
 {
   $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
   $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
   
  $this->query = "SELECT a.TRADE_TIME as OASIS_TIME, a.record_type, a.market_id, a.board_id, a.phase_id,"
       .	" a.SECURITY_ISIN, a.SECURITY_STATUS, a.TRADE_TYPE, a.TRADE_SOURCE,"
	   .	" a.trade_number, a.price, a.volume, a.trade_status,  to_char(a.trade_date,'YYYYMMDD') as trade_date,"
       .	" a.trade_time, a.report_only_execute_time, a.buy_trade_id,"
       .	" a.buy_member_id, a.buy_member_order_number, a.buy_order_number,"
       .	" to_char(a.buy_order_date,'YYYYMMDD') as buy_order_date, a.buy_csd_account_id, a.buy_m_c_flag,"
       .	" a.buy_clearing_member_id, a.buy_ip_address, a.sell_trade_id,"
       .	" a.sell_member_id, a.sell_member_order_number,"
       .	" a.sell_order_number, to_char(a.sell_order_date,'YYYYMMDD') as sell_order_date, a.sell_csd_account_id,"
       .	" a.sell_m_c_flag, a.sell_clearing_member_id, a.sell_ip_address,"
       .	" a.update_cancel_time, to_char(a.update_cancel_date,'YYYYMMDD') as update_cancel_date, a.update_user_id, 0 as TRADE_VALUE"
  		.	" FROM mm_oasis_executions a"
		.	"   where"
		. 	"   a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
  		.	"   and SECURITY_ISIN in (".$parent->markets[$parent->cfg->market]->getSecQstr().")";
	
	
  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_executions_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  // $parent->errorlog->addlog( 'callbackget_executions_intoDB: q:'.$this->query); 
 $prev_trade_time='';
 $trade_mils=0;
 
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';
		
	if ($row->SECURITY_ISIN != $thisIsin) {
		$secInfo = $parent->markets[$parent->cfg->market]->getSec($row->SECURITY_ISIN);
		$thisIsin=$row->SECURITY_ISIN;
		}

	$secInfo->totalTrades++;
	
	foreach($row as $col=>$val)
	{
	 if ($col=='OASIS_TIME')
	 {
	  if ($val==$prev_trade_time)
	  {     
		  $val .= '0';
		  if ($trade_mils==0)
		      $trade_mils=(int)substr($val,7,2);
		  $val = substr($val,0,7) . ++$trade_mils;
		  }
		else {	
			  $prev_trade_time = $val;
			  $trade_mils=0;
			  $val .= '0';
			  }
		}
	
	if ($col=='PRICE')
	{
		$secInfo->minTradePrice=min($secInfo->minTradePrice,$val*1);
		$secInfo->maxTradePrice=max($secInfo->maxTradePrice,$val*1);
	}
	elseif ($col=='VOLUME')
	{
		$secInfo->minTradeVol=min($secInfo->minTradeVol,$val*1);
		$secInfo->maxTradeVol=max($secInfo->maxTradeVol,$val*1);
				
		$secInfo->totTradeVol += $val*1;
		$secInfo->totTradeValue += ($val*1) * ($row->PRICE*1);		
	}
	
	  						
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->markTradeOrders($msgId,$row->OASIS_TIME,$row->PRICE,$row->TRADE_NUMBER,$row->BUY_ORDER_NUMBER,$row->SELL_ORDER_NUMBER);
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "executions_intoDB - ".$err."==".$this->query."\n");
		  break;
	   }
    }
 	 
  } 

/** 
* Gather info from local and Ora tables. Insert resurlts into imk_MS,imk_SS.
* @version 2.0.1
* @author JL
*/ 
 
 function createMarketStats()
 { 
  $parent=$this->context;
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
  
  $q="SELECT DISTINCT concat( BUY_TRADER_ID, '-', BUY_CSD_ACCOUNT_ID ) AID"
	. " FROM imk_IS s"
	. " WHERE SECURITY_ISIN = \'$isin\'"
//	. " AND SESSION= ".$parent->session->sesid
	. " AND TRADE_DATE= '".$parent->getSessionDate()."'"
	. " ORDER BY AID";	

  $parent->dp->setQuery(stripslashes($q));
  $parent->dp->query();
  $rows = $parent->dp->loadObjectList();
  $s_bid_traders='';
  foreach($rows as $row){
  		$s_bid_traders.= ($s_bid_traders ?',':'')."'".mb_convert_encoding($row->AID, "ISO-8859-7", "UTF-8")  ."'";
  		}
  unset($rows);
  
  $q="SELECT DISTINCT concat( SELL_TRADER_ID, '-', SELL_CSD_ACCOUNT_ID ) AID"
	. " FROM imk_IS s"
	. " WHERE SECURITY_ISIN = \'$isin\'"
//	. " AND SESSION= ".$parent->session->sesid
	. " AND TRADE_DATE= '".$parent->getSessionDate()."'"	
	. " ORDER BY AID";

  $parent->dp->setQuery(stripslashes($q));
  $parent->dp->query();
  $rows = $parent->dp->loadObjectList();
  $s_ask_traders='';
  foreach($rows as $row){
  		$s_ask_traders.= ($s_ask_traders ?',':'')."'".mb_convert_encoding($row->AID, "ISO-8859-7", "UTF-8")."'";
  		}
  unset($rows);

 if ($s_bid_traders!='')
 {  
  $this->query = "SELECT sum( volume) vol, STID"
	. " from"
	. " ("
	. " SELECT  a.volume,"
	. "  a.buy_trade_id || '-' || a.buy_csd_account_id STID"
	. "  FROM mm_oasis_executions a"
	.	"   where"
	. 	"   a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
	. "   )m"
	. " WHERE STID IN (".$s_bid_traders.")"
	. "   group by STID"
	. "   order by 1 desc";

  $stmt = $this->db_query();
// print ("createMarketStats stmt:" .$stmt."\n".$this->query."\n"); 
  if (DB::isError($stmt) || $stmt==false)
		{
			$parent->errorlog->addlog( 'createMarketStats: -err:'.$stmt->getCode().' in: q='. $this->query);
		//	$this->disconnectORA();
			return;
			}	
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    
	 $q="INSERT INTO imk_MS VALUES(0,".$parent->session->sesid.",'".$parent->getSessionDate()."','".$isin."','".mb_convert_encoding($row->STID, "UTF-8","ISO-8859-7")."','".'B'."',".$row->VOL.",0,0)"; 
	
	$parent->dp->setQuery(stripslashes($q));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "createMarketStats - ".$err."==".stripslashes($q)."\n");
		  break;
	   }
	 }
  unset($stmt); 
  }
  	
 if ($s_ask_traders!='')
 {
  $this->query = "SELECT sum( volume) vol, STID"
	. " from"
	. " ("
	. " SELECT  a.volume,"
	. "  a.sell_trade_id || '-' || a.sell_csd_account_id STID"
	. "  FROM mm_oasis_executions a"
	.	"   where"
	. 	"   a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
	. "   )m"
	. " WHERE STID IN (".$s_ask_traders.")"
	. "   group by STID"
	. "   order by 1 desc";	
  
  $stmt = $this->db_query();
  
  if (DB::isError($stmt) || $stmt==false)
		{
			$parent->errorlog->addlog( 'createMarketStats: -err:'.$stmt->getCode().' in: q='. $this->query);
//			$this->disconnectORA();
			return;
			}	
   
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    
	$q="INSERT INTO imk_MS VALUES(0,".$parent->session->sesid.",'".$parent->getSessionDate()."','".$isin."','".mb_convert_encoding($row->STID, "UTF-8","ISO-8859-7")."','".'S'."',".$row->VOL.",0,0)"; 
	
	$parent->dp->setQuery(stripslashes($q));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "createMarketStats - ".$err."==".stripslashes($q)."\n");
		  print("createMarketStats - ".$err."==".stripslashes($q)."\n");
		  break;
	   }
	 }
	}
	 
	unset($s_bid_traders); 
	unset($s_ask_traders); 
	
	$this->query="INSERT INTO imk_SS VALUES(0,".$parent->session->sesid.",'".$parent->getSessionDate()."','".$isin
	.	"','".	mb_convert_encoding($secInfo->maxVolTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->maxPriceTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->minVolTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->minPriceTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->maxVolSellTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->maxPriceSellTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->minVolSellTrader, "UTF-8","ISO-8859-7")
	.	"','".	mb_convert_encoding($secInfo->minPriceSellTrader, "UTF-8","ISO-8859-7")
	.	"',".$secInfo->minVol
	.	",".$secInfo->maxVol
	.	",".$secInfo->minPrice
	.	",".$secInfo->maxPrice
	.	",".$secInfo->minSellVol
	.	",".$secInfo->maxSellVol
	.	",".$secInfo->minSellPrice
	.	",".$secInfo->maxSellPrice
	.	",".$secInfo->minTradeVol
	.	",".$secInfo->maxTradeVol
	.	",".$secInfo->minTradePrice
	.	",".$secInfo->maxTradePrice
	.	",".$secInfo->totTradeVol
	.	",".$secInfo->totTradeValue
	.	",".$secInfo->totalOrders
	.	",".$secInfo->totalTrades
	.	")";
	
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
	print( "marketStats_intoDB - ".$err."==".$this->query."\n");
		  $parent->errorlog->addlog( "marketStats_intoDB - ".$err."==".$this->query."\n");
	   }
  }

  
 public function callbackget_openprice_intoDB($parent,$msgId)
 {
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $q="select SECURITY_HELLENIC_SYMBOL from imk_IB where ISIN_CODE='".$secInfo->getIsinSec()."'";
  $parent->dp->setQuery(stripslashes($q));
  $symbol=$parent->dp->loadResult();
  $symbol=mb_convert_encoding($symbol, "ISO-8859-7", "UTF-8") ; 
   
  $this->query = "SELECT  '00000000' as OASIS_TIME, '" .$msgId . "' AS MESSAGE_TYPE, ". "'0' AS MESSAGE_INDICATOR, '". $parent->cfg->market. "' AS MKT, to_char(a.trans_date,'YYYYMMDD') AS trade_date, b.isin AS ISIN_CODE, '0' AS STATUS, a.price_open*100 as START_OF_DAY_PRICE, a.price_min*100 as FLOOR_PRICE_PCT, a.price_max*100 as CEILING_PRICE_PCT"
  		.	" FROM mm_stock_day_trans a, mm_securities b"
  		.	" where"
  		.	" a.trans_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
  		.	" and a.ase_symbol=b.HELLENIC_SYMBOL"
		.	" and b.isin in (".$parent->markets[$parent->cfg->market]->getSecQstr().")"
  		.	" and b.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')";

//print( "callbackget_openprice_intoDB:".$this->query);

  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_openprice_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
//			$this->disconnectORA();
			return;
			}	
   
  //$cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS];
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	  
	}
	
	$isinObj = $parent->markets[$parent->cfg->market]->getSec($row->ISIN_CODE);
	$isinObj->startPrice = $row->START_OF_DAY_PRICE;
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "openprice_intoDB - ".$err."==".$this->query."\n");
	   }
    }
	 
  }
  

 public function callbackget_projected_intoDB($parent,$msgId)
 {
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  //$q="select gr_symbol from imk_imarket_cfg where id=".$parent->session->sesid;
  //$parent->dp->setQuery(stripslashes($q));
  //$symbol=$parent->dp->loadResult();
  //$symbol=mb_convert_encoding($symbol, "ISO-8859-7", "UTF-8") ; 
 
  $this->query = "SELECT a.msg_time,'". $msgId . "' AS msg_id, a.market_id, to_char(a.trade_date,'YYYYMMDD') AS trade_date, a.security_isin, "
  		.	" a.PROJECTED_OPENING_PRICE,a.OPENING_PRICE_AUCTION_PRICE,a.closing_price, a.last_traded_price,"
		.	" a.total_traded_volume, a.total_traded_value, a.total_number_of_trades,"
		.	" a.today_high_price, a.today_low_price, a.previous_closing_price,"
		.	" a.irr_closing_price, a.irr_last_price, a.irr_high_price, a.irr_low_price, a.irr_start_price,"
		.	" a.message_category"
  		.	" FROM mm_oasis_projected_price a,mm_securities b"
  		.	" where"
  		.	" a.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" and a.ase_symbol=b.HELLENIC_SYMBOL"
		.	" and b.isin in (".$parent->markets[$parent->cfg->market]->getSecQstr().")"
  		.	" and b.trade_date=to_date('".$parent->cfg->fdate."','YYYY/MM/DD')"
		.	" order by MSG_TIME";

//print( "callbackget_projected_intoDB:".$this->query."\n");

  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'callbackget_projected_intoDB: -err:'.$stmt->getCode().' in: q='. $this->query);
			$this->disconnectORA();
			return;
			}	
   
  $cols = $this->MSG_r[$msgId][_MSG_IDX_INSCOLS]['columns'];
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			}
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$this->query="INSERT INTO imk_$msgId ($cols) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "projected_intoDB - ".$err."==".$this->query."\n");
	   }
    } 
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
	
/*
* Read Securities in Dates filtered by the Client
* @param	string msgId record type (IT,IS), int posStart: next record position ( for multiple ajax calls), int msg_num: number of limit records
* @version 2.0.1
* @author JL
*/
function getOraSecs($format, $posStart, $count, $results, $sort, $dir, $newRequest, $filter)
{
  $symbol_condition="";
  $volume_condition="";
  $trader_condition="";
  $investor_condition="";
  $phase_condition="";
  $suspended_condition="";
  $from="";
  $join="";
  $group="";
  $having="";
  
  $parent=$this->context;
  $secInfo = $parent->markets[$parent->cfg->market]->getActiveSec();
  $isin=$secInfo->getIsinSec();
   
  $ar=json_decode(stripslashes($filter)); 
  
  $select = "TOTAL_VOLUME_MAIN VOLUME";
  
  if ($ar->datecond1=="<>")
      $date_condition = " and exe.trade_date between to_date('".$ar->datefrom."','DD/MM/YYYY')" . " and to_date('".$ar->dateto."','DD/MM/YYYY')";
	  else
	     $date_condition = " and exe.trade_date ".$ar->datecond1 ." ". "to_date('".$ar->datefrom."','YYYY/MM/DD')";
  
  if ($ar->symbol!='')
  	    $symbol_condition =  "and sec.hellenic_symbol='".mb_convert_encoding($ar->symbol, "ISO-8859-7", "UTF-8")."' ";
		
  if ($ar->volume>0)  
		$volume_condition = " and volume ".$ar->volcond . " ". $ar->volume;
		
  if ($ar->trader!="")  {
		$trader_condition = " and ".$ar->sidetrader." = '".$ar->trader."'";
		$from = ",mm_dba.mm_oasis_executions exe";
		$join = " and sec.hellenic_symbol = exe.ase_symbol";
		if ($ar->datecond1=="<>")
            $trader_condition = " and exe.trade_date = sec.trade_date ";
		if ($ar->volume>0){
			$having=" HAVING sum(volume) > ".$ar->volume;
			$volume_condition="";
			$select = ",sum(exe.VOLUME) AS VOLUME ";
			$group = " GROUP BY sec.trade_date,sec.isin,hellenic_symbol";
		   }
		}
  
  if ($ar->investor!="")  {
		$investor_condition = " and ".$ar->sideinvestor. " = '".$ar->investor."'";
		$from = ",mm_dba.mm_oasis_executions exe";
		$join = " and sec.hellenic_symbol = exe.ase_symbol";
		}
  
  if ($ar->phase!='-')
      $phase_condition = " and exe.phase_id = '".$ar->phase . "'";
	  
  if ($ar->suspended!="no")  {
  	  if ($ar->datecond1=="<>")
         $suspended_condition = " and voi.trade_date = sec.trade_date ";
    
	  $suspended_condition .= " and voi.REJECT_CODE = '".$ar->suspended."'";
	  $from .= ",mm_dba.mm_oasis_security_halts voi";
	  $join .= " and sec.hellenic_symbol = voi.ase_symbol";
	  }
      
  $new_posStart = $posStart;
 // if(results==0) {
	$existed_rows=0;
	print("getOraSecs id=".$ar->id."==".$newRequest."\n");
	if ((int)$ar->id > 0 && $newRequest==0)
	{
	 $sql="SELECT count(*) FROM imk_QOS WHERE CR_ID=".$ar->id." AND SES=".$parent->session->sesid;
	 $parent->dp->setQuery(stripslashes($sql)); 
	 $existed_rows=$parent->dp->loadResult();
	 print("existed_rows=".$existed_rows."\n");
	 }
  	
	if ( ($ar->id==0 && $newRequest>0) || !$existed_rows)
	{
	$sql="DELETE FROM imk_QOS WHERE SES=".$parent->session->sesid;
	$parent->dp->setQuery(stripslashes($sql));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "orasecs_intoDB - ".$err."==".$sql."\n");
	   }
	   
	$this->query = "SELECT ".$parent->session->sesid." AS SES, sec.isin AS ISIN_CODE,"
	.	" to_char(sec.trade_date,'YYYY/MM/DD') as FDATE, sec.hellenic_symbol SYMBOL ".$select
  	. 	" FROM  mm_dba.mm_securities sec" . $from
	.	" where"
 // 	.	" board_id='".$parent->cfg->board."'"
	.	" sec.market_id='".$parent->cfg->market."'"
	.	  $symbol_condition
	.	  $date_condition
	.	  $volume_condition
	.	  $trader_condition
	.	  $investor_condition
	.     $phase_condition
	.	  $suspended_condition
	.	  $join
	.	  $having
	.	  $group
	.	" order by sec.trade_date";
print( "getOraSecs:".$this->query."\n");
  $stmt = $this->db_query();
  
  if (DB::isError($stmt))
		{
			$parent->errorlog->addlog( 'getOraSecs: -err:'.$stmt->getCode());
			//$this->disconnectORA();
			print( "getOraSecs:".$this->query."\n");
			return;
			}	
     
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';
    $cols='';
	foreach($row as $col=>$val)
	{
	if ($cols!='') {
	  		$cols.=",";
			$values.=",";
			}
	  $cols.=$col;
	  $values.="\"".ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7"))."\"";
	}
	
	$cols.=",CR_ID";
	$values.=",".$ar->id;
	 
	$this->query="INSERT INTO imk_QOS (".$cols.") VALUES(".stripslashes($values).")"; 
//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "orasecs_intoDB - ".$err."==".$this->query."\n");
	   }
    } 
	}
	return("ok");
/*		 
	 if(results==0) {
		$sql="SELECT count(*) FROM imk_QOS WHERE CR_ID=".$ar->id." AND SES=".$parent->session->sesid;
	
		$parent->dp->setQuery(stripslashes($sql)); 
 		$totalCount = $parent->dp->loadResult();
	
		//$count = $totalCount;
		//$posStart = 0;	
		}
		
		$limitStr = " LIMIT ".$new_posStart . "," . $count;
	// }
		
		$sql = "SELECT "
			.	" FDATE,SYMBOL,ISIN_CODE,VOLUME"
			.	" FROM imk_QOS"
			.	" WHERE "
			.	" SES=".$parent->session->sesid
			.	" ORDER BY ".$sort . " " . $dir
	 		. 	$limitStr;
	
//print( 'getHistoryTrades sql: '.$sql); 

 $script = "<rows total_count='".$totalCount."' pos='".$posStart."'>";
 
 $parent->dp->setQuery(stripslashes($sql)); 
 $rows=$parent->dp->loadObjectList();
	
 if ($parent->dp->getErrorNum()){
	    $parent->errorlog->addlog( 'getHistoryTrades sql: '.$this->dp->stderr());
		return _TRADES_RET_ERROR;
		}
 
  
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
*/
}
/*
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
*/ 

function db_fetch_row($stmt,$msgId) 
{
  $parent=$this->context;
  $values='';
  $columns='';
 
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';
    $columns='';
	foreach($row as $col=>$val)
	{
	//echo $col .": ".$val . "\n";
	if ($values!='') {
	  		$values.=",";
			$columns.=",";
			}
	  $values.="\"".mb_convert_encoding($val, "UTF-8", "ISO-8859-7")."\"";
	  $columns.="$col";
	}
	
	$this->query="INSERT INTO imk_$msgId ($columns) VALUES(".stripslashes($values).")"; 
	//echo $this->query."\n";
	$parent->dp->setQuery(stripslashes($this->query));
    $parent->dp->query();
    $err=$parent->dp->getAffectedRows();	
    if ($err < 1) {
		  $parent->errorlog->addlog( "FetchInto - ".$err."==".$this->query."\n");
	   }
    }
 } 
	
}	
?>