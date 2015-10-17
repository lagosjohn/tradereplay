<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>PlayThread Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxlayout.css"> 
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxlayout_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxaccordion_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxwindows.css"> 
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_dhx_blue.css"> 			
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_aqua_orange.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_aqua_sky.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_standard.css" >
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_modern_red.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxwindows_clear_silver.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxtoolbar_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxeditor_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxtoolbar_standard.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxtabbar.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxcombo.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxeditor.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxgrid_dhx_black.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxgrid.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxgrid_hmenu.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxmenu_standard.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxmenu_clear_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="codebase/marketplay.css">

<script>
    var gridQString = "";//we'll save here the last url with query string we used for loading grid (see step 5 for details)
    //we'll use this script block for functions
</script>

<link rel="stylesheet" type="text/css" href="codebase/dhtmlxslider.css">
<link rel="stylesheet" type="text/css" href="codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="codebase/skins/dhtmlxcalendar_skins.css">

<meta http-equiv="Cache-Control" content="no-cache" />
<script type="text/javascript" src="http://comet-imarket2.helex.gr/meteor.js?v=5"></script>

<script>window.dhx_globalImgPath="codebase/imgs/toolbar/";</script>
<script src="codebase/dhtmlxcommon.js"></script>
<script src="codebase/dhtmlxlayout.js"></script>
<script src="codebase/dhtmlxaccordion.js"></script>
<script src="codebase/dhtmlxwindows.js"></script>
<script  src="codebase/ext/dhtmlxwindows_wtb.js"></script>
<script  src="codebase/ext/dhtmlxwindows_sb.js"></script>
<script  src="codebase/ext/dhtmlxwindows_wmn.js"></script>
<script  src="codebase/dhtmlXProtobar.js"></script>
<script  src="codebase/dhtmlxtoolbar.js"></script>
<script  src="codebase/dhtmlxtabbar.js"></script>
<script  src="codebase/dhtmlxtabbar_start.js"></script>
<script  src="codebase/ext/dhtmlxtabbar_wins.js"></script>
<script  src="codebase/dhtmlxcombo.js"></script>
<script  src="codebase/dhtmlxmenu.js"></script>
<script  src="codebase/dhtmlxgrid.js"></script>	
<script  type="text/javascript" src="js/graphs/swfobject.js"></script>		
<script  src="codebase/dhtmlxgridcell.js"></script>
<script  src="codebase/ext/dhtmlxgrid_srnd.js"></script>
<script  src="codebase/ext/dhtmlxgrid_selection.js"></script>
<script  src="codebase/ext/dhtmlxgrid_splt.js"></script>
<script  src="codebase/excells/dhtmlxgrid_excell_sub_row.js"></script>

<script  src="js/json_sans_eval.js"></script>

<script  src="codebase/ext/dhtmlxgrid_hmenu.js"></script>
<script  src="codebase/ext/dhtmlxgrid_fast.js"></script>
<script  src="codebase/ext/dhtmlxgrid_json.js"></script>
<script  src="codebase/excells/dhtmlxgrid_excell_combo.js"></script>
<script  src="codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script  src="codebase/ext/dhtmlxgrid_filter.js"></script>
<script  src="codebase/dhtmlxdataprocessor.js"></script>
<script  src="codebase/ext/dhtmlxgrid_markers.js"></script>
<script src="codebase/dhtmlxslider.js"></script>
<script src="codebase/dhtmlxcalendar.js"></script>
<script src="codebase/dhtmlxeditor.js"></script>
<script src="codebase/excells/dhtmlxgrid_excell_dhxcalendar.js"></script>	

<!--CSS file (default YUI Sam Skin) -->
<link type="text/css" rel="stylesheet" href="yui/build/datatable/assets/skins/sam/datatable.css">

<script type="text/javascript" src="yui/build/yahoo/yahoo.js"></script>
<!-- Dependencies -->
<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="yui/build/element/element-min.js"></script>
<script type="text/javascript" src="yui/build/datasource/datasource-min.js"></script>

<!-- OPTIONAL: JSON Utility (for DataSource) -->
<script type="text/javascript" src="yui/build/json/json-min.js"></script>

<!-- OPTIONAL: Connection Manager (enables XHR for DataSource) -->
<script type="text/javascript" src="yui/build/connection/connection-min.js"></script>

<!-- OPTIONAL: Get Utility (enables dynamic script nodes for DataSource) -->
<script type="text/javascript" src="yui/build/get/get-min.js"></script>

<!-- OPTIONAL: Drag Drop (enables resizeable or reorderable columns) -->
<script type="text/javascript" src="yui/build/dragdrop/dragdrop-min.js"></script>

<!-- OPTIONAL: Calendar (enables calendar editors) -->
<script type="text/javascript" src="yui/build/calendar/calendar-min.js"></script>

<script type="text/javascript" src="yui/build/datatable/datatable-min.js"></script>

<!--script type="text/javascript" src="yui/build/my_yui/yui_expand.js"></script> -->



<!--[if IE]>
<style type="text/css">
/* IE Specific Style addition to constrain table from automatically growing in height */
div.TableContainer {
 height: 121px; 
 overflow-x:hidden;
 overflow-y:auto;
}
</style>
<![endif]-->

<script>

// Function to scroll to top before sorting to fix an IE bug
// Which repositions the header off the top of the screen
// if you try to sort while scrolled to bottom.
function GoTop() {
 document.getElementById('TableContainer').scrollTop = 0;
}

// For those browsers that fully support the CSS :hover pseudo class the "table.scrollTable tr:hover" definition above 
// will work fine, but Internet Explorer 6 only supports "hover" for "<a>" tag elements, so we need to use the following 
// JavaScript to mimic support (at least until IE7 comes out, which does support "hover" for all elements)

// Create a JavaScript function that dynamically assigns mouseover and mouseout events to all 
// rows in a table which is assigned the "scrollTable" class name,  in order to simulate a "hover" 
// effect on each of the tables rows
HoverRow = function() {

 // If an IE browser
 if (document.all) {
  var table_rows = 0;
	
  // Find the table that uses the "scrollTable" classname
  var allTableTags=document.getElementsByTagName("table"); 
  for (i=0; i<allTableTags.length; i++) { 
   // If this table uses the "scrollTable" class then get a reference to its body and first row
   if (allTableTags[i].className=="scrollTable") { 
    table_body = allTableTags[i].getElementsByTagName("tbody");
    table_rows = table_body[0].getElementsByTagName("tr");
    i = allTableTags.length + 1; // Force an exit from the loop - only interested in first table match
   } 
  } 

  // For each row add a onmouseover and onmouseout event that adds, then remove the "hoverMe" class
  // (but leaving untouched all other class assignments) to the row in order to simulate a "hover"
  // effect on the entire row
  for (var i=0; i<table_rows.length; i++) {
   // ignore rows with the title and total class assigned to them
   if (table_rows[i].className != "title" && table_rows[i].className != "total") {
    table_rows[i].onmouseover=function() {this.className += " hoverMe";}
    table_rows[i].onmouseout=function() {this.className=this.className.replace(new RegExp(" hoverMe\\b"), "");}
   }
  } // End of for loop
  
 } // End of "If an IE browser"

}
// If this browser suports attaching events (IE) then make the HoverRow function run on page load
// Hote: HoverRow has to be re-run each time the table gets sorted
//if (window.attachEvent) window.attachEvent("onload", HoverRow);
</script>
<style>
/** 
*
* Style the yui-dt-expandablerow-trigger column 
*
**/
.yui-dt-expandablerow-trigger{
    width:18px;
    height:22px;
    cursor:pointer;
}
.yui-dt-expanded .yui-dt-expandablerow-trigger{
    background:url(arrow_open.png) 4px 4px no-repeat;
}
.yui-dt-expandablerow-trigger, .yui-dt-collapsed .yui-dt-expandablerow-trigger{
    background:url(arrow_closed.png) 4px 4px no-repeat;
}
.yui-dt-expanded .yui-dt-expandablerow-trigger.spinner{
    background:url(spinner.gif) 1px 4px no-repeat;
}

/** 
*
* Style the expansion row
*
**/
.yui-dt-expansion .yui-dt-liner{
    padding:0;
    border:solid 0 #bbb;
    border-width: 0 0 2px 0;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table{
    border:none;
    background-color:#fff;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table th{
    background-image:none;
    background-color:#eee;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table td{
    border:solid 0 #eee;
    border-width: 0 0 1px 1px;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table td div{
    padding:3px;
    overflow:hidden;
    width:100px;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table td.big div{
    width:300px;
}
.yui-dt-expansion .yui-dt-liner th, .yui-dt-expansion .yui-dt-liner table td ul{ padding:0;margin:0; }
</style>
</head>



<body class="yui-skin-sam">
<?php 
 if (isset($_SESSION["MARKETPLAY"])) 
	$_SESSION["MARKETPLAY"]+=1;
	else
	  $_SESSION["MARKETPLAY"]=1;  
?>
<div id="flashcontent"><strong>Market Graph chart area</strong></div>
<div id="trades_flashcontent"><strong>Trading Graph chart area</strong></div>
<div id="bidask_flashcontent"><strong>Bid/Ask Graph chart area</strong></div>
<div id="projected_flashcontent"><strong>Auction projected Graph chart area</strong></div>

<div id="progressBarContainer" left="20px" top="200px" width="304px" height="200px"></div>
<div id="ordersId" style="width:40%;height:40%;overflow:auto;"></div>
<div id="tabfic" style="width:100%;height:150px;overflow:hidden;"></div>
<div id="tabsup" style="width:100%;height:150px;overflow:hidden;"></div>
<div id="tab_lasttrades" style="width:100%;height:255px;overflow:hidden;"></div>
<div id="div_events" style="width:100%;height:255px;overflow:hidden;"></div>
<div id="tab_lastorders" class="yui-dt" style="width:100%;height:255px;overflow:hidden;"></div>
<div id="tab_mktdepth" style="height:600px;overflow:auto;">
<div id="tab_mktdepthB" style="float:left;width:270px;height:280px;overflow:auto;"></div>
<div id="tab_mktdepthS" style="float:left;width:270px;height:280px;overflow:auto;"></div>
<div id="mdb_flashcontent" style="float:left;width:270px;height:260px;overflow:hidden;"><strong>Bid Depth</strong></div>
<div id="mda_flashcontent" style="float:left;width:270px;height:260px;overflow:hidden;"><strong>Offer Depth</strong></div>
</div>
<div id="tab_graphdepth" style="height:260px;overflow:hidden;">
<div id="mdb_flashcontent1" style="float:left;width:260px;height:260px;overflow:hidden;"><strong>Bid Depth</strong></div>
<div id="mda_flashcontent1" style="float:left;width:260px;height:260px;overflow:hidden;"><strong>Offer Depth</strong></div>
</div>
<div id="trade_tabs"></div>

<div id="SecInfo" class="hide">
<FORM name="SecInfoForm" id="SecInfoForm">
<TABLE border=0 class="secform_tablestyle" width=410>
<TR >
<TD>Source:</TD>
<TD><input type="text" size="15" name="source" id="source" value="" readonly /></TD></TR>
<TR >
<TD>Market:</TD>
<TD><input type="text" size="45" name="market" id="market" value="" readonly /></TD></TR>
<TR >
<TD>Board:</TD>
<TD><input type="text" size="15" name="board" id="board" value="" readonly /></TD></TR>
<TR >
<TD>Security Symbol:</TD>
<TD><input type="text" size="15" name="symbol" id="symbol" value="" readonly />
&nbsp;&nbsp;
<select name="isin" id="isin"></select>

<!--input type="text" size="15" name="isin" id="isin" value="" readonly /!--></TD></TR>
<TR >
<TD><input type="text" size="8" name="otime" class="otime" id="otime" value="00:00:00:00" readonly /><br /><input type="text" size="8" name="ttime" class="ttime" id="ttime" value="00:00:00:00" readonly /></TD>
<TD><div id="slider" class="slider"></div></TD></TR>
<TR >
<TD>Price:</TD>
<TD><input type="text" size="10" name="price" id="price" value="" readonly />
&nbsp;%
<input type="text" size="4" name="pct_var" id="pct_var" value="" readonly />
&nbsp;
<img src="../images/stable.gif" border="0" NAME="index_img" id="index_img" />
&nbsp;
Lo:
<input type="text" size="3" name="lo" CLASS="lo" id="low" value="" readonly />
&nbsp;&nbsp;Hi:
<input type="text" size="3" name="hi" CLASS="hi" id="high" value="" readonly /></TD>
</TR>
<TR >
<TD>Auction Price:</TD>
<TD><input type="text" size="10" name="oprice" id="oprice" value="" readonly />
&nbsp;&nbsp;
<button type='button' id='submit' name='submit' onclick='showOpenPrice()'>Method</button>
  
<input type="text" size="2" name="ostop" id="ostop" value="" readonly />
<img src="../images/next_order.gif" border="0" align="absbottom" NAME="next_img" />
<input type="text" size="2" name="next_order" CLASS="next_order" id="next_order" value="" readonly /></TD>
</TR>

<TR >
<TD>Last Trade:</TD>
<TD><input type="text" size="5" name="last" id="last" value="" readonly />
&nbsp;Trade:
<input type="text" size="5" name="trade"  id="trade" value="" readonly />
&nbsp;&nbsp;Buy:
<input type="text" size="5" name="buy"  id="buy" value="" readonly />
&nbsp;Sell:
<input type="text" size="5" name="sell"  id="sell" value="" readonly />
</TD>
</TR>

</TABLE>
</FORM>

</div>

<div id="customPeriod" class="hide">
<TABLE border=0 class="secform_tablestyle" width=410>
<TR >
<TD>Start Time:</TD>
<TD><input type="text" size="20" name="starttime" id="st" value="00:00:00:00" /></TD></TR>
<TR >
<TD>End Time:</TD>
<TD><input type="text" size="20" name="endtime" id="et" value="00:00:00:00" /></TD></TR>
<TR >
<TD width="50px">Set Start:</TD>
<TD><div id="slider_start" class="slider_hr"></div></TD></TR>
<TR >
<TD width="50px">Set Stop:</TD>
<TD><div id="slider_stop" class="slider_hr"></div></TD></TR>
<TR >
<TD>&nbsp;&nbsp;</TD>
<TD colspan="3">
&nbsp;&nbsp;
<button type='button' id='cperiod' name='customperiod' onclick='startCustomPeriod()'>Run period</button></TD>
</TR>
<TR >
<TD>
<TD><div id="editorObj" style="width: 420px; background-color:#FFFFFF; height: 220px; border: #999999 1px solid;"></div></TD>
</TABLE>
</div>

<script>
/* ============ GLOBAL VARS ============= */
ses_str='#'+<?php echo '"'.$_SESSION["MARKETPLAY"].'"'?>;
ses=<?php echo '"'.$_SESSION["MARKETPLAY"].'"'?>;
custom_content="<h3>Επιλογή Χρονικού Κλάσματος.</h3><p> ΜΕ την χρήση των <em>Sliders</em> επιλέγουμε την χρονική περίοδο συνεδρίασης.<br/>Με τον πρώτο Slider επιλέγετε την χρονική στιγμή έναρξης, και με τον δεύτερο Slider, την λήξη της περιόδου.</p>";
error=0;
ans='';
CRMback='<div id="CRMback" style="width:300px;height:350px;overflow:auto;">test</div>';
dhxPlayWins='';
OrdersWin='';
dhxPlayLayout='';
dhxPlayLayoutA='';
dhxPlayLayoutB='';
dhxPlayLayoutC='';
dhxAccord='';
mygrid_orders='';
mygrid_trades=-1;
TradesWin='';
OrdersWin='';
wingrid_trades='';
wingrid_orders='';
mygrid_events='';
mygrid_resol='';
dhxTabbarA='';
tab_trade='';
winCustomPeriod='';
winNotes='';
TradesGraphWin='';
BuySellGraphWin='';
tradeWins=[];
tabs=[];
tabgrids=[];
wintabsCntr=[];
trades_tabs_id=0;
myDataProcessor='';
mygrid_a='';
mygrid_b='';
mygrid_bob='';
mygrid_sob='';
mygrid_mdb='';
mygrid_mds='';
webBar='';
webBar_a='';
webBar_c='';
mkt_slider='';
start_slider='';
stop_slider='';
so='';  // Flash Player object
trades_so='';
bidask_so='';
mdb_so='';
mds_so='';
projected_so='';
flashMovie=''; // this will create flashMovie in global scope
flashTrades='';
flashMdb='';
flashMda='';
flashBidAsk='';
flashProjected='';
first_orders=0;

// Grid Securities
ordersIds='';
ordersDef='';
ColHeader = '';
fields = '';
ColSize='';
ColAlign='';
ColSort='';
ColTypes='';

ordersDataSource='';

test1="test1.php";
sg_signal="signal.php";
rbdata="rbdata.php";
smartTrades="smartTrades.php";
smartOrders="smartOrders.php";
tradeOrders="tradeOrders.php";
Gdispatch="Gdispatcher.php";
secsThread="secsthread.php";
dataThread="dataThread.php";
ctrlThread="ctrlThread.php";
timerThread="timerThread.php";
mktResol="mktResol.php";
images = "codebase/imgs/";
graphs= "js/graphs/";
graphdata= "graph-data.php/";
Serv1_dispatcher="Serv1dispatcher.php";

//		== CFG ==    //
vieworders=0;
fdate='';
filepath='';
mode='';
board='';
market='';
market_name='';
isin='';
hsym='';
esym='';
custom_start='';
custom_stop='';
start_price=0;
prev_price=0;
Data_events=Array("PRECALLOPEN","OPENPRICE","CONTINUOUSOPEN","CONTINUOUSCLOSE", "END");
ev_idx=0;
ev_time='';
//====================//
cmd='';
cmd_all=77;
cmd_msg='';
orders_r=[];
trades_r=[];
_IDX_TRADE=9;
stop_steps=0;

global_tradex=0;
global_current_trades=0;
global_matched_index=-1;
global_matching_flag=false;
trade_added_flag=0;
 
Continuous=false;
LocktimerFlag=0;
file_exists=false;
wait_signal='';
wait_next_Order='';
global_order_index=0;
wait_datasignal='';
wait_marketsignal=0;
wait_tradesignal=0;
wait_MktDpth=0;
trades_added_flag=0;
orders_sorted_flag=0;
delay=400000;
total_secs=0;
signal='';
sub_signal='';
Lock=0;
Data='';
nTrades=0;
xOrder=0;
nOrders=0;
xTrade=0;
prev_xTrade=0;
total_trades_val=0;
phase='';
interrupt=0;
Datasignal='';
slider2=300;
step=1;
market_steps=0;
market_fwrd=0;
market_r=null;
mXphase=0;
mXlength=1;
mXsteps=2;
mXcounter=3;
mXstatus=4;
mPcurrent='C';
mPdone='D';

// Order/Trade
cols='';
sort_cols='';
types_cols='';
cols_align='';
colIds='';
// Trade
tr_cols='';
tr_sort_cols='';
tr_types_cols='';
tr_cols_align='';
tr_colIds='';

security='';
current_order='';

opts1='';
opts2b='';
opts3b='';
opts4b='';
pos=0;
maxStep = 0;
bmd_cntr=0;
smd_cntr=0;
currTradeId=0;

script_is_busy=false;

startResol=0;
endResol=0;
selection=0;

nBuyIdx=0;
nSellIdx=0;

screenW = 640;
screenH = 480;

markerHTML = "|";
minWidth = 10;
dragingColumn = null;
startingX = 0;
currentX = 0;
		
	if (parseInt(navigator.appVersion)>3) {
 		screenW = screen.width;
 		screenH = screen.height;
		}
	else if (navigator.appName == "Netscape" 
    		&& parseInt(navigator.appVersion)==3
    		&& navigator.javaEnabled())
		{
 		var jToolkit = java.awt.Toolkit.getDefaultToolkit();
 		var jScreenSize = jToolkit.getScreenSize();
 		screenW = jScreenSize.width;
 		screenH = jScreenSize.height;
		}

dhtmlxError.catchError("ALL", myErrorHandler);
function myErrorHandler(type, desc, erData){
 alert(type+"=="+desc);
return false;
}


/* This code should not be modified */

(function(){

var Dom = YAHOO.util.Dom,

    STRING_STATENAME  = 'yui_dt_state',

    CLASS_EXPANDED    = 'yui-dt-expanded',
    CLASS_COLLAPSED   = 'yui-dt-collapsed',
    CLASS_EXPANSION   = 'yui-dt-expansion',
    CLASS_LINER       = 'yui-dt-liner',

    //From YUI 3
    indexOf = function(a, val) {
        for (var i=0; i<a.length; i=i+1) {
            if (a[i] === val) {
                return i;
            }
        }

        return -1;
    };

YAHOO.lang.augmentObject(
        YAHOO.widget.DataTable.prototype , {

        ////////////////////////////////////////////////////////////////////
        //
        // Private members
        //
        ////////////////////////////////////////////////////////////////////

        /**
            * Gets state object for a specific record associated with the
            * DataTable.
            * @method _getRecordState
            * @param {Mixed} record_id Record / Row / or Index id
            * @param {String} key Key to return within the state object.
            * Default is to return all as a map
            * @return {Object} State data object
            * @type mixed
            * @private
        **/
        _getRecordState : function( record_id, key ){
            var row_data    = this.getRecord( record_id ),
                row_state   = row_data.getData( STRING_STATENAME ),
                state_data  = (row_state && key) ? row_state[ key ]:row_state;

            return state_data || {};
        },

        /**
            * Sets a value to a state object with a unique id for a record
            * which is associated with the DataTable
            * @method _setRecordState
            * @param {Mixed} record_id Record / Row / or Index id
            * @param {String} key Key to use in map
            * @param {Mixed} value Value to assign to the key
            * @return {Object} State data object
            * @type mixed
            * @private
        **/
        _setRecordState : function( record_id, key, value ){
            var row_data      = this.getRecord( record_id ).getData(),
                merged_data   = row_data[ STRING_STATENAME ] || {};

            merged_data[ key ] = value;

            this.getRecord(record_id).setData(STRING_STATENAME, merged_data);

            return merged_data;

        },

        //////////////////////////////////////////////////////////////////////
        //
        // Public methods
        //
        //////////////////////////////////////////////////////////////////////

        /**
            * Over-ridden initAttributes method from DataTable
            * @method initAttributes
            * @param {Mixed} record_id Record / Row / or Index id
            * @param {String} key Key to use in map
            * @param {Mixed} value Value to assign to the key
            * @return {Object} State data object
            * @type mixed
        **/
        initAttributes : function( oConfigs ) {

            oConfigs = oConfigs || {};

            YAHOO.widget.DataTable.superclass.initAttributes.call(this,
                oConfigs);

                /**
                * @attribute rowExpansionTemplate
                * @description Value for the rowExpansionTemplate attribute.
                * @type {Mixed}
                * @default ""
                **/
                this.setAttributeConfig("rowExpansionTemplate", {
                        value: "",
                        validator: function( template ){
                    return (
                        YAHOO.lang.isString( template ) ||
                        YAHOO.lang.isFunction( template )
                    );
                },
                method: this.initRowExpansion
                });

        },

        /**
            * Initializes row expansion on the DataTable instance
            * @method initRowExpansion
            * @param {Mixed} template a string template or function to be
            * called when Row is expanded
            * @type mixed
        **/
        initRowExpansion : function( template ){
            //Set subscribe restore method
            this.subscribe('postRenderEvent',
                this.onEventRestoreRowExpansion);

            //Setup template
            this.rowExpansionTemplate = template;

            //Set table level state
            this.a_rowExpansions = [];
        },

        /**
            * Toggles the expansion state of a row
            * @method toggleRowExpansion
            * @param {Mixed} record_id Record / Row / or Index id
            * @type mixed
        **/
        toggleRowExpansion : function( record_id ){
            var state = this._getRecordState( record_id );
            
            if( state && state.expanded ){
                this.collapseRow( record_id );
            } else {
                this.expandRow( record_id );
            }
        },

        /**
            * Sets the expansion state of a row to expanded
            * @method expandRow
            * @param {Mixed} record_id Record / Row / or Index id
            * @param {Boolean} restore will restore an exisiting state for a
            * row that has been collapsed by a non user action
            * @return {Boolean} successful
            * @type mixed
        **/
        expandRow : function( record_id, restore ){

            var state = this._getRecordState( record_id );

            if( !state.expanded || restore ){

                var row_data          = this.getRecord( record_id ),
                    row               = this.getRow( row_data ),
                    new_row           = document.createElement('tr'),
                    column_length     = this.getFirstTrEl().childNodes.length,
                    expanded_data     = row_data.getData(),
                    expanded_content  = null,
                    template          = this.rowExpansionTemplate,
                    next_sibling      = Dom.getNextSibling( row );

                //Construct expanded row body
                new_row.className = CLASS_EXPANSION;
                var new_column = document.createElement( 'td' );
                new_column.colSpan = column_length;

                new_column.innerHTML = '<div class="'+CLASS_LINER+'"></div>';
                new_row.appendChild( new_column );

                var liner_element = new_row.firstChild.firstChild;

                if( YAHOO.lang.isString( template ) ){

                    liner_element.innerHTML = YAHOO.lang.substitute( 
                        template, 
                        expanded_data
                    );

                } else if( YAHOO.lang.isFunction( template ) ) {

                    template( {
                        row_element : new_row,
                        liner_element : liner_element,
                        data : row_data, 
                        state : state 

                    } );

                } else {
                    return false;
                }

                //Insert new row
                newRow = Dom.insertAfter( new_row, row );

                if (newRow.innerHTML.length) {

                    this._setRecordState( record_id, 'expanded', true );

                    if( !restore ){
                        this.a_rowExpansions.push(
                            this.getRecord(record_id).getId()
                        );
                    }

                    Dom.removeClass( row, CLASS_COLLAPSED );
                    Dom.addClass( row, CLASS_EXPANDED );

                    //Fire custom event
                    this.fireEvent( "rowExpandEvent",
                        { record_id : row_data.getId() } );

                    return true;
                } else {
                    return false;
                } 
            }
        },

        /**
            * Sets the expansion state of a row to collapsed
            * @method collapseRow
            * @param {Mixed} record_id Record / Row / or Index id
            * @return {Boolean} successful
            * @type mixed
        **/
        collapseRow : function( record_id ){
            var row_data    = this.getRecord( record_id ),
                row         = Dom.get( row_data.getId() ),
                state       = row_data.getData( STRING_STATENAME );

            if( state && state.expanded ){
                var next_sibling = Dom.getNextSibling( row ),
                        hash_index = indexOf(this.a_rowExpansions, record_id);

                if( Dom.hasClass( next_sibling, CLASS_EXPANSION ) ) {
                    next_sibling.parentNode.removeChild( next_sibling );
                    this.a_rowExpansions.splice( hash_index, 1 );
                    this._setRecordState( record_id, 'expanded', false );

                    Dom.addClass( row, CLASS_COLLAPSED );
                    Dom.removeClass( row, CLASS_EXPANDED );

                    //Fire custom event
                    this.fireEvent("rowCollapseEvent",
                        {record_id:row_data.getId()});

                    return true;
                } else {
                    return false;
                }
            }
        },

        /**
            * Collapses all expanded rows. This should be called before any
            * action where the row expansion markup would interfere with
            * normal DataTable markup handling. This method does not remove
            * events attached during implementation. All event handlers should
            * be removed separately.
            * @method collapseAllRows
            * @type mixed
        **/
        collapseAllRows : function(){
            var rows = this.a_rowExpansions;

            for( var i = 0, l = rows.length; l > i; i++ ){
                //Always pass 0 since collapseRow
                //removes item from the a_rowExpansions array
                this.collapseRow( rows[ 0 ] );

            }

            a_rowExpansions = [];

        },

        /**
            * Restores rows which have an expanded state but no markup. This
            * is to be called to restore row expansions after the DataTable
            * renders or the collapseAllRows is called.
            * @method collapseAllRows
            * @type mixed
        **/
        restoreExpandedRows : function(){
            var expanded_rows = this.a_rowExpansions;
            
            if( !expanded_rows.length ){
                return;
            }

            if( this.a_rowExpansions.length ){
                for( var i = 0, l = expanded_rows.length; l > i; i++ ){
                    this.expandRow( expanded_rows[ i ] , true );
                }
            }
        },

        /**
            * Abstract method which restores row expansion for subscribing to
            * the DataTable postRenderEvent.
            * @method onEventRestoreRowExpansion
            * @param {Object} oArgs context of a subscribed event
            * @type mixed
        **/
        onEventRestoreRowExpansion : function( oArgs ){
            this.restoreExpandedRows();
        },

        /**
            * Abstract method which toggles row expansion for subscribing to
            * the DataTable postRenderEvent.
            * @method onEventToggleRowExpansion
            * @param {Object} oArgs context of a subscribed event
            * @type mixed
        **/
        onEventToggleRowExpansion : function( oArgs ){
            if(YAHOO.util.Dom.hasClass(oArgs.target,
                'yui-dt-expandablerow-trigger')){
                this.toggleRowExpansion( oArgs.target );
            }
        }

    }, true //This boolean needed to override members of the original object
);

})();
  
  
createPlayLayout();
// Set this to something unique to this client
var now = new Date();
Meteor.hostid = now.getTime()+""+Math.floor(Math.random()*1000000);
// Our Meteor server is on the data. subdomain
Meteor.host = "comet-imarket2.helex.gr"; //"data."+location.hostname;
// Call the test() function when data arrives
Meteor.registerEventCallback("process", test);
// Join the demo channel and get last five events, then stream
Meteor.mode = 'stream';
// Start streaming!
Meteor.joinChannel("events",1);						
									Meteor.joinChannel("orders",1);
									Meteor.joinChannel("mktbeat",1);
									Meteor.joinChannel("trades",1);
									Meteor.joinChannel("hiloPrices",1);
									Meteor.joinChannel("OBB",1);
									Meteor.joinChannel("OBS",1);
									Meteor.joinChannel("MDB",1);
									Meteor.joinChannel("MDS",1); 
//Meteor.connect();
  

 
 
function showOpen()
{
alert("showOpen");
}

function myFilter(key,val) {   
     // format price as a string   
    alert(key+"=="+val);
 }   

//alert(Meteor.port+"=="+Meteor.status);
// Handle incoming events
function test(datastr) 
{ 

if (script_is_busy==true)
	return;
 
 script_is_busy=true;	
 var channel;
 channel = Meteor.currentChannel();

// var data =eval("(" + datastr + ")");
// var data = jsonParse(datastr);

YAHOO.lang.JSON.useNativeParse = false;
if (!YAHOO.lang.JSON.isSafe(datastr))
    alert ("It is not Safe");
	
  try {   
     				var data = YAHOO.lang.JSON.parse(datastr); //'{"rows":[{"id":1,"data":[{"TIME":"08:05:28:34","PHASE":"PRECALLOPEN"}]}]}');    
					//alert(data1.toSource());
 					}   
 			catch (e) {   
    					 alert("Invalid JSON data from channel: "+channel);   
 						}   
						

 var rows=data.rows;

// alert(rows[0].data);
 if (channel=='ctrl') {
 	 //mygrid_resol.parse(data,"json");
	// dhxTabbarA.setTabActive("fic");
	}
  
  if (channel=='events') {
     var events=data.rows;
	 // [{id:1, data:[{TIME:"08:05:28:34", PHASE:"PRECALLOPEN"}]}]
	 //alert(events.data.toSource() );
//	 alert(events.toSource()+"=="+events[0].data[0].PHASE+"=="+events.length );
// 	 mygrid_events.parse(data,"json");
//	 mygrid_events.addRow(data.rows[0]); //,data.rows[0].id
	     // alert(events[0].data);
      for(var i = 0; i < events.length; i++) 
      {
	    mygrid_events.addRow(data.rows[i].data[0]);
		phase = events[i].data[0].PHASE;
		//alert(channel+"=="+events[i].data[0].PHASE);
	  	if (events[i].data[0].PHASE=='OPENPRICE') {
		 	var execCode="";
		//	alert(events.toSource()+"=="+events[0].data[0].PHASE+"=="+events.length );
			marketPause();
			signal="PAUSE_OPENPRICE";
			
			if (projected_so=='')
				createProjectedGraph();
				else
				  if (flashProjected) 
				  		flashProjected.reloadData();
			//alert(signal);
	  		//msg='task=pause_trade'+"&data=p"+ses_str;
	  		//var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	  		//eval_return(loader.xmlDoc.responseText);
			}
		else if (events[i].data[0].PHASE=='PRICE') {
		 	     document.getElementById('oprice').value = events[i].data[0].TIME;
			}
		else if (events[i].data[0].PHASE=='FWRD_PAUSED') {
			sub_signal="";
			marketSpeed();
			}
		else if (events[i].data[0].PHASE=='CUSTOM_PERIOD_START') {
			sub_signal+=":START";
			marketSlowTrade();
			}
		else if (events[i].data[0].PHASE=='CUSTOM_PERIOD_STOP') {
			sub_signal="CUSTOM_FWRD:STOP";
			marketPause();
			marketSlowTrade();
			}
		else if (events[i].data[0].PHASE=='MARKET_CLOSED') {
		 	var execCode="";
			signal="STOP";
	  		msg='task=stop_trade'+"&data=s"+ses_str;
	  		var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	  		eval_return(loader.xmlDoc.responseText);
			}
			
      }
	}
	
//  if (channel=='mktbeat') {
//        AdvanceSlider(0);
//	}
	
  else if (channel=='orders') {
  	 AdvanceSlider(0);
  	 if (vieworders) {
	    var ar_signal=sub_signal.split(":");
		var rows=data.rows;
//		alert(rows[0].data[0]);
//alert(data.toSource() );
//alert(rows.toSource() );
	    if (sub_signal=="FWRD") {
		   if ( mkt_slider.getValue() >= stop_steps-10) 
 	 	       { //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
			   mygrid_orders.addRow(data.rows);
			   }
		   if ( mkt_slider.getValue() == stop_steps)
		       sub_signal="";
		   }
		  else if (ar_signal[0]=="CUSTOM_FWRD") 
				{
				 if (ar_signal[1]=="START") 
			         //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
					 mygrid_orders.addRow(data.rows);
				 }    
		  else {
		     //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
			   mygrid_orders.addRow(data.rows[0].data[0]);  //,data.rows[0].id
			   }
//({rows:[{id:"00012510", data:[{OASIS_TIME:"08:05:28:35", PHASE_ID:" ", SIDE:"B", ORDER_NUMBER:"00012510", VOLUME:"50", PRICE:"10.7", TRADER_ID:"EX00C", ORDER_STATUS:"O", M_C_FLAG:"C"}]}]})	
		//var rows=data.rows;
		xOrder+=rows.length;
//	alert(data.toSource() +"=="+xOrder+"=="+rows[0].id);
		document.getElementById('otime').value = rows[0].data[0].OASIS_TIME;
		if (sub_signal=="FWRD") {
			document.getElementById('ostop').value = stop_steps;
			}
		document.getElementById('next_order').value = xOrder;
			
//	    nOrders=mygrid_orders.getRowsNum();
		recs = mygrid_orders.getRecordSet().getRecords();
		nOrders=recs.length;

	    if ( xOrder &&  nOrders > 10) {
		
		//recs.splice(0,5);
		//mygrid_orders.deleteRows(1,9);
	 		for( i = 0; i < nOrders-10; i++) {
			   // mygrid_orders.selectRow(i);
				mygrid_orders.deleteRow(i);
//	  			mygrid_orders.selectRow(i, false, true, false);
				//alert(i+"==="+mygrid_orders.getRowId(i)+" New:"+rows[0].id);	
				}
			
	//		mygrid_orders.deleteSelectedRows();
				
			//alert(xTrade +"=x=="+  nTrades+"==="+rows.length+"==="+mygrid_trades.getRowsNum()+"=="+mygrid_trades.getRowIndex(219));
			}
		
		}
	}

 
  else if (channel=='OBB') {
 	 	//mygrid_bob.parse(data,"json");
		orders_r.push(data.rows[0].data);
	}
	
  else if (channel=='OBS') {
 	 	//mygrid_sob.parse(data,"json");
		orders_r.push(data.rows[0].data);
	}
  
  else if (channel=='MDB') {
 	 	var rows=data.rows;
		var ar_signal=sub_signal.split(":");
		
					
		if (mdb_so=='')		
			createMDbGraph();
				
		if (rows.length==1) 
			{
	//	alert('MDB='+rows.length+"=="+rows[0].id);
			if (mygrid_mdb.doesRowExist(rows[0].id)) {
				mygrid_mdb.cells(rows[0].id,1).setValue(rows[0].data[1]);
				mygrid_mdb.cells(rows[0].id,2).setValue(rows[0].data[2]);
				}
				else {
				//alert('MDB new ='+rows[0].id);
					mygrid_mdb.parse(data,"json");
					}
			}
		else {
 	 		  if (sub_signal=="FWRD") 
			     {
		  		  if ( mkt_slider.getValue() >= stop_steps-10) 
					 {
	 					 mygrid_mdb.clearAll();
			  			 mygrid_mdb.parse(data,"json");
						 if (flashMdb)
						     flashMdb.reloadData();
					  }
	 			  }
				 else { 
				 		if (ar_signal[0]=="CUSTOM_FWRD") 
						{
				 			 if (ar_signal[1]=="START")  
							    {
			        			 mygrid_mdb.clearAll();
			  					 mygrid_mdb.parse(data,"json");
								 if (flashMdb)
						     		flashMdb.reloadData();
								 }
				 		 } 
						 else
	 						{
 	 		  				mygrid_mdb.clearAll();
			  				mygrid_mdb.parse(data,"json");
							if (flashMdb)
						     	flashMdb.reloadData();
							}
						}	
			 }
	}
	
  else if (channel=='MDS') {
        var rows=data.rows;
		var ar_signal=sub_signal.split(":");
	//	alert('MDS='+rows.length);
		if (mds_so=='')		
			createMDaGraph();
			
		if (rows.length==1) 
			{
			if (mygrid_mds.doesRowExist(rows[0].id)) {
				mygrid_mds.cells(rows[0].id,1).setValue(rows[0].data[1]);
				mygrid_mds.cells(rows[0].id,2).setValue(rows[0].data[2]);
				}
				else
					 mygrid_mds.parse(data,"json");
			}		 
		else {
		      if (sub_signal=="FWRD") 
			     {
		  		  if ( mkt_slider.getValue() >= stop_steps-10) 
					 {
	 					 mygrid_mds.clearAll();
			  			 mygrid_mds.parse(data,"json");
						 if (flashMda)
						     flashMda.reloadData();
					  }
	 			  }
				  else { 
				  		if (ar_signal[0]=="CUSTOM_FWRD") 
							{
				 			 if (ar_signal[1]=="START")  
							    {
			        			 mygrid_mds.clearAll();
			  					 mygrid_mds.parse(data,"json");
								 if (flashMda)
						     		 flashMda.reloadData();
								 }
				 			 } 
							 else
	 							{
 	 		  					mygrid_mds.clearAll();
			  					mygrid_mds.parse(data,"json");
								if (flashMda)
						     		flashMda.reloadData();
								}
						 }	
			  }
	}
  else if (channel=='hiloPrices') 
         {
		   var rows=data.rows;
    		document.getElementById('low').value = rows[0].data[0].lo;
			document.getElementById('high').value = rows[0].data[0].hi;
			document.getElementById('trade').value = rows[0].data[0].trade;
			document.getElementById('last').value = rows[0].data[0].last;
			document.getElementById('buy').value = rows[0].data[0].bprice;
			document.getElementById('sell').value = rows[0].data[0].aprice;
		   }
		
  else if (channel=='trades') {
 // alert("trades-"+channel);
// alert(data.rows.toSource() );
 	  var rows=data.rows;	 
 	  var ar_signal=sub_signal.split(":");
	  
	  if (sub_signal=="FWRD") {
		  if ( mkt_slider.getValue() >= stop_steps-10) 
	 			//mygrid_trades.parse(data, calculateFooterValues(rows.length), "json");
				for(var i = 0; i < rows.length; i++)
					       mygrid_trades.addRow(data.rows[i].data[0]);
	 	}
		else if (ar_signal[0]=="CUSTOM_FWRD") 
				{
				 if (ar_signal[1]=="START") 
			        // mygrid_trades.parse(data, calculateFooterValues(rows.length), "json");
					for(var i = 0; i < rows.length; i++)
					       mygrid_trades.addRow(data.rows[i].data[0]);
				 } 
			 	else {
				     
					  for(var i = 0; i < rows.length; i++)
	 				      mygrid_trades.addRow(data.rows[i].data[0]);
					}
					//mygrid_trades.parse(data, calculateFooterValues(rows.length), "json");
	 //mygrid_trades.clearAll() ;	 

      for(var i = 0; i < rows.length; i++)   //rows.length
      {
	     //							Id,time,price,buy,sell,low,high
		 afterParseTrades(rows[i].id,rows[i].data[0].OASIS_TIME,rows[i].data[0].PRICE);
		 xTrade++;
		// trades_grid.addRowToTable(rows[i].data);
      }
	  
	 // nTrades=mygrid_trades.getRowsNum();
		nTrades = mygrid_trades.getRecordSet().getLength();
				
	  if ( xTrade &&  nTrades > 10) {
	//  mygrid_trades.deleteRows(0,9);
	 		for( i = 0; i < nTrades-10; i++) {
				mygrid_trades.deleteRow(i);
	  		//	mygrid_trades.selectRow(i, false, true, false);
				//alert(i+"==="+mygrid_trades.getRowId(i));	
				}
			
			//mygrid_trades.deleteSelectedRows();
			//alert(xTrade +"=x=="+  nTrades+"==="+rows.length+"==="+mygrid_trades.getRowsNum()+"=="+mygrid_trades.getRowIndex(219));
			}
			
	
	if (bidask_so==''){
	
	//	createTradesGraph();
		createBidAskGraph();
		bidask_so.addVariable("settings_file", escape(graphs+"bidask_settings.xml"));
		}
	  else	{
	  		//alert("flashMovie");
			if (flashBidAsk  && dhxPlayWins.window("TradesGraph").isOnTop()) {
	
				if (sub_signal) 
				   {
					var ar_signal = sub_signal.split(":");
					if (ar_signal[0]=="CUSTOM_FWRD")
						if (ar_signal[1]=="START")
							if ( (nTrades - prev_xTrade) > 4 ) {
				    			if (flashTrades  && dhxPlayWins.window("TradesGraph").isOnTop())
								    flashTrades.reloadData();
								if (flashBidAsk)
								    appendBidAskGraph();
								prev_xTrade=nTrades;
								} 
					}
				else	
			 	 	if ( (xTrade - prev_xTrade) > 10 ) {
				    	if (flashTrades  && dhxPlayWins.window("TradesGraph").isOnTop())
							flashTrades.reloadData();
								if (flashBidAsk)
									appendBidAskGraph();
								    
								    //flashBidAsk.reloadData();
						prev_xTrade=xTrade;
						}
				}
			}	
		
	 //for( i = 0; i < rows.length; i++)
	//	  mygrid_trades.showRow(rows[i].id);
	
	 if (signal=="PAUSE_OPENPRICE") {
	        marketRun();
			}
	}
	
 delete data;
 delete datastr;
 script_is_busy=false;
 }


function appendBidAskGraph(rows)
{
 data=rows[0].data[0]+","+document.getElementById('buy').value+","+document.getElementById('sell').value+","+rows[0].data[3]+","+document.getElementById('last').value+","+document.getElementById('high').value+","+document.getElementById('low').value;
 
 //so.addVariable("chart_data",data);
 flashBidAsk.appendData(data);
 }
 
function marketRun()
{
var execCode="";
	
	webBar.setItemText('START','Run');
  	webBar.setItemImage('START', 'playtrade.png');
//	marketFastTrade();
	marketSpeed();
	signal="RUN";
			
 }


function marketPause()
{
	webBar.setItemText('START','Pause');
  	webBar.setItemImage('START', 'pausetrade.png');
//alert("marketPause");			
	signal="PAUSE";		
	msg='task=pause_trade'+"&data=p"+ses_str;
	var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	eval_return(loader.xmlDoc.responseText); 
}

function marketSpeed()
{
 if (sub_signal=="")
    var sp=60;
  else {
	    var ar_signal=sub_signal.split(":");
		if (ar_signal[0]=="CUSTOM_FWRD") 
			var sp=1;
		}
 if (sp==1)	
 	marketFastTrade();
  else
  	marketSlowTrade();
 }
 
function marketSlowTrade()

{
 webBar.setValue("S1", 60);
 delay=webBar.getValue("S1")*10000;
// msg='task=set_delay'+"&data="+delay+":r"+ses_str;
 msg='task=run_trade'+"&data="+delay+":r"+ses_str;
 var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
 eval_return(loader.xmlDoc.responseText);
 }

function marketFastTrade()
{
 webBar.setValue("S1",  1);
 delay=webBar.getValue("S1")*10000;
 msg='task=run_trade'+'&execWhenReturn='+execCode+"&data="+delay+":r"+ses_str;
 var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
 eval_return(loader.xmlDoc.responseText);
 }
						
function Orders_calculateFooterValues(nRows){
        var nrQ = document.getElementById("nr_orders");
        nrQ.innerHTML = xOrder+nRows;      
    }   
	
function calculateFooterValues(nRows){
        var nrQ = document.getElementById("nr_q");
        nrQ.innerHTML = xTrade+nRows;
        var srQ = document.getElementById("sr_q");
        srQ.innerHTML = sumColumn(7);
      
    }
    
function sumColumn(ind){
        
        for(var i=0;i<mygrid_trades.getRowsNum();i++){
            total_trades_val+= parseFloat(mygrid_trades.cells2(i,ind).getValue())
        }
        return total_trades_val;
    }
	
function tradesClick(iteration)
{
var row = document.getElementById( iteration );
row.style.backgroundColor= "#fefdc1";

//var table = document.getElementById('mp_trades');
//var indexOfRow = row.rowIndex;
}

function ch(channel, msg_no)
{
//alert("ch="+msg_no);
if (channel=='orders')
	ctrl_messages=msg_no;
document.getElementById('ostop').value = msg_no;	
/*
 Meteor.joinChannel("ctrl",ctrl_messages);
  Meteor.mode = 'simplepoll';
// Start streaming!
  Meteor.connect();
  */
}
/* ======================================================================================== *
 *								MAIN Draw Layout Function									*
 * ============================ =========================  ================================ */
function createPlayLayout(){
var msg='';

	dhxPlayWins = new dhtmlXWindows();
	 
       		  layoutPlayWin = dhxPlayWins.createWindow("w1", 0, 0, screenW-3, screenH-150); //175  
			  dhxPlayWins.window("w1").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="w1") {
						  	  if (mygrid_resol)
						      	Meteor.leaveChannel("ctrl");
							  Meteor.disconnect();
							  						      
							  if (vieworders)
							     // mygrid_orders.destructor();
								  mygrid_orders.destroy();
								  mygrid_trades.destroy();
							  //mygrid_trades.destructor();
							  //mygrid_bob.destructor();
							  //mygrid_sob.destructor();
							  //mygrid_bmd.destructor();
							  //mygrid_smd.destructor();
							  mygrid_events.destroy();
							  }
						  return true;
						  }); 
			   //dhxPlayWins.window("w1").setPosition(190,160); 
			   
//			   dhxPlayLayout = new dhtmlXLayoutObject(layoutPlayWin, "5I", "dhx_blue");
			   dhxPlayLayout = new dhtmlXLayoutObject(layoutPlayWin, "4U", "dhx_blue");  // a,b,c,d    d=a,b
			//   dhxPlayLayout.cells("d").setHeight(350);
			   
			   dhxPlayLayoutA = new dhtmlXLayoutObject(dhxPlayLayout.cells("d"), "2U");
			   
			   
			   dhxPlayLayout.cells("a").setHeight(240);
			   dhxPlayLayout.cells("b").setHeight(240);
			   
			   dhxPlayLayoutA.cells("a").setWidth(580);
			  // dhxPlayLayoutA.cells("b").setWidth(450);
			   
			   
			   dhxPlayLayout.cells("b").setWidth(410);
			   dhxPlayLayoutA.cells("a").hideHeader();
			   dhxTabbarA = dhxPlayLayoutA.cells("a").attachTabbar();
			   dhxTabbarA.setImagePath(images); 
			   dhxTabbarA.addTab("mktdepth","Market Depth",100,0,0);
			   dhxTabbarA.setContent("mktdepth","tab_mktdepth");
			   
			   dhxTabbarA.addTab("graphdepth","Graph Depth",100,1,0);
			   dhxTabbarA.setContent("graphdepth","tab_graphdepth");
			   dhxTabbarA.setTabActive("mktdepth",true);
			   
//			   dhxTabbarA.addTab("fic","Market Resolution",100,0,0);
//			   dhxTabbarA.setOnSelectHandler(HresolTab);

//			   dhxTabbarA.setContent("fic","tabfic");
//			   dhxTabbarA.addTab("sup","Trading Data",100,1,0);
//			   dhxTabbarA.setContent("sup","tabsup");


	//		   dhxPlayLayoutB = new dhtmlXLayoutObject(dhxPlayLayout.cells("b"), "2E");
			   //dhxPlayLayoutA.cells("b").setWidth(520);			   
			   dhxTabbarB = dhxPlayLayout.cells("a").attachTabbar();
			   dhxTabbarB.setImagePath(images); 
			   dhxTabbarB.addTab("trades","Last Trades",100,0,0);
			   //dhxTabbarB.setOnSelectHandler(HtradesTab);
			   dhxTabbarB.setContent("trades","tab_lasttrades");
			   dhxTabbarB.addTab("orders","Last Orders",100,1,0);
			   dhxTabbarB.setContent("orders","tab_lastorders");
			   
//			   dhxTabbarB.addTab("mktdepth","Market Depth",100,1,0);
//			   dhxTabbarB.setContent("mktdepth","tab_mktdepth");
			   
//			   dhxTabbarB.addTab("graphdepth","Graph Depth",100,2,0);
//			   dhxTabbarB.setContent("graphdepth","tab_graphdepth");
			   dhxTabbarB.setTabActive("trades",true);
			   
			   layoutPlayWin.setText("MarketPlay ");
//			   dhxPlayLayout.cells("e").setText("Orders");
			//	dhxPlayLayoutA.cells("a").setText("Market Area");
				dhxPlayLayout.cells("a").setText("Market Data feed");
				dhxPlayLayoutA.cells("b").setText("Market Visualization <img src='codebase/imgs/mkt_graph.png' />");
				dhxPlayLayout.cells("b").hideHeader();
				dhxPlayLayout.cells("d").hideHeader();
				dhxPlayLayout.cells("c").setText("Security Phases");
				//dhxPlayLayoutB.cells("b").setText("Orders");
				//dhxPlayLayoutB.cells("a").setText(Trades");
			   //dhxPlayLayoutC.cells("b").setText("Trading Events");
		   
			   var obj = document.getElementById("SecInfo");
			   if (obj)
			   	   dhxPlayLayout.cells("b").attachObject(obj);
				document.getElementById("SecInfo").className = 'showSecInfoform';
				
			   dhxAccord = dhxPlayLayoutA.cells("b").attachAccordion();
			   dhxAccord.setIconsPath(images);
			   //dhxAccord.addItem(1, "Market Input");
			   //dhxAccord.addItem(1, "Security Trading");
			   dhxAccord.addItem(1, "Security Bid/Ask");
			   dhxAccord.addItem(2, "Auction Projected");

			   //obj = document.getElementById("flashcontent");
			   //if (obj)
					//dhxAccord.cells(1).attachObject(obj);
			   //obj = document.getElementById("trades_flashcontent");
			   //if (obj)
				//	dhxAccord.cells(1).attachObject(obj);
			   
			   obj = document.getElementById("bidask_flashcontent");
			   if (obj)
					dhxAccord.cells(1).attachObject(obj);
				
			   obj = document.getElementById("projected_flashcontent");
			   if (obj)
					dhxAccord.cells(2).attachObject(obj);
				
			   //
			  
//			   dhxLayout.cells("e").attachURL("http://imarket.helex.gr/imarket/index2.php?option=com_marketplay&task=readcsv2");
//			   dhxLayout.cells("c").attachURL("http://www.ase.gr/");
			   webBar = dhxPlayLayout.attachToolbar(); 
			   webBar.setIconsPath(images);
			   //webBar_c = dhxPlayLayout.cells("c").attachToolbar();
			   var width = dhxPlayLayout.cells("a").getWidth();
			 
			 dhxPlayWins.setSkin("dhx_blue"); //

			 webBar.addButton("C1",1,"","config.gif","config_off.gif"); 
			 webBar.addButton("C2",1,"","msgsconf.png");
			 webBar.addSeparator("sep01", 2);

			 webBar.attachEvent("onClick", function(id)
			 					 {
			 						if (id=='C1') {
			  			 				webBar.disableItem('C1');
			 			 				createConfigWindow();
						 				}
										if (id=='C2') {
			  			 				   //webBar.disableItem('C2');
			 			 				   createMsgsWindow();
						 				   }
			        				});
			 			
			   
			 webBar.addSlider("S1", 2, 100, 1, 100, delay/10000, "Min", "Max", "Timer");
			 webBar.attachEvent("onValueChange", function(id, value)
			 					{
								 if (id=='S1')
								 	delay = value*10000;
								 	webBar.setItemToolTipTemplate("S1","Delay is:+%v");
								 	
									msg='task=set_delay'+"&data="+delay+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);
								 });
			 
		/* ==================== INPUT FILE DATE ====================== */
			 webBar.addText("t1",3,"Ημερομηνία:");
			 webBar.addInput("i1",4,"",70);
			
		  	webBar.addButton("START",20,"Open","opentrade.gif");
			webBar.hideItem("START");
			webBar.addButton("STOP",21,"Stop","stoptrade.png");
			webBar.hideItem("STOP");
			

			 // Attach Calendar
			 var inpObj =webBar.objPull[webBar.idPrefix+"i1"].obj.childNodes[0].childNodes[0].childNodes[0].childNodes[0].childNodes[0];
			 
			 mCal = new dhtmlxCalendarObject(inpObj,true); 
			 //mCal.setYearsRange(1990, 2008); 
			 mCal.setSkin('dhx_black'); 
			 //mCal.setDateFormat("%Y-%m-%d");
    		 mCal.draw();
			 
			 
			 // Trap and check new Entered manually date
			 webBar.attachEvent("onEnter", function(id, value)
			 					{
									var execCode="fillDate();";						
									msg='task=checkDate'+'&objId='+fdate+'&execWhenReturn='+execCode;
									tasks[pos]=new callServer(pos,"/imarket/components/com_marketplay/test1.php", msg );
									tasks[pos].start();
									pos++;
								  });
			 mCal.attachEvent("onClick",function(date)
			 					{
								 var d1= mCal.getDate();
								// alert(date+"=="+d1);
								var execCode="fillDate();";						
			  					msg='task=checkDate'+'&objId='+date+'&execWhenReturn='+execCode+"&my=test";
			   					var loader=dhtmlxAjax.postSync(test1,msg);
			  					eval(loader.xmlDoc.responseText); 
								  }) 
								  
		// Initialize once during Layout drawn, DATE from DB Configuration 
			
			 
			var execCode="fillDate();";						
			  msg='task=getConfigDate'+'&objId='+fdate+'&execWhenReturn='+execCode;
			  //dhtmlxAjax.get(msg,function(loader){eval(loader.xmlDoc.responseText);});
			  var loader=dhtmlxAjax.postSync(test1,msg);
			  eval(loader.xmlDoc.responseText); 
		/* ============================================================ */
		
				
			 webBar.attachEvent("onClick", function(listOptionId, selectButtonId) 
				{
				var Id=listOptionId.substr(3,1);
				  });
									 
			 webBar.attachEvent("onClick", function(id)
			 					{
						// Start thread for reading ficsnd! 1st: Read markets info.
			 					if (id=="START" && signal=="") {
			 						var execCode="";
									
									msg='task=startup_fic'+'&execWhenReturn='+execCode+"&data="+fdate+ses_str;						
									//dhtmlxAjax.get(msg,function(loader){document.write(loader.xmlDoc.responseText);});
									wait_signal = setInterval(wait_for_signal, 2000)
									
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);
									
									//wait_signal = setInterval(wait_for_signal, 2000);
									return;
									}
								if (id=="START" && webBar.getItemText(id)=='Start') { // Ctrl have Initialized !!!!
								
									var execCode=""; //afterReadPreInfo();
									webBar.showItem("NEXT"); 
									webBar.showItem("ORDERS");
									webBar.showItem("TRADES");
									webBar.showItem("CUSTOM");
									webBar.showItem("BuySellGraph");
									webBar.showItem("TradesGraph");
									
						//			dhxPlayWins.window("w1").setModal(true);
									dhxPlayWins.window("w1").progressOn();
									
									wait_datasignal = setInterval(wait_for_DataSignal, 2000);
									maxStep=170;
									msg='task=startup_sup'+'&execWhenReturn='+execCode+"&data="+fdate+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval(loader.xmlDoc.responseText);
									
								//	Meteor.joinChannel("events",1);						
								//	Meteor.joinChannel("orders",1);
								//	Meteor.joinChannel("mktbeat",1);
								//	Meteor.joinChannel("trades",1);
								//	Meteor.joinChannel("hiloPrices",1);
								//	Meteor.joinChannel("OBB",1);
								//	Meteor.joinChannel("OBS",1);
								//	Meteor.joinChannel("MDB",1);
								//	Meteor.joinChannel("MDS",1);
  							//		dhxPlayWins.window("w1").setModal(false);
									dhxPlayWins.window("w1").progressOff();
									return;
									}
										// INTERRUPT Trading and PAUSE until final STOP or NEXT step
								if (id=="START" && webBar.getItemText(id)=='Pause' ) {
									var execCode="";
									interrupt=1;
									signal="PAUSE";
									
									msg='task=pause_trade'+"&data=p"+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);
																		
									webBar.setItemText('START','Run');
									webBar.setItemImage('START', 'playtrade.png');
									return;
									}
									
								if (id=="START" && webBar.getItemText(id)=='Run' ) { // Ctrl has Initialized !!!!
															
									webBar.setItemText('START','Pause');
  									webBar.setItemImage('START', 'pausetrade.png');
									signal="RUN";
									var execCode="";
									msg='task=run_trade'+'&execWhenReturn='+execCode+"&data="+delay+":r"+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);																	
									}
																		
								if (id=='STOP') {
			 						msg='task=stop_trade'+"&data=s"+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);
									
									webBar.setItemText('START','Run');
									webBar.setItemImage('START', 'playtrade.png');
									
									Meteor.leaveChannel("events");						
									Meteor.leaveChannel("orders");
									Meteor.leaveChannel("trades");
									Meteor.leaveChannel("hiloPrices");	
									Meteor.leaveChannel("mktbeat");	
									Meteor.leaveChannel("OBB");
									Meteor.leaveChannel("OBS");
									Meteor.leaveChannel("MDB");
									Meteor.leaveChannel("MDS");							
									return;									
									}
									
								if (id=='NEXT') {
			 						var execCode="";
								
									msg='task=next_Order'+"&data=n"+ses_str;
									var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
									eval_return(loader.xmlDoc.responseText);
									}
								
								if (id=='CUSTOM') {
			 								 setCustomPeriod();
											}
								
								if (id=='TradesGraph')
									createTradesGraphWin();
									
								if (id=='BuySellGraph')
									createBuySellGraphWin();
									
								if (id=='ORDERS') {
										if (OrdersWin!='') {
										   if (OrdersWin.isHidden()) 
											 	OrdersWin.show();												
			 								 OrdersWin.bringToTop();
											}
								
										else {
											  execCode="createOrdersWin();";
											  msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn='+execCode;
											  loader = dhtmlxAjax.postSync(test1,msg);
											  eval_return(loader.xmlDoc.responseText);
											  OrdersWin.show();
											  OrdersWin.bringToTop();	
											  }
										return;									
									 }								
									 
								if (id=='TRADES') {
								        if (TradesWin) {
										   if (TradesWin.isHidden()) 
											 	TradesWin.show();												
			 								 TradesWin.bringToTop();
											}
								
										else {
											  execCode="createTradesWin();";
											  msg='task=getMsgColumns'+'&objId=IS'+'&execWhenReturn='+execCode;
											  loader = dhtmlxAjax.postSync(test1,msg);
											  eval_return(loader.xmlDoc.responseText);
											  TradesWin.show();
											  TradesWin.bringToTop();	
											  }
										return;									
									 }
			        			});

			
			webBar.addButton("NEXT",22,"Next","nextorder.png");
			webBar.hideItem("NEXT");
			
			webBar.addButton("ORDERS",23,"Past Orders","ordersgrid.gif");
			webBar.hideItem("ORDERS");
			
			webBar.addButton("TRADES",24,"Past Trades","tradesgrid.gif");
			webBar.hideItem("TRADES");
			
			webBar.addButton("CUSTOM",25,"Custom Period","customperiod.png");
			webBar.hideItem("CUSTOM");

			webBar.addButton("BuySellGraph",26,"Buy/Sell Graph","buysell_graph.png");
			webBar.hideItem("BuySellGraph");
			
			webBar.addButton("TradesGraph",27,"Trades Graph","buysell_graph.png");
			webBar.hideItem("TradesGraph");
			
			 createGrids();
}

function eval_return(return_str)
{
 str=return_str;
 if (str.search(/script/i)){
     str = str.replace(/\<script src=\".\/components\/com_marketplay\/AjT.js\"\>\<\/script\>/,"");
	 }
 if (str.search(/script/i))
	str = str.replace(/<script>/,"");
 if (str.search(/script/i))
	str.replace(/<\/script>/,"");
	
 eval(str);
}

function AdvanceSlider(nTicks)
{
	//webBar.setValue("S2", market_steps+step);
	if (sub_signal=="SET_FWRD")
		return;
	mkt_slider.setValue(market_steps+step);
	
	market_steps += (parseInt(nTicks)==0) ?step :nTicks;
			
	if (market_steps >= total_secs) {  // STOP Trading...
		var execCode="";
		interrupt=1;
		Lock=1;
		//msg=sg_signal+'?task=setSignal'+'&objId=SLEEP'+'&Sig=CLIENT'+'&execWhenReturn='+execCode;
		//dhtmlxAjax.get(msg,function(loader){document.write(loader.xmlDoc.responseText);});
		} 
}

function wait_for_signal(){ // ...from control Thread.

if (ans=="ok")
   { 		 // ctrlThread is up and Ready!
	clearInterval(wait_signal);
	signal="pre-info-ok";
	webBar.setItemText('START','Start');
	webBar.setItemImage('START', 'starttrade.gif');
	
	var execCode="fillDate();";					
			  msg='task=getConfigDate'+'&execWhenReturn='+execCode;
			  var loader = dhtmlxAjax.postSync(test1,msg); 
			  eval(loader.xmlDoc.responseText);	
	ans="";		  
	}
   else
		if (ans=="error")
	   		{ 		 // ctrlThread does not come up due to errors!
			clearInterval(wait_signal);
			}
}


/*---------------------------------------------------------------------------------------
*	Called with afterReadPreInfo, to check if DATA Thread finished its Initialiazation
*----------------------------------------------------------------------------------------*/
function wait_for_DataSignal(){
//alert("wait_for_DataSignal - "+ans);
if (ans=="ok") {
    Datasignal="pre-data-ok";
	clearInterval(wait_datasignal);
	webBar.setItemText('START','Run');
	webBar.setItemImage('START', 'playtrade.png');
	
	//webBar.showItem("S2");
	webBar.showItem("NEXT");
	webBar.showItem("ORDERS");
	webBar.showItem("TRADES");
		
	execCode="MakeMarketSlider();";
	msg='task=computeMarket'+'&objId='+slider2+'&execWhenReturn='+execCode;
	var loader=dhtmlxAjax.postSync(test1,msg);
	eval_return(loader.xmlDoc.responseText);
	
	
/*	var obj = document.getElementById("SecInfo");
			   if (obj)
			   	   dhxPlayLayoutA.cells("b").attachObject(obj);
				document.getElementById("SecInfo").className = 'showSecInfoform';
*/				
	//if(_progressAt >= _progressEnd) {
//    dhxPlayWins.setSkin("clear_silver");
		//}
	}
	else 
		if (ans=="error")
	   		{ 		 // ctrlThread does not come up due to errors!
			clearInterval(wait_datasignal);
			}
}

function MakeMarketSlider()
{
//webBar.showItem("S2"); 
//webBar.removeItem("S2");
document.getElementById('market').value = market_name;

msg='task=init_MarketResolutionTable';
var loader=dhtmlxAjax.postSync(test1,msg);
//eval(loader.xmlDoc.responseText);

//	INIT Meteor   =====================================================================================================
  Meteor.mode = 'stream';
// Start streaming!
  Meteor.connect();
  
  msg='task=openMeteor'+'&data=startup'+ses_str;
  var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
  eval_return(loader.xmlDoc.responseText);
//	INIT Meteor   =====================================================================================================
  
//createMktResolutionWindow();
//createMktDataResolutionWindow();

cmd = cmd_all;

var obj = document.getElementById("slider");
document.getElementById('price').value = start_price;
prev_price = start_price;

mkt_slider = new dhtmlxSlider(obj, 300, 'simplesilver', false, 0, total_secs, 0, step);
mkt_slider.setImagePath(images);

var newImage = "url(../images/test.png)";
document.getElementById('slider').style.backgroundImage = newImage;

mkt_slider.attachEvent("onChange",function(newValue,sliderObj){
						if (newValue > 0) {
							sub_signal="SET_FWRD";
							document.getElementById('ostop').value = newValue;
							stop_steps=newValue;							
							mkt_slider.setValue(stop_steps);
							}
						});
mkt_slider.attachEvent("onSlideEnd",function(newValue,sliderObj){
						//sub_signal="SET_FWRD";
						//document.getElementById('ostop').value = newValue;
						//market_steps=newValue;
						//mkt_slider.setValue(market_steps);
						msg='task=pause_trade'+"&data=p"+ses_str;
						var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
						eval_return(loader.xmlDoc.responseText);
																		
						webBar.setItemText('START','Run');
						webBar.setItemImage('START', 'playtrade.png');
						stop_steps=newValue;
						clearGrids();
						
						market_steps=0;
						mkt_slider.setValue(market_steps);
						
						webBar.setValue("S1", 1);
						delay=webBar.getValue("S1")*10000;
	  					msg='task=set_delay'+"&data="+delay+ses_str;
	  					var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	  					eval_return(loader.xmlDoc.responseText);
						xOrder=0;
						xTrade=0;
						total_trades_val=0;
						msg='task=step_fwrd'+"&data="+stop_steps+ses_str;
						var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
						eval_return(loader.xmlDoc.responseText);
						sub_signal="FWRD";						
						});
mkt_slider.init();
					
//webBar.addSlider("S2", 13, 300, 0, total_secs, 0, "Start", "Stop", "Market");
//webBar.setItemToolTipTemplate("S2","Item: +%v");
webBar.enableItem("START");
// <![CDATA[

// ]]>
//}
//else alert ("Flash Player Version is NOT 8.0.0");
  

//if (market_r==null)
 //  alert("array is null");
}

function createBuySellGraphWin()
{
 if ( !dhxPlayWins.window("BuySell") ) {
	 BuySellGraphWin = dhxPlayWins.createWindow("BuySell", 10, 110, 520, 350);
 	 BuySellGraphWin.setText("Market Buy/Sell");
	 BuySellGraphWin.attachEvent("onClose", function(win) 
			 					{
								 if (win.getId()=="BuySell") {
									BuySellGraphWin.hide();
									}
								   return true;	
								  });
	obj = document.getElementById("flashcontent");
	if (obj)
		dhxPlayWins.window("BuySell").attachObject(obj);
	
	if (so=='')
	    createBuySellGraph();	
	}

 BuySellGraphWin.show();
 BuySellGraphWin.bringToTop();
 }

function createTradesGraphWin()
{
 if ( !dhxPlayWins.window("TradesGraph") ) {
	 TradesGraphWin = dhxPlayWins.createWindow("TradesGraph", 10, 110, 520, 350);
 	 TradesGraphWin.setText("Market Trades");
	 TradesGraphWin.attachEvent("onClose", function(win) 
			 					{
								 if (win.getId()=="TradesGraph") {
									TradesGraphWin.hide();
									}
								   return true;	
								  });
	obj = document.getElementById("trades_flashcontent");
	if (obj)
		dhxPlayWins.window("TradesGraph").attachObject(obj);
	
	if (trades_so=='')
	 	createTradesGraph();
	}

 TradesGraphWin.show();
 TradesGraphWin.bringToTop();
 }
 	
//=================================================================================*
function createBuySellGraph()  
{
 so = new SWFObject(graphs+"amstock.swf", "amstock", "98%", "400", "8", "#FFFFFF");
 so.addVariable("chart_id", "amstock");
 so.addVariable("path", graphs);
 so.addVariable("settings_file", escape(graphs+"amstock_settings.xml"));                // you can set two or more different settings files here (separated by commas)
//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 so.write("flashcontent");
 }
 
function createTradesGraph()
{
trades_so = new SWFObject(graphs+"amstock.swf", "trades", "100%", "480", "8", "#FFFFFF");
trades_so.addVariable("path", graphs);
trades_so.addVariable("chart_id", "trades");
trades_so.addVariable("settings_file", escape(graphs+"trades_settings.xml"));
trades_so.write("trades_flashcontent");
}

function createBidAskGraph()
{
bidask_so = new SWFObject(graphs+"amstock.swf", "bidask", "90%", "480", "8", "#FFFFFF");
bidask_so.addVariable("path", graphs);
bidask_so.addVariable("chart_id", "bidask");
bidask_so.addVariable("chart_data", encodeURIComponent("08:00:00:000;0;2;4;6;8;10;12;14"));
//bidask_so.addVariable("settings_file", escape(graphs+"bidask_settings.xml"));
//bidask_so.addVariable("data_file", escape(graphs+"bidask_dataa.txt"));
bidask_so.write("bidask_flashcontent");
}

function createProjectedGraph()
{
projected_so = new SWFObject(graphs+"amstock.swf", "projected", "90%", "480", "8", "#FFFFFF");
projected_so.addVariable("path", graphs);
projected_so.addVariable("chart_id", "projected");
projected_so.addVariable("settings_file", escape(graphs+"projected_settings.xml"));
//bidask_so.addVariable("data_file", escape(graphs+"bidask_dataa.txt"));
projected_so.write("projected_flashcontent");
}

function createMDbGraph()
{
mdb_so = new SWFObject(graphs+"amcolumn.swf", "mdb", "100%", "320", "8", "#FFFFFF");
mdb_so.addVariable("path", graphs);
mdb_so.addVariable("chart_id", "mdb");
mdb_so.addVariable("settings_file", escape(graphs+"mdb_settings.xml"));
mdb_so.addVariable("data_file", escape(graphs+"mdb_data.txt"));
mdb_so.write("mdb_flashcontent");
}

function createMDaGraph()
{
 mds_so = new SWFObject(graphs+"amcolumn.swf", "mda", "100%", "320", "8", "#FFFFFF");
 mds_so.addVariable("path", graphs);
 mds_so.addVariable("chart_id", "mda");
 mds_so.addVariable("settings_file", escape(graphs+"mda_settings.xml"));
 mds_so.addVariable("data_file", escape(graphs+"mda_data.txt"));
 mds_so.write("mda_flashcontent");
}

function amChartInited(chart_id) {
if (chart_id=="mdb")
  flashMdb = document.getElementById(chart_id);
  else
    if (chart_id=="mda")
	  flashMda = document.getElementById(chart_id);
	  else
    	if (chart_id=="bidask")
	  		flashBidAsk = document.getElementById(chart_id);
		  else
		    if (chart_id=="trades")
	  			flashTrades = document.getElementById(chart_id);
		  else
    		if (chart_id=="projected")
	  			flashProjected = document.getElementById(chart_id);
	  else {
    		flashMovie = document.getElementById(chart_id);
			    //alert("flashMovie:"+chart_id);
			flashMovie.setParam("header.text","Security Index: "+hsym);
			//flashMovie.reloadSettings();
			}
}

function amError(chart_id, message) 
{
  alert('Error from chart_id ' + chart_id + ': ' + message);
 }
   
function amClickedOnBullet (chart_id, graph_index, value, axis, url, description) {
  alert('Whoa! Bullet was clicked: ' + value);
  if (url != '') {
    window.location.href=url;
  }
} 

function amClickedOn(chart_id, date, period)
{
alert('Whoa! Chart was clicked: ' + date+"=="+ period); 
 }

function amClickedOnEvent____(chart_id, date, description, id, url)
{
alert('Whoa! Event was clicked: ' + date+"=="+ description+"=="+ id+"=="+ url);
 }
 
 
function selectInterval(interval){
  flashMovie.setZoom(2006 - interval, 2006);
}                
 
function showAll(){
  flashMovie.showAll(); 
}
//=================================================================================*

function createGrids()
{
 if (vieworders > 0)
 	createOrdersGrid();
	
    execCode="createGridTradeBook();";
	msg='task=getMsgColumns'+'&objId=IS'+'&execWhenReturn='+execCode;
	var loader = dhtmlxAjax.postSync(test1,msg);
	eval(loader.xmlDoc.responseText);	
	
//	createTradesWin();
	
	createGridMarketDepth();
	 
// createGridBUYOrderBook();
// createGridSELLOrderBook();
// createGridBUYMarketDepth();
// createGridSELLMarketDepth();
 createGridEvents();
}

function createOrdersGrid()
{
 var execCode="createGridOrders();";	
 
	msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn='+execCode;	
	var loader = dhtmlxAjax.postSync(test1,msg);
	eval(loader.xmlDoc.responseText); //document.write
}
	
function clearGrids()
{

 if (vieworders > 0)
	// mygrid_orders.clearAll();
	mygrid_orders.deleteRows(0,-1);
//mygrid_trades.clearAll();
mygrid_trades.deleteRows(0,-1);
//mygrid_events.clearAll();
mygrid_events.deleteRows(0,-1);
//mygrid_smd.clearAll();
//mygrid_bmd.clearAll();
//mygrid_sob.clearAll();
//mygrid_bob.clearAll();
}

/******************************************************************************************
			GRIDS
******************************************************************************************/

function createGridOrders()
{
// DataTable constructor syntax
 var ordersDataSource = new YAHOO.util.DataSource({});   
 ordersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 ordersDataSource.responseSchema = {  fields: columnsIds   };  //["OASIS_TIME","PHASE_ID","SIDE","ORDER_NUMBER","VOLUME","PRICE","TRADER_ID","ORDER_STATUS","M_C_FLAG"]
 // resultsList: "data",
 var myConfigs = {   
    // Set up pagination   
    // paginator : new YAHOO.widget.Paginator({   
    //     rowsPerPage    : 50   
    // }),   
     // Set up initial sort state   
   //  sortedBy : {   
   //      key: "OASIS_TIME",   
   //      dir: YAHOO.widget.DataTable.CLASS_ASC   
   // },   
     // Sorting and pagination will be routed to the server via generateRequest   
     scrollable:true,
	 dynamicData : true,
	 height: "17em", 
	 renderLoopSize: 30, 
	 initialLoad:false  
 };  

 mygrid_orders = new YAHOO.widget.ScrollingDataTable("tab_lastorders", columnsDef, ordersDataSource,myConfigs); //scrollable:true
 mygrid_orders.subscribe("rowClickEvent", mygrid_orders.onEventSelectRow);
 mygrid_orders.subscribe("rowMouseoverEvent", mygrid_orders.onEventHighlightRow);
 mygrid_orders.subscribe("rowMouseoutEvent", mygrid_orders.onEventUnhighlightRow);
 }

function createGridOrders_1(){
//			dhxPlayLayoutC.cells("a").setWidth(650);
	//		dhxPlayLayoutB.cells("b").setWidth(650);
	//		dhxPlayLayoutB.cells("b").setHeight(250);
			//dhxPlayLayoutC.cells("a").setHeight(250);
			//OrdersWin = dhxPlayWins.createWindow("w2", 100, 100, 350, 200);
/*			dhxPlayWins.window("w2").attachObject("ordersId", true);
			mygrid_orders = dhxPlayWins.window("w2").attachGrid();
			dhxPlayWins.window("w2").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="w2") {
						  	  Meteor.leaveChannel("orders");
							  vieworders=false;
							  mygrid_orders.destructor();
						  	}
						 });
*/						 
//			mygrid_orders = dhxPlayLayoutC.cells("a").attachGrid();
	//		mygrid_orders = dhxPlayLayoutB.cells("b").attachGrid();
			mygrid_orders = new dhtmlXGridObject("tab_lastorders");
			//mygrid_orders.attachEvent("onBeforeSorting",sortGridOrdersOnServer); 
						 
			mygrid_orders.imgURL = images;
			//alert(cols);
			mygrid_orders.setHeader(cols);
			//mygrid_orders.attachHeader("#text_filter,#numeric_filter");
			mygrid_orders.setColumnIds(colIds);
			
			mygrid_orders.setInitWidths(ColSize);
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_orders.setColAlign(cols_align);
			mygrid_orders.setColTypes(types_cols);
			mygrid_orders.setColSorting(sort_cols);
			//mygrid_orders.attachFooter("<b>Total:</b>{#stat_count},#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
			mygrid_orders.attachFooter("Σύνολο Εντολών,#cspan,-,<div id='nr_orders'>0,#cspan,#cspan,#cspan");
			mygrid_orders.setSkin("modern");
			//mygrid_orders.enableAlterCss("even","uneven");
			mygrid_orders.setNumberFormat("000.00",mygrid_orders.getColIndexById('PRICE'));
			//orders_commands;
			//mygrid_orders.setDateFormat('d-m-y');
	
			//mygrid_orders.setXMLAutoLoading(orderThread);
			mygrid_orders.init();
			//mygrid_orders.enableDistributedParsing(true,10,100);
			//mygrid_orders.splitAt(2);
			mygrid_orders.enableHeaderMenu();

}

function createOrdersWin()
{
		OrdersWin = dhxPlayWins.createWindow("orders", 10, 110, 520, 350);
 		OrdersWin.setText("Current Orders");
 		dhxPlayWins.window("orders").hide();
// dhxPlayWins.window("trades").attachObject("ordersId", true);
 		wingrid_orders = dhxPlayWins.window("orders").attachGrid();
 		dhxPlayWins.window("orders").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="orders") {
							  OrdersWin='';
							  return true;
						  	}
						 });
						 
		menu = new dhtmlXMenuObject(null, "standard");
		menu.setIconsPath(images);
		menu.renderAsContextMenu();
		menu.setOpenMode("web");
		menu.attachEvent("onClick",onLastOrdersMenuClick);
		menu.loadXML("_last_orders_context.xml");	
						 
			wingrid_orders.imgURL = images;
			//alert(cols);
			wingrid_orders.setHeader(cols);
			//mygrid_orders.attachHeader("#text_filter,#numeric_filter");
			wingrid_orders.setColumnIds(colIds);
			
			wingrid_orders.setInitWidths(ColSize);
			//mygrid_orders.setColumnMinWidth(10,0);
			wingrid_orders.setColAlign(cols_align);
			wingrid_orders.setColTypes(types_cols);
			wingrid_orders.setColSorting(sort_cols);
			//mygrid_orders.attachFooter("<b>Total:</b>{#stat_count},#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
			wingrid_orders.attachFooter("Σύνολο Εντολών,#cspan,-,<div id='nr_orders'>0,#cspan,#cspan,#cspan");
			wingrid_orders.setSkin("modern");
			//mygrid_orders.enableAlterCss("even","uneven");
			wingrid_orders.setNumberFormat("000.00",wingrid_orders.getColIndexById('PRICE'));
			//orders_commands;
			//mygrid_orders.setDateFormat('d-m-y');
	 		wingrid_orders.enableContextMenu(menu);
 			wingrid_orders.attachEvent("onBeforeOrdersContextMenu",last_orders_pre_func);

			wingrid_orders.init();
			wingrid_orders.setMultiselect(true);

			wingrid_orders.enableHeaderMenu();
			wingrid_orders.enableSmartRendering(true,20);
			wingrid_orders.loadXML(smartOrders+"?posStart=0&count=20"+"&session="+ses);
}

function onLastOrdersMenuClick(menuitemId,type)
{
    var data = wingrid_orders.contextID.split("_"); //rowId_colInd
	if (menuitemId.split("_")[0]=='edit')
	    wingrid_orders.setRowTextStyle(data[0],"color:"+menuitemId.split("_")[1]);
	if (menuitemId.split("_")[0]=='info')
		onButtonClickLastOrders(data[0],menuitemId.split("_")[1]);
	return true;
	}

function last_orders_pre_func(rowId,celInd,grid){
	    if (celInd==1) return false;
	    return true;
}
	
function onButtonClickLastOrders(rowId,type)
    {
	if (type=='Bid' || type=='Ask') {
			var trade_time=mygrid_trades.cells(rowId,0).getValue();
			var te = trade_time.split(':');
			trade_time='';
			for(var i=0; i<te.length; i++)
			    trade_time+=te[i];
			//trade_time+='\0';	
		    var bid_num=mygrid_trades.cells(rowId,7).getValue();
			var ask_num=mygrid_trades.cells(rowId,10).getValue();
			var trade_num=mygrid_trades.cells(rowId,2).getValue();
			createTradeOrdersWindow_bid(trade_time,trade_num,bid_num,"Buy Detail","order");
			createTradeOrdersWindow_bid(trade_time,trade_num,ask_num,"Sell Detail","order");
			}
		else if (type=='Order') {
		    var order_time=wingrid_orders.cells(rowId,0).getValue();
			var te = order_time.split(':');
			order_time='';
			for(var i=0; i<te.length; i++)
			    order_time+=te[i];
			//order_time+='\0';	
			var order_num=wingrid_orders.cells(rowId,3).getValue();
			createTradeOrdersWindow_bid(order_time,order_num,order_num,"Order Detail","order");
			}
		else if (type=='Notes') {
			var order_price=wingrid_orders.cells(rowId,5).getValue();
			var trader_num=wingrid_orders.cells(rowId,6).getValue();
			var order_num=wingrid_orders.cells(rowId,3).getValue();
		    editTradeNotes(order_num,order_price,trader_num,"Here put your Notes<br/><b>about...</b>");
			}
		
    return false;
    }
	
function lasttrades_pre_func(rowId,celInd,grid){
	    if (celInd==1) return false;
	    return true;
}
 
function responseTrades (sRequest , oResponse , oPayload )
 {
  alert(sRequest+"=="+oResponse.toSource());
 // oResponse.onDataReturnSetRows(sRequest , oResponse , oPayload ); //onDataReturnSetRows(sRequest, oResponse, oPayload);
  return true;
  }
function responseCached (sRequest , oResponse , oPayload )
 {
  alert("responseCached=="+oResponse.toSource());
  mygrid_trades.onDataReturnSetRows.apply(mygrid_trades, arguments);
  return true;
  }
function rowadded (oreq, oresp)
 {
  alert("=="+oreq.toSource()+"=="+oresp.toSource());
  //onDataReturnSetRows(sRequest, oResponse, oPayload);
  }
  

 function responseEvent(request , oresponse , ocallback , otId , ocaller )
 {
 alert("responseEvent=="+request.toSource()+"=="+oresponse.toSource());
 }

    

function createGridTradeBook()
{
// DataTable constructor syntax
 var tradesDataSource = new YAHOO.util.LocalDataSource({});  //"http://"+Meteor.host+((Meteor.port==80)?"":":"+Meteor.port)+"/stream.html" 
 tradesDataSource.responseType = YAHOO.util.LocalDataSource.TYPE_JSON; 
 tradesDataSource.responseSchema = { fields: columnsIds   };  //["OASIS_TIME","PHASE_ID","SIDE","ORDER_NUMBER","VOLUME","PRICE","TRADER_ID","ORDER_STATUS","M_C_FLAG"]
 tradesDataSource.subscribe("dataReturnEvent",responseTrades);

 
var callback1 = {   
             success : responseTrades,   
             failure : responseTrades,   
             scope : mygrid_trades   
         };  
		  
tradesDataSource.sendRequest("channel=trades",callback1);   
//tradesDataSource.doBeforeParseData=responseTrades;
//tradesDataSource.getCachedResponse=responseCached;

 // resultsList: "data",
 var myConfigs = {   
    // Set up pagination   
    // paginator : new YAHOO.widget.Paginator({   
    //     rowsPerPage    : 50   
    // }),   
     // Set up initial sort state   
   //  sortedBy : {   
   //      key: "OASIS_TIME",   
   //      dir: YAHOO.widget.DataTable.CLASS_ASC   
   // },   
     // Sorting and pagination will be routed to the server via generateRequest   
     scrollable:true,
	 dynamicData : false,
	 height: "14em", 
	 renderLoopSize: 30, 
	 initialLoad:true,
	 initialRequest:"channel=trades"  
 };  
// responseEvent ( oArgs.request , oArgs.response , oArgs.callback , oArgs.tId , oArgs.caller )
// handleResponse ( oRequest , oRawResponse , oCallback , oCaller , tId )
 mygrid_trades = new YAHOO.widget.DataTable("tab_lasttrades", columnsDef, tradesDataSource,myConfigs); //scrollable:true
 mygrid_trades.subscribe("rowClickEvent", mygrid_trades.onEventSelectRow);
 mygrid_trades.subscribe("rowMouseoverEvent", mygrid_trades.onEventHighlightRow);
 mygrid_trades.subscribe("rowMouseoutEvent", mygrid_trades.onEventUnhighlightRow);
// mygrid_trades.onDataReturnSetRows=responseTrades; //onDataReturnInitializeTable
// mygrid_trades.subscribe("dataReturnEvent", rowadded);
// mygrid_trades.onDataReturnInsertRows=responseTrades;
 // mygrid_trades.getRecordSet().subscribe("recordAddEvent",responseTrades);
 } 
 
function createGridTradeBook__1(){
//			mygrid_trades = dhxPlayLayoutB.cells("a").attachGrid();
			mygrid_trades = new dhtmlXGridObject("tab_lasttrades");
		//	dhxPlayLayout.cells("b").setWidth(540);

			//mygrid_trades = dhxPlayLayout.cells("d").attachGrid();
			mygrid_trades.attachEvent("onRowAdded",function (Id)
								{
								  mygrid_trades.showRow(Id);
								  var buy=mygrid_trades.cells(Id,0).getValue(); //
								  var sell=mygrid_trades.cells(Id,0).getValue(); // mygrid_trades.getColIndexById('SELL_ORDER_NUMBER'))
								  var price=mygrid_trades.cells(Id,3).getValue(); //mygrid_trades.getColIndexById('PRICE'))
								  document.getElementById('price').value = price;
								  
								 trade_added_flag=1;					
								 });		 
			mygrid_trades.imgURL = images;
			//alert(cols);
			//mygrid_trades.setNumberFormat("000.00",mygrid_trades.getColIndexById('PRICE'));
//			mygrid_trades.setHeader("Trade number, Trade time, Buy order,Sell order, VOLUME, PRICE");
	menu = new dhtmlXMenuObject(null, "standard");
	menu.setImagePath(images);
	menu.setIconsPath(images);
	menu.renderAsContextMenu();
	menu.setOpenMode("web");
	menu.attachEvent("onClick",onLastTradesMenuClick);
	menu.loadXML("_last_trades_context.xml");
	
			mygrid_trades.setHeader(cols);
			mygrid_trades.setColumnIds(colIds);
			
			mygrid_trades.setInitWidths(ColSize);
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_trades.setColAlign(cols_align);
			mygrid_trades.setColTypes(types_cols);
			mygrid_trades.setColSorting(sort_cols);
			
			mygrid_trades.attachFooter("Total quantity,#cspan,-,<div id='nr_q'>0</div>,-,#cspan,#cspan,<div id='sr_q'>0</div>",["text-align:left;"])
			//mygrid_trades.attachFooter("<b>Total:</b>{#stat_count},#cspan,#cspan,#cspan,{#stat_total},#cspan,#cspan,{#stat_total}");
			
			//mygrid_trades.attachFooter("#cspan,#cspan,#cspan,<div id='tradespg'></div>,#cspan,#cspan,#cspan,#cspan");
						
			mygrid_trades.setSkin("modern");
	//		mygrid_trades.enableAlterCss("trade_even","trade_uneven");
			
			mygrid_trades.enableContextMenu(menu);
 			mygrid_trades.attachEvent("onBeforeContextMenu",lasttrades_pre_func);
 
			mygrid_trades.init();
			mygrid_trades.setMultiselect(true);
			//mygrid_orders.splitAt(0);
	//		mygrid_trades.enablePaging(true,30,null,"tradespg",true,"");

			mygrid_trades.enableSmartRendering(false);			
}


function onLastTradesMenuClick(menuitemId,type)
{
    var data = mygrid_trades.contextID.split("_"); //rowId_colInd
	if (menuitemId.split("_")[0]=='edit')
	    mygrid_trades.setRowTextStyle(data[0],"color:"+menuitemId.split("_")[1]);
	if (menuitemId.split("_")[0]=='info')
		onButtonClickLastTrades(data[0],menuitemId.split("_")[1]);
	return true;
	}

function lasttrades_pre_func(rowId,celInd,grid){
	    if (celInd==1) return false;
	    return true;
}
	
function onButtonClickLastTrades(rowId,type)
    {
	
	
	if (type=='Bid' || type=='Ask') {
			var trade_time=mygrid_trades.cells(rowId,0).getValue();
			var te = trade_time.split(':');
			trade_time='';
			for(var i=0; i<te.length; i++)
	   			 trade_time+=te[i];
			//trade_time+'\0';	
		    var bid_num=mygrid_trades.cells(rowId,7).getValue();
			var ask_num=mygrid_trades.cells(rowId,10).getValue();
			var trade_num=mygrid_trades.cells(rowId,2).getValue();
			createTradeOrdersWindow_bid(trade_time,trade_num,bid_num,"Buy Detail","order");
			createTradeOrdersWindow_bid(trade_time,trade_num,ask_num,"Sell Detail","order");
			}
		else if (type=='Trade') {
		    var trade_time=mygrid_trades.cells(rowId,0).getValue();
			var te = trade_time.split(':');
			trade_time='';
			for(var i=0; i<te.length; i++)
	   			 trade_time+=te[i];
			//trade_time+'\0';
		    var trade_num=mygrid_trades.cells(rowId,2).getValue();
			createTradeOrdersWindow_bid(trade_time,trade_num,trade_num,"Trade Detail","trade");
			}
		else if (type=='Notes') {
			var bid_num=mygrid_trades.cells(rowId,7).getValue();
			var ask_num=mygrid_trades.cells(rowId,10).getValue();
			var trade_num=mygrid_trades.cells(rowId,2).getValue();
		    editTradeNotes(trade_num,bid_num,ask_num,"Here put your Notes<br/><b>about...</b>");
			}
		
    return false;
    }

function createTradesWin()
{
 TradesWin = dhxPlayWins.createWindow("trades", 10, 110, 520, 350);
 TradesWin.setText("Current Trades");
 dhxPlayWins.window("trades").hide();
// dhxPlayWins.window("trades").attachObject("ordersId", true);
 wingrid_trades = dhxPlayWins.window("trades").attachGrid();
 dhxPlayWins.window("trades").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="trades") {
							  TradesWin='';
							  return true;
						  	}
						 });
						 
	menu = new dhtmlXMenuObject(null, "standard");
	menu.setImagePath(images);
	menu.setIconsPath(images);
	menu.renderAsContextMenu();
	menu.setOpenMode("web");
	menu.attachEvent("onClick",onTradesMenuClick);
	menu.loadXML("_trades_context.xml");
	
 wingrid_trades.setHeader(cols);
 wingrid_trades.setColumnIds(colIds);
			
 wingrid_trades.setInitWidths(ColSize);
			//mygrid_orders.setColumnMinWidth(10,0);
 wingrid_trades.setColAlign(cols_align);
 wingrid_trades.setColTypes(types_cols);
 wingrid_trades.setColSorting(sort_cols);

 wingrid_trades.attachHeader("#text_filter,#text_filter,#numeric_filter,#select_filter,<button type='button' style='width:90;' id='trref' name='trref' onclick='refreshTrades()'>Refresh</button>");	
 wingrid_trades.attachFooter("<b>Total:</b>{#stat_count},#cspan,#cspan,#cspan,{#stat_total},#cspan,#cspan,{#stat_total}");			
			//mygrid_trades.attachFooter("#cspan,#cspan,#cspan,<div id='tradespg'></div>,#cspan,#cspan,#cspan,#cspan");
						
 wingrid_trades.setSkin("modern");
 wingrid_trades.enableAlterCss("trade_even","trade_uneven");
			
 wingrid_trades.enableContextMenu(menu);
 wingrid_trades.attachEvent("onBeforeContextMenu",my_pre_func);
 
 wingrid_trades.init();
 wingrid_trades.setMultiselect(true);
			//mygrid_orders.splitAt(0);
	//		mygrid_trades.enablePaging(true,30,null,"tradespg",true,"");
// wingrid_trades.attachEvent("onRightClick",onButtonClickTrades);

 wingrid_trades.enableSmartRendering(true,20);
 wingrid_trades.loadXML(smartTrades+"?posStart=0&count=20&session="+ses);

}

function onTradesMenuClick(menuitemId,type)
{
    var data = wingrid_trades.contextID.split("_"); //rowId_colInd
	if (menuitemId.split("_")[0]=='edit')
	    wingrid_trades.setRowTextStyle(data[0],"color:"+menuitemId.split("_")[1]);
	
	if (menuitemId.split("_")[0]=='info')
		onButtonClickTrades(data[0],menuitemId.split("_")[1]);
	return true;
	}

function my_pre_func(rowId,celInd,grid){
	    if (celInd==1) return false;
	    return true;
}

	
function onButtonClickTrades(rowId,type)
    {     	
		if (type=='Bid' || type=='Ask') {
			var trade_time=wingrid_trades.cells(rowId,0).getValue();
			var te = trade_time.split(':');
			trade_time='';
			for(var i=0; i<te.length; i++)
		    	trade_time+=te[i];
			//trade_time+'\0';	
		    var bid_num=wingrid_trades.cells(rowId,7).getValue();
			var ask_num=wingrid_trades.cells(rowId,10).getValue();
			var trade_num=wingrid_trades.cells(rowId,2).getValue();
			createTradeOrdersWindow_bid(trade_time,trade_num,bid_num,"Buy Detail","order");
			createTradeOrdersWindow_bid(trade_time,trade_num,ask_num,"Sell Detail","order");
			}
		else if (type=='Trade') {
			var trade_time=wingrid_trades.cells(rowId,0).getValue();
			var te = trade_time.split(':');
			trade_time='';
			for(var i=0; i<te.length; i++)
	   			 trade_time+=te[i];
			//trade_time+'\0';
		    var trade_num=wingrid_trades.cells(rowId,2).getValue();
			createTradeOrdersWindow_bid(trade_time,trade_num,trade_num,"Trade Detail","trade");
			}
		else if (type=='Refresh') {
				refreshTrades();
			}
		else if (type=='Notes') {
			var bid_num=wingrid_trades.cells(rowId,7).getValue();
			var ask_num=wingrid_trades.cells(rowId,10).getValue();
			var trade_num=wingrid_trades.cells(rowId,2).getValue();
		    editTradeNotes(trade_num,bid_num,ask_num,"Here put your Nores<br/><b>about</b>");
			}
        return false;
    }

function refreshTrades()
{
 //wingrid_trades.clearAll(true);
 wingrid_trades.updateFromXML(smartTrades+"?posStart=0&count=20"+"&session="+ses,true);
 }

function saveTradeNotes(trade_num,bid_num,ask_num,text)
{
	var msg='task=saveNotes'+'&objId=IS'+'&trade='+trade_num+'&ask='+ask_num+'&bid='+bid_num+'&htmlText='+text;
	dhtmlxAjax.post(Gdispatch,msg,function(loader){eval(loader.xmlDoc.responseText);});
	//var loader = dhtmlxAjax.postSync(tradeOrders,msg);
	//eval_return(loader.xmlDoc.responseText);
}

function editTradeNotes(trade_num,bid_num,ask_num,content) 
{
  if (winNotes) {
     winNotes.show();
	 winNotes.bringToTop();
	 if (content)
	 	 editor.setContent(content);
	 }
	else 
		{
		  dhxWins = new dhtmlXWindows();
		  //dhxPlayWins.enableAutoViewport(false);
		  //dhxPlayWins.setViewport(350, 50, 400, 400);
		  //dhxPlayWins.attachViewportTo("winVP");
		  winNotes = dhxWins.createWindow("wNotes", (window.innerWidth/2)- 350, 50, 600, 390);
		  winNotes.setText("User Notes on item: "+trade_num);
		  winNotes.btns["close"]._doOnClick = function() {
						winNotes.hide();
						winNotes.setModal(false);
						}
				  
  		winNotes.setModal(true);
		winNotes.bringToTop();
  		editor = winNotes.attachEditor();
  		editor.init();
  		editor.tb.attachEvent("onClick",function(id)
					{
 			 		if(id=="sn") {
									winNotes.hide();
									winNotes.setModal(false);
									saveTradeNotes(trade_num,bid_num,ask_num,editor.getContent());
									}
 					});

  		editor.tb.addButton("sn", 0, "Save","save.gif","save_dis.gif"); //id, pos, text, imgEnabled, imgDisabled
	//	editor.setContent(custom_content);
 	  }
 }

function memberInfo(msgId,time,msg_num,id) 
{  	
//	dhxWins = new dhtmlXWindows();
   if ( !dhxPlayWins.window("wMemberInfo") ) {
	 winMemberInfo = dhxPlayWins.createWindow("wMemberInfo", (window.innerWidth/2)- 300, 70, 350, 310);
	 winMemberInfo.setText("CRM service - Trader: "+id);
	 winMemberInfo.attachEvent("onClose", function(win) 
			 					{
								 if (win.getId()=="wMemberInfo") {
									winMemberInfo.hide();
									}
								   return true;	
								  });
	 winMemberInfo.setModal(true);
	
	var menu = winMemberInfo.attachMenu();
    menu.setImagePath(images);
    menu.setIconsPath(images);
	menu.attachEvent("onClick",onMemberInfoMenuClick);
	menu.attachEvent("onCheckboxClick", onMemberInfoMenuCheck);
    menu.loadXML("memberinfo_menu.xml?" + new Date().getTime());

     mygrid_memberinfo = winMemberInfo.attachGrid();
	 }
	 winMemberInfo.show();
	 mygrid_memberinfo.loadXML(tradeOrders+'?task=getMemberInfo'+'&objId='+msgId+'&msg_num='+msg_num+'&time='+time+'&trader='+id+'&session='+ses);
	 winMemberInfo.bringToTop();

//	 var obj=document.getElementById("CRMback");
//	 winMemberInfo.appendObject(obj);
//	 winMemberInfo.attachHTMLString(CRMback);
 }

function onMemberInfoMenuClick(menuitemId)
{
alert(menuitemId);
}

function onMemberInfoMenuCheck(menuitemId, state, zoneId, casState)
{
alert(menuitemId+"=="+state);
            // user-defined handler
            // ctrl
            if (casState["ctrl"] == true) {
                    // ctrl key was pressed with click
            } else {
                    // ctrl key was not pressed with click
            }
            // alt
            if (casState["alt"] == true) {
                    // alt key was pressed with click
            } else {
                    // alt key was not pressed with click
            }
            // shift
            if (casState["shift"] == true) {
                    // shift key was pressed with click
            } else {
                    // shift key was not pressed with click
            }
            return true;
}
		
function afterParseTrades(Id,time,price)
{
	
	//var lastrow=currTradeId; //mygrid_trades.getRowsNum()-1;
	
	if (Id==null)
		return;

//	mygrid_trades.startFastOperations();
	//var Id = mygrid_trades.getRowId(lastrow);

	/*	
	var time=mygrid_trades.cells(Id,0).getValue();
	var buy=mygrid_trades.cells(Id,5).getValue(); //
	var sell=mygrid_trades.cells(Id,6).getValue(); // mygrid_trades.getColIndexById('SELL_ORDER_NUMBER'))
	var price=mygrid_trades.cells(Id,3).getValue(); //mygrid_trades.getColIndexById('PRICE'))
*/	
	document.getElementById('price').value = start_price + " => "+ price;
	document.getElementById('ttime').value = time;
	newImage = (price > prev_price) ?"../images/indices_up.gif" :((price < prev_price) ?"../images/indices_down.gif" :"../images/stable.gif");
	document['index_img'].src=newImage;
	//document.getElementById('index_img').src = newImage;
	prev_price=price;
	
	div = (parseFloat(price)/parseFloat(start_price))-1;
	num1 = Math.pow(10, 2);
	div = div*100;
	pct_price_var = Math.round(div*num1)/num1;
	document.getElementById('pct_var').value = pct_price_var;
	
/*	--- TEMP
	if (mygrid_bob.doesRowExist(buy))
								   	  {
								  		
										//mygrid_bob.deleteRow(buy);
										//mygrid_bob.sortRows(mygrid_sob.getColIndexById('PRICE'), 'int', 'asc');
										mygrid_bob.setRowTextStyle(buy,'background-color:#F19090;');
										mygrid_bob.showRow(buy);
										}
	if (mygrid_sob.doesRowExist(sell))
								   	  {
								  		
										//mygrid_bob.deleteRow(buy);
									//mygrid_bob.sortRows(mygrid_sob.getColIndexById('PRICE'), 'int', 'asc');
										mygrid_sob.setRowTextStyle(sell,'background-color:#F19090;');
										mygrid_sob.showRow(sell);
										}
*/
										
/*
	mygrid_bob.forEachRow(function(Id)
						{
						  if (mygrid_bob.cells(Id,0).getValue()==buy)	 
						   {									    
						    mygrid_bob.deleteRow(Id);
							return;
							}
						  })
	mygrid_sob.forEachRow(function(Id)
			           {
					    if (mygrid_sob.cells(Id,0).getValue()==sell)
						   {
						    mygrid_sob.deleteRow(Id);
							return;
							}
					     });
*/						 
    
	//mygrid_trades.stopFastOperations();						 	
 }


function createGridBUYOrderBook(){
			dhxPlayLayout.cells("b").setWidth(190);
			mygrid_bob = dhxPlayLayout.cells("b").attachGrid();
											  
			mygrid_bob.imgURL = images;
			//alert(cols);
			mygrid_bob.setHeader("Order,Volume,Price");
			
			mygrid_bob.setColumnIds("ORDER,VOLUME,PRICE");
			mygrid_bob.setInitWidths("65,60,45");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_bob.setColAlign("center,right,right");
			mygrid_bob.setColTypes("ro,ro,edn");
			mygrid_bob.setColSorting("na,na,na");
			mygrid_bob.setSkin("modern");
	
			mygrid_bob.setNumberFormat("000.00",mygrid_bob.getColIndexById('PRICE'),",",".");
			mygrid_bob.init();
			//mygrid_bob.setColumnHidden(0,true); 
			//mygrid_bob.enableSmartRendering(true);				
}

function createGridSELLOrderBook(){
			dhxPlayLayout.cells("c").setWidth(190);
			mygrid_sob = dhxPlayLayout.cells("c").attachGrid();
								  	 
			mygrid_sob.imgURL = images;
			//alert(cols);
			mygrid_sob.setHeader("Order,Volume,Price");
			
			mygrid_sob.setColumnIds("ORDER,VOLUME,PRICE");
			mygrid_sob.setInitWidths("65,60,45");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_sob.setColAlign("center,right,right");
			mygrid_sob.setColTypes("ro,ro,edn");
			mygrid_sob.setColSorting("na,na,na");
			mygrid_sob.setSkin("modern");
	
			mygrid_sob.setNumberFormat("000.00",mygrid_sob.getColIndexById('PRICE'));
			mygrid_sob.init();

			//mygrid_orders.splitAt(0);
			//mygrid_sob.enableSmartRendering(true);						
}

function createGridMarketDepth(){
//			dhxPlayLayoutB.cells("b").setWidth(170);
			//mygrid_mdb = dhxTabbarB.cells("mktdepth").attachGrid();
			//mygrid_mds = dhxTabbarB.cells("mktdepth").attachGrid();
			
			mygrid_mdb = new dhtmlXGridObject("tab_mktdepthB");
/*			mygrid_bmd.attachEvent("onRowAdded",function (Id)
									 {
									 
									 //alert(mygrid_sob.getRowsNum());
									  //if ( mygrid_sob.getRowsNum()>1)
									  orders_sorted_flag=0;
									  // mygrid_bmd.sortRows(mygrid_bmd.getColIndexById('PRICE'), 'int', 'des');
									  });	 
			mygrid_bmd.attachEvent("onAfterSorting",function ()
								  {  
								    orders_sorted_flag=1;
									  });
*/									  		 
			mygrid_mdb.imgURL = images;
			//alert(cols);
			mygrid_mdb.setHeader("det,Entries,Volume,Price");
			//mygrid_mdb.attachHeader(["#rspan","Volume","#rspan"]);
			mygrid_mdb.setSizes();
			mygrid_mdb.setColumnIds("A,ENTRIES,BID,PRICE");
			mygrid_mdb.setInitWidths("20,70,70,90");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_mdb.setColAlign("right,right,right,center");
			mygrid_mdb.setColTypes("sub_row_grid,ro,ro,edn");
			mygrid_mdb.setColSorting("na,na,na,na");
			mygrid_mdb.setSkin("gray");
			mygrid_mdb.enableAlterCss("buy_depth_even","buy_depth_uneven");
			
			//grid.setStyle(ss_header, ss_grid, ss_selCell, ss_selRow);
			mygrid_mdb.setStyle("background-color:#D5D3EB;color:navy; font-weight:bold;", "","color:red;", "");
			mygrid_mdb.init();
	
			mygrid_mds = new dhtmlXGridObject("tab_mktdepthS");					  		 
			mygrid_mds.imgURL = images;
			
			mygrid_mds.attachEvent("onSubGridLoaded",function(subgrid){
						subgrid.attachEvent("onRightClick",onClickSubBuyDepth);				 
						});
		//	mygrid_mds.cellById(rowId, cellInd).getSubGrid 
			
			mygrid_mds.setHeader("det,Price,Volume,Entries");
//			mygrid_mds.attachHeader(["#rspan","Volume","#rspan"]);
			
			mygrid_mds.setSizes();
			mygrid_mds.setColumnIds("A,PRICE,OFFER,ENTRIES");
			mygrid_mds.setInitWidths("20,70,70,90");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_mds.setColAlign("right,center,right,right");
			mygrid_mds.setColTypes("sub_row_grid,edn,ro,ro");
			mygrid_mds.setColSorting("na,na,na,na");
			mygrid_mds.setSkin("gray");
			mygrid_mds.enableAlterCss("sell_depth_even","sell_depth_uneven");
			mygrid_mds.setStyle("background-color:#D5D3EB;color:navy; font-weight:bold;", "","color:red;", "");

			mygrid_mds.init();	
			mygrid_mds.enableMultiselect(true);
}

function onClickSubBuyDepth(id,ind,obj)
{
 
 }

function createGridBUYMarketDepth(){
			dhxPlayLayoutB.cells("b").setWidth(170);
			mygrid_bmd = dhxPlayLayoutB.cells("b").attachGrid();
/*			mygrid_bmd.attachEvent("onRowAdded",function (Id)
									 {
									 
									 //alert(mygrid_sob.getRowsNum());
									  //if ( mygrid_sob.getRowsNum()>1)
									  orders_sorted_flag=0;
									  // mygrid_bmd.sortRows(mygrid_bmd.getColIndexById('PRICE'), 'int', 'des');
									  });	 
			mygrid_bmd.attachEvent("onAfterSorting",function ()
								  {  
								    orders_sorted_flag=1;
									  });
*/									  		 
			mygrid_bmd.imgURL = images;
			//alert(cols);
			mygrid_bmd.setHeader("Price,Volume,Entries");
			
			mygrid_bmd.setColumnIds("PRICE,VOLUME,ENTRIES");
			mygrid_bmd.setInitWidths("50,60,40");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_bmd.setColAlign("center,right,right");
			mygrid_bmd.setColTypes("edn,ro,ro");
			mygrid_bmd.setColSorting("na,na,na");
			mygrid_bmd.setSkin("modern");
//			mygrid_bmd.enableAlterCss("even","uneven");
			mygrid_bmd.init();
			mygrid_bmd.setNumberFormat("000.00",mygrid_bmd.getColIndexById('PRICE'));
		
}

function createGridSELLMarketDepth(){
			dhxPlayLayoutB.cells("c").setWidth(160);
			mygrid_smd = dhxPlayLayoutB.cells("c").attachGrid();
								  
			mygrid_smd.imgURL = images;
			//alert(cols);
			mygrid_smd.setHeader("Price,Volume,Entries");
			
			mygrid_smd.setColumnIds("PRICE,VOLUME,ENTRIES");
			mygrid_smd.setInitWidths("50,60,40");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_smd.setColAlign("center,right,right");
			mygrid_smd.setColTypes("edn,ro,ro");
			mygrid_smd.setColSorting("int,na,na");
			mygrid_smd.setSkin("modern");
	//		mygrid_smd.enableAlterCss("even","uneven");
			mygrid_smd.init();
			mygrid_smd.setNumberFormat("000.00",mygrid_smd.getColIndexById('PRICE'));			
}

function createGridEvents()
{
// DataTable constructor syntax
 var eventsDataSource = new YAHOO.util.DataSource({});   
 eventsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 eventsDataSource.responseSchema = {  fields: ["TIME","PHASE"]   };  
 // resultsList: "data",
 var eventsDef=[{key:"TIME", label:"Time", width:90},{key:"PHASE", label:"Event",width:145}];
 
 var myConfigs = {   
     scrollable:true,
	 height: "17em", 
	 renderLoopSize: 30, 
	 initialLoad:false  
 };  

 var obj = document.getElementById('div_events');
 dhxPlayLayout.cells("c").attachObject(obj);
 mygrid_events = new YAHOO.widget.ScrollingDataTable("div_events", eventsDef, eventsDataSource, myConfigs); //scrollable:true
 mygrid_events.subscribe("rowClickEvent", mygrid_events.onEventSelectRow);
 mygrid_events.subscribe("rowMouseoverEvent", mygrid_events.onEventHighlightRow);
 mygrid_events.subscribe("rowMouseoutEvent", mygrid_events.onEventUnhighlightRow);
 }

function createGridEvents_1(){
			//dhxPlayLayoutA.cells("c").setWidth(160);
			mygrid_events = dhxPlayLayout.cells("c").attachGrid();
			
			mygrid_events.attachEvent("onRowAdded",function (Id)
									 {
									   mygrid_events.showRow(Id);
									  });
								 
			mygrid_events.imgURL = images;
			//alert(cols);
			mygrid_events.setHeader("Time,Event");
			
			mygrid_events.setColumnIds("Time,Event");
			mygrid_events.setInitWidths("90,145");
			//mygrid_orders.setColumnMinWidth(10,0);
			mygrid_events.setColAlign("left,left");
			mygrid_events.setColTypes("ro,ro");
			mygrid_events.setColSorting("na,na");
			mygrid_events.setSkin("modern");
			mygrid_events.enableAlterCss("events_even","events_uneven");
			mygrid_events.init();			
}

/******************************************************************************************
									WINDOWS
******************************************************************************************/
function createConfigWindow()
{
 var dhxCnfgWins = dhxPlayWins.createWindow("wConfig", (window.innerWidth/2)- 300, 50, 600, 300);
 dhxCnfgWins.setText("Market Configuration");
 dhxPlayWins.window("wConfig").setModal(true);
	//ProgressUpdate();	// Initialize bar
 mygrid_config = dhxPlayWins.window("wConfig").attachGrid();
 var config_bar = dhxPlayWins.window("wConfig").attachToolbar();
 						  
 mygrid_config.attachEvent("onEditCell",function(stage,id,ind,newVal,oldVal)
 						  {						
	  					if (stage == 2   && ind == 1) {
						      var val;
							  var type = mygrid_config.cells(id,ind).cell._cellType; //||grid.getColType(ind); 
							  if (type=='combo'){
							  	   var combo=mygrid_config.cells(id,ind).getCellCombo();
								   val=combo.getSelectedValue();
								   }
								   else
								     val=newVal;
														      	   
							  if (id=='market')
							     market_name=combo.getSelectedText();
														   
							  var msg='task=updateConfig'+'&column='+id+"&value="+val;		
							  var loader=dhtmlxAjax.postSync(test1,msg);
							  eval(loader.xmlDoc.responseText);	
						        }
						    return true;
							});
							
 dhxPlayWins.window("wConfig").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="wConfig") {
						      webBar.enableItem('C1');
							  mode=mygrid_config.cells2(0,1).getValue(); 
							  //dbase=mygrid_config.cells(2,1).getValue(); 
							  filepath=mygrid_config.cells2(2,1).getValue(); 
							  fdate=mygrid_config.cells2(3,1).getValue(); 
							  market=mygrid_config.cells2(4,1).getValue(); 
							  //board=mygrid_config.cells(6,1).getValue(); 
							  
							  indexes=mygrid_config.cells2(6,1).getValue(); 
							  isin=mygrid_config.cells2(7,1).getValue();
							 // alert(filepath+"=="+isin);
							  vieworders=mygrid_config.cells2(8,1).getValue(); 
							  //init=mygrid_config.cells('init',1).getValue();

							  var msg='task=getSecurityInfo'+'&execWhenReturn=fillSecForm();';		
							  var loader=dhtmlxAjax.postSync(test1,msg);
							  eval(loader.xmlDoc.responseText);
		
							  mygrid_config.destructor();
							  if (vieworders >0 ) {
							  		if (mygrid_orders=='')
							      		createGridOrders();
									}
								  else
								    if (mygrid_orders!='')
								  	    mygrid_orders.destructor();	
							  }
						  return true;
						  }); 
 
 config_bar.setIconsPath(images);
 config_bar.addButton("saveConfig",1,"Save", "saveconfig.gif");
 config_bar.attachEvent("onClick", function(id, value)
			 					{
								if (id=="saveConfig")
									alert(id);
								  });		 
 mygrid_config.setImagePath(images);
 mygrid_config.setDateFormat("%Y-%m-%d");
 mygrid_config.loadXML(test1+"?task=config");

 mygrid_config.attachEvent("onCheck",function(id,col,val){
				 var msg='task=updateConfig'+'&column='+id+"&value="+val;		
				 var loader=dhtmlxAjax.postSync(test1,msg);
				 eval(loader.xmlDoc.responseText);	
				});	
}

function createMsgsWindow()
{
 var dhxMsgsWins = dhxPlayWins.createWindow("wMessages", (window.innerWidth/2)- 300, 50, 430, 450);
 dhxMsgsWins.setText("Messages Configuration");
 dhxPlayWins.window("wMessages").setModal(true);
	//ProgressUpdate();	// Initialize bar
 mygrid_msgs = dhxPlayWins.window("wMessages").attachGrid();
// var config_bar = dhxPlayWins.window("wMessages").attachToolbar();
 
 dhxPlayWins.window("wMessages").attachEvent("onClose", function(win) 
			 					{
								 if (win.getId()=="wMessages") {
									mygrid_msgs.destructor();
									}
								   return true;	
								  });

 mygrid_msgs.attachEvent("onCheck",function(id){
					  var type = mygrid_msgs.cells(id,0).getValue();	
				      var sel = mygrid_msgs.cells(id,2).getValue();
					  	
			  		  msg='task=setMsgHeader'+'&objId='+type+'&select='+sel;
					  var loader=dhtmlxAjax.postSync(test1,msg);
					  eval(loader.xmlDoc.responseText); 
					  });
  mygrid_msgs.imgURL = images;
  mygrid_msgs.setHeader("Type,Description,Select");
  mygrid_msgs.attachHeader("id,message,<input type='checkbox' onclick='msgClick(this);'>");
  mygrid_msgs.setColumnIds("Type,Description,Select");
  mygrid_msgs.setInitWidths("70,300,50");
  mygrid_msgs.setColumnMinWidth(10,0);
  mygrid_msgs.setColAlign("left,left,center");
  mygrid_msgs.setColTypes("ro,ro,ch");
  mygrid_msgs.setColSorting("na,na,na");
  mygrid_msgs.setSkin("xp");
  mygrid_msgs.enableAlterCss("even","uneven");
  mygrid_msgs.init();
  mygrid_msgs.loadXML(test1+"?task=getmessages");
 }

function msgClick(node)
{
 mygrid_msgs.forEachRow(function(id){
       mygrid_msgs.cells(id,2).setValue(node.checked?1:0);
    })
	msg='task=setMsgHeader'+'&objId=ALL'+'&select='+node.checked;
	var loader=dhtmlxAjax.postSync(test1,msg);
	eval(loader.xmlDoc.responseText); 
//alert(node.checked);
}

/******************************************************************************************/
function createMktResolutionWindow()
{
 //mygrid_resol = dhxPlayLayoutA.cells("a").attachGrid();
 mygrid_resol = new dhtmlXGridObject('tabfic');

 //dhxPlayLayoutA.cells("a").setText("Market Control Time Resolution");
mygrid_resol.attachEvent("onRowDblClicked",function(id,idx)
 						 {
						 var msg = mygrid_resol.cells(id,1).getValue();	
						 createMsgDetailWindow(id,msg.substr(0,2));
						 }); 
 mygrid_resol.attachEvent("onSelectStateChanged",function(ids)
 						 { 
						  //var ar = Array();
						 // ar='['+ids+']';
						  selection+=1;
						  if (selection==1)
						      startResol = ids;
						  if (selection==2) {
						      endResol = ids; 
							  selection=0;
							  }
						  //ar=[];	  
						// alert(selection+"=="+startResol+"=="+endResol+"=="+ids);	  
						   });
 
 		
 menu = new dhtmlXMenuObject(null,"clear_blue");

 menu.setImagePath(images);
 menu.setIconsPath(images);
 menu.renderAsContextMenu();
 menu.setOpenMode("web");
 menu.attachEvent("onClick",onButtonClick);
 //menu.addNewSibling(null, "start", "Start", false);
 //menu.addNewSibling(null, "Stop", "Stop", false);

//menu.enableDynamicLoading(test1);
 menu.loadXML("dyn_context.xml");
	 
  mygrid_resol.imgURL = images;
  mygrid_resol.setHeader("Time,Event,Info");
  mygrid_resol.setColumnIds("Time,Event,Info");
  mygrid_resol.setInitWidths("70,200,200");
  mygrid_resol.setColumnMinWidth(10,0);
  mygrid_resol.setColAlign("left,left,left");
  mygrid_resol.setColTypes("ro,ro,ro");
  mygrid_resol.setColSorting("na,na,na");
  mygrid_resol.setSkin("modern");
  mygrid_resol.enableAlterCss("even","uneven");
  mygrid_resol.enableContextMenu(menu);
  mygrid_resol.init();
//  mygrid_resol.attachEvent("onBeforeContextMenu",onShowMenu);
 // mygrid_resol.enableBlockSelection(true);
  mygrid_resol.enableMultiselect(true); 
  mygrid_resol.enableSmartRendering(true,50);
  
 // mygrid_resol.loadXML(mktResol+"?task=ctrl&fdate="+fdate+"&filepath="+filepath);
    	
  Meteor.joinChannel("ctrl",1);
  Meteor.mode = 'stream';
// Start streaming!
  Meteor.connect();
  
  msg='task=STARTctrl'+'&data='+'50'+ses_str;
  var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
  eval_return(loader.xmlDoc.responseText);
 }

function onShowMenu(rowId,celInd,grid)
{
 switch(celInd){
				case 0:
				case 1:
				case 2:
					menu.showItem("start");
					menu.showItem("stop");
  					break;    
				}
 return true;
 }

function onButtonClick(menuitemId)
{
  var data=mygrid_resol.contextID.split("_"); //rowInd_colInd

  var rId = data[0];
  var cInd = data[1];
 
  switch(menuitemId){
                    case "start":
                        startResol=rId;
                        break;
                    case "stop":
                        endResol=rId;
                        break;
					}
 }
 
 
function createMsgDetailWindow(id,msgId)
{
 var dhxCdetWins = dhxPlayWins.createWindow("wCtrlDetail", (window.innerWidth/2)- 300, 50, 600, 300);
 dhxCdetWins.setText("Message Details");
 dhxPlayWins.window("wCtrlDetail").setModal(true);
	//ProgressUpdate();	// Initialize bar
 mygrid_cdet = dhxPlayWins.window("wCtrlDetail").attachGrid();
 mygrid_cdet.setImagePath(images);
 mygrid_cdet.setDateFormat("%Y-%m-%d");
 
 mygrid_cdet.loadXML(mktResol+'?task=ctrlMsgDetail'+'&objId='+msgId+'&select='+id+'&fdate='+fdate+'&filepath='+filepath);
}

function resolTab(idn,ido)
{
 if (idn == 'sup' && (endResol && startResol)  && (endResol > startResol) )
 {
  var start=mygrid_resol.cells(startResol, 0).getValue();
  var endt=mygrid_resol.cells(endResol, 0).getValue();
  
  endResol=startResol=0;
  
  var execCode="initMktDataResolutionWindow();";
  msg='task=setDataResol'+'&start='+start+'&end='+endt+'&isin='+isin+
									   		'&execWhenReturn='+execCode;
  var loader=dhtmlxAjax.postSync(test1,msg);
  eval(loader.xmlDoc.responseText);
  }
  return true;
 }


function createMktDataResolutionWindow()
{
// var dhxResolWins = dhxPlayWins.createWindow("wResol", 30, 50, 500, 300);
// dhxResolWins.setText("Market Time Resolution");
// dhxPlayWins.window("wResol").setModal(true);
 //mygrid_resol = dhxPlayWins.window("wResol").attachGrid();
 mygrid_sup = new dhtmlXGridObject('tabsup');
  
  mygrid_sup.imgURL = images;
  mygrid_sup.setHeader("Time,Event,Info");
  mygrid_sup.setColumnIds("Time,Event,Info");
  mygrid_sup.setInitWidths("70,200,200");
  mygrid_sup.setColumnMinWidth(10,0);
  mygrid_sup.setColAlign("left,left,left");
  mygrid_sup.setColTypes("ro,ro,ro");
  mygrid_sup.setColSorting("na,na,na");
  mygrid_sup.setSkin("modern");
  mygrid_sup.enableAlterCss("even","uneven");
  mygrid_sup.init();
  mygrid_sup.enableSmartRendering(true);
 }
 
 function initMktDataResolutionWindow()
 {
  mygrid_sup.clearAll(false);
  
  mygrid_sup.loadXML(mktResol+"?task=data&fdate="+fdate+"&filepath="+filepath);	
 }

function showOpenPrice()
{
	 winOpenprice = dhxPlayWins.createWindow("wOpenprice", (window.innerWidth/2)- 300, 50, 600, 830);
	 winOpenprice.setText("Open Price Details");

	 dhxPlayWins.window("wOpenprice").setModal(true);
	//ProgressUpdate();	// Initialize bar
     mygrid_openprice = dhxPlayWins.window("wOpenprice").attachGrid();
	 mygrid_openprice.loadXML(tradeOrders+'?task=openPrice&session='+ses);
}

function createTradeOrdersWindow_bid(ttime,trade_no,bid,ask,tab_title,type)
{
 if (typeof(tradeWins[trade_no]) == "undefined" || tradeWins[trade_no]=='') {
 	 wintabsCntr[trade_no]=-1;
//	 alert("new win"+tradeWins.indexOf(trade_no));
	 tradeWins[trade_no] = dhxPlayWins.createWindow("wTrade"+trade_no, (window.innerWidth/2)- 300, 50, 600, 830);
	 tradeWins[trade_no].setText("Trade:"+trade_no+" Details");
	 }
	
// dhxPlayWins.window("wTradeOrders").setModal(true);
	//ProgressUpdate();	// Initialize bar
	 if(typeof(tabs[trade_no]) == "undefined" || tabs[trade_no]=='')
	 	tabs[trade_no]=tradeWins[trade_no].attachTabbar("tab_"+trade_no);
// 	 tab_trade = dhxTradeOrdersWins.attachTabbar("tab_trade");
 	 tabs[trade_no].setStyle("modern"); 
//	 tab_trade.setSkinColors("#D3E2E5","#D3E2E5");
 	 tabs[trade_no].setAlign("left");
	 tabs[trade_no].setImagePath(images); 
	 
	 wintabsCntr[trade_no]+=1;
	 var divid='A'+trade_no+wintabsCntr[trade_no];
	 //addElement(trade_no+wintabsCntr[trade_no]);
	 
	 document.getElementById('trade_tabs').innerHTML ='<div id="'+divid+'" style="padding-left:10px; width:100%;height:750px;overflow:hidden;"></div>';
		 
	 tabs[trade_no].addTab(bid,tab_title,100,wintabsCntr[trade_no],0);
	 tabs[trade_no].setOnSelectHandler(HtradeTab);
	 tabs[trade_no].setContent(bid,divid);
	 	 
 tabgrids[wintabsCntr[trade_no]] = new dhtmlXGridObject(divid);
 tabgrids[wintabsCntr[trade_no]].setImagePath(images);
 tabgrids[wintabsCntr[trade_no]].setDateFormat("%Y-%m-%d");
 
 tabgrids[wintabsCntr[trade_no]].attachEvent("onEditCell",function(stage,id,ind,newVal,oldVal)
 						  {						
	  					   if (stage == 2   && ind == 1) {
						      var val;
							  var type = tabgrids[wintabsCntr[trade_no]].cells(id,ind).cell._cellType; //||grid.getColType(ind); 
							  if (type=='combo'){
							  	   var combo=tabgrids[wintabsCntr[trade_no]].cells(id,ind).getCellCombo();
								   val=combo.getSelectedValue();
								   }
								   else
								     val=newVal;
							
							  wintabsCntr[trade_no]+=1;
							  //addElement(val+wintabsCntr[trade_no]);
							  var tradediv='A'+val+wintabsCntr[trade_no];
							  
							  document.getElementById('trade_tabs').innerHTML ='<div id="'+tradediv+'" style="padding-left:10px; width:100%;height:750px;overflow:hidden;"></div>';
							  tabgrids[wintabsCntr[trade_no]] = new dhtmlXGridObject(tradediv);
							  tabs[trade_no].addTab(val,"Trade:"+val,100,wintabsCntr[trade_no],0);
							  tabs[trade_no].setContent(val,tradediv);
							  tabgrids[wintabsCntr[trade_no]].loadXML(tradeOrders+'?task=tradeOrders'+'&time='+ttime+'&trade='+val+'&session='+ses);	
							  tabs[trade_no].setTabActive(val,true);					      	   
						      }
						    return true;
							});
 
 dhxPlayWins.window("wTrade"+trade_no).attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="wTrade"+trade_no) {
						  	  delete tradeWins[trade_no];
							  delete tabs[trade_no];
							  for(var i=0; i<wintabsCntr[trade_no]; i++)
							       delete tabgrids[i];
							  
							  delete wintabsCntr[trade_no];
							 
							  //tabgrids[bid].destructor();
							  }
						  return true;
						  }); 
				
 msg=tradeOrders+'?task=tradeOrders'+'&'+type+'='+bid+'&ask='+ask+'&time='+ttime+'&session='+ses;	
 //alert(msg);	  							
 tabgrids[wintabsCntr[trade_no]].loadXML(msg);

 tabs[trade_no].setTabActive(bid,true);
}

function removeItem(originalArray, itemToRemove) {
var j = 0;
while (j < originalArray.length) {
// alert(originalArray[j]);
if (originalArray[j] == itemToRemove) {
//originalArray[j] = undefined;
originalArray.splice(j, 1);
} else { j++; }
}
// assert('hi');
return originalArray;
}

function addElement(divIdName) {
  var b = document.body;
  
  var newdiv = document.createElement('div');
  newdiv.setAttribute('id',divIdName);
  newdiv.setAttribute('name',divIdName);
  //newdiv.setAttribute('style.padding-left='10px';
  newdiv.style.width='100%';
  newdiv.style.height='750px';
  newdiv.style.overflow='hidden';

  b.appendChild(newdiv);
}

function removeElement(divNum) {
alert("removeElement = "+divNum+" !");
	var b = document.body;
	var el=document.getElementById(divNum);
	if(el)
	   b.removeChild(el);
}

function setCustomPeriod() 
{
  if (winCustomPeriod) {
     winCustomPeriod.show();
	 winCustomPeriod.bringToTop();
	 }
	else 
		{
		  winCustomPeriod = dhxPlayWins.createWindow("wCustomPeriod", (window.innerWidth/2)- 300, 50, 600, 420);
		  winCustomPeriod.setText("Custom Period");
		  winCustomPeriod.btns["close"]._doOnClick = function() {
		  				document.getElementById("customPeriod").className = 'hide';
						winCustomPeriod.hide();
						}
/*
  dhxPlayWins.window("wCustomPeriod").attachEvent("onClose", function(win) 
 						{ 
						  if (win.getId()=="wCustomPeriod") {
						      _time=document.getElementById('st').value;
							  ar=_time.split(":");
							  custom_start=ar[0]+ar[1]+ar[2]+ar[3];
							  
							  _time=document.getElementById('et').value;
							  ar=_time.split(":");
							  custom_end=ar[0]+ar[1]+ar[2]+ar[3];
						  	  delete start_slider;
							  delete stop_slider;
							  }
						  return true;
						  }); 
*/						  
  dhxPlayWins.window("wCustomPeriod").setModal(true);
	//ProgressUpdate();	// Initialize bar
  document.getElementById("customPeriod").className = 'showCustomPeriodform';
  var obj = document.getElementById('customPeriod');
  if (obj) {
	dhxPlayWins.window("wCustomPeriod").attachObject(obj);
	var divobj = document.getElementById('slider_start');
	start_slider = new dhtmlxSlider(divobj, 300, 'simplesilver', false, 0, 300, 0, 1);
	start_slider.setImagePath(images);

	start_slider.attachEvent("onChange",function(newValue,sliderObj){
						if (newValue >= 30) {
							var hh= parseInt(newValue/30)+8;
							var mm=(newValue%30)*2;
							hh=(hh<10) ?"0"+hh :hh;
							mm=(mm<10) ?"0"+mm :mm;
							document.getElementById('st').value = hh+":"+mm+":00:00";
							}
							else {
							      var mm=newValue*2;
								  mm=(mm<10) ?"0"+mm :mm; 
							      document.getElementById('st').value = "08:"+mm+":00:00";
								  }
						   if (stop_slider.getValue() < newValue)
						       stop_slider.setValue(newValue);
						});  
     start_slider.init();
	  
	var divobj = document.getElementById('slider_stop');
	stop_slider = new dhtmlxSlider(divobj, 300, 'simplesilver', false, 0, 300, 0, 1);
	stop_slider.setImagePath(images);

	stop_slider.attachEvent("onChange",function(newValue,sliderObj){
						if (newValue >= 30) {
							var hh= parseInt(newValue/30)+8;
							var mm=(newValue%30)*2;
							hh=(hh<10) ?"0"+hh :hh;
							mm=(mm<10) ?"0"+mm :mm;
							document.getElementById('et').value = hh+":"+mm+":00:00";
							}
							else {
							      var mm=newValue*2;
								  mm=(mm<10) ?"0"+mm :mm; 
							      document.getElementById('et').value = "08:"+mm+":00:00";
								  }
						if (start_slider.getValue() > newValue)
						       start_slider.setValue(newValue);
						});  
      stop_slider.init();
	  editor = new dhtmlXEditor("editorObj","standard");
      editor.setIconsPath(images);
	  
	  
      editor.init();
	  editor.tb.forEachItem(function(id){
  	  			editor.tb.removeItem(id);
	  			});
	 // editor.tb.attachEvent("onClick",function(id)
	 // 						{
 	//					 		if(id=="cp") showColorPicker();
 	//						});
	  //editor.tb.addButton("cp", 0, "Test Button 1");
	  editor.setContent(custom_content);
	  	  
	  }
	}
  //document.getElementById("customPeriod").className = 'showCustomPeriodform';
 }

function showColorPicker() 		
{
}

function startCustomPeriod()
{
	_time=document.getElementById('st').value;
	ar=_time.split(":");
	custom_start=ar[0]+ar[1]+ar[2]+ar[3];
							  
	_time=document.getElementById('et').value;
	ar=_time.split(":");
	custom_end=ar[0]+ar[1]+ar[2]+ar[3];
	dhxPlayWins.window("wCustomPeriod").setModal(false);
						
	if (signal=="RUN") 
		marketPause();
						
	//webBar.setItemText('START','Run');
//	webBar.setItemImage('START', 'playtrade.png');

	clearGrids();
						
	market_steps=0;
	mkt_slider.setValue(market_steps);
						
	webBar.setValue("S1", 1);
	delay=webBar.getValue("S1")*10000;
	msg='task=set_delay'+"&data="+delay+ses_str;
	var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	eval_return(loader.xmlDoc.responseText);
						
	xOrder=0;
	xTrade=0;
	total_trades_val=0;
	msg='task=custom_period'+"&data="+custom_start+":"+custom_end+ses_str;
	var loader = dhtmlxAjax.postSync(Serv1_dispatcher,msg);
	eval_return(loader.xmlDoc.responseText);
	sub_signal="CUSTOM_FWRD";
	
	marketRun();
	winCustomPeriod.hide();
}
				
/******************************************************************************************
				Handlers
*******************************************************************************************/				
function HresolTab(idn,ido)
{
 if (idn == 'sup' && (endResol && startResol)  && (endResol > startResol) )
 {
  var start=mygrid_resol.cells(startResol, 0).getValue();
  var endt=mygrid_resol.cells(endResol, 0).getValue();
  
  endResol=startResol=0;
  
  var execCode="initMktDataResolutionWindow();";
  msg='task=setDataResol'+'&start='+start+'&end='+endt+'&isin='+isin+
									   		'&execWhenReturn='+execCode;
  var loader=dhtmlxAjax.postSync(test1,msg);
  eval(loader.xmlDoc.responseText);
  }
  return true;
 }	

function HtradeTab(idn,ido)
{
 return true;
 }
  
function fillDate()
{

webBar.setValue('i1',fdate);

if (file_exists==true) {

	 webBar.showItem("START");
	 webBar.showItem("STOP"); 

	 if (signal!="") {  // ctrlThread is running and info tables are ready!
	 
	//	dhxPlayWins.setSkin("aqua_orange");
		
		execCode="fillSecForm();";						
		msg='task=getSecurityInfo'+'&execWhenReturn='+execCode;		
		var loader=dhtmlxAjax.postSync(test1,msg);
		eval(loader.xmlDoc.responseText);
		}
	}
	else {
	  webBar.hideItem("START");
	  webBar.hideItem("STOP");
	  }
}

function fillSecForm()
{
	document.getElementById('source').value = (mode==0 ?"Log File" :"Database");
	document.getElementById('board').value = board;
	document.getElementById('market').value = market_name;
	document.getElementById('symbol').value = hsym + " - " + esym;
	document.getElementById('isin').value = isin;

	var selectbox = document.forms.SecInfoForm.elements.isin;
	for(var i=selectbox.options.length-1;i>=0;i--)
	{
	selectbox.remove(i);
	}
	var temp = new Array(); 
	temp = isin.split(',');
//	alert(isin);
	for(i=0;i<temp.length;i++)
	    selectbox.options[selectbox.options.length] = new Option(temp[i], temp[i]); //, true, true
}

</script>

</body>

</html>