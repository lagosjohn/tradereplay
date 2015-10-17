<?php
/**
* This script contains a class, serving requests concering the Documents
* @package aux_traders
* @version 1.0.0
* @author JL
*/

/**
*	CRM  Data
*/
global $vardefs;
global $member_vardefs;
global $mod_strings;
global $member_mod_strings;
 echo dirname(__FILE__);
require_once dirname(__FILE__) . "/../../CRM1/custom/include/language/el_gr.lang.php";
require_once dirname(__FILE__) ."/../../CRM1/custom/modulebuilder/packages/members/modules/mb_member/vardefs.php";
require_once dirname(__FILE__) ."/../../CRM1/custom/modulebuilder/packages/members/modules/mb_member/language/el_gr.lang.php";
$member_vardefs = $vardefs;
$member_mod_strings = $mod_strings;
require_once dirname(__FILE__) ."/../../CRM1/custom/modulebuilder/packages/members/modules/mb_stocks_traders/vardefs.php";
require_once dirname(__FILE__) ."/../../CRM1/custom/modulebuilder/packages/members/modules/mb_stocks_traders/language/el_gr.lang.php";
 
class aux_traders  extends aux_basic
{
/** 
*   getTradersNode
*/ 
 public $vardefs;
 static $hive;
 public $colors_table=array(); //'b6b2b2','a51a1f','7ebf4e','0fbbb9','f7af23','7d558d','a7d0e8','0163a4','db938b','8538ad',
 							  //'b600b2','a5001f','7e004e','0f00b9','f70023','7d008d','a700e8','0100a4','db008b','8500ad');

/* 
  function __construct()
 {
  $this->vardefs=$vardefs;
  }
 */

/**
*	Help function called by aux::getChartBubble
*/
 function getStartPrice($nodeId,$ses,$pastTime,$refDate)
 { 
  $ORAdp=$this->ORAdp;
  $dp=$this->dp; 
 
 // $nodeId = $_DATA['objId']; //$this->utf8Urldecode($_DATA['objId']);
  $q="SELECT fdate, market,board"
	 .	" FROM imk_imarket_cfg WHERE id =$ses";
	
	$dp->setQuery(stripslashes($q));
    $dp->loadObject($session_sec);
	
  $ORAdp->query = "SELECT a.price_pre_open as START_OF_DAY_PRICE,  a.price_open, a.price_max as CEILING_PRICE, a.price_min as FLOOR_PRICE"     
  	. 	" FROM mm_dba.mm_stock_day_trans a, mm_dba.mm_securities b"
	.	" where"
  	.	" a.ase_symbol = b.hellenic_symbol"
	.	" and ISIN = '".$nodeId."'"
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and market_id= '".$session_sec->market."'"
	.	" and trade_date=to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY/MM/DD')" .($pastTime ?"-".$pastTime :"")
	.	" and trade_date=trans_date";
	
//  print( $ORAdp->query);
  $stmt = $ORAdp->db_query();
  $prices = oci_fetch_object($stmt);
 // oci_free_statement($stm);
  
  return $prices;
  }
   
 function getDatesNode($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp; 
  $script="";

 $nodeId = $_DATA['objId'];
 $nodeType = $_DATA['objType'];
 $date = $_DATA['date'];
 $ses = $_DATA['ses']; 
 
 $trader = ($nodeType==-1 ?"member_id" :($nodeType==1  ?"trader_id" :"CSD_ACCOUNT_ID"));
 $buy_cond_trader = ($nodeType==2 ?" and buy_member_id='$nodeId'" :($nodeType==3  ?" and buy_trader_id='$nodeId'" :""));
 $sell_cond_trader = ($nodeType==2 ?" and sell_member_id='$nodeId'" :($nodeType==3  ?" and sell_trader_id='$nodeId'" :""));
 $nodeType=($nodeType==0 ?-1 :($nodeType==-1 ?1 :1));

 if ( $nodeType==-1) { 
    $q="SELECT concat( '\"', substring( securities,1,instr( securities,',')-1),'\"',',\"',substring( securities,instr( securities,',')+1,length(securities)),'\"')"
	.	" FROM imk_imarket_cfg WHERE id =$ses";
	
	$dp->setQuery(stripslashes($q));
    $session_secs = $dp->loadResult();
	
 $q="SELECT DISTINCT FDATE as id, FDATE as title,SUM(VOLUME) as vol, $nodeType as type "
	.	" FROM imk_QOS"
	.	" WHERE SES = $ses"
	.	" AND ISIN_CODE in ($session_secs)"
	.	" GROUP BY FDATE" 
	.	" ORDER BY 1 desc";
	}
 else
	$q="SELECT DISTINCT s.ISIN_CODE as id, SECURITY_HELLENIC_SYMBOL as title, VOLUME as vol, $nodeType as type "
	.	" FROM imk_QOS s, imk_IB b"
	.	" WHERE SES = $ses"
	.	" AND s.ISIN_CODE = b.ISIN_CODE"
	.	" AND s.FDATE = '".$date."'"	
	.	" ORDER BY 2";

 $dp->setQuery(stripslashes($q));
 $docs = $dp->loadObjectList();
 if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}

 $result='{"ResultSet":{"Result":[';
 $resval='';
 foreach($docs as $row)
 {
  if($resval!=''){
 	$result.=',';	
	$resval='';
	}
 $resval.='{';
 foreach($row as $col=>$val){
 	
	if($resval!='{')
	   $resval.=',';
	if ($col=='vol')
	   $val=number_format($val);
	$resval.="\"".$col."\":". "\"".$val."\"";
	}
 $resval.='}';
 $result.=$resval;
 }
 $result.="]}}";
//$result.=$resval;

 $obj->write($socket_id, $channel_id, $result);
 }
 
 
 function getTradersNode($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp; 
  $script="";

 $nodeId = $_DATA['objId'];
 $nodeType = $_DATA['objType'];
 $sec = $_DATA['sec'];
 $ses = $_DATA['ses']; 
 
/**
* get a start price by calling factory:  $this->hive->getStartPrice($_DATA);
*/ 
$trader = ($nodeType==1 ?"member_id" :($nodeType==2  ?"trader_id" :"CSD_ACCOUNT_ID"));
$buy_cond_trader = ($nodeType==2 ?" and buy_member_id='$nodeId'" :($nodeType==3  ?" and buy_trader_id='$nodeId'" :""));
$sell_cond_trader = ($nodeType==2 ?" and sell_member_id='$nodeId'" :($nodeType==3  ?" and sell_trader_id='$nodeId'" :""));
$nodeType+=1;

if ( $nodeType==1) { 
 $q="SELECT concat( '\"', substring( securities,1,instr( securities,',')-1),'\"',',\"',substring( securities,instr( securities,',')+1,length(securities)),'\"')"
	.	" FROM imk_imarket_cfg WHERE id =$ses";
	
	$dp->setQuery(stripslashes($q));
    $session_secs = $dp->loadResult();
	
 $q="SELECT DISTINCT SECURITY_ISIN AS id, SECURITY_HELLENIC_SYMBOL as title,$nodeType as type "
	.	" FROM imk_IS, imk_imarket_cfg c, imk_IB"
	.	" WHERE id = $ses AND FDATE = TRADE_DATE"
	.	" AND SECURITY_ISIN=ISIN_CODE"
	.	" AND SECURITY_ISIN in ($session_secs)"
	.	" ORDER BY 2";
	}
else	
 $q="SELECT a.id, a.id as title, a.bvol, a.svol, $nodeType as type"
	.	" FROM ("
	.	" SELECT m.trader as id, "
	.	" 	sum(CASE WHEN m.side = 'B' THEN m.vol ELSE 0 END ) bvol,"
	.	" 	sum(CASE WHEN m.side = 'S' THEN m.vol ELSE 0 END ) svol"	
	.	"    FROM ("
	.	" 			SELECT 'B' side, buy_".$trader ." AS trader, sum(volume) AS vol"
	.	" 			FROM imk_IS b, imk_imarket_cfg"
	.	" 				WHERE b.SECURITY_ISIN = '$sec'"
	.	" 			AND id = $ses" 			
	.	"   		AND FDATE= b.trade_DATE"
	.				$buy_cond_trader
	.	" 			GROUP BY 2"
	.	" 		UNION"
	.	" 			SELECT 'S' side, sell_".$trader ." AS trader, sum(volume) AS vol"
	.	" 			FROM imk_IS s, imk_imarket_cfg"
	.	" 	 			WHERE s.SECURITY_ISIN = '$sec'"
	.	" 			AND id = $ses" 			
	.	"   		AND FDATE= s.trade_DATE"
	.				$sell_cond_trader
	.	" 			GROUP BY 2"
	.	" 		)m"
	.	" GROUP BY trader"
	.	" ORDER BY trader"
	.	" )a";

//print $q;

$dp->setQuery(stripslashes($q));
$docs = $dp->loadObjectList();
if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}

$result='{"ResultSet":{"Result":[';
$resval='';
foreach($docs as $row)
{
 if($resval!=''){
 	$result.=',';	
	$resval='';
	}
 $resval.='{';
 foreach($row as $col=>$val){
 	
	if($resval!='{')
	   $resval.=',';
	$resval.="\"".$col."\":". "\"".$val."\"";
	}
 $resval.='}';
 $result.=$resval;
 }
 $result.="]}}";
//$result.=$resval;

 $obj->write($socket_id, $channel_id, $result);
 //print $q;
 }

 function getNodeInfo($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->CRMdp; 
  $script="";
  global $vardefs;
  global $mod_strings;
  global $member_vardefs;
  global $member_mod_strings;
  
  $nodeId = $_DATA['objId'];
  $nodeType = $_DATA['objType'];
  
  if ($nodeType==1) {
  		$this->getParentNodeInfo($obj, $socket_id, $channel_id, $_DATA);
		return;
  }
  
  if ($nodeType==2) {
  	$q = "SELECT member_id, name,address1_postalcode, phone, jan_turnover, bic,email,mkt_member,oper,spneg,actdate,stocks_odl,country"
		. " FROM mbr_mb_member"
		. " WHERE member_id = \'$nodeId\' AND deleted=0";
		$func_vardefs=$member_vardefs;
		$func_mod_strings=$member_mod_strings;
		}
	elseif($nodeType==3) {
			$q = "SELECT b.user_id, CONCAT( a.name, ' ', a.surname ) AS user_name,"
			.	" b.stocks_mkt, b.activ_date, b.deactiv_date, b.sp_neg,"
			.	" b.pre_agr, b.stock_user_type,  b.userslimit, b.groupinvestors, b.stocks_user_stat, b.cyp_user_stat"
			.	" FROM mbr_mb_traders a"
			.	" LEFT JOIN mbr_mb_tradocks_traders_c c ON a.id = mbr_mb_tra6ccftraders_ida"
			.	" LEFT JOIN mbr_mb_stocks_traders b ON mbr_mb_tra3d15traders_idb = b.id"
			.	" AND b.user_id =  \'$nodeId\'"
			.	" WHERE b.deleted =0"
			.	" AND c.deleted =0"
			.	" AND a.deleted =0";
			
			$func_vardefs=$vardefs;
		    $func_mod_strings=$mod_strings;
			}
		
	$this->CRMdp->setQuery(stripslashes($q)); 
 	$this->CRMdp->loadObject($mem);
	
 	if ($this->CRMdp->getErrorNum()){
	    $this->errorlog->addlog( 'getNodeInfo sql: '.$this->CRMdp->stderr());
		 $obj->write($socket_id, $channel_id, "");
		}

  $html = '{"ResultSet":{"Result":['; 
  $result='';
  

  if ($mem)
  {
	foreach($mem as $col=>$val)
	{
   		if ($result!='')
   	  		 $result.=",";
		$str="";
		if ($func_vardefs["fields"][$col]['type']=='enum')
		    $str=$GLOBALS['app_list_strings'][$func_vardefs["fields"][$col]['options']][$val];
		
		elseif ($func_vardefs["fields"][$col]['type']=='multienum') {
			//$col="Markets:";
			$ar=explode("^,^",$val); 
			for($i=0;$i < count($ar); $i++)
			    $str.=($str!='' ?"," :"") . $GLOBALS['app_list_strings'][$func_vardefs["fields"][$col]['options']][$ar[$i]];
			}   
		  elseif ($func_vardefs["fields"][$col]['type']=='bool') 
		  	    $str = ($val==1 ?"Yes" :"No");		
			elseif($func_vardefs["fields"][$col]['type']=='float')
				   $str = number_format($val);  
			// elseif ($func_vardefs["fields"][$col]['type']=='date') 
			 	//if ($col=="activ_date")
			  else
			   $str=$val;
			  
	    $idx=$func_vardefs["fields"][$col]['vname'];
		//print($col."=".$idx."\n");
   		$result  .= '{"title"'.':"'.$func_mod_strings[$idx].'","'."value".'":"'.$str.'"}';
  		}
  }
  $html  .= $result . ']}}';
 
 // require_once('JSON.php');
 // $json = new Services_JSON();
   	
  $obj->write($socket_id, $channel_id, $html);
 }

/**
* 	Called by getNodeInfo in case $nodeId contains a Security Isin
*/
 function getParentNodeInfo($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp;
  $ORAdp=$this->ORAdp ;
  $script="";
    
  $nodeId = $_DATA['objId'];
  $ses = $_DATA['ses'];
  
   $q="SELECT fdate, market,board"
	.	" FROM imk_imarket_cfg WHERE id =$ses";
	
	$dp->setQuery(stripslashes($q));
    $dp->loadObject($session_sec);
	
  $ORAdp->query = "SELECT isin, english_symbol ||'-'|| hellenic_symbol as symbol,"
  	.	" START_OF_DAY_PRICE,floor_price, ceiling_price, open_price,"
    .   " closing_price, low_price, high_price, TOTAL_VOLUME_MAIN, TOTAL_VALUE_MAIN"     
  	. 	" FROM mm_dba.mm_securities"
	.	" where"
  	.	" ISIN = '".$nodeId."'"
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and market_id= '".$session_sec->market."'"
	.	" and trade_date=to_date('".$session_sec->fdate."','YYYY/MM/DD')";
//print($ORAdp->query);
	$stmt = $ORAdp->db_query();
 
	if (DB::isError($stmt))
		{
			$this->errorlog->addlog( 'callbackget_securities_intoDB: -err in: q='. $ORAdp->query);
			//$this->disconnectORA();
			return;
			}	
  
  $result='';
  $html = '{"ResultSet":{"Result":['; 
  
  while (($row = oci_fetch_object($stmt))) {
    // Use upper case attribute names for each standard Oracle column
    $values='';

	foreach($row as $col=>$val)
		{
					 
	  	$result  .= ($result!='' ?"," :""). '{"title"'.':"'.$col.'","'."value".'":"'.ereg_replace('"', "",mb_convert_encoding($val, "UTF-8", "ISO-8859-7")).'"}';
		}
	}

 $html  .= $result . ']}}';
 //oci_free_statement($stm);
 
 $obj->write($socket_id, $channel_id, $html);
 }

/**
*	getChartData
*/ 
 function getChartSettings($obj, $socket_id, $channel_id, $_DATA)
 { 
 // $pastTime = $_DATA['pastTime']; 
 // if ($pastTime)
  //		return ( $this->getOraChartData($obj, $socket_id, $channel_id, $_DATA));
		
  $dp=$this->dp; 
  $script="";
  $session_cond="";
  $session_from="";

  $nodeId = $_DATA['objId']; //$this->utf8Urldecode($_DATA['objId']);
 // print $_DATA['objId']."==".$nodeId."==".urldecode($_DATA['objId'])."==".utf8_decode($_DATA['objId'])."\n";
  $nodeType = $_DATA['objType'];
  $sec = $_DATA['sec'];
  $ses = $_DATA['ses'];
  $pastTime = $_DATA['pastTime'];
  $refDate = $_DATA['refDate']; 
  
  if ($pastTime){
  		$q="SELECT fdate, market,board"
		.	" FROM imk_imarket_cfg WHERE id =$ses";
	
 	 	$dp->setQuery(stripslashes($q));
  		$dp->loadObject($session_sec);
		
   		while( $this->validateOraTradeDate($session_sec->fdate, $pastTime)==0 )
  			 $pastTime++;
		}	 
			 
  $secPrices = $this->getStartPrice($sec,$ses,$pastTime,$refDate);
//  if ($nodeType==1) {	
//		$this->getChartBubble($obj, $socket_id, $channel_id, $_DATA);
//		return;
//	    }
/*	
	if ($nodeType==2)
  	  $cod_where="member_id";
	  elseif($nodeType==3)
  	  	$cod_where="trader_id";
	   elseif($nodeType==4)
  	  	 $cod_where="CSD_ACCOUNT_ID";
	
	if ($ses) {
      $session_from = ", imk_imarket_cfg";
	  $session_cond = " AND id = $ses AND fdate=trade_date"	;  
	  }
	if ($sec)
  	  $session_cond .= " AND SECURITY_ISIN = '$sec'"	;  
	   
	$q="SELECT 'B' AS side, buy_member_id, "
	.	" max( trim(TRAILING '0' FROM (price) /100 ) ) AS maxpr, "
	.	" min( trim(TRAILING '0' FROM (price) /100 ) ) AS minpr "
	.	" FROM `imk_IS`" . $session_from
	.	" WHERE " . "buy_".$cod_where ." = \"$nodeId\""
	.	$session_cond
	.	" GROUP BY ". "buy_".$cod_where
	.	" union "
	.	" SELECT 'S' AS side, sell_member_id, "
	.	" max( trim(TRAILING '0' FROM (price) /100 ) ) AS maxpr, " 
	.	" min( trim(TRAILING '0' FROM (price) /100 ) ) AS minpr"
	.	" FROM imk_IS" . $session_from
	.	" WHERE " . "sell_".$cod_where ." = \"$nodeId\""
	.	$session_cond
	.	" GROUP BY ". "sell_".$cod_where;
//print $q;
	$dp->setQuery(stripslashes($q));
    $data = $dp->loadObjectList();
	if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}
 	 
	 $script ="<settings><values><y_right>";
	 $min=9999;
	 $max=0;
	 foreach($data as $row){
	 	$min = min($min,$row->minpr);
		$max = max($max,$row->maxpr);	
	 	}
*/		
	// $min-=0.50;
	// $max+=0.50;
	 //$script ="<settings><values><y_right>";
	// $script .= "<min>".$secPrices->FLOOR_PRICE ."</min><max>".$secPrices->CEILING_PRICE ."</max><strict_min_max>true</strict_min_max></y_right></values></settings>";
	 $script = $nodeId."_min=".$secPrices->FLOOR_PRICE .";";
	 $script .= $nodeId."_max=".$secPrices->CEILING_PRICE .";";

 $obj->write($socket_id, $channel_id, $script);
 } 
	  
/**
*	getChartData
*/ 
 function getChartData($obj, $socket_id, $channel_id, $_DATA)
 { 
  $pastTime = $_DATA['pastTime']; 
		
  $dp=$this->dp; 
  $script="";
  $session_cond="";
  $session_from="";

  $nodeId = $_DATA['objId']; //$this->utf8Urldecode($_DATA['objId']);
 // print $_DATA['objId']."==".$nodeId."==".urldecode($_DATA['objId'])."==".utf8_decode($_DATA['objId'])."\n";
  $nodeType = $_DATA['objType'];
  $sec = $_DATA['sec'];
  $ses = $_DATA['ses'];
  $refDate = $_DATA['refDate'];
  
//  print_r($_DATA);
  
  if ($nodeType==1) {	
		$this->getChartBubble($obj, $socket_id, $channel_id, $_DATA);
		return;
	    }
  if ($pastTime or $refDate)
  		return ( $this->getOraChartData($obj, $socket_id, $channel_id, $_DATA));
		
  if ($nodeType==2)
  	  $cod_where="member_id";
	  elseif($nodeType==3)
  	  	$cod_where="trader_id";
	   elseif($nodeType==4)
  	  	 $cod_where="CSD_ACCOUNT_ID";
	  
  if ($refDate) {
  		$session_from = "";
	    $session_cond = " AND DATE_FORMAT( '".$refDate."', '%Y%m%d' ) = trade_date ";
		}
  	else		
  		if ($ses) {
      		$session_from = ", imk_imarket_cfg";
	  		$session_cond = " AND id = $ses AND fdate=trade_date"	;  
	  		}
		
  if ($sec)
  	  $session_cond .= " AND SECURITY_ISIN = '$sec'"	;  
   //CONCAT(DATE_FORMAT( trade_date, '%Y/%m/%d' ),' ',
  
  $q =  "SELECT DISTINCT  TIME_FORMAT( concat( hr, '00' ) , '%H:%i:%s' ) as fdate," 
  	. 	" sum(CASE WHEN side = 'B' THEN v ELSE 0 END ) bv,"
	.	" sum(CASE WHEN side = 'S' THEN v ELSE 0 END ) sv, "
	. 	" sum(CASE WHEN side = 'B' THEN pr ELSE 0 END ) bp,"
	.	" sum(CASE WHEN side = 'S' THEN pr ELSE 0 END ) sp, c"
	.	" FROM ("
	.	" SELECT 'B' AS side,trade_date,substring( OASIS_TIME, 1, 4 ) hr, sum( volume ) v, "
	.	" trim(TRAILING '0' FROM (avg( price ) /100 )) AS pr, count( * ) c"
	.	" FROM `imk_IS`" . $session_from
	.	" WHERE " . "buy_".$cod_where ."= \"$nodeId\""
	.	  $session_cond
	.	" GROUP BY hr"
	.	" UNION"
	.	" SELECT 'S' AS side,trade_date,substring( OASIS_TIME, 1, 4 ) hr, sum( volume ) v, "
	.	" trim(TRAILING '0' FROM (avg( price ) /100 )) AS pr, count( * ) c"
	.	" FROM `imk_IS`" . $session_from
	.	" WHERE " . "sell_".$cod_where ."= \"$nodeId\""
	.	  $session_cond	
	.	" GROUP BY hr"
	.	" UNION"
	.	" SELECT 'B' AS side,trade_date,substring( OASIS_TIME, 1, 4 ) hr, 0 AS v, 0 AS pr, count( * ) c"
	.	" FROM `imk_IS`" . $session_from
	.	" WHERE " . "buy_".$cod_where ." != \"$nodeId\""
	.	  $session_cond	
	.	" GROUP BY hr"
	.	" UNION"
	.	" SELECT 'S' AS side,trade_date,substring( OASIS_TIME, 1, 4 ) hr, 0 AS v, 0 AS pr, count( * ) c"
	.	" FROM `imk_IS`" . $session_from
	.	" WHERE " . "sell_".$cod_where ." != \"$nodeId\""
	.	  $session_cond	
	.	" GROUP BY hr"	
	.	" )a"
	.	" GROUP BY fdate"
	.	" ORDER BY fdate";
//print "getChartData: ". $q."\n";

 $dp->setQuery(stripslashes($q));
 $data = $dp->loadObjectList();
 if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}
  
 $graph1='<graph gid="1">';
 $graph2='<graph gid="2">';
 $graph3='<graph gid="3">';
 $graph4='<graph gid="4">';
 $series='<series>\n';
 
 $cntr=0;
 $prev_bp = 0;
 $prev_sp = 0;
 
 foreach($data as $row){	 
		//$series.='<value xid="'.$cntr.'">'.$row->fdate.'</value>';
			//$graph1.= '<value xid="'.$cntr.'">'.$row->bv.'</value>';
			//$graph2.= '<value xid="'.$cntr.'">'.((int)$row->bv>0 ?$row->bp :$prev_bp).'</value>';  
			//$graph3.= '<value xid="'.$cntr.'">'.$row->sv.'</value>';
			//$graph4.= '<value xid="'.$cntr.'">'.((int)$row->sv>0 ?$row->sp :$prev_sp).'</value>'; 

//		$script.=$row->fdate.";".($row->bv > 0 ?$row->bv :"") .";".($row->bp > 0 ?$row->bp :"").($row->sv > 0 ?$row->sv :"") .";".($row->sp > 0 ?$row->sp :"") .";"."\n"; //.";".$row->bv.";".$row->sv
		$script.=$row->fdate.";".$row->bv .";".((int)$row->bv > 0 ?$row->bp :$prev_bp).";".$row->sv .";".((int)$row->sv>0 ?$row->sp :$prev_sp) ."\n"; //.";".$row->bv.";".$row->sv
		$prev_bp = ((int)$row->bp > 0 ?$row->bp :$prev_bp);
		$prev_sp = ((int)$row->sp > 0 ?$row->sp :$prev_sp);
		$cntr++;
	   }

 $graph1.='</graph>';
 $graph2.='</graph>';
 $graph3.='</graph>';
 $graph4.='</graph>';
 $series.='</series>';
 
 $obj->write($socket_id, $channel_id, $script);  //"<chart>".$series.'<graphs>'.$graph1.$graph2.$graph3.$graph4."</graphs></chart>"
// print "<chart>".$series.'<graphs>'.$graph1.$graph2."</graphs></chart>";
//print $graph4;
 }
 
/**
* 	Called by getChartData 
*/
 function getOraChartData($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp;
  $ORAdp=$this->ORAdp ;
  $script="";
    
  $pastTime = $_DATA['pastTime']; 
 
  $nodeId = $_DATA['objId']; 
  $nodeType = $_DATA['objType'];
  $sec = $_DATA['sec'];
  $ses = $_DATA['ses'];
  $topCount = ((int)(strlen($_DATA['top'])>0 ?$_DATA['top'] :10)) - 1;
  
  $interval = (int)(strlen($_DATA['interval'])>0 ?$_DATA['interval'] :0);
  $ref = (strlen($_DATA['ref'])>0 ?$_DATA['ref'] :'');
  $periodType = $_DATA['period'];
  $refDate = $_DATA['refDate']; 
      
  $result='';
  
  $cntr  = 0;
  $fdate = "";
  $MemCntr=0;
  
  if ($nodeType==2)
  	  $cod_where="member_id";
	  elseif($nodeType==3)
  	  	$cod_where="trade_id";
	   elseif($nodeType==4)
  	  	 $cod_where="CSD_ACCOUNT_ID";
	  
  $q="SELECT fdate, market,board"
	.	" FROM imk_imarket_cfg WHERE id =$ses";
	
  $dp->setQuery(stripslashes($q));
  $dp->loadObject($session_sec);
	
  $group_format = ($pastTime == 30 ?"YYMM-W" :($pastTime == 180 ?"YY-MM" :($pastTime == 7 ?"YYMM-DD" :"YYMM-DD")));
	 
  if ($pastTime)
	  while( $this->validateOraTradeDate(($refDate ?$refDate :$session_sec->fdate), $pastTime)==0 )
  			 $pastTime++;
			 else
			   $pastTime=0;

  if ($ref=='start')
  	  $secPrices = $this->getStartPrice($sec,$ses,$pastTime,$refDate);   
	  
   if ($periodType=='S')  {	//Single past date
       $group_time= " substr(s.trade_time,1,2) ".($interval>0 ?",trunc(to_number(substr(s.trade_time,3,2))/$interval) " :"");
	   $select_time= " substr(s.trade_time,1,2) ".($interval>0 ?" || ':' || DECODE(trunc(to_number(substr(s.trade_time,3,2))/$interval),0,'00','30') as fdate " :" as fdate");
  	   $dateCompare = " = to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')-$pastTime";
	   }
	  else	{   // Cumulative
	   		$group_time= " to_char(s.trade_date,'".$group_format."') ";
			$select_time= " to_char(s.trade_date,'".$group_format."') fdate ";
			$dateCompare = " between to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')-$pastTime and to_date('".$session_sec->fdate."','YYYY-MM-DD') ";
		 	}
			
  //while (($row = oci_fetch_object($stmt))) 
  $ORAdp->query =  
  		"SELECT DISTINCT fdate," 
  	. 	" sum(CASE WHEN side = 'B' THEN v ELSE 0 END ) bv,"
	.	" sum(CASE WHEN side = 'S' THEN v ELSE 0 END ) sv, "
	. 	" sum(CASE WHEN side = 'B' THEN pr ELSE 0 END ) bp,"
	.	" sum(CASE WHEN side = 'S' THEN pr ELSE 0 END ) sp"
	.	" FROM ("
	
	.	" SELECT 'B' AS side, ".$select_time. ", sum( volume ) v, "
	.	" round((avg( price ) /100 ),2) AS pr"
	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" WHERE " . "s.buy_".$cod_where ."= '". mb_convert_encoding($nodeId,"ISO-8859-7","UTF-8")."'"
	.	" and ISIN = '".$sec."'"
	.	" and ASE_SYMBOL = HELLENIC_SYMBOL"	
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".$session_sec->fdate."','YYYY-MM-DD')-$pastTime"
	.	" and s.trade_date ". $dateCompare  
	.	" GROUP BY ".$group_time
	
	.	" UNION "
	.	" SELECT 'S' AS side, ".$select_time. ", sum( volume ) v, "
	.	" round((avg( price ) /100 ),2) AS pr"
	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" WHERE " . "s.sell_".$cod_where ."= '". mb_convert_encoding($nodeId,"ISO-8859-7","UTF-8")."'"
	.	" and ISIN = '".$sec."'"
	.	" and ASE_SYMBOL = HELLENIC_SYMBOL"	
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".$session_sec->fdate."','YYYY-MM-DD')-$pastTime"
	.	" and s.trade_date ". $dateCompare  
	.	" GROUP BY ".$group_time
/*	
	.	" UNION "
	.	" SELECT 'B' AS side, ".$select_time. ", 0 v, 0 AS pr"
	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" WHERE " . "s.buy_".$cod_where ." != '". mb_convert_encoding($nodeId,"ISO-8859-7","UTF-8")."'"
	.	" and ISIN = '".$sec."'"
	.	" and ASE_SYMBOL = HELLENIC_SYMBOL"	
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".$session_sec->fdate."','YYYY-MM-DD')-$pastTime"
	.	" and s.trade_date ". $dateCompare  
	.	" GROUP BY ".$group_time
	
	.	" UNION ALL"
	.	" SELECT 'S' AS side, ".$select_time. ", 0 v, 0 AS pr"
	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" WHERE " . "s.sell_".$cod_where ." != '". mb_convert_encoding($nodeId,"ISO-8859-7","UTF-8")."'"
	.	" and ISIN = '".$sec."'"
	.	" and ASE_SYMBOL = HELLENIC_SYMBOL"	
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".$session_sec->fdate."','YYYY-MM-DD')-$pastTime"
	.	" and s.trade_date ". $dateCompare  
	.	" GROUP BY ".$group_time
	*/	
	.	" )a"
	.	" GROUP BY fdate"
	.	" ORDER BY fdate";
  
  
//print "getOraChartData: ". $ORAdp->query."\n";
	
  $stmt = $ORAdp->db_query();
  if (DB::isError($stmt))
		{
			$this->errorlog->addlog( 'callbackget_securities_intoDB: -err in: q='. $ORAdp->query);
			//$this->disconnectORA();
			return;
			}	
			
  $nrows = oci_fetch_all($stmt, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
  
 $prev_bp = 0;
 $prev_sp = 0;
 
 foreach($res as $row){	 
		$script.=$row["FDATE"].";".$row["BV"] .";".((int)$row["BV"] > 0 ?$row["BP"] :$prev_bp).";".$row["SV"] .";".((int)$row["SV"]>0 ?$row["SP"] :$prev_sp) ."\n"; //.";".$row->bv.";".$row->sv
		$prev_bp = ((int)$row["BP"] > 0 ?$row["BP"] :$prev_bp);
		$prev_sp = ((int)$row["SP"] > 0 ?$row["SP"] :$prev_sp);
		}
 
//  oci_free_statement($stm);
//print $script;		
  $obj->write($socket_id, $channel_id, $script); 
 }  
  
 function getBubbleSettings($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp; 
  $nodeId = $_DATA['objId']; //$this->utf8Urldecode($_DATA['objId']);
  $nodeType = $_DATA['objType'];
  $ses = $_DATA['ses'];
  $ref = (strlen($_DATA['ref'])>0 ?$_DATA['ref'] :'');
  $topCount = ((int)(strlen($_DATA['top'])>0 ?$_DATA['top'] :10)) - 1;
  $pastTime = $_DATA['pastTime'];
  $refDate = $_DATA['refDate'];
  
  if ($ref=='start') // start_price
	  $secPrices = $this->getStartPrice($nodeId,$ses,$pastTime,$refDate);  // aux_misc::
  
//  <color>b6b2b2,a51a1f,7ebf4e,0fbbb9,f7af23,7d558d,a7d0e8,0163a4,db938b,8538ad</color>
  $settings = '<settings>';
  $settings.= '<labels><label><x>159</x><y>50</y><text_color>#ffffcc</text_color><text_size>16</text_size><text><b>Historical Crude Oil Prices</b></text></label></labels>';
  $settings.= "<balloon><color></color><text_color>#FFFFFF</text_color><corner_radius>4</corner_radius><border_width>2</border_width><border_alpha>50</border_alpha>";
  $settings.= "<border_color>#000000</border_color><alpha>80</alpha><width>200</width></balloon>";
  
  $settings.= "<help><button><x>1</x><y>10</y></button><balloon><text>Select the area to enlarge</text></balloon></help>";
  $settings.= "<bullets><grow_time>2</grow_time><sequenced_grow>true</sequenced_grow></bullets>";

  $settings.= "<font>Arial</font><text_color>999999</text_color><plot_area><margins><left></left><top></top><right>90</right><bottom>120</bottom></margins></plot_area>";
  $settings.= "<values><inside>false</inside><min>".($ref=='' ?'' :$secPrices->FLOOR_PRICE)."</min><max>".($ref=='' ?'' :$secPrices->CEILING_PRICE)."</max><strict_min_max>true</strict_min_max></y></values>";
  
  $settings.= '<date_formats><date_input>hh:mm</date_input><duration_input/>';
  $settings.= '<axis_values><ss first="month DD, YYYY" regular="hh:mm:ss"/><mm first="month DD, YYYY" regular="hh:mm"/><hh first="month DD, YYYY" regular="hh:mm"/>';
  $settings.= '<DD first="month DD, YYYY" regular="month DD"/><MM first="month, YYYY" regular="month"/><YYYY first="YYYY" regular="YYYY"/></axis_values>';
  $settings.= '<balloon>month DD, YYYY</balloon><data_labels>month DD, YYYY</data_labels></date_formats>';
  $settings.= "</settings>";
//
  $obj->write($socket_id, $channel_id, $settings);
// print "\n".$secPrices->FLOOR_PRICE_PCT."==".abs($secPrices->START_OF_DAY_PRICE - $secPrices->FLOOR_PRICE_PCT)*2 ."==".(($secPrices->FLOOR_PRICE_PCT - abs($secPrices->START_OF_DAY_PRICE - $secPrices->FLOOR_PRICE_PCT)*2) / 100)."\n";    
  }
  
/**
*	Called by getChartData in case  $nodeId conrains ISIN
*/ 
 function getChartBubble($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp; 
  $script="";
  $session_cond="";
  $session_from="";
  $minPrice = 0;
  $maxPrice = 0;
  $minVol	= 0;
  $maxVol	= 0;

  $pastTime = $_DATA['pastTime'];
  $refDate = $_DATA['refDate'];
   
  if ($pastTime or $refDate)
  		return ( $this->getOraChartBubble($obj, $socket_id, $channel_id, $_DATA));
		
  $nodeId = $_DATA['objId']; 
  $nodeType = $_DATA['objType'];
  $sec = $_DATA['sec'];
  $ses = $_DATA['ses'];
  
  $topCount = ((int)(strlen($_DATA['top'])>0 ?$_DATA['top'] :10)) - 1;
  
  $interval = (int)(strlen($_DATA['interval'])>0 ?$_DATA['interval'] :0);
  $ref = (strlen($_DATA['ref'])>0 ?$_DATA['ref'] :'');
 
  if ($ref=='start')
  	  $secPrices = $this->getStartPrice($nodeId,$ses,$pastTime,$refDate);  // aux_misc::
  
  if ($refDate) {
  		$session_from = "";
	    $session_cond = " AND DATE_FORMAT( '".$refDate."', '%Y%m%d' ) = trade_date ";
		}
  else		
 	 if ($ses) {
      		$session_from = ", imk_imarket_cfg";
	  		$session_cond = " AND id = $ses AND fdate=trade_date"	;  
	  		}
 
	  
  $q = "SELECT concat(substring(hr,1".($interval==0 ?',2),"00")' :',4),"")') ." AS fdate, mem, "
	.	" (sum(CASE WHEN side = 'B' THEN vol ELSE 0 END ) + sum( CASE WHEN side = 'S' THEN vol ELSE 0 END ) ) vol, round((price/100),2) as price"
	.	" FROM ("
	.	" (SELECT 'B' side, substring( OASIS_TIME, 1, 6 ) hr, BUY_MEMBER_ID mem, sum( volume ) vol,avg( price ) price"
	.	" FROM imk_IS". $session_from
	.	" WHERE SECURITY_ISIN = '".$nodeId."'"
	.	  $session_cond
	.	" GROUP BY hour( hour( convert( substring( OASIS_TIME, 1, 6 ) , TIME ) ) )," .($interval>0 ? " floor( minute( hour( convert( substring( OASIS_TIME, 1, 6 ) , TIME ) ) ) /$interval )," :"")." BUY_MEMBER_ID"
	.	" ORDER BY hr, vol desc)"
	.	" UNION"
	.	" (SELECT 'S' side, substring( OASIS_TIME, 1, 6 ) hr, SELL_MEMBER_ID mem, sum( volume ) vol,avg( price ) price"
	.	" FROM imk_IS". $session_from
	.	" WHERE SECURITY_ISIN = '".$nodeId."'"
	.	  $session_cond
	.	" GROUP BY hour( hour( convert( substring( OASIS_TIME, 1, 6 ) , TIME ) ) )," .($interval>0 ? " floor( minute(hour( convert( substring( OASIS_TIME, 1, 6 ) , TIME ) ) ) /$interval )," :"")." SELL_MEMBER_ID"
	.	" ORDER BY hr, vol desc)"	
	.	" )a"
	.	" GROUP BY fdate,mem"
	.	" ORDER BY fdate,vol desc";
//print "getChartBubble: ". $q."\n";	
  $dp->setQuery(stripslashes($q));
  $data = $dp->loadObjectList();
  if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id,  $dp->stderr());
		return;
		}
/* 
 foreach($data as $row){
 	$minPrice = min($row->price,$minPrice);
	$maxPrice = max($row->price,$maxPrice);
	$minVol	= min($row->vol/100,$minVol/100);
	$maxVol	= max($row->vol/100,$maxVol/100);
 	}
*/ 
  $cntr  = 0;
  $fdate = "";
  $MemCntr=0;
  //max_date_count="8"
  $script='<timeline swf_file="js/graphs/amxy.swf" settings_file="js/graphs/traders_settings_bubble.xml" max_date_count="'.count($data).'" slider_x="60" slider_y="!70" slider_width="510" font="Tahoma" text_color="#000000" text_size="10"  date_text_color="#80aae9" date_x="25%" date_y="53%" date_text_size="30" delay="0.8" loop="true" autoplay="false">'."\n";
 
  foreach($data as $row){				
		$timestr=($interval==0 ?$row->fdate :$this->roundTime($row->fdate,$interval));
		if ($timestr != $fdate ) 	// Time Break
		{
			$cntr=0;
			
			$script.=($fdate != "" ?'</graphs></chart></data_set>'."\n" :"");
			$script.='<data_set date="'.$timestr.'"><chart><graphs>'."\n";
			$fdate = $timestr;
			$mem = $row->mem;
			$MemCntr=0;
			//$cntr++;
			}
			elseif ($cntr >= $topCount)		// Top count Break
				{
				//$change = ($MemCntr+1 == $topCount ?true :false);  // Ignore when Greater than topCount
				$cntr++;
				continue;
				}
		
		if ($row->mem != $mem) {
			$mem = $row->mem;
			$MemCntr=0;
			}
		$yVal = ($ref!='start' ?$row->price :($row->price * ((($secPrices->START_OF_DAY_PRICE) > $row->price) ?  -1 :1)) );
		$script.='<graph gid="'.$cntr.'" title="'.$row->mem.'" color="'.$this->getMemberColor($row->mem).'" visible_in_legend="1" alpha="0" balloon_text="Member:{title} Price:{y} Vol:{value}" bullet="bubble"'.'>'."\n";
//data_labels="{title}"
		$script.='<point x="' . ($row->vol)  // /100
				.'" y="'.$yVal.'" value="'.$row->vol
				.'" color="'.$this->colors_table[$cntr].'" bullet_color="'.$this->colors_table[$cntr].'"></point>'."\n";
		$script.='</graph>'."\n";
		$cntr++;
		//$MemCntr++;
	   }
  $script.='</graphs></chart></data_set>'."\n";
  $script.='</timeline>';

  $obj->write($socket_id, $channel_id, $script); 
//  print  $script;
  }

/**
* 	Called by getOraChartBubble in case $nodeId contains a Security Isin
*/
 function getOraChartBubble($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp;
  $ORAdp=$this->ORAdp ;
  $script="";
    
  $pastTime = $_DATA['pastTime']; 
 
  $nodeId = $_DATA['objId']; 
  $nodeType = $_DATA['objType'];
  $sec = $_DATA['sec'];
  $ses = $_DATA['ses'];
  $refDate = $_DATA['refDate'];
  
  $topCount = ((int)(strlen($_DATA['top'])>0 ?$_DATA['top'] :10)) - 1;
  
  $interval = (int)(strlen($_DATA['interval'])>0 ?$_DATA['interval'] :0);
  $ref = (strlen($_DATA['ref'])>0 ?$_DATA['ref'] :'');
  $periodType = $_DATA['period'];
  
 
  
/*   SELECT 'S' side,  substr(s.trade_time,1,2) fdate, 
DECODE(trunc(to_number(substr(s.trade_time,3,2))/30),0,'00','30') half,
SELL_MEMBER_ID mem, sum( volume ) vol, avg( price ) price 
FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b 
where ASE_SYMBOL = HELLENIC_SYMBOL and ISIN = 'GRS260333000' 
and substr(valid_board_id,0,1)='Y' and s.market_id= 'M' and b.market_id= s.market_id 
and s.board_id= 'M' and b.trade_date = to_date('2010-01-26','YYYY-MM-DD') 
and s.trade_date between to_date('2010-01-26','YYYY-MM-DD')-30 and to_date('2010-01-26','YYYY-MM-DD') 
GROUP BY substr(s.trade_time,1,2) ,trunc(to_number(substr(s.trade_time,3,2))/30), SELL_MEMBER_ID 
ORDER BY fdate, vol desc*/
     
   $q="SELECT fdate, market,board"
	.	" FROM imk_imarket_cfg WHERE id =$ses";
	
	$dp->setQuery(stripslashes($q));
    $dp->loadObject($session_sec);
	
	 $group_format = ($pastTime == 30 ?"YYMM-W" :($pastTime == 180 ?"YY-MM" :($pastTime == 7 ?"YYMM-DD" :"YYMM-DD")));
	 
	if ($pastTime)
		while( $this->validateOraTradeDate(($refDate ?$refDate :$session_sec->fdate), $pastTime)==0 )
  		   	   $pastTime++;
		else		// in case call is comming from tv dates
			$pastTime=0;
	
   if ($ref=='start')
  	   $secPrices = $this->getStartPrice($nodeId,$ses,$pastTime,$refDate);  //
	  
   if ($periodType=='S')  {	//Single past date
       $group_time= " substr(s.trade_time,1,2) ".($interval>0 ?",trunc(to_number(substr(s.trade_time,3,2))/$interval) " :"");
	   $select_time= " substr(s.trade_time,1,2) ".($interval>0 ?" || ':' || DECODE(trunc(to_number(substr(s.trade_time,3,2))/$interval),0,'00','30') as fdate " :" as fdate");
  	   $dateCompare = " = to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')-$pastTime";
	   }
	  else	{   // Cumulative
	   		$group_time= " to_char(s.trade_date,'".$group_format."') ";
			$select_time= " to_char(s.trade_date,'".$group_format."') fdate ";
			$dateCompare = " between to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')-$pastTime and to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD') ";
		 	}
	
  $ORAdp->query = "SELECT fdate, mem, "
	.	" (sum(CASE WHEN side = 'B' THEN vol ELSE 0 END ) + sum( CASE WHEN side = 'S' THEN vol ELSE 0 END ) ) vol, round(avg(price/100),2) as price"
	.	" FROM ("
  	.	" SELECT 'B' side, ".$select_time.", BUY_MEMBER_ID mem, sum( volume ) vol, avg( price ) price"     
  	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" where"
  	.	" ASE_SYMBOL = HELLENIC_SYMBOL"
	.	" and ISIN = '".$nodeId."'"
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')"
	.	" and s.trade_date ". $dateCompare  
	.	" GROUP BY ".$group_time . ", BUY_MEMBER_ID"
	.	" UNION ALL"
	.	" SELECT 'S' side, ". $select_time .", SELL_MEMBER_ID mem, sum( volume ) vol, avg( price ) price"     
  	. 	" FROM mm_dba.mm_oasis_executions s, mm_dba.mm_securities b"
	.	" where"
  	.	" ASE_SYMBOL = HELLENIC_SYMBOL"
	.	" and ISIN = '".$nodeId."'"
	.	" and substr(valid_board_id,0,1)='Y'"
	.	" and s.market_id= '".$session_sec->market."'"
	.	" and b.market_id= s.market_id"	
	.	" and s.board_id= '".$session_sec->board."'"	
	.	" and b.trade_date = to_date('".($refDate ?$refDate :$session_sec->fdate)."','YYYY-MM-DD')"
	.	" and s.trade_date " . $dateCompare
	.	" GROUP BY ". $group_time .", SELL_MEMBER_ID"
	.	" ORDER BY fdate, vol desc) a"
	.	" GROUP BY fdate,mem"
	.	" ORDER BY fdate, vol desc";
//print($ORAdp->query."\n");
	$stmt = $ORAdp->db_query();
 
	if (DB::isError($stmt))
		{
			$this->errorlog->addlog( 'callbackget_securities_intoDB: -err in: q='. $ORAdp->query);
			//$this->disconnectORA();
			return;
			}	
  
  $result='';
 
  $cntr  = 0;
  $fdate = "";
  $MemCntr=0;
  
 $nrows = oci_fetch_all($stmt, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
 
 $maxdate = ($pastTime >= 7 ?7 :($pastTime >= 180 ?180/6 :($pastTime >= 30 ?30/4 :count($res))));
 $script='<timeline swf_file="js/graphs/amxy.swf" settings_file="js/graphs/traders_settings_bubble.xml" max_date_count="'.$maxdate .'" slider_x="60" slider_y="!70" slider_width="510" font="Tahoma" text_color="#000000" text_size="10"  date_text_color="#80aae9" date_x="25%" date_y="53%" date_text_size="30" delay="0.8" loop="true" autoplay="false">'."\n";
 //print("Rows=".$nrows);
  //while (($row = oci_fetch_object($stmt))) 
  foreach($res as $row)
  {
//    print_r($row);
	// Use upper case attribute names for each standard Oracle column
        $values='';
			
		$timestr=$row["FDATE"];
		if ($timestr != $fdate ) 	// Time Break
		{
			$cntr=0;
			
			$script.=($fdate != "" ?'</graphs></chart></data_set>'."\n" :"");
			$script.='<data_set date="'.$timestr.'"><chart><graphs>'."\n";
			$fdate = $timestr;
			$mem = $row["MEM"];
			$MemCntr=0;
			//$cntr++;
			}
			elseif ($cntr >= $topCount)		// Top count Break
				{
				//$change = ($MemCntr+1 == $topCount ?true :false);  // Ignore when Greater than topCount
				$cntr++;
				continue;
				}
		
		if ($row["MEM"] != $mem) {
			$mem = $row["MEM"];
			$MemCntr=0;
			}
		$yVal = ($ref!='start' ?$row["PRICE"] :($row["PRICE"] * ((($secPrices->START_OF_DAY_PRICE) > $row["PRICE"]) ?  -1 :1)) );
		$trans_mem = mb_convert_encoding($row["MEM"], "UTF-8","ISO-8859-7");
		$script.='<graph gid="'.$cntr.'" title="'. $trans_mem.'" color="'.$this->getMemberColor($trans_mem).'" visible_in_legend="1" alpha="0" balloon_text="Member:{title} Price:{y} Vol:{value}" bullet="bubble"'.'>\n';

		$script.='<point x="' . ($row["VOL"])/100
				.'" y="'.$yVal.'" value="'.$row["VOL"]
				.'" color="'.$this->colors_table[$cntr].'" bullet_color="'.$this->colors_table[$cntr].'"></point>'."\n";
		$script.='</graph>'."\n";
		$cntr++;
		//$MemCntr++;
	   }
  $script.='</graphs></chart></data_set>'."\n";
  $script.='</timeline>';
  
//  oci_free_statement($stm);
//print $script;
  $obj->write($socket_id, $channel_id, $script); 
 }

/*
* Read Securities in Dates filtered by the Client
* @param	string msgId record type (IT,IS), int posStart: next record position ( for multiple ajax calls), int msg_num: number of limit records
* @version 2.0.1
* @author JL
*/
function searchOraSecs($obj, $socket_id, $channel_id, $_DATA)
 { 
  $dp=$this->dp;
  $ORAdp=$this->ORAdp ;
  
  $format=$_DATA["format"];
  $filter=$_DATA["filter"];
  $sesid=$_DATA["session"];
  $newRequest=$_DATA["newRequest"];
  
  $symbol_condition="";
  $volume_condition="";
  $trader_condition="";
  $investor_condition="";
  $phase_condition="";
  $suspended_condition="";
  $price_condition="";
  $from="";
  $join="";
  $group="";
  $having="";
   
  $ar=json_decode(stripslashes($filter)); 
  
  $select = "TOTAL_VOLUME_MAIN VOLUME";
  
  if ($ar->datecond1=="<>")
      $date_condition = " and exe.trade_date between to_date('".$ar->datefrom."','DD/MM/YYYY')" . " and to_date('".$ar->dateto."','DD/MM/YYYY')";
	  else
	     $date_condition = " and exe.trade_date ".$ar->datecond1 ." ". "to_date('".$ar->datefrom."','YYYY/MM/DD')";
  
  if ($ar->symbol!='')
  	    $symbol_condition =  " and sec.hellenic_symbol='".mb_convert_encoding($ar->symbol, "ISO-8859-7", "UTF-8")."' ";
		
  if ($ar->volume>0)  
		$volume_condition = " and volume ".$ar->volcond . " ". $ar->volume;
		
  if ($ar->trader!="")  {
		$trader_condition = " and ".$ar->sidetrader." = '".mb_convert_encoding($ar->trader, "ISO-8859-7", "UTF-8")."'";
		$from = ",mm_dba.mm_oasis_executions exe";
		$join = " and sec.hellenic_symbol = exe.ase_symbol";
		if ($ar->datecond1=="<>")
            $trader_condition .= " and exe.trade_date = sec.trade_date ";
		if ($ar->volume>0){
			$having=" HAVING sum(volume) > ".$ar->volume;
			$volume_condition="";
			$select = ",sum(exe.VOLUME) AS VOLUME ";
			$group = " GROUP BY sec.trade_date,sec.isin,hellenic_symbol";
		   }
		}
  
  if ($ar->investor!="")  {
		$investor_condition = " and ".$ar->sideinvestor. " = '".mb_convert_encoding($ar->investor, "ISO-8859-7", "UTF-8")."'";
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

  if ($ar->pricefield!="no")  { // $ar->pricecond=="prices"
  	  $pf1 = ($ar->pricefield!="price" ?"ROUND(($ar->pricefield/100),0)" :$ar->pricefield);
	  $pf2 = ($ar->pricefield2!="price" ?"ROUND(($ar->pricefield2/100),0)" :$ar->pricefield2);
	  
	  $price_condition=" and $pf1 $ar->pricecond $pf2 and ((100 * ($pf1 -  $pf2)/$pf1)) $ar->pricecond $ar->pricepct";
	  $from .= ",mm_dba.mm_stock_day_trans dt";
	  $join .= " and sec.hellenic_symbol = dt.ase_symbol and exe.trade_date=dt.trans_date";
	  }
	      
 // if(results==0) {
	$existed_rows=0;
//	print("getOraSecs id=".$ar->id."==".$newRequest."\n");
	if ((int)$ar->id > 0 && $newRequest==0)
	{
	 $sql="SELECT count(*) FROM imk_QOS WHERE CR_ID=".$ar->id." AND SES=".$sesid;
	 $dp->setQuery(stripslashes($sql)); 
	 $existed_rows=$dp->loadResult();
//	 print("existed_rows=".$existed_rows."\n");
	 }
  	
	$q="SELECT fdate, market,board"
	.	" FROM imk_imarket_cfg WHERE id =$sesid";
	
	$dp->setQuery(stripslashes($q));
    $dp->loadObject($session_cfg);
	
	if ( ($ar->id==0 && $newRequest>0) || !$existed_rows)
	{
	 if ($existed_rows > 0) {
		$sql="DELETE FROM imk_QOS WHERE SES=".$sesid;
		$dp->setQuery(stripslashes($sql));
	    $dp->query();
	    $err=$dp->getAffectedRows();	
    	if ($err < 1) {
			  $obj->write($socket_id, $channel_id, 'searchOraSecs err: '.$dp->stderr());
	       }
	   }
	   
	$ORAdp->query = "SELECT ".$sesid." AS SES, sec.isin AS ISIN_CODE,"
	.	" to_char(sec.trade_date,'YYYY/MM/DD') as FDATE, sec.hellenic_symbol SYMBOL ".$select
  	. 	" FROM  mm_dba.mm_securities sec" . $from
	.	" where"
 // 	.	" board_id='".$parent->cfg->board."'"
	.	" sec.market_id='".$session_cfg->market."'"
	.	  $symbol_condition
	.	  $date_condition
	.	  $volume_condition
	.	  $trader_condition
	.	  $investor_condition
	.     $phase_condition
	.	  $suspended_condition
	.	  $price_condition
	.	  $join
	.	  $having
	.	  $group
	.	" order by sec.trade_date";
//print( "getOraSecs:".$this->query."\n");
   
    $stmt = $ORAdp->db_query();
 
	if (DB::isError($stmt))
		{
			$this->errorlog->addlog( 'searchOraSecs: -err in: q='. $ORAdp->query);
			//$this->disconnectORA();
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
	 
	$sql="INSERT INTO imk_QOS (".$cols.") VALUES(".stripslashes($values).")"; 
//echo $this->query."\n";
	$dp->setQuery(stripslashes($sql));
    $dp->query();
    $err=$dp->getAffectedRows();	
    if ($err < 1) {
		  $obj->write($socket_id, $channel_id, 'searchOraSecs err: '.$dp->stderr());
	      return;
		  }
    } 
	}
	
 $obj->write($socket_id, $channel_id, "err='ok';");
 } 
/*
* Read Securities in Dates filtered by the Client
* @param	string msgId record type (IT,IS), int posStart: next record position ( for multiple ajax calls), int msg_num: number of limit records
* @version 2.0.1
* @author JL
*/
 function getSelectedSecs($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp;

  $format=$_DATA["format"];
  $startIndex=$_DATA["startIndex"];
  $posStart=$_DATA["posStart"]; 
  $count=$_DATA["count"]; 
  $results=$_DATA["results"];
  $sort=$_DATA["sort"];
  $dir=$_DATA["dir"];
  $newRequest=$_DATA["newRequest"];
  $filter=$_DATA["filter"];
  $ses=$_DATA["session"];
    
  $cond1 = "";
  
  $ar=json_decode(stripslashes($filter));
  $new_posStart = $posStart;
  if ($ar->id!=0)
	  $cond1 = "CR_ID=".$ar->id." AND ";
  
  if($results==0) {
		$sql="SELECT count(*) FROM imk_QOS WHERE ".$cond1." SES=".$ses;
	
		$dp->setQuery(stripslashes($sql)); 
 		$totalCount = $dp->loadResult();
	
		//$count = $totalCount;
		//$posStart = 0;	
		}
		
  if ($new_posStart)	
		$limitStr = " LIMIT ".$new_posStart . "," . $count;

		$sql = "SELECT "
			.	" CASE WHEN SELECTED=1 THEN 'checked' ELSE '' END AS SELECTED,FDATE,SYMBOL,ISIN_CODE,VOLUME"
			.	" FROM imk_QOS"
			.	" WHERE "
			.	" SES=".$ses
			.	" ORDER BY ".$sort . " " . $dir
	 		. 	$limitStr;
	
//print( 'getHistoryTrades sql: '.$sql); 

 $script = "<rows total_count='".$totalCount."' pos='".$posStart."'>";
 
 $dp->setQuery(stripslashes($sql)); 
 $rows=$dp->loadObjectList();
	
 if ($dp->getErrorNum()){
		$obj->write($socket_id, $channel_id, 'getHistoryTrades sql: '.$dp->stderr());
		return;
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

  
    require_once('JSON.php');
    $json = new Services_JSON();
    
	$obj->write($socket_id, $channel_id, $json->encode($returnValue));
	}
 }

 function checkSelectedSecs($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp;

  $format=$_DATA["format"];
  $value=$_DATA["value"];
  $fdate=$_DATA["fdate"]; 
  $ses=$_DATA["session"];

  $q="UPDATE imk_QOS SET SELECTED=".($value=="true" ?1 :0). " WHERE FDATE='".$fdate."' AND SES=".$ses; 

  $dp->setQuery(stripslashes($q));
  $dp->query();
  $err=$dp->getAffectedRows();	
  if ($err < 1) {
		$obj->write($socket_id, $channel_id, 'checkSelectedSecs upd: '.$q."==".$dp->stderr());
		return '';
	   }
	     
  $obj->write($socket_id, $channel_id, "ok");
  }
  
 function getMemberColor($mem)
 {
  if (!$this->colors_table[$mem])
	 {
	  $this->colors_table[$mem]= '#' . $this->component(255) . $this->component(255) . $this->component(255);
	  }
  return  $this->colors_table[$mem];
  }

 function component($max)
 {
    return str_pad(dechex(rand(0,$max)),2,'0',STR_PAD_LEFT);
 }  
/**
*  check if back trade date exists
*/ 
 function validateOraTradeDate($startDate, $pastTime)
 {
  $ORAdp=$this->ORAdp;
  
  $ORAdp->query = "SELECT count(*) cnt FROM mm_dba.mm_oasis_executions where trade_date = to_date('".$startDate."','YYYY-MM-DD')-$pastTime";
  $stmt = $ORAdp->db_query();
  $row = oci_fetch_object($stmt);
//  oci_free_statement($stm);
  
  return $row->CNT;
  }
  
 function roundTime($ftime,$interval)
 {
  $min=substr($ftime,2,2);
  if ($interval==0)
  	  return (substr($ftime,0,2).':'.$min);
	  
  $p = floor((int)$min / (int)$interval); // 8.5 -> 8
//    print  $ftime."==".$interval."==".$p."==".substr($ftime,0,2).sprintf("%02d",$p*$interval)."\n";
  return (substr($ftime,0,2).':'.sprintf("%02d",$p*$interval)); //.substr($ftime,5,3)
  }
     
} // class
/*
SELECT CONCAT( DATE_FORMAT( trade_date, '%Y/%m/%d' ) , ' ', TIME_FORMAT( concat( hr, '0000' ) , '%H:%i:%s' ) ) AS fdate, mem, (
sum(
CASE WHEN side = 'B'
THEN vol
ELSE 0
END ) + sum(
CASE WHEN side = 'S'
THEN vol
ELSE 0
END )
)svol, price /100
FROM (
(
SELECT 'B'side, trade_date, substring( OASIS_TIME, 1, 2 ) hr, BUY_MEMBER_ID mem, sum( volume ) vol, avg( price ) price
FROM imk_IS
WHERE SECURITY_ISIN = 'GRS426003000'
GROUP BY hr, BUY_MEMBER_ID
)
UNION (

SELECT 'S'side, trade_date, substring( OASIS_TIME, 1, 2 ) hr, SELL_MEMBER_ID mem, sum( volume ) vol, avg( price ) price
FROM imk_IS
WHERE SECURITY_ISIN = "GRS426003000"
GROUP BY hr, SELL_MEMBER_ID
)
)a
GROUP BY fdate, mem
ORDER BY fdate, mem 

SELECT 'B'side, convert( substring( OASIS_TIME, 1, 4 ) , TIME ) hr, OASIS_TIME, BUY_MEMBER_ID mem, sum( volume ) vol, avg( price ) price
FROM imk_IS
WHERE SECURITY_ISIN = 'GRS426003000'
GROUP BY hour( hr ) , floor( minute( hr ) /30 ) , BUY_MEMBER_ID
ORDER BY hr
*/