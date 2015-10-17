<?php
/** 
* This script contains a class, serving requests for sessions administration
* @package aux_service
* @version 1.0.0
* @author JL
*/

class aux_sessions extends aux_basic
{
 static $hive; // factory object
 
/**
* return a session id
*/
public function getSessionId($obj, $socket_id, $channel_id, $_DATA)
{
 $dp=$this->dp;
 $script="";
 
 $sesId = (strlen($_DATA['ses'])==0 ?0 :$_DATA['ses']);
  
    if ($sesId!=0)
		{
	    $q="SELECT * FROM `imk_SES` WHERE id = $sesId";
 
		$dp->setQuery($q);
		$dp->loadObject($ses);
		} 
	   else
	   {
	    $q="SELECT * FROM `imk_SES` WHERE STATUS = 'C' AND id >1 LIMIT 0,1"; // LIMIT 1
 
		$dp->setQuery($q);
		$dp->loadObject($ses);
	
		}
		
	if (!$ses || count($ses)==0)
 	   {
		  $q="INSERT into imk_SES (id,orderows,traderows,showprjgraph,status,title,duration) VALUES(0,5,5,1,'O','New Session',0)";
		  $dp->setQuery($q);
		  $dp->query();
		  $err=$dp->getAffectedRows();
		  if ($err < 1) {
		 	  $script= "INSERTerr=".$err . ";";
			  $obj->write($socket_id, $channel_id, $script);
	  		  }
	      $sesId = $dp->insertid();
		  $title="New Session";
		  
		   $q="SELECT * FROM `imk_imarket_cfg` WHERE id = ".$sesId;
		   $dp->setQuery($q);
		   $dp->loadObject($cfg);
		   if (!$cfg || count($cfg)==0)
		      {
			   $q="INSERT imk_imarket_cfg (id,dbase,filepath,state,fdate,market,securities,gr_symbol,en_symbol,indexes,board,init,vieworders,market_depth,matching) (SELECT ".$sesId.", dbase,filepath,state,fdate,market,securities,gr_symbol,en_symbol,indexes,board,init,vieworders,market_depth,matching FROM  imk_imarket_cfg  WHERE id = 1)";
			   $dp->setQuery($q);
		  	   $dp->query();
			   $err=$dp->getAffectedRows();
			   if ($err < 1) {
		 	  		$script= "err=".$err . ";" . "q=".$q;
			  		$obj->write($socket_id, $channel_id, $script);
	  		  		}
			   }
	   }
	 else {  
/**
* Check also, the days left for this Session before warning for files clearing!
*/
	 		 $now             = time();
 			 $unix_date       = strtotime( $ses->start_date);
			 $diff= (int)$now - (int)$unix_date;
			 $elapsed=$this->time_elapsed_string($unix_date);
			 $days = floor($diff/(60*60*24));
			 
			 $q="UPDATE imk_SES SET status='O'". ($days ?", duration=duration-$days" :"") ." WHERE id=".$ses->id;
		     $dp->setQuery($q);
		     $dp->query();
			 
			 $sesId = $ses->id;
			 $title=$ses->title;
			// Check if cfg with the same id exists...!
			 $q="SELECT * FROM `imk_imarket_cfg` WHERE id = ".$sesId;
		     $dp->setQuery($q);
		     $dp->loadObject($cfg);
		     if (!$cfg || count($cfg)==0)
		      {
			   $q="INSERT imk_imarket_cfg (id,dbase,filepath,state,fdate,market,securities,gr_symbol,en_symbol,indexes,board,init,vieworders,market_depth,matching) (SELECT ".$sesId.", dbase,filepath,state,fdate,market,securities,gr_symbol,en_symbol,indexes,board,init,vieworders,market_depth,matching FROM  imk_imarket_cfg  WHERE id = 1)";
			   $dp->setQuery($q);
		  	   $dp->query();
			   $err=$dp->getAffectedRows();
			   if ($err < 1) {
		 	  		$script= "err=".$err . ";" . "q=".$q;
			  		$obj->write($socket_id, $channel_id, $script);
	  		  		}
			   }
	 		} 

 //$script.= "days_left=\"" .$diff."==".$ses->start_date."==".floor($diff/(60*60*24)) ."\";";
 
 $ses_str='_' . $sesId; 
 
 $script.= "ses_str=\"".$ses_str."\";";
 $script.= "ses=".$sesId.";";
 $script.= "ses_title=\"".$title."\";";
 $script.= "ses_days_left=".$days.";";
 $script.= "elapsed_time='".$elapsed."';";
 $obj->write($socket_id, $channel_id, $script);
 }

function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    
    if ($etime < 1) {
        return '0 seconds';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}

/** 
* close an Opened Session
*/ 
 function closeSession($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 
 $sessionId = $_DATA['ses'];
 
 $q="UPDATE imk_SES SET status='C' WHERE id=".$sessionId;
 $dp->setQuery($q);
 $dp->query();
 $err=$dp->getAffectedRows();
 if ($err < 1) {
	$obj->write($socket_id, $channel_id, "err=".$err . ";"); 
	}
 $obj->write($socket_id, $channel_id, "err='OK';"); 
 }
 
}
?>
