<?php
include("class.clientsocket.php");

$trader='';
$format = $_GET['format'];
$newRequest = 0;
	
	if ($format == 'JSON')
	{
		if(strlen($_GET['results']) > 0) {
    		$results = $_GET['results'];
			}
			else
			  $results=0;

		if(strlen($_GET['task']) > 0) {
			$task = $_GET['task'];
			$date = $_GET['date'];
			$value = $_GET['value'];
			}
		else
			$task='';
		
		if(strlen($_GET['newRequest']) > 0) 
			$newRequest = $_GET['newRequest'];
		else
			$newRequest=0;
			
		if(strlen($_GET['filter']) > 0) {
			$filter = $_GET['filter'];
			}
		else
			$filter='';
				
		// Start at which record?
		if(strlen($_GET['startIndex']) > 0) {
			$startIndex = $_GET['startIndex'];
			}
		else
			$startIndex=0;	

		// Sorted?
		if(strlen($_GET['sort']) > 0) {
		    $sort = $_GET['sort'];
			}
		else
		    $sort="";	

		// Sort dir?
		if((strlen($_GET['dir']) > 0) && ($_GET['dir'] == 'desc')) {
		    $dir = 'desc';
		    $sort_dir = SORT_DESC;
			}
		else {
		    $dir = 'asc';
		    $sort_dir = SORT_ASC;
			}
		
		if(strlen($_GET['phase']) > 0) {
    		$phase = $_GET['phase'];
			if (!strcmp($phase,'All'))
				$phase='';
			}
			else
			  $phase='';
		
		$exec_js = $_REQUEST['exec'];
		
			
		$posStart=$startIndex;
		$count=$results;
	 }
	else { 
   
    //define variables from incoming values
    if(isset($_GET["posStart"]))
        $posStart = $_GET['posStart'];
    else
        $posStart = 0;
    if(isset($_GET["count"]))
        $count = $_GET['count'];
    else
        $count = 100;
	}

$ses = $_GET['session'];
	
$host="127.0.0.1" ;
$port=12345;
$timeout=30;

$ans='error';
if ($format != 'JSON')
{
 header("Content-type:text/xml");
 print('<?xml version="1.0" encoding="UTF-8"?>');
 }


$socket = new ClientSocket($host, $port);
if ($socket) {
	$socket->msConnectSocket();
	if ($socket->connected) {
	    if ($task=='selectOraSec'){
			$data = implode(':', array($trader,$value));
			$nw=socket_write($socket->_SOCK, "select_OraSec,".$data."_".$ses);
			}
		  else {	
			//str_replace(",","-",$filter);
			
			$data = implode(':', array($format,$posStart,$count,$results,$sort,$dir,$newRequest,$filter));
			
/*
* string $filter contain JSON stringnified JS object with query paramters
*   preg_split with this expression "/[\s,]*\{([^\_]+)\}[\s,]*|[\s,]+/",
*   in service3 will eat the braces {}, so we have add them again! 
*/			
			$nw=socket_write($socket->_SOCK, "get_orasecs,".$data."_".$ses);
			}
		$nr=socket_recv($socket->_SOCK,$ans,20000,0);

		//printHeader();
		//header("Content-type:text/xml");
		
		print($ans);
		if ($exec_js)
			print $exec_js;
		socket_close($socket->_SOCK);	
		}
	 else
		print "proxy_error='".$socket->errstr."';";
	}
	


function printHeader()
{

if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
		header("Content-type: application/xhtml+xml"); 
		} 
	   else { header("Content-type: text/xml"); 
			} 
			
     return '<?xml version="1.0" encoding="UTF-8"?>';
}	
?>