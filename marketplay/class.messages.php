<?php
/*
*	Reads Messages templates from MySql and	creates a Collection (Arrays) of Messages  - Fields.
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/

require_once dirname(__FILE__) ."/Collection.class.php";

DEFINE('_IDX_LENGTH',1);
DEFINE('_IDX_HEADER',2);
DEFINE('_IDX_DECIMALS',3);
DEFINE('_IDX_TITLE',4);
DEFINE('_IDX_ID',5);

DEFINE('_HEADER',21);
DEFINE('_HEADER_OASIS_ID',-18);
DEFINE('_HEADER_OASIS_ID_LEN',6);
DEFINE('_HEADER_TIME',-8);
DEFINE('_HEADER_TIME_LEN',8);
DEFINE('_MSGLEN',2);
DEFINE('_MESSAGE_SIZE_LEN',3);
DEFINE('_MESSAGE_NEWLINE_LEN',2);
DEFINE('_MESSAGE_POS',0);
DEFINE('_MESSAGE_POS_LEN',12);

/*
* Reads Messages templates from MySql and	creates a Collection (Arrays) of Collection.class objects.
* @package TradeReplay 
* @version 2.0.1
* @author JL
*/
class oasis_messages {

private $msgsInfo;
private $dp;
private $errorlog;

	function __construct ($DBname,$filter=0)
	{
	    $this->dp = new dp_database( 'localhost', 'root', '', $DBname, '', 0 );
		$this->msgsInfo = new Collection;
		$this->errorlog = new msg_log;
		$this->buildMsgsInfo($filter);
	}


	function buildMsgsInfo($filter=0)
	{	    	
		$q="SELECT m.mid,m.name,m.type,m.length"
 			.	" FROM imk_msgs AS m" ;
 		if ($filter)
		   $q.=" WHERE type=$filter";
		   
		$this->dp->setQuery($q);
		$msgsheader = $this->dp->loadObjectlist();
		
		foreach($msgsheader as $msg_header) {	
			$this->msgsInfo->set($msg_header->mid,new Collection)->append($msg_header->mid)->append($msg_header->name)->append($msg_header->length)->append($msg_header->type) ;
		}
		
		$q="SELECT f.id,f.msg_id,f.field,f.decimals,f.offset, f.header,f.length,f.show_id,f.show_title"
 			.	" FROM imk_msg_fields AS f ORDER by id";

		$this->dp->setQuery($q);
		$rows = $this->dp->loadObjectList();	
//echo		$rows[0]->msg_id;
		$invalid_chars = array(' ','-','/','%',"'",'#',"’");
		foreach($rows as $row) {
			$ar=$this->msgsInfo->get($row->msg_id);
			//print_r($ar);
			if (empty($ar))
			    continue;
			// *** first, add an extra column, the time from the Message Header ***
			   // if ($this->msgsInfo[$row->msg_id][3]==1)
				//	$this->msgsInfo->get($row->msg_id)->set("OASIS_POSITION",new Collection)->
					//												append(_MESSAGE_POS)->append(_MESSAGE_POS_LEN);	
//$this->errorlog->addlog( 'buildMsgsInfo: '. $row->msg_id);					
				$this->msgsInfo->get($row->msg_id)->set("OASIS_TIME",new Collection)->
																	append(_HEADER_TIME)->append(_HEADER_TIME_LEN)->append(3)->append(-3)->append('time')->append("OASIS_TIME");
				if ($row->msg_id=='IT') 
				{
				    $this->msgsInfo->get($row->msg_id)->set("ORDER_MOD",
								new Collection)->append(_HEADER_TIME)->append(1)->append(1)->append(-1)->append('ORDER_MOD');
					$this->msgsInfo->get($row->msg_id)->set("ORDER_ID",
								new Collection)->append(_HEADER_OASIS_ID)->append(_HEADER_OASIS_ID_LEN)->append(3)->append(-1)->append('ORDER_ID');
				 }				
																					
				$field_name=str_replace($invalid_chars, "_", strtoupper($row->field));
				//if ($row->msg_id=="IS" && $row->header==3)
				//	   print( 'buildMsgsInfo: '. $row->msg_id."=".$row->show_title."==".$row->field."=".$row->show_id."\n");	
	   			$this->msgsInfo->get($row->msg_id)->set($field_name,new Collection)->append($row->offset)->append($row->length)->append($row->header)->append($row->decimals)->append(($row->show_title=="" ?$row->field :$row->show_title))->append($row->show_id);
		}
/*		
		$is = $this->msgsInfo->get("IA"); 
		if ($is != NULL) {
				
		echo $is->get('0');
		echo $is->get('1');	
		
		foreach($is as $key=>$val) {
		if (is_numeric($key)) continue;
		echo $key;
			foreach($val as $k=>$v) {
				
				echo " v=".$v ;				
				}
		}
		}
*/		
	}
	
	function getMsg($id)
	{
	    //return $this->msgsInfo[$id];
		
		return ( $this->msgsInfo->get($id) ); 
	}
	
	function getIndexMsg($id)
	{
		
		return ( $this->msgsInfo->indexOf($id) ); 
	}
	
	function getMsgName($id)
	{
		return ( $this->msgsInfo->get($id)->get('0') ); 
	}
	
	function getMsgLength($id)
	{
		return ( $this->msgsInfo->get($id)->get('1') ); 
	}
	
	function getMsgFieldNum($id)
	{
		return ( $this->msgsInfo->size() ); 
	}
		
	function getMsgFields($id)
	{
		$isMsg = $this->msgsInfo->get($id);
		return ($isMsg->toArray());
	}
/*
		if ($isMsg != NULL) {
			foreach($isMsg as $key=>$val) {
				if (is_numeric($key)) continue;
				foreach($val as $k=>$v) {		
					echo " v=".$v ;				
					}
			}
		}
	}
	*/
	function __destruct ()
	{
	//echo "oasis_messages prototypes destroyed!</br>";
	}

}	
?>
