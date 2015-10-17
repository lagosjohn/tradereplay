<?php
/** 
* This script contains a class, serving requests concering the setup of the market
* @package aux_service
* @version 1.0.0
* @author JL
*/

class aux_setup extends aux_basic
{
 static $hive; // factory object
 
/** 
* tuneApp  Edit session parameters 	
*/ 

 function tuneApp($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp;
  $script="";

  $sesid = $_DATA['ses'];
	
	$q="SELECT *"
	.	" FROM imk_SES"
	.	" WHERE  id = ".$sesid;
 
	$dp->setQuery($q);
	$dp->loadObject($row);
	
 $orows = $row->orderows;
 $trows = $row->traderows;

 $sec_script = $this->makeSecsList($sesid);
			
 $script.= '<rows><head>';
 $script.= '<column width="200" type="ro" align="left" color="#D4D0C8" sort="na">Parameter</column>';
 $script.= '<column width="*" type="co" align="left" sort="na">Values';
 $script.= '</column>';
 $script.= '</head>';

 $script.= '<row id="id"><cell> </cell><cell type="hidden">'.$row->id.'</cell></row>';
 $script.= '<row id="title"><cell style="background-color:#D4D0C8;">Title</cell><cell type="ed">'.$row->title.'</cell></row>';
 $script.= '<row id="orderows"><cell style="background-color:#D4D0C8;">Orders rows</cell><cell type="ed">'.$orows.'</cell></row>';

 $script.= '<row id="traderows"><cell style="background-color:#D4D0C8;">Trade rows</cell><cell type="ed">'.$trows.'</cell></row>';
 $script.= '<row id="showgraph"><cell style="background-color:#D4D0C8;">Show BidAsk Graph?</cell><cell type="ch">'.$row->showgraph.'</cell></row>';

 $script.= $sec_script;
 $script.= '<row id="delsecs"><cell>Erase marked Secs?</cell><cell type="ra">'.'del'.'</cell></row>';
 $script.= '<row id="delsecs"><cell>Update marked Secs?</cell><cell type="ra">'.'upd'.'</cell></row>';
 $script.= '</rows>';
 
 $obj->write($socket_id, $channel_id, $script);
 }

 function updateTuneOptions($obj, $socket_id, $channel_id, $_DATA)
 {
  $dp=$this->dp;
  $script="";
  
 $ses = 0;
 $ar_isins=array();
 $cols='';
 $delsecs='';
 $showgraph=0;
 
 //$sesid = $_DATA['ses'];
 
 foreach($_DATA as $col=>$val)
 {
  //print($col."\n");
  if (strlen($col)>4 && substr_compare($col,'isin_',0,5)==0)
      {
	   $ar_isins[] = substr($col,5);
	   continue;
	   }
	     
  if ($val=='')
      continue;
	  
  if ($col=='delsecs'){
      $delsecs=$val;
	  continue;
	  }
/*  if ($col=='showgraph'){
  	  $showgraph=$val;
	  continue;
	  }
  */
  if ($col=='id'){
  	  $sesid=$val;
  	  continue;
	  }
    		
  if ($cols!='')
      $cols.=',';
  $cols .= $col . " = ". ($val == "on" ?1 : "\"". $val . "\""); 
  }

 if (count($ar_isins))
 {
  $q="SELECT *"
	.	" FROM imk_imarket_cfg"
	.	" WHERE  id = ".$sesid;
 
	$dp->setQuery($q);
	$dp->loadObject($row);
	
	$secs= $row->securities;
	$ar=explode(",",$secs);

	$valid_secs="";
	
	foreach($ar as $sec)
	{
	 if (in_array($sec,$ar_isins)==true)
	 {
	     if ($valid_secs!="")
     	 	 $valid_secs.=",";
		 $valid_secs.=$sec;
		 }
	}

	if ($delsecs=='del')
	{
	 if ($valid_secs!="")
	 {
	  $q="UPDATE imk_imarket_cfg"
	  	.	" SET securities=\"".$valid_secs."\""
		.	" WHERE  id = ".$sesid;
	  $dp->setQuery($q);
	  $dp->query();
	  if ($dp->getAffectedRows()==-1)
    	  $obj->write($socket_id, $channel_id, "error=$q;");
	  
	  $q = "UPDATE imk_imarket_cfg SET init=4 WHERE id = $sesid";

 	  $dp->setQuery($q);
 	  $dp->query();
 
 	  if ($dp->getAffectedRows()==-1)
     	  $obj->write($socket_id, $channel_id, "error=$q;");
	  }
	}
	
	if ($delsecs=='upd')
	{ 
     $str_secs="";

	foreach($ar_isins as $sec)
	{
	 if ($str_secs!="")
     	$str_secs.=",";
	 $str_secs.="'".$sec."'";
 	}
	
    $q = "DELETE FROM imk_MF WHERE ISIN_CODE IN ($str_secs)";
    $dp->setQuery($q);
    $dp->query();
    if ($dp->getAffectedRows()==-1)
        $obj->write($socket_id, $channel_id, "error=$q;");
   }
  }
    
 $q = "UPDATE imk_SES SET " . $cols .",showgraph=$showgraph". " WHERE id = $sesid";

 $dp->setQuery($q);
 $dp->query();
 
 $obj->write($socket_id, $channel_id, ($dp->getAffectedRows()==-1 ?"err=$q;" :"\n") );
 }
 
function makeSecsList($sesid)
{
 $dp=$this->dp;
 
 $q="SELECT *"
	.	" FROM imk_imarket_cfg"
	.	" WHERE  id = ".$sesid;
 
	$dp->setQuery($q);
	$dp->loadObject($row);
	
	$secs= $row->securities;
	$ar=explode(",",$secs);

	$str_secs="";

	foreach($ar as $sec)
	{
	 if ($str_secs!="")
     	$str_secs.=",";
	 $str_secs.="'".$sec."'";
 	}
 
	$i=0;
	$q="SELECT ISIN_CODE,SECURITY_HELLENIC_SYMBOL"
		.	" FROM imk_IB"
		.	" WHERE ISIN_CODE in (".$str_secs.")";
//	print $q;
	$dp->setQuery($q);
	$rows = $dp->loadObjectlist();
		
	$items = ""; //"<complete>";	
	$i=0;
	
	foreach($rows as $row) {
	    $checked="";
//		$items = $items . '<option value="'.$row->ISIN_CODE .'"' .'>'.$row->ISIN_CODE ." - ".$row->SECURITY_HELLENIC_SYMBOL.'</option>';
		$items = $items .  '<row id="'."isin_".$row->ISIN_CODE.'"'.'><cell>'.$row->SECURITY_HELLENIC_SYMBOL.'</cell><cell type="ch">'.$row->SECURITY_HELLENIC_SYMBOL.'</cell></row>';
		}
 return $items;
 }
 
/** 
* getConfig  Edit configuration parameters 	
*/ 
 function config($obj, $socket_id, $channel_id, $_DATA)
 {
  $sesid = $_DATA['ses'];
	
	$q="SELECT *"
	.	" FROM imk_imarket_cfg"
	.	" WHERE  id = ".$sesid;
 
	$this->dp->setQuery($q);
	$this->dp->loadObject($row);
	
	$fdate = $row->fdate;
	$state = $row->state;

	$sessions = $this->getsessions($sesid);
	$index_script=$this->getIndexes($sesid);
	$mkt_script = $this->getMarketsList2($sesid);
	$board_script= $this->getBoardsList2($sesid);
	$sec_script = $this->getSecuritiesList2($sesid);
/*
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
		header("Content-type: application/xhtml+xml"); 
		} 
		else header("Content-type: text/xml"); 
*/			 
	$script.= '<rows><head>';
	$script.= '<column width="200" type="ro" align="left" color="#D4D0C8" sort="na">Parameter</column>';
	$script.= '<column width="*" type="co" align="left" sort="na">Values';
	$script.= '</column>';
	$script.= '</head>';

	$script.= '<row id="sessions"><cell>Sessions</cell><cell xmlcontent="1" type="combo" editable="0">'.$row->id.$sessions.'</cell></row>';
	$script.= '<row id="comments_config"><cell> </cell><cell type="span" editable="0">sessions</cell></row>';
	$script.= '<row id="state"><cell style="background-color:#D4D0C8;">Source (DB/File)</cell><cell type="ch">'.$row->state.'</cell></row>';
	$script.= '<row id="dbase"><cell style="background-color:#D4D0C8;">Database</cell><cell type="ed">'.$row->dbase.'</cell></row>';
	$script.= '<row id="filepath"><cell style="background-color:#D4D0C8;">File path</cell><cell type="ed">'.$row->filepath.'</cell></row>';
	$script.= '<row id="fdate"><cell>Date</cell><cell type="dhxCalendarA">'.$row->fdate.'</cell></row>';
	$script.= '<row id="market"><cell>Market</cell><cell xmlcontent="1" source="test1.php?task=MarketsList2" auto="true" cache="false" type="combo" text="'.$row->market.'">'.$row->market.$mkt_script .'</cell></row>';
	$script.= '<row id="board"><cell>Board</cell><cell xmlcontent="1" type="combo" editable="0">'.$row->board.$board_script .'</cell></row>'; //source="test1.php?task=BoardsList2" auto="true" cache="false" type="combo" text="'.$row->board.'
	$script.= '<row id="indexes"><cell style="background-color:#D4D0C8;">Indexes</cell><cell xmlcontent="1" type="combo" editable="0">'.$row->indexes.$index_script.'</cell></row>';
	$script.= '<row id="securities"><cell>Securities</cell><cell xmlcontent="1" type="combo" editable="0">'.$row->securities.$sec_script.'</cell></row>'; //source="test1.php?task=SymbolsList2" auto="true" cache="false" type="combo" text="'.$row->securities
	$script.= '<row id="vieworders"><cell>Show Orders?</cell><cell type="ch">'.$row->vieworders.'</cell></row>';
	$script.= '<row id="init"><cell>Init data?</cell><cell type="ch">'.$row->init.'</cell></row>';
//$script.= '<row id="9"><cell></cell><cell type="cp">red</cell></row>';
	$script.= '</rows>';

  $obj->write($socket_id, $channel_id, $script);
  }

/** 
* updateMarketOptions  Edit configuration parameters 	
*/   
 function updateMarketOptions($obj, $socket_id, $channel_id, $_DATA)
 {
 $dp=$this->dp;
 $ses = 0;
 
 $cols="";
 foreach($_DATA as $col=>$val)
 {
  if ($col=='sessions')
      {
	   $ses = $val;
	   continue;
	   }
	  
  if ($cols!="")
      $cols.=',';
  $cols .= $col . " = ". ($val == "on" ?1 : "\"". $val . "\""); 
  }
 if (!isset($_DATA['init']))
     $cols .= ($cols!="" ?",":""). ' init=0';
 if (!isset($_DATA['state']))
     $cols .= ($cols!="" ?",":"").' state=0';
 if (!isset($_DATA['vieworders']))
     $cols .= ($cols!="" ?",":"").' vieworders=0';
 	 
 $ses = ($ses==0) ?1 :$ses;
 $q = "UPDATE imk_imarket_cfg SET " . $cols . " WHERE id = $ses";
 
 $dp->setQuery($q);
 $dp->query();
 
 $obj->write($socket_id, $channel_id, ($dp->getAffectedRows()==-1 ?"error=$q;":"\n"));
 }

/**
* get a List of free sessions
* (function called internally)
*/ 
 function getsessions($sesid)
 {
	$q="SELECT id,title"
	.	" FROM imk_mplay.imk_SES"
	.	" WHERE  status = 'C' OR id=".$sesid;
 
	$this->dp->setQuery($q);
	$rows=$this->dp->loadObjectList();
	
	if(count($rows)==0) {
		return;
	}
	
	foreach($rows as $row) 
	  {	
	   $items = $items . '<option  value="'.$row->id.'"'. ($sesid==$row->id ?' selected=" SELECTED"' :''). '>'.$row->title.'</option>';
		}

 return $items;
 }

/**	getMarketsList
* 	getMarketsList
*/
function getMarketsList2 ($ses, $mkt="M"){
$items='';

$mkt_boards=array("M"=>"Main", "S"=>"Special", "B"=>"RptOnly", "O"=>"Odd", "F"=>"ForcedSale");
$mkt_exchnges=array("G"=>"???µat?st???? ??????","C"=>"???µat?st???? ??p???");

	$q="SELECT MARKET_ID,HELLENIC_MARKET_NAME,BOARD_ID,EXCHANGE_ID"
 			.	" FROM imk_IA" ;

	$this->dp->setQuery($q);
	$rows = $this->dp->loadObjectlist();
	if ($this->dp->getErrorNum()){
    	echo $this->dp->stderr();
		return;
		}
	
	
	$items = ""; //"<complete>";	
	$i=0;
    if (count($rows) == 0 || $rows=='' || !$rows)
	    $items =  '<option value="'.'M'.'" selected="SELECTED">'.'M'." - ".$mkt_boards['M'].'</option>';
	   else
		foreach($rows as $row) 
	  	{	
	   		$items = $items . '<option  value="'.$row->MARKET_ID.'"'. ($mkt==$row->MARKET_ID ?' selected=" SELECTED"' :"").'>'.$row->MARKET_ID." - ".$row->HELLENIC_MARKET_NAME.'</option>';
		}

return $items;
}


/**
* 	 getBoardsList
*/
function getBoardsList2($ses)
{
	$q="SELECT market"
	.	" FROM imk_imarket_cfg"
	.	" WHERE id = $ses";
 
	$this->dp->setQuery($q);
	$this->dp->loadObject($row);

$mkt = ($row ?$row->market :'');

$mkt_boards=array("M"=>"Main", "S"=>"Special", "B"=>"RptOnly", "O"=>"Odd", "F"=>"ForcedSale");

	$q="SELECT DISTINCT BOARD_ID"
	.	" FROM imk_IF"
	.	" WHERE MARKET_ID = '$mkt'";

	$this->dp->setQuery($q);
	$rows = $this->dp->loadObjectlist();
	
	$items = ""; //"<complete>";	
	$i=0;
	
	if (count($rows) == 0 || $rows=='' || !$rows)
	    $items =  '<option value="'.'M'.'" selected="SELECTED">'.'M'." - ".$mkt_boards['M'].'</option>';
	   else
	    foreach($rows as $row) {	
			$board=$mkt_boards[$row->BOARD_ID];
			$items = $items . '<option value="'.$row->BOARD_ID.'">'.$row->BOARD_ID." - ".$board.'</option>';
//		$items = $items . '<cell>'.$row->BOARD_ID.'^'.$board.'</cell>';
			}
	
return $items;
}

/**
* 	 getIndexes  SELECTED to Play
*/
function getIndexes($ses){

$mkt_boards=array("M"=>"Main", "S"=>"Special", "B"=>"RptOnly", "O"=>"Odd", "F"=>"ForcedSale");

$q="SELECT indexes"
	.	" FROM imk_imarket_cfg"
	.	" WHERE id = $ses";
 
	$this->dp->setQuery($q);
	$this->dp->loadObject($row);

	$idx = $row->indexes;
	
			
	$q="SELECT HELLENIC_INDEX_SYMBOL" 
	.	" FROM imk_IC";
 
	$this->dp->setQuery($q);
	$rows = $this->dp->loadObjectlist();
	
	if(count($rows)==0) {
		return;
	}
	
	$items = "";	
	$i=0;
	
	foreach($rows as $row) {	
	  		
		$items = $items . "<option value=\"".$row->HELLENIC_INDEX_SYMBOL."\"".($idx==$row->HELLENIC_INDEX_SYMBOL ?' selected=" SELECTED"' :"").">".$row->HELLENIC_INDEX_SYMBOL."</option>";
		}
	
return $items;
}

/**
* 	 getSecuritiesList  SELECTED to Play
*/
 function getSecuritiesList2($ses)
 {
	$q="SELECT market, securities"
	.	" FROM imk_imarket_cfg"
	.	" WHERE id = $ses";
 
	$this->dp->setQuery($q);
	$this->dp->loadObject($row);

 $mkt = $row->market;
 $secs= $row->securities;
 $ar=explode(",",$secs);

	$i=0;
	$q="SELECT ISIN_CODE,SECURITY_HELLENIC_SYMBOL"
		.	" FROM imk_IB"
		.	" WHERE MARKET_ID = '$mkt'";
	
	$this->dp->setQuery($q);
	$rows = $this->dp->loadObjectlist();
		
	$items = ""; //"<complete>";	
	$i=0;
	
	foreach($rows as $row) {
	    $checked="";
		for ($i=0; $i<count($ar); $i++)
		     if ($row->ISIN_CODE==$ar[$i])
			      $checked=" selected=\"CHECKED\" ";
				   		
		$items = $items . '<option value="'.$row->ISIN_CODE .'"' . $checked.'>'.$row->ISIN_CODE ." - ".$row->SECURITY_HELLENIC_SYMBOL.'</option>';
		}
		
 return $items;
 }

 }
?>
