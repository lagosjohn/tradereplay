<?php
/** 
* This class holds all the information concerning Markets and Securities
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/

class mkt_info 
{
 private $mkt;
 private $board;
 public $secs;
 private $status;
 private $Helper;
 private $qstr;
 public $qsec_missed;
 public $erase_qsec;
 
 function __construct($msgobj)
	{
/** 
* Holds the parent main process class object of {@link messages_io}
* @version 2.0.1
*/
		$this->Helper =$msgobj;
/** 
* Holds Configuration info read from the main process class {@link messages_io}
* @version 2.0.1
*/
		
		$this->mkt =$msgobj->cfg->market;
		$this->board =$msgobj->cfg->board;
				 
		$msgobj->filter_sec = explode(",",$msgobj->cfg->securities);
/** 
* Holds all the securities of this market of class {@linksec_info}
* @version 2.0.1
* @var array
*/		
		$this->secs = array();
/** 
* Holds the sql strings of erasing in case ini_flag=_DO_ERASE
* @version 2.0.1
* @var array
*/		
		$this->qstr="";
		$this->qsec_missed="";
		$this->erase_qsec="";
			
//		$msgobj->errorlog->addlog( 'mkt_info: - secs='. $msgobj->cfg->securities."==".$msgobj->filter_sec);
		if ($this->Helper->init_flag==_DO_ERASE){
			$q = "Select DISTINCT SECURITY_ISIN FROM imk_IT WHERE TRADE_DATE = \"".$this->Helper->getSessionDate()."\"";
			print $q."\n";
			$this->Helper->dp->setQuery(stripslashes($q));
	 		$it_secs = $this->Helper->dp->loadObjectList();	
			foreach($it_secs as $sec){
				 	if (in_array($sec->SECURITY_ISIN,$msgobj->filter_sec)==false)
						{
							$this->erase_qsec .= ($this->erase_qsec!="" ?"," :"");
						 	$this->erase_qsec .= "'".$sec->SECURITY_ISIN."'";
							}
				 	}
		print("Erase isins: ".$this->erase_qsec."\n");
		}
		
		foreach($msgobj->filter_sec as $isin)
			{
			    $this->secs[$isin] = new sec_info($msgobj,$isin);
				$this->qstr .= ($this->qstr!="" ?"," :"");
				$this->qstr .= "'".$isin."'";
				
				//print $this->qstr."\n";
				$msgobj->errorlog->addlog( 'mkt_info Obj: - init_flag='. $isin);
			 }		
	 }
	 
 function getMarket()
 {
  return $this->mkt;
  }

 function getSecQstr()
 {
  return ($this->Helper->init_flag==_DO_INITIALIZATION ?$this->qstr :($this->Helper->init_flag==_DO_ERASE ?$this->erase_qsec :($this->qsec_missed=="" ?$this->qstr :$this->qsec_missed)));
  }
  
 function getActiveSec()
 {
// print_r($this->secs);
  foreach($this->secs as $sec)
  {
 //  print("getActiveSec-".$sec->isin."==".$sec->active."\n");
   if ($sec->getActiveSec()==true)
   	   return $sec;
  }
  return $this->setActiveFirstSec();	
 }
 
 function getSec($isin)
 {
  return $this->secs[$isin];
 }

 function setActiveSec($isin)
 {
  $this->getActiveSec()->setInactive();
  return $this->secs[$isin]->setActiveSec();
 }
 
 function setActiveFirstSec()
 {
  reset($this->secs);
  $sec=current($this->secs);
  if ($sec instanceof sec_info){
		$actsec=$sec->setActiveSec();
//		print ("setActiveFirstSec 2-".$actsec->getIsinSec()."\n");
		return $actsec;
		}
		//else
		//print ("setActiveFirstSec -".$sec."\n");
	return false;	
  }
 
 function getFirstSecIsin()
 {
  reset($this->secs);
  return current($this->secs)->getIsinSec();
  }
   
 function setActiveNextSec()
 {
  //reset($this->secs);
  $act=0;
	foreach($this->secs as $sec)
	{
	if ($sec instanceof sec_info)
	   {
	    if ($sec->getActiveSec()==true) {
            $sec->setInactive();
			$act=1;
	   		//print("setActiveNextSec Inactive:".$sec->getIsinSec()."\n");
			}
			else
			   if ($act && $sec instanceof sec_info)
			   {
		    	$sec->setActiveSec();
		    	return($sec);
		    	}
		}
		}
	$this->setActiveFirstSec();
 	return false; 
 }
 
 function getSecPhase($isin='')
 {
  if ($isin=='')
      $isin=$this->getActiveSec()->getIsinSec();

  if ($isin!='' && ($this->secs[$isin]->phase instanceof msg_info) )	  
	  return ($this->secs[$isin]->phase);
	  else {
	  		print("getSecPhase error:".$isin."\n");	 
			return false;
			}
  }
 
 }

/** 
* sec_info: This class holds all the information concerning a Security
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/

class sec_info 
{
 public $gr_symbol;
 public $en_symbol;
 public $isin;
 public $sessionSecs;
 public $market_stream;
 public $cntr;
/* public $graphHourFname;
 public $graphBidAskFname;
 public $graphBidAskEventsFname;
 public $graphProjectedFname1;
 public $graphProjectedFname2;
 public $graphProjectedEventsFname;
 public $graphTradersFname;
 */
 public $ar_graphObj;
 public $filesDir;

 public $hourGraphData;  //   array: IS per/hr for graph
 public $statistics;
 public $phase;
 private $status;
 public $Helper;
 public $reverseMatchingFlag;
 public $active;
 public $BM;
 public $posStart;
 public $count;
 public $lowest;
 public $highest;
 public $tradePrice;
 public $auctionPrice;
 public $openAuctionPrice;  // Updated from Projected
 public $auctionPriceIndex;
 public $startPrice;
 public $bid_1;		// last first level of MKT Depth
 public $ask_1;
 public $bid_p;		// prev first level of MKT Depth
 public $ask_p;
 public $Read_trades;
 public $tr_orders;		//   array: matched orders of the last trade query
 public $trade_numbers;
 public $md;				//   array: Last MD query
 public $lastTrades;		//   array: Last trade query 
 public $lastOrders;		//	 array: Last Fetched orders
 public $projected;		//   array: last auction projected prices
 public $projected_orders; // array: projected orders
 public $lastTrade;		//   last trade, updated after the current trade has transmitted (from service3.php)
 public $TradersGraph; 	//	 array: Last query of traders vol totals
 public $ob_entries;		//   array: OB entries for matching
 public $MDchange;
 public $priceInRange;
 public $nextOrderKey;
 public $membersScript;
 
 public $survTraders;
 public $surv_str;
 public $totalTrades;
 public $totalOrders;
 public $maxVol;
 public $maxPrice;
 public $minVol;
 public $minPrice;
 public $maxSellVol;
 public $maxSellPrice;
 public $minSellVol;
 public $minSellPrice; 
 public $minTradeVol;
 public $minTradePrice;
 public $maxTradeVol;
 public $maxTradePrice; 
 public $totTradeVol;
 public $totTradeValue;
 public $bid_traders;
 public $ask_traders;
 public $members;
 public $bid_traders_str; 
 public $ask_traders_str; 
 public $maxVolTrader;
 public $minVolTrader;
 public $maxPriceTrader;
 public $minPriceTrader;  
 public $maxVolSellTrader;
 public $minVolSellTrader;
 public $maxPriceSellTrader;
 public $minPriceSellTrader;   
 public $marketGraphObj; 
  
 function __construct($msgobj,$isin)
 {
		$this->Helper =$msgobj;
		$this->isin  = $isin;
		$this->marketGraphObj='';
		$this->sessionSecs = 0;
		$this->Read_trades = 0;
		$this->statistics = '';
		$this->hourGraphData="";
		$this->reverseMatchingFlag=false;
		$this->lowest = 99999;
		$this->highest = 0;
		$this->tradePrice = 0;
		$this->posStart=0;
 		$this->count=0;
		$this->cntr=0;  // pointer into market_stream //
/*
		$this->graphProjectedFname1="Projected_data1_".$isin."_".$msgobj->session->sesid.".txt";
		$this->graphProjectedFname2="Projected_data2_".$isin."_".$msgobj->session->sesid.".txt";
		$this->graphProjectedEventsFname="Projected_events_".$isin."_".$msgobj->session->sesid.".xml";
		$this->graphHourFname="hourtrades_data_".$isin."_".$msgobj->session->sesid.".txt";
		$this->graphBidAskFname="bidask_data_".$isin."_".$msgobj->session->sesid.".txt";
		$this->graphBidAskEventsFname="bidask_events_".$isin."_".$msgobj->session->sesid.".xml";
		$this->graphBidAskFnameGlobal="bidask_data_".$isin."_g".".txt";
		$this->graphBidAskEventsFnameGlobal="bidask_events_".$isin."_g".".xml";
		$this->graphTradersFname = array();
		$this->graphTradersFname['B']="traders_".$isin."_".$msgobj->session->sesid."B.txt";
		$this->graphTradersFname['S'] ="traders_".$isin."_".$msgobj->session->sesid."S.txt";	
*/			
						
		
		$this->survTraders="";
		$this->surv_str="";
		
		$this->BM = array();
		$this->totalTrades=0;
		$this->totalOrders=0;
		$this->maxVol=0;
		$this->maxPrice=0;
		$this->minVol=99999999999;
		$this->minPrice=99999;
		$this->maxSellVol=0;
		$this->maxSellPrice=0;
		$this->minSellVol=99999999999;
		$this->minSellPrice=99999;		
		$this->minTradeVol=99999999999;
		$this->minTradePrice=99999;
		$this->maxTradeVol=0;
		$this->maxTradePrice=0; 
		$this->totTradeVol=0;
		$this->totTradeValue=0;
		$this->bid_traders=array();
  		$this->ask_traders=array();
  		$this->members=array();
  		$this->bid_traders_str='';
  		$this->ask_traders_str='';
  		$this->maxVolTrader='';
  		$this->minVolTrader='';
  		$this->maxPriceTrader='';
  		$this->minPriceTrader='';
		$this->maxVolSellTrader='';
  		$this->minVolSellTrader='';
  		$this->maxPriceSellTrader='';
  		$this->minPriceSellTrader='';
		$this->membersScript="";

		$this->phase = new msg_info($this,$isin);
		$this->Helper->errorlog->addlog( 'sec_info Obj: - init_flag='. $this->Helper->init_flag." for ISIN:".$isin);
		if ($this->Helper->init_flag==0) {
			$this->phase->restorePhases($isin);
			$this->phase->reset_currphase();
			}
		
		$this->marketGraphObj = new market_graph($this);
		if( ($this->marketGraphObj instanceof market_graph) == false) 
			 $this->errorlog->addlog( 'Error creating market_graph! ');
		
		if ($this->Helper->cfg->init != _DO_INITIALIZATION)	 
			{
				$this->gatherInfo();
				$this->getMembersInfo();
			}
		
		$this->createGraphFiles();		  
	
  }

 function createGraphFiles()
 {
	$this->filesDir="/u01/imarket/marketplay/js/graphs/";
	$this->ar_graphObj = array();
	
	$this->ar_graphObj["bidask"]= new graph_file($this,$this->isin,"bidask");
	$this->ar_graphObj["bidask_g"]= new graph_file($this,$this->isin,"bidask","g");
	$this->ar_graphObj["projected"]= new graph_file($this,$this->isin,"Projected");
	$this->ar_graphObj["hourtrades"]= new graph_file($this,$this->isin,"hourtrades");
	$this->ar_graphObj["Btraders"]= new graph_file($this,$this->isin,"Btraders");
	$this->ar_graphObj["Straders"]= new graph_file($this,$this->isin,"Straders");
	}

 function removeSessionFiles()
 {
  $this->ar_graphObj["bidask"]->removeFile();
  $this->ar_graphObj["bidask_g"]->removeFile();
  $this->ar_graphObj["projected"]->removeFile();
  $this->ar_graphObj["hourtrades"]->removeFile();
  $this->ar_graphObj["Btraders"]->removeFile();
  $this->ar_graphObj["Straders"]->removeFile();
  }
  		
 function resetStats()
 {
  foreach ($this->bid_traders as &$trader){
	   $trader[1]=0;
	   $trader[2]=0;
	   }
  foreach ($this->ask_traders as &$trader){
	   $trader[1]=0;
	   $trader[2]=0;
	   }	   
  foreach ($this->members as &$member){
	   $member["B"][1]=0;
	   $member["B"][2]=0;
	   $member["S"][1]=0;
	   $member["S"][2]=0;
	   }
  }
  
 function setActiveSec()
 {
  $this->active=true;
  print("SEC: Now active:".$this->isin."\n");
  return $this;
  }	
 
 function setInactive()
 {
  $this->active=false;
  print("SEC: Now inActive:".$this->isin."\n");
  return $this;
  }
  
 function getActiveSec()
 {
  return $this->active;
  }	 
  
 function getIsinSec()
 {
  return $this->isin;
  }	  
  
 function getPhase()
 {
   return($this->phase);
  }
  
 function gatherInfo()
 {
  $isin=$this->isin;
  
  $q = "Select count(*) FROM imk_IT WHERE TRADE_DATE = \"".$this->Helper->getSessionDate()."\" AND SECURITY_ISIN=\"$isin\"";
	$this->Helper->dp->setQuery(stripslashes($q));
 	$this->sessionSecs = $this->Helper->dp->loadResult();
	if ($this->Helper->dp->getErrorNum()){
	    	print( 'check Missed Secs  sql: '.$q."==".$this->Helper->dp->stderr()."\n");
			return false;
			}
	$q = "Select count(*) FROM imk_MF WHERE FDATE = \"".$this->Helper->getSessionDate()."\" AND ISIN_CODE=\"$isin\"";
	$this->Helper->dp->setQuery(stripslashes($q));
 	$mf_recs = $this->Helper->dp->loadResult();	
	if ($mf_recs==0 || is_null($mf_recs) || empty($mf_recs))
		$this->sessionSecs = 0;	
	print ("New Sec:$isin =".$mf_recs."==".$this->sessionSecs."\n");	
			
  $q="SELECT trader FROM imk_ST WHERE security_isin = \"".$isin."\"";
   	$this->Helper->dp->setQuery(stripslashes($q));
  	$this->survTraders = $this->Helper->dp->loadObjectList();
	if (count($this->survTraders)==0){
    	print( 'check Missed Surv Traders  sql: '.stripslashes($q)."==".$this->Helper->dp->stderr()."\n");			
		}
	else	
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
  }
 
 //
 //   == Read members stored info  ==
 // 
 function getMembersInfo()
 {
  $q="SELECT side, substr( trader_id , 1, 2 ) member, sum( volume ) VOL"
	.	" FROM imk_MS"
	.	" WHERE security_isin = '".$this->isin."'"
	.	" GROUP BY side, substr( trader_id , 1, 2 ) "
	.	" ORDER BY side, vol DESC ";
	
  $this->Helper->dp->setQuery(stripslashes($q)); 
  $rows=$this->Helper->dp->loadObjectList();
  if ($this->Helper->dp->getErrorNum()){
	    	print( 'getMembersInfo sql: '.$q."==".$this->Helper->dp->stderr()."\n");
			return false;
			}	
  foreach($rows as $row)
  {
   $this->members[$row->side][$row->member][0]=$row->VOL;
   $this->members[$row->side][$row->member][1]=0;
   $this->members[$row->side][$row->member][2]=0;
     // print("members - ".$row->member."==".$row->VOL."==".$this->members[$row->side][$row->member][0]."\n");
   } 
  } 

 //
 //   == Read active members and pack  ==
 //  
 function packMembers()
 {
  $len = max(count($this->members["B"]),count($this->members["S"]));

	 $this->membersScript='{"rows":[{"id":"members","data":[';
	 
	 for($i=0; $i < $len; $i++) 
	 {
	  list($mrow_key, $mrow) = @each($this->members["B"]);
	  list($srow_key, $srow) = @each($this->members["S"]);
//print("mrow_key: ".$mrow_key."==".$mrow[1]."\n");
//	  $mrow[2]=0;
//	  $srow[2]=0;

	  if ((int)$mrow[1]>0 && (int)$mrow[0]>0) {	  
		  $mrow[2]=round( ((int)$mrow[1] *100)/(int)$mrow[0], 3);		  
		  }
  	  if ((int)$srow[1]>0 and (int)$srow[0]>0) {  
		  $srow[2]=round( ((int)$srow[1] *100)/(int)$srow[0], 3);
		  }
	    $this->membersScript .= ($i>0 ?"," :"");
	    $this->membersScript .= '{"' ."MBKEY".'":"'.$mrow_key.'"';
		$this->membersScript .= ',"' ."MBVAL".'":"'.$mrow[1].'"';
		$this->membersScript .= ',"' ."MBPCT".'":"'.$mrow[2].'"';
		$this->membersScript .= ',"' ."MSKEY".'":"'.$srow_key.'"';
		$this->membersScript .= ',"' ."MSVAL".'":"'.$srow[1].'"';
		$this->membersScript .= ',"' ."MSPCT".'":"'.$srow[2].'"';
		$this->membersScript .= "}";
		}
	 $this->membersScript .= "]}]}";
//	 print($this->membersScript."\n");	
  }
  
 }// CLASS
?>
