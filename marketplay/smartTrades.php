<?php
include("class.clientsocket.php");

   
    //define variables from incoming values
	$format = $_GET['format'];
	
	if ($format == 'JSON')
	{
		if(strlen($_GET['results']) > 0) {
    		$results = $_GET['results'];
			}
			else
			  $results=100;

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
		
		if(strlen($_GET['buyer']) > 0) {
    		$buyer = $_GET['buyer'];
			}
			else
			  $buyer='';
		
		if(strlen($_GET['seller']) > 0) {
    		$seller = $_GET['seller'];
			}
			else
			  $seller='';
			  	
		$posStart=$startIndex;
		$count=$results;
	 }
	else { 
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

header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
if ($format != 'JSON')
{
 header("Content-type:text/xml");
 print('<?xml version="1.0" encoding="UTF-8"?>');
 }
 else
   header('Content-type: application/json; charset=utf-8');


$socket = new ClientSocket($host, $port);
if ($socket) {
	$socket->msConnectSocket();
	if ($socket->connected) {
		$data = implode(':', array('IS',$format,$posStart,$count,$phase,$buyer,$seller));
		$nw=socket_write($socket->_SOCK, "get_currentTrades,".$data."_".$ses);
		$nr=socket_recv($socket->_SOCK,$ans,20000,0);
		//if ($format!='JSON')
			//printHeader();
		//header("Content-type:text/xml");
		
		print($ans);
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