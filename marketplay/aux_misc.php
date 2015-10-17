<?php
/** 
* This script contains a class, which serves requests concerning mainly the market initiation
* @package aux_service
* @version 1.0.0
* @author JL
*/

/**
* 	Misc Class - Serves misc calls
*/ 
class aux_misc extends aux_basic
{
static $hive;

/**
* 	getConfigDate
*/
function getConfigDate($obj, $socket_id, $channel_id, $_DATA)
{
 $dp=$this->dp;

 $fdate='';
 $script="";
 $mkt = $_DATA['objId'];
 $ses = $_DATA['ses'];

	$q="SELECT fdate,filepath,market,board,state,vieworders,CalendarDates_CR" // HELLENIC_MARKET_NAME,
	.	" FROM imk_imarket_cfg" //,imk_IA"
	.	" WHERE id = $ses";  // AND market=MARKET_ID
 
	$dp->setQuery($q);
	$dp->loadObject($row);
	if (!$row) {
		$obj->write($socket_id, $channel_id, "getConfigDate:". $dp->stderr());
		return;
		}

 $ar=array();
 $fdate = $row->fdate;
 $state = $row->state;
 $market = $row->market;
 $board = $row->board;
 $filepath=$row->filepath;
 $vieworders=$row->vieworders;
 $crId=$row->CalendarDates_CR;
//$market_name=$row->HELLENIC_MARKET_NAME;

	$q="SELECT orderows,traderows, showgraph FROM `imk_SES` WHERE id = $ses";
	$dp->setQuery($q);
	$dp->loadObject($sesRow);

 $script.= "maxOrders=".$sesRow->orderows.";";
 $script.="maxTrades=".$sesRow->traderows.";";
 $script.="showGraph=".$sesRow->showgraph.";";

 $script.="fdate=\"$fdate\";";
 $script.="mode=\"$state\";";
 $script.="market=\"$market\";";

 $script.="board=\"$board\";";
 $script.="filepath=\"$filepath\";";
 $script.="vieworders=$vieworders;";
 $script.="oradates_id=$crId;";
 
 if ($row->state==0){
	if (file_io::checkFile($filepath, 'ficsnd', $fdate) == true)
		print "file_exists=true;";
		}
	else  // Input Source is DB Oracle
	   $script.= "file_exists=true;";	
	   
 $obj->write($socket_id, $channel_id, $script);   
}


 
/**
* 	getSecurity info  SELECTED to Play
*/
function getSecurityInfo($obj, $socket_id, $channel_id, $_DATA)
{
 $dp=$this->dp;
 $script="";

 $flag = $_DATA['objId'];
 $ses = $_DATA['ses'];
 $fdate = $_DATA['fdate'];


	$q="SELECT fdate,securities"
	.	" FROM imk_imarket_cfg"
	.	" WHERE id = $ses";
 
	$dp->setQuery($q);
	$dp->loadObject($row);
	
 $secs = $row->securities;
 $fdate=substr($row->fdate,0,4).substr($row->fdate,5,2).substr($row->fdate,8,2);
 $str_secs="";
 $sec_r = explode(",",$secs);

 foreach($sec_r as $sec)
 {
  if ($str_secs!="")
     $str_secs.=",";
  $str_secs.="'".$sec."'";
 }

	$i=0;
	$prep="SELECT ISIN_CODE,SECURITY_HELLENIC_SYMBOL"
		.	" FROM imk_IB"
		.	" WHERE ISIN_CODE IN ($str_secs)";
	
	$dp->setQuery($prep);
	$info = $dp->loadObjectList();
	
	$hsym=$info[0]->SECURITY_HELLENIC_SYMBOL;
	$esym=$info[0]->SECURITY_ENGLISH_SYMBOL;	

 $script.= "isinAr=[];";

 foreach ($info as $row)
 {
  $script.= "isinAr[\"".$row->ISIN_CODE."\"]=[];";
  $q= "SELECT max( SUBT ) FROM `imk_MF` WHERE fdate=\"$fdate\" AND ISIN_CODE=\"".$row->ISIN_CODE."\"";  // Get only the TOTAL

  $dp->setQuery($q);
  $total_phases = $dp->loadResult();
  foreach ($row as $col => $val) {
	 $script.= "isinAr[\"".$row->ISIN_CODE."\"].push(\"$val\" );"; // This line updates the script array with new entry
	 }
  $script.= "isinAr[\"".$row->ISIN_CODE."\"].push( $total_phases );";
 }	 
	
 $script.=  "hsym=\"$hsym\";";
 $script.=  "esym=\"$esym\";";
 $script.=  "isin=\"$secs\";";

$obj->write($socket_id, $channel_id, $script);
}

/**
* Select all necessary info about Market
*/
function computeMarket($obj, $socket_id, $channel_id, $_DATA)
{
 $dp=$this->dp;
 $script="";

$slider = $_DATA['objId'];
$ses = $_DATA['ses'];


$q= "SELECT max( SUBT ) FROM `imk_MF` WHERE session=$ses";
$dp->setQuery($q);
$total_phases = $dp->loadResult();


$q= "SELECT fdate,securities,HELLENIC_MARKET_NAME FROM imk_imarket_cfg,imk_IA WHERE id=$ses AND market=MARKET_ID";
$dp->setQuery($q);
$dp->loadObject($row);

$fdate = $row->fdate;
$date_ar=explode("-",$fdate);
$fdate=implode($date_ar);

$secs = $row->securities;
$sec_r=explode(",",$secs);

$str_secs="";

foreach($sec_r as $sec)
{
 if ($str_secs!="")
     $str_secs.=",";
 $str_secs.="'".$sec."'";
 }
$market_name = $row->HELLENIC_MARKET_NAME;

$q= "SELECT ISIN_CODE,START_OF_DAY_PRICE FROM imk_II WHERE ISIN_CODE IN ($str_secs) LIMIT 0,".count($sec_r);

$dp->setQuery($q);
$secs_price = $dp->loadObjectList();
foreach($secs_price as $sec_price)
{
 $price=$sec_price->START_OF_DAY_PRICE;
 $script.= "isinAr[\"".$sec_price->ISIN_CODE."\"].push(".((int)$price / pow(10,2)).");";
 }

$q= "SELECT ID,ISIN_CODE,OASIS_TIME,END_TIME,PHASE,SUBT,PTOTAL,OPEN_CMNT FROM `imk_MF` WHERE ISIN_CODE IN ($str_secs) ORDER BY ID";
$dp->setQuery($q);
$rows = $dp->loadObjectList();
$n=0;
$ar=array();
$font_size=1;
$width=1;
//$scale_factor=280/$total_phases;
//$char_size=imagefontwidth($font_size)*1;
$cur_isin="";

foreach($rows as $row){
		//if ($row->OPEN_CMNT =='N')
		 //   continue;
		 if ($row->ISIN_CODE != $cur_isin)
		 {
		  if ( $cur_isin!="") {
		  		//print "mkt_secs=".timeDiff(substr($start_time,0,6),substr($ar[count($ar)-1][1],0,6)).";";
				$seconds = $this->timeDiff(substr($start_time,0,6),substr($ar[count($ar)-1][1],0,6));
				$script.= "isinAr[\"".$cur_isin."\"].push($seconds);";
				}
		  $cur_isin=$row->ISIN_CODE;
		  $start_time=$row->OASIS_TIME;
		  }
		  
		$ar[$row->ID]=array($row->OASIS_TIME, $row->END_TIME, $this->timeDiff(substr($row->OASIS_TIME,0,6),substr($row->END_TIME,0,6)), $row->PHASE, $row->PTOTAL);
		$text.= $row->PHASE;
		$text2.= $row->OASIS_TIME;
		$time_len=imagefontwidth($font_size)*strlen($row->OASIS_TIME);
		//$width2=round(($row->PTOTAL*$scale_factor)/$char_size);
		//$width2-=$time_len;
		//$width=round(($row->PTOTAL*$scale_factor)/$char_size);
//print "sf=".$scale_factor. "; PTOTAL=".$row->PTOTAL."; W=".$width."; Cs=".$char_size.";";  
		//for ($i=0;$i<$width;$i++)
		//	$text.=' ';
		//for ($i=0;$i<$width2;$i++)
		//	$text2.=' ';
		//$len=imagefontwidth($font_size)*$width;
		}

 $seconds = $this->timeDiff(substr($start_time,0,6),substr($row->END_TIME,0,6));
 $script.= "isinAr[\"".$row->ISIN_CODE."\"].push($seconds);";

 $q= "SELECT SECURITY_ISIN, count( SECURITY_ISIN ) as TOT FROM imk_IS WHERE TRADE_DATE='$fdate' AND SECURITY_ISIN IN ($str_secs) GROUP BY SECURITY_ISIN";
 $dp->setQuery($q);
 $total_trades = $dp->loadObjectList();
 foreach($total_trades as $total_trade)
 {
  $tot=$total_trade->TOT;
  $script.= "isinAr[\"".$total_trade->SECURITY_ISIN."\"].push($tot);";
  }

 foreach ($ar as $ar_num => $phases)
 {
 $script.= "phasesAr[$ar_num]=[];";
 foreach ($phases as $phases_num => $phase)
	{    	 
	 $script.= "phasesAr[$ar_num].push(\"$phase\" );"; // This line updates the script array with new entry
	 }
 }	 

 $script.=  "market_name='$market_name';";
 
 $obj->write($socket_id, $channel_id, $script);
}



/**
* getSimilarTime | called from: customPeriod_dialog
*/
function getSimilarTime($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 
 $start = $_DATA['start'];
 $stop = $_DATA['stop'];
 $isin = $_DATA['isin'];
 $fdate = $_DATA['fdate'];

	$q="SELECT OASIS_TIME"
 			.	" FROM imk_IT WHERE TRADE_DATE='$fdate' AND SECURITY_ISIN='$isin' AND OASIS_TIME >= \"$start\" ORDER BY ORDER_ID,OASIS_TIME LIMIT 0,1" ;

	$dp->setQuery($q);
	$js_start=$dp->loadResult();
	if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}
	
	$q="SELECT OASIS_TIME"
 			.	" FROM imk_IT WHERE TRADE_DATE='$fdate' AND SECURITY_ISIN='$isin' AND OASIS_TIME >= \"$stop\" ORDER BY ORDER_ID,OASIS_TIME LIMIT 0,1" ;
 	
	$dp->setQuery($q);
	$js_stop=$dp->loadResult();
	if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());		
		return;
		}
			
 $script .= "custom_start='".$js_start."';";
 $script .= "custom_end='".$js_stop."';";
 
 $obj->write($socket_id, $channel_id, $script);
 }

/**
* convert to unix timestamps
* return the difference
*/
 function timeDiff($firstTime,$lastTime)
 {
  $firstTime=strtotime($firstTime);
  $lastTime=strtotime($lastTime);

// perform subtraction to get the difference (in seconds) between times
  $timeDiff=$lastTime-$firstTime;

 return ($timeDiff);
 }
 
 function getOrderLimitTime($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp;

  $start = $_DATA['start'];

	$q = "SELECT OASIS_TIME FROM imk_IT WHERE TRADE_DATE='$fdate' AND SECURITY_ISIN='$isin' LIMIT $start , 1";
	$dp->setQuery($q);
	
	$js_start=$dp->loadResult();
	if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id, $dp->stderr());
		return;
		}

  $script = "custom_start='".$js_start."';";
  $obj->write($socket_id, $channel_id, $script);
 }

/**
*	Help function called by aux::getChartBubble
*/
 function getStartPrice($_DATA)
 { 
  $dp=$this->dp; 
 
  $nodeId = $_DATA['objId']; //$this->utf8Urldecode($_DATA['objId']);
  $q= "SELECT START_OF_DAY_PRICE FROM imk_II WHERE ISIN_CODE = '$nodeId'";

  $dp->setQuery($q);
  $price = $dp->loadResult();
  
  return $price;
  }

} // Class
 
?>
