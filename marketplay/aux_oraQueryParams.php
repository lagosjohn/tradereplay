<?php
/** 
* This script contains a class, which serves the needs for searching the Dates into Ora DB
* @package aux_service
* @version 1.0.0
* @author JL
*/

class oraQueryParams extends aux_basic
{

 function saveOraQueryParams($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";

 $params = $_DATA['params'];
 $exec = $_DATA['exec'];
 
 $ar=json_decode(stripslashes($params));
 $ar->id=0;
 
 foreach($ar as $col=>$val){
 if ($cols!=''){
    $cols.=',';
	$vals.=',';
	}
	 
  $cols.=$col;
  $vals.="'".$val."'";
  }
 
 $q="INSERT into imk_CR ($cols) VALUES($vals)";
 //print $q;
 $dp->setQuery($q);
 $dp->query();
 $err=$dp->getAffectedRows();
 if ($err < 1) {
 	  $script.= "error='".$dp->stderr()."'";
	  $obj->write($socket_id, $channel_id, $script);
	  }

 $script.="newOraQueryParamsId=".$dp->insertid().";".$exec;	
 $obj->write($socket_id, $channel_id, $script);  
 }

 function getOraQueryParams($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";
 
 $ses = $_DATA['session'];
 $getSymbols = $_DATA['getSymbols'];
 	 
 $q="SELECT * FROM imk_CR WHERE SESSION=".$ses;

 $dp->setQuery($q);
 $params=$dp->loadObjectlist();
 if ($dp->getErrorNum()){
    	$script.= "error='".$ses." __ ".$q."'";
		$obj->write($socket_id, $channel_id, $script);
		}
 
 if ($getSymbols)
     $script .= $this->getSecuritiesSymbolList($obj, $socket_id, $channel_id, '1');
 //$jparams=json_encode($params);

 //print("qparams=".$jparams.";");
 require_once('JSON.php');
 $json = new Services_JSON();
 $script .= "qparams=".$json->encode($params).";"; 	 
   
 $obj->write($socket_id, $channel_id, $script); 
 }

 function getOraSecCR($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";
 $cond="";
 
 $ses = $_DATA['session'];
 $id = $_DATA['id'];
 if ($id)
	$cond =" ID=".$id." AND ";
/**
*  Try to fetch only One. If ID is undefined, get all of session, but only the first!
*/ 
 $q="SELECT * FROM imk_CR WHERE ".$cond." SESSION=".$ses." ORDER BY ID LIMIT 1";

 $dp->setQuery($q);
 $dp->loadObject($param);
 if ($dp->getErrorNum()){
    	$script.= "error='".$dp->stderr()."'";
		$obj->write($socket_id, $channel_id, $script);
		}

 $script.="qparam=".json_encode($param).";";
 //require_once('JSON.php');
// $json = new Services_JSON();
// print ("qparams=".$json->encode($params).";"); 	
 $obj->write($socket_id, $channel_id, $script);
 }
 
 function getSecuritiesSymbolList($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";
 
 $q="SELECT SECURITY_ENGLISH_SYMBOL en_symbol,SECURITY_HELLENIC_SYMBOL gr_symbol"
		.	" FROM imk_IB ORDER BY SECURITY_HELLENIC_SYMBOL";
 
 $dp->setQuery($q);
 $secs = $dp->loadObjectList();
 if ($dp->getErrorNum()){
    	$script.= "error:".$dp->stderr();
		$obj->write($socket_id, $channel_id, $script);
		}
 
 require_once('JSON.php');
 $json = new Services_JSON();
 
 $script.= "secsList=".$json->encode($secs).";"; 	 
 
 if ($_DATA=='1')
     return $script;
	 
 $obj->write($socket_id, $channel_id, $script);
 }

 function clearOraQueryParams($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $script="";

 $ses = $_DATA['session'];
 
 $q="SELECT max(id) FROM imk_CR WHERE SESSION=".$ses;

 $dp->setQuery($q);
 $maxid = $dp->loadResult();
 
 if ($maxid)
 {
  $q="DELETE FROM imk_CR WHERE SESSION=$ses AND ID < $maxid";
  $dp->setQuery($q);
  $dp->query();
  $err=$dp->getAffectedRows();
  if ($err < 1) {
 	  $script.= "err=".$err . ";";
	  $obj->write($socket_id, $channel_id, $script);
	  return;
	  }    
  getOraQueryParams();
  }
 }

function getOraDates($obj, $socket_id, $channel_id, $_DATA){
 $dp=$this->dp;

 $script="";

 $ses = $_DATA['ses'];
 $isin = $_DATA['isin'];
 $crid = $_DATA['crid'];

 $q="SELECT FDATE FROM imk_QOS WHERE CR_ID=$crid AND ISIN_CODE='$isin' AND SES=$ses ORDER BY FDATE";
 
 $dp->setQuery($q);
 $dates = $dp->loadObjectList();
 if ($dp->getErrorNum()){
    	$obj->write($socket_id, $channel_id, "error:".$dp->stderr());
		return;
		}
 
 require_once('JSON.php');
 $json = new Services_JSON();
 $script.= "datesList=".$json->encode($dates).";"; 	 
 $obj->write($socket_id, $channel_id, $script);  
 }

 function setConfigCalendar($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;

 $script="ok";

 $ses = $_DATA['session'];
 $crid = $_DATA['crid'];

 $q="UPDATE imk_imarket_cfg a set a.CalendarDates_CR = $crid WHERE id = $ses";
 $dp->setQuery($q);
 $dp->query();
 $err=$dp->getAffectedRows();
 if ($err < 1) {
 	  $script.= "error='setConfigCalendar ".$dp->stderr()."';";
	  $obj->write($socket_id, $channel_id, $script);
	  }
 $obj->write($socket_id, $channel_id, $script);
 }
 
} // Class
?>
