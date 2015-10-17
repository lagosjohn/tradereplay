<?php
/*

* ObjectClarity TimeWriter Component
* @package TimeWriter
* @copyright (C) 2007 ObjectClarity / All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.objectclarity.com
*
* Based on the Mambotastic Timesheets Component
* @copyright (C) 2005 Mark Stewart / All Rights Reserved
* @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @author Mark Stewart / Mambotastic
*
*/


//defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );


class dpConfig extends dp_mosDBTable {
	var $id = null;
	var $dbase = null;
	var $filepath = null;
	var $state = null;
	var $fdate = null;
	var $market = null;
  	var $securities = null;
  	var $indexes = null;
  	var $board = null;
		
	function dpConfig( &$db ) {
		$this->mosDBTable( 'imk_imarket_cfg', 'id', $db );
	}
}

class dpMsgs extends dp_mosDBTable {
	var $mid=null;
  	var $id=0;
  	var $type=0;
  	var $name=0;
  	var $length=0;	
	
	function dpMsgs( &$db ) {
		$this->mosDBTable( 'imk_msgs', 'id', $db );
	}
}

class dpDelMsg extends dp_mosDBTable {
	var $mid=null;
  	var $id=0;
  	var $type=0;
  	var $name=0;
  	var $length=0;	
	
	function dpDelMsg( &$db ) {
		$this->mosDBTable( 'imk_msgs', 'mid', $db );
	}
}
  
class dpMsgFields extends dp_mosDBTable {
	var $id=0;
	var $msg_id=0;
  	var $field=null;
  	var $decimals=0;
  	var $offset=0;
  	var $header=0;	
  	var $length=0;		
	
	function dpMsgFields( &$db ) {
		$this->mosDBTable( 'imk_msg_fields', 'id', $db );
	}
}