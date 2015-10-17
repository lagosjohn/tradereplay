<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>PlayThread Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-Control" content="no-cache" />

<script type="text/javascript"> 
window.onbeforeunload = function (evt) { 
  var message = 'Are you sure you want to leave?'; 
  if (typeof evt == 'undefined') { 
    evt = window.event; 
  } 
  if (evt) { 
    evt.returnValue = message;
	systemReset();  
  } 
  return message; 
}
</script> 

<!--CSS file (default YUI Sam Skin) -->
<!--
<link rel="stylesheet" type="text/css" href="yui/build/reset/reset-min.css" />
<link type="text/css" rel="stylesheet" href="yui/build/assets/skins/sam/skin.css">
<link rel="stylesheet" type="text/css" href="yui/build/reset-fonts-grids/reset-fonts-grids.css" />
<link rel="stylesheet" type="text/css" href="yui/build/resize/assets/skins/sam/resize.css" />
<link rel="stylesheet" type="text/css" href="yui/build/layout/assets/skins/sam/layout.css" />
<link rel="stylesheet" type="text/css" href="yui/build/container/assets/container-core.css" />
<link rel="stylesheet" type="text/css" href="yui/build/menu/assets/skins/sam/menu.css" />
<link rel="stylesheet" type="text/css" href="yui/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="yui/build/slider/assets/skins/sam/slider.css" />
<link rel="stylesheet" type="text/css" href="yui/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="yui/build/calendar/assets/skins/sam/calendar.css" />
<link rel="stylesheet" type="text/css" href="codebase/build/reset-fonts-grids/reset-fonts-grids.css">
<link rel="stylesheet" type="text/css" href="codebase/build/grids/grids-min.css">-->
<link rel="stylesheet" type="text/css" href="codebase/marketplay.css">
<link rel="stylesheet" type="text/css" href="tradereplay.css">
<style>

</style>

<style>
        #toolbar {
            border: 1px solid black;
            margin: .5em 0;
            padding: .25em .1em;
            background: url(yui/build/assets/skins/sam/sprite.png) repeat-x scroll 0 0;
        }

        #button_reply .first-child {
            background:url(button1.gif) center center no-repeat;
			
        }
		#button_pause  .first-child {
            background:url(yui/images/Pause.png) center center no-repeat;
			padding-right: 2.5em;
        }
		
		#button_play  .first-child {
            background:url(yui/images/Play.png) center center no-repeat;
			padding-right: 2.5em;
        }
		#button_start  .first-child {
            background:url(yui/images/Start.png) center center no-repeat;
			padding-right: 2.5em;
        }
		
		#button_open .first-child {
            background:url(yui/images/Open.png) center center no-repeat;
			padding-right: 2.5em;
        }
		#button_stop .first-child {
            background:url(yui/images/Stop.png) center center no-repeat;	
        }
		
        #button_forward .first-child {
            background:url(yui/images/Forward.png) center center no-repeat;
        }

        #button_back .first-child {
            background:url(yui/images/Back.png) center center no-repeat;
			
        }

       
       #button_forward .first-child button,#button_back .first-child button {
            padding-left: 2.5em;
        }
        
		#button_reply .first-child button{
            padding-right: 3.5em;
        }
		
        #button_stop .first-child button, #button_start .first-child button, #button_forward .first-child button {
            padding-left: 2em;
        }

        #toolbar .yui-button, #toolbar .yui-button .first-child {
            border-color: transparent;
        }
        #toolbar .yui-button-hover, #toolbar .yui-button-hover .first-child {
            border-color: #808080;
        }
        #toolbar .yui-button {
            background: none;
        }
        #toolbar .yui-button-hover {
            background:transparent url(yui/build/assets/skins/sam/sprite.png) repeat-x scroll 0 -1300px;
        }

 #div_control{
 width:20px;
 height:100px;
 }
    
</style>

<style>
#dt-options {text-align:right;margin:1em 0;}
#dt-dlg {visibility:hidden;border:1px solid #808080;background-color:#E3E3E3;}
#dt-dlg .hd {font-weight:bold;padding:1em;background:none;background-color:#E3E3E3;border-bottom:0;}
#dt-dlg .ft {text-align:right;padding:.5em;background-color:#E3E3E3;}
#dt-dlg .bd {height:10em;margin:0 1em;overflow:auto;border:1px solid black;background-color:white;}
#dt-dlg .dt-dlg-pickercol {clear:both;padding:.5em 1em 3em;border-bottom:1px solid gray;}
#dt-dlg .dt-dlg-pickerkey {float:left;}
#dt-dlg .dt-dlg-pickerbtns {float:right;}
#dt-dlg-trades {visibility:hidden;border:1px solid #808080;background-color:#E3E3E3;}
#dt-dlg-trades .hd {font-weight:bold;padding:1em;background:none;background-color:#E3E3E3;border-bottom:0;}
#dt-dlg-trades .ft {text-align:right;padding:.5em;background-color:#E3E3E3;}
#dt-dlg-trades .bd {height:10em;margin:0 1em;overflow:auto;border:1px solid black;background-color:white;}
#dt-dlg-trades .dt-dlg-pickercol-trades {clear:both;padding:.5em 1em 3em;border-bottom:1px solid gray;}
#dt-dlg-trades .dt-dlg-pickerkey-trades {float:left;}
#dt-dlg-trades .dt-dlg-pickerbtns-trades {float:right;}
#wi_n {visibility:hidden;border:1px solid #808080;background-color:#E3E3E3;}
#wi_n .hd {font-weight:bold;padding:1em;background:none;background-color:#E3E3E3;border-bottom:0;}
#wi_n .ft {text-align:right;padding:.5em;background-color:#E3E3E3;}
#wi_n .bd {height:10em;margin:0 1em;overflow:auto;border:1px solid black;background-color:white;}
#win .pickercol {clear:both;padding:.5em 1em 3em;border-bottom:1px solid gray;}
#win .pickerkey {float:left;}
#win .pickerbtns {float:right;}

/* Container workarounds for Mac Gecko scrollbar issues */
.yui-panel-container.hide-scrollbars #dt-dlg .bd {
    /* Hide scrollbars by default for Gecko on OS X */
    overflow: hidden;
}
.yui-panel-container.show-scrollbars #dt-dlg .bd {
    /* Show scrollbars for Gecko on OS X when the Panel is visible  */
    overflow: auto;
}
#dt-dlg_c .underlay {overflow:hidden;}


/* rounded corners */
#dt-dlg-trades #dt-dlg .corner_tr {
    background-image: url( yui/examples/datatable/assets/img/tr.gif);
    position: absolute;
    background-repeat: no-repeat;
    top: -1px;
    right: -1px;
    height: 4px;
    width: 4px;
}
#dt-dlg-trades #dt-dlg .corner_tl {
    background-image: url( yui/examples/datatable/assets/img/tl.gif);
    background-repeat: no-repeat;
    position: absolute;
    top: -1px;
    left: -1px;
    height: 4px;
    width: 4px;
}
#dt-dlg-trades #dt-dlg .corner_br {
    background-image: url( yui/examples/datatable/assets/img/br.gif);
    position: absolute;
    background-repeat: no-repeat;
    bottom: -1px;
    right: -1px;
    height: 4px;
    width: 4px;
}
#dt-dlg-trades #dt-dlg .corner_bl {
    background-image: url( yui/examples/datatable/assets/img/bl.gif);
    background-repeat: no-repeat;
    position: absolute;
    bottom: -1px;
    left: -1px;
    height: 4px;
    width: 4px;
}

.inprogress {position:absolute;} /* transitional progressive enhancement state */

.yui-dt-liner {white-space:nowrap;}

#paginated {
    text-align: center;
}
#paginated table {
    margin-left:auto; margin-right:auto;
}
#paginated, #paginated .yui-dt-loading {
    text-align: center; background-color: transparent;
}
.yui-skin-sam .yui-layout {    background-color:#CCCCCC;}
.yui-skin-sam .yui-dt-even { background-color:#FfF0F0; } .yui-skin-sam .tin-dt-odd { background-color:#FCFCFC; }
.yui-skin-sam1 tr.yui-dt-even, .tin-dt-even {background-color:#0fF0F0; color:#CC0000}

#div_events tr.yui-dt-even { background-color:#434E5A; color:#FFFF00} 
#div_events tr.yui-dt-odd { background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0; color:#FFFF00}
#div_events th#yui-dt0-fixedth-TIME,th#yui-dt0-fixedth-PHASE{font:Georgia;font-weight:bold; font-size:11px; Color:#E7E8E8; background:#D8D8DA url(yui/examples/datatable/assets/img/evheader.gif) repeat-x 0 0; }
#div_events td.yui-dt-col-PHASE { Color:#F0F0F0;}
#div_events tr.yui-dt-selected { background-color:#8393A1}

#div_mktdepth_bidask tr.yui-dt-even { font:Georgia; font-weight:bold;background-color:#434E5A; color:#F0F0F0} 
#div_mktdepth_bidask tr.yui-dt-odd { background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0;color:#F0F0F0}
#div_mktdepth_bidask th#yui-dt3-fixedth-ENTRIES, th#yui-dt3-fixedth-BID,th#yui-dt3-fixedth-PRICE {font:Georgia;font-weight:bold; font-size:11px;Color:#66DD66; background:#D8D8DA url(yui/examples/datatable/assets/img/evheader.gif) repeat-x 0 0; }
#div_mktdepth_bidask th#yui-dt3-fixedth-SPRICE,th#yui-dt3-fixedth-ASK,th#yui-dt3-fixedth-SENTRIES {font:Georgia;font-weight:bold; font-size:11px;Color:#DD6666; background:#D8D8DA url(yui/examples/datatable/assets/img/evheader.gif) repeat-x 0 0; }

#div_MdBidTraders tr.yui-dt-even { font:Georgia;font-weight:bold;background-color:#434E5A; color:#F0F0F0} 
#div_MdBidTraders tr.yui-dt-odd { font:Georgia;font-weight:bold;background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0;color:#F0F0F0}
#div_MdBidTraders th#yui-dt3-fixedth-OASIS_TIME, th#yui-dt3-fixedth-TRADER, th#yui-dt3-fixedth-ORDER_ID, th#yui-dt3-fixedth-ORDER_NUMBER,th#yui-dt3-fixedth-PRICE,th#yui-dt3-fixedth-VOLUME,th#yui-dt3-fixedth-UNMATCHED {font:Georgia;font-weight:bold; font-size:11px; Color:#d0d0d0; background:#D8D8DA url(yui/examples/datatable/assets/img/evheader.gif) repeat-x 0 0; }

#div_MdAskTraders tr.yui-dt-even { font:Georgia; font-weight:bold;background-color:#434E5A; color:#F0F0F0} 
#div_MdAskTraders tr.yui-dt-odd { font:Georgia;font-weight:bold;background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0;color:#F0F0F0}
#div_MdAskTraders th#yui-dt4-fixedth-OASIS_TIME, th#yui-dt4-fixedth-TRADER, th#yui-dt4-fixedth-ORDER_ID, th#yui-dt4-fixedth-ORDER_NUMBER,th#yui-dt4-fixedth-PRICE,th#yui-dt4-fixedth-VOLUME,th#yui-dt4-fixedth-UNMATCHED {font:Georgia;font-weight:bold; font-size:11px;Color:#d0d0d0; background:#D8D8DA url(yui/examples/datatable/assets/img/evheader.gif) repeat-x 0 0; }

.yui-dt-scrollable .yui-dt-bd { 
       overflow-y:scroll; /*for vertical Scroll*/
       __overflow-x:scroll; /*for horizontalScroll*/
}

	
 
/*.yui-skin-sam .yui-dt td.align-right{text-align:right;}*/
</style>

<script type="text/javascript" src="http://comet-imarket2.helex.gr/meteor2.js?v=5"></script>
<script type="text/javascript" src="yui/build/yahoo/yahoo-min.js" charset="utf-8"></script>

<!--script type="text/javascript" src="http://comet-imarket2.helex.gr/meteor.js?v=5"></script>-->
<script type="text/javascript" src="yui/build/yuiloader/yuiloader-min.js"></script> 

<script type="text/javascript" src="js/effects.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</head>

<BODY class="yui-skin-sam">

<div id="helpDocsTreeMenu" class="yui-panel">
<div class="hd">DOCiNav</div>
<div class="bd"></div>
<div class="ft">Esc to close</div>
</div>
<div id="TreeMenu"></div>

<div id="tradersTreeMenu" class="yui-panel">
<div class="hd">Session Traders Charts</div>
<div class="bd"></div>
<div class="ft">Esc to close</div>
</div>

<div id="datesTreeMenu" class="yui-panel">
<div class="hd">Traders Charts</div>
<div class="bd"></div>
<div class="ft">Esc to close</div>
</div>

<div id="div_progress_win"></div>
<div id="div_events"></div>
<div id="div_trades"></div>
<div id="div_tv_tv">
<div id="div_traders_tv" title="List of Securities and traders of Session"></div>
<div id="div_dates_tv" class="ygtv-checkbox" title="List of selected Dates out of this Session"></div>
</div>
<div id="div_traders_layout"></div>

<div id="div_traders_info">
 <div id="div_nodeInfo"></div>
 
 <div id="div_nodeChart1"><strong>Traders Graph chart area</strong></div>
 <div id="div_nodeChart2"><strong></strong></div>
 
 <div id="nodeChart2"  class="yui-panel">
 <div class="hd">
 <div style="float:left;"><span style="float:left">Intervals:&nbsp;</span><select name="binterval"  class="qdate_cr" id="binterval">
<option  value="0" selected >1hr</option>
<option value="30" >30min</option>
<option value="15">15min</option>
<option value="10">10min</option>
</select></div>
<div style="float:left;"><span style="float:left">Reference:&nbsp;</span><select name="breference" id="breference" class="qdate_cr" >
<option value="start">Start price</option><option selected value="rel">Relative price</option>
</select></div>
<div style="float:left;"><span style="float:left">Top items:&nbsp;</span><select name="btop"  class="qdate_cr" id="btop">
<option value="5" >Top 5</option>
<option  value="10" selected >Top 10</option>
<option value="15" >Top 15</option>
<option value="20">Top 20</option>
</select></div>
<div style="float:left;"><span style="float:left">Period:&nbsp;</span><select name="bperiod"  class="qdate_cr" id="bperiod">
<option value="C" >Cumulative</option>
<option  value="S" selected >Single</option>
</select></div>&nbsp;&nbsp;<button class="scButton_default" id="refresh_bubble" onClick="App.showBubbleChart([1,'B']);">Refresh</button>
 </div>
 <div class="bd">
 <div id="div_nodeChart3"></div>
 <div id="div_nodeChart4"></div>
 </div>
 </div>
</div>

<div id="div_uploader_win__" class="yui-panel">
<div class="hd">UPLiNav</div>
<div class="bd"></div>
<div id="dataTableContainer___"></div>
<div id="div_uploader" style="display:inline;">
		<div id="uploaderContainer__">
			<div id="uploaderOverlay" style="position:absolute; z-index:2"></div>
			<div id="selectFilesLink" style="z-index:1"><a id="selectLink" href="#">Select Files</a></div>
		</div>
		<button type='button' class='btn' id='uploadLink' onMouseOver="this.className='btn btnhov'" onMouseOut="this.className='btn'" onClick="App.upload(); return false;">Upload Files</button>		
</div>
</div>
</div>

<div id="div_uploader_win" class="yui-panel">
<div class="hd">UPLiNav</div>
<div class="bd"></div>
<div id="dataTableContainer"></div>
<div id="uploaderUI" style="width:100px;height:40px;margin-left:5px;float:left"></div>
<div class="uploadButton" style="float:left"><a class="rolloverButton" href="#" onClick="App.upload(); return false;"></a></div>
<div class="clearButton" style="float:left"><a class="rolloverButton" href="#" onClick="App.handleClearFiles(); return false;"></a></div>
</div>
</div>


<div id="div_control"><input type='button' size="15" id='hide' name='hide' onclick='showOpenPrice()' value='Hide'></div>

<div id="div_members">
<div class="buyers_hdr">Buyers</div><div class="sellers_hdr">Sellers</div>
<div id="div_buyers" class="buyers"><table id="mem_blist"><tr><td><ul id="mem_blist"></ul></td></tr></table></div>
<div id="div_sellers" class="sellers"><table><tr><td><ul id="mem_slist"></ul></td></tr></table></div>
</div>

<div id="div_mktdepth">
	<div id="div_mktdepth2" style="float:left;width:170px;height:140px;"></div>
	<div id="div_mktdepth3" style="float:right;width:170px;height:140px;"></div>
	<div id="div_mktdepth_bidask" style="float:left;width:370px;height:115px;"></div>
	<div id="div_mktdepth_bid_traders"  class="mktdepth_bid_traders"><ul id="mdfst_blist"></ul></div>
	<div id="div_mktdepth_ask_traders" class="mktdepth_ask_traders"><ul id="mdfst_slist"></ul></div>	
</div>

<div id="div_surv">
 <div id="div_bidsurv"></div>
 <div id="div_asksurv" style="float:left;width:370px;"></div>
</div>

<div id="div_docHTML_win" class="yui-panel">
<div class="hd">
<div class="tl"></div>
  <span>DOCiNav Document of: </span><span id="docHTMLtitle"></span><div class="tr"></div> 
</div>
<div class="bd">
<form id="htmlForm" name="htmlForm" method="post" action="#">
			
			<textarea id="docHtml" name="docHtml" ></textarea>
			<h2 class="yui-noedit"></h2>
						
	</form>		
</div>
<div class="ft"></div>
</div>

<div id="div_editor_win">
<div class="hd">
  <span>DOCiNav Editor</span>
</div>
<div class="bd">
	<form id="dialogForm" name="dialogForm" method="post" action="test1.php">
			
			<p>Title:&nbsp;<input name="doctitle" readonly id="doctitle" />&nbsp;&nbsp;Type:&nbsp;<span id="doctypename"></span> </p>
			<p>Description:</p>

			<textarea id="docEditor" name="docEditor" ></textarea>
			<div id="descriptionContainer"></div>
			<input id="objId"  name="objId"type="hidden" value=""/>
			<input id="docType"  name="type"type="hidden" value=""/>
			<input id="task" name="task" type="hidden" value="saveDocContent" />
			<p><input id="submitButton" type="submit" /></p>
	</form>					
</div>
</div>

			
<div id="bidask_flashcontent"></div>

<div id="div_mktdepth_graph"></div>
<div id="mda_flashcontent"></div>
<div id="mdb_flashcontent"></div>

<div id="DlgCalendar">
	<div id="dlgcal"></div>
</div>

<div id="q_dlgcal"></div>
<div id="q_dlgcal2"></div>

<div id="bidaskGlobal_win"  class="yui-panel">
<div class="hd">
  <span>Current Security Bid/Ask Graph chart</span>
</div>
<div class="bd">
<div id="bidaskGlobal_flashcontent" class="fixed"><strong>bid/ask Global Graph chart area</strong></div>
</div>
<div class="ft"></div>
</div>

<div id="bidaskCompare_win"  class="yui-cms-item yui-panel selected">
<div class="hd">
  <span>Session Securities Comparison Graph chart</span>
</div>
<div class="bd">
<div id="bidaskCompare_flashcontent" class="fixed"><strong>bid/ask Compare Graph chart area</strong></div>
</div>
<div class="ft"></div>
</div>

<div id="projected_win"  class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Projected Prices</span><div class="tr"></div>
</div>
<div class="bd">
<div id="projected_flashcontent"><strong>Auction projected Graph chart area</strong></div>
</div>
<div class="ft"></div>
</div>

<div id="buysell_win"  class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Security Buy/Sell - Market Phases</span><div class="tr"></div>
</div>
<div class="bd">
<div id="buysell_flashcontent"><strong>Security Buy/Sell Graph chart area</strong></div>
</div>
<div class="ft"></div>
</div>

<div id="md_traders_win" class="yui-cms-item yui-panel selected">
<div class="hd">
  <div class="tl"></div>
  <span>Market Depth Level Traders</span><div class="tr"></div>
</div>
<div class="bd">
<div class="fixed">Bid</div>
<div id="div_MdBidTraders"></div>
<div class="fixed">Ask</div>
<div id="div_MdAskTraders"></div>
</div>
<div class="ft"><a href="#" class="accordionToggleItem">collapse/expand</a></div>
</div>

<div id="div_mktdepth_graph"></div>

<div id="config_dialog"></div>
<div id="memberinfo_dialog"></div>
<div id="order_dialog"></div>
<div id="trade_dialog"></div>

<div id="openprice_win" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Analysis Precall Open Price</span><div class="tr"></div>
</div>
<div class="bd">
<div id="div_openprice"></div>
</div>
<div class="ft"><span id="openprice"></span></div>
<div id="paginated"></div>
</div>

<div id="smartTraders_win" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Bid/Ask Traders</span><div class="tr"></div>
</div>
<div class="bd">
<div id="div_smartTraders"></div>
<div id="div_both_traders_graph" style="height:240px;border:1px solid #990000">
<div id="div_traders_graph" style="float:left;height:240px;width:50%;"></div>
<div id="div_ask_traders_graph" style="float:right;height:240px;width:50%;"></div>
</div>
</div>
<div class="ft"><span id="smartTraders"></span></div>
<div id="smartTraders_pag"></div>
</div>

<div id="smartOraSecs_win" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Query Security</span><div class="tr"></div>
</div>
<div class="bd">
  <div id="div_smartOraSecs"></div>
</div>
<div class="ft">
<div style="float:left;">
<center>
<TABLE  hspace="3" align="center" border="1"  cellpadding="5" cellspacing="3" id="t_OraSecs">
<thead>
<th>From</th>
<th>Date to</th>
<th>User</th>
<th>Vol</th>
<th>Volume</th>
<th>Client</th>
<th>Phase</th>
<th>Halt</th>
<th>Price</th>
<th>Price2</th>
<th>Cond</th>
<th>Pct</th>
<th>Symbol</th>
</thead>
<TR>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
</TR>
</table>
</center>
</div>
<div id="smOraButtons"><span id="smartOraSecs">
</span></div></div>
<div id="smartTraders_pag"></div>
</div>
						
<div id="smartTrades_win" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Recall Trades</span><div class="tr"></div>
</div>
<div class="bd">
<div id="smartTradesFilterContainer">
					<b>Filter: </b>
					<input type="radio"  name="phase" value="All" checked>
					<input type="radio"  name="phase" value="(P)Auction" >
					<input type="radio"  name="phase" value="(T)Contin">
					<input type="radio"  name="phase" value="(C)Close">
					Buyer: <input type="text"  id="trg_buyer" size="5" name="phase" value="">
					Seller: <input type="text"  id="trg_seller" size="5" name="phase" value="">
 </div>
<div id="div_smartTrades"></div>
</div>
<div class="ft"><span id="smartTrades"><div id="smartTradespaginated"></div></span></div>
</div>

<div id="smartOrders_win" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Recall Orders</span><div class="tr"></div>
</div>

<div class="bd">
<div id="phaseRadioContainer">
					<b>Filter: </b>
					<input type="radio" id='All' name="tphase" value="All" checked>
					<input type="radio" id='P' name="tphase" value="(P)Auction" >
					<input type="radio" id='T' name="tphase" value="(T)Contin">
					<input type="radio" id='C' name="tphase" value="(C)Close">
					Trader: <input type="text"  id="trg_ord_trader" size="5" name="phase" value="">
 </div>
<div id="div_smartOrders"></div>
</div>

<div class="ft"><span id="smartOrders"><div id="smartOrderspaginated"></div></span></div>
</div>


<div id="top1">
    <div id="top_wrap">
        <div id="logo"></div>

        <div id="info">
        <strong>Tune Grids</strong>
        <span class="links"><a href="javascript:App.mygrid_trades.showDlg();">Trades</a>, <a href="javascript:App.mygrid_orders.showDlg();">Orders</a></span>		
        </div>
		
		<div id="sync">
		<strong>Sync Graph/Market</strong>
        <span class="links"><input type="radio"  name="sync" value="1" onClick="javascript:SetMarketToGraph();"> Sync zoom, <input type="radio"  name="sync" value="1" checked onClick="javascript:UnSetMarketToGraph();"> deSynced</span>
		</div>
		
	
	
        <div id="searchBox"><input type="text" size="20" id="query" value="Search the iMarket.."><input type="button" id="search" value="Search"></div>
<!--span class="links"><a href="http://imarket2.helex.gr/imarket/" target="_blank">iMarket</a> | <a href="http://imarket2.helex.gr/" target="_blank">TrReplay</a> | <a href="http://imarket2.helex.gr/" target="_blank">Tr Help</a></span-->
    </div>
</div>

<div id="dt-dlg" class="inprogress">
    <span class="corner_tr"></span>
    <span class="corner_tl"></span>
    <span class="corner_br"></span>
    <span class="corner_bl"></span>
    <div id="dt-dlg-hd" class="hd">
        Choose which columns of Orders you would like to see:    </div>
    <div id="dt-dlg-picker" class="bd">    </div>
</div>

<div id="dt-dlg-trades" class="inprogress">
    <span class="corner_tr"></span>
    <span class="corner_tl"></span>
    <span class="corner_br"></span>
    <span class="corner_bl"></span>
    <div id="dt-dlg-trades-hd" class="hd">
        Choose which columns of Orders you would like to see:    </div>
    <div id="dt-dlg-picker-trades" class="bd">    </div>
</div>

<div id="orderBook_win" class="yui-panel">
	<div class="hd">
	<div class="tl"></div>
		<h1>Order Book Contents</h1>
	<div class="tr"></div>
	</div>
	<div class="bd">
		<div id="OB_Container">
						
		</div>	
	</div>
	<div class="ft"><div id="OB_Paginator"></div></div>
</div>

<div id="QuerySec" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Search Security Dates</span><div class="tr"></div>
</div>
<div id="qinternal">
<div id="qBody" class="bd">
<div id="QuerySec_hd"><div id="qpage" onClick="App.toggleOraSecForm();"></div></div>
<FORM name="QuerySecForm" id="QuerySecForm">
<TABLE border=0 align="left" class="qform_tablestyle">
<TR align="left">
<TD colspan="2"><span class="qheader">Trade Date:</span></TD></TR>
<TR height="20px" valign="top" align="left">
<TD><div style="float:left;"><span class="qtext">Date</span><select name="qdatecond1"  class="qdate_cr" id="qdatecond1">
<option  value="<>" selected >Between</option>
<option value="=" >Equal</option>
<option value=">">Greater</option>
<option value=">=">GreaterEq</option>
</select><span class="qlabel3">From:</span><input type="text" size="8" name="qdatefrom" id="qdatefrom" value="" /><button class='qbtn' type='button' id='bt_qdatefrom'>&nbsp;&nbsp;</button></div>
<div style="float:left"><span class="qlabel3">To:</span><input type="text" size="8" name="qdateto" id="qdateto" value="" /><button class='qbtn' type='button' id='bt_qdateto' />  </button></div></TD></TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><select name="qsidetrader" id="qsidetrader" class="qdate_cr" ><option selected value="buy_trade_id">Buy Trader</option><option value="sell_trade_id">Sell Trader</option></select>&nbsp;
<input type="text" size="5" name="qtrader" id="qtrader" value="" /></div>  
<div style="width:auto;float:left"><span class="qlabel">Volume:</span><select name="qvolcond" id="qvolcond" class="qdate_cr" >
<option selected value="no">Volume</option>
<option value="=">Equal</option>
<option value=">">Greater</option>
<option value=">=">GreaterEq</option>
<option value="<">Less</option>
<option value="<=">LessEq</option>
</select></div>
<div style="float:left">&nbsp;<input type="text" size="5" name="qvolume" id="qvolume"  title="Volume in condition on the left..." value="0" /></div>
</TD></TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><select name="qsideinvestor" id="qsideinvestor" class="qdate_cr" ><option selected value="BUY_CSD_ACCOUNT_ID">Buy Investor</option><option value="SELL_CSD_ACCOUNT_ID">Sell Investor</option></select>&nbsp;
<input type="text" size="5" name="qinvestor" id="qinvestor" value="" /></div></TD></TR>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR height="20px" valign="top" align="left">
<TD><div style="float:left;"><span class="qlabel">Phase:</span><select name="qphase" id="qphase" class="qdate_cr">
<option  value="-" selected >All</option>
<option value="P" >Auction</option>
<option value="T">Continues</option>
<option value="C">Close</option>
</select></div></TD></TR>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR height="20px" valign="top" align="left">
<TD><div style="float:left;"><span class="qlabel">Suspend:</span><select name="qsuspended" id="qsuspended" class="qdate_cr">
<option  value="no" selected >No</option>
<option value="008" >Various</option>
<option value="001">Halted</option>
<option value="006">Technical</option>
<option value="012">Volatility</option>
</select></div></TD></TR>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR  align="left">
<TD><span class="qheader">Price:</span></TD></TR>
<TR align="left">
<TD><div style="vertical-align:middle; width:auto;float:left"><select name="qpricefield" id="qpricefield" class="qdate_cr">
<option selected value="no">Prices</option>
<option value="price">Trade</option>
<option value="PRICE_PRE_OPEN">Start</option>
<option value="PRICE_OPEN">Open</option>
<option value="PRICE_CLOSE">Close</option>
</select>&nbsp;<input type="text" size="5" name="qpricepct" id="qpricepct" value="0" /></div>
<div style="width:auto;float:left"><span class="qlabel">Condition:</span><select name="qpricecond" id="qpricecond" class="qdate_cr">
<option selected value="no">Prices</option>
<option value="=">Equal</option>
<option value=">">Greater</option>
<option value=">=">GreaterEq</option>
<option value="<">Less</option>
<option value="<=">LessEq</option>
</select></div>
<div style="float:left"><select name="qpricefield2" id="qpricefield2" class="qdate_cr">
<option selected value="no">Prices</option>
<option value="price">Trade</option>
<option value="PRICE_PRE_OPEN">Start</option>
<option value="PRICE_OPEN">Open</option>
<option value="PRICE_CLOSE">Close</option>
</select></div></TD></TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel">Security:</span><input type="text" size="5" name="qsymbol" id="qsymbol" value="0" /></div>
<select name="qsecsymbol" id="qsecsymbol" onchange="document.QuerySecForm.qsymbol.value = document.QuerySecForm.qsecsymbol.options[document.QuerySecForm.qsecsymbol.selectedIndex].value;document.QuerySecForm.qsecsymbol.value=''">
</select>
</TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel">Parameters</span><select name="qparams" id="qparams">
<option selected value="new">New</option>
</select></div><div style="width:auto;float:left"><button id="bt_qclearlist" class="qclearlist scButton_default">REM</button></div></TD>
</TR>
<TR align="left">
<TD><div style="width:auto;float:left"><span class="qlabel"></span>&nbsp;</div></TD>
</TR>
</TABLE>
</FORM>
</div>
</div>
<div class="ft"></div>
</div>

<div id="OrderEntry" class="yui-panel">
<div class="hd">
  <div class="tl"></div>
  <span>Order Entry</span><div class="tr"></div>
</div>
<div class="bd">
<FORM name="OrderEntryForm" id="OrderEntryForm">
<TABLE border=0  align="left" class="secform_tablestyle">
<TR  align="left">
<TD>Time:</TD><TD><input type="text" size="15" name="orderTime" id="orderTime" value="00:00:00:00" /></TD>
</TR>
<TR  align="left">
<TD>Market:</TD><TD><input type="text" size="15" name="orderMkt" id="orderMkt" value="" readonly /></TD>
</TR>
<TR  align="left">
<TD>Board:</TD><TD><input type="text" size="15" name="orderBrd" id="orderBrd" value="" readonly /></TD>
</TR>
<TR  align="left">
<TD>Security:</TD><TD><input type="text" size="15" name="orderSec" id="orderSec" value="" readonly />&nbsp;<span style="font-size:10px;color:#999999" id="orderisin"></span></TD><TD rowspan="4"><div id="athex_logo" style="background-image:red url(yui/images/ATHEXLogo1.jpg) no-repeat;"</div></TD>
</TR>
<TR  align="left">
<TD>Side:</TD><TD><select name="orderSide" id="orderSide">
<option value="B">Buy</option>
<option value="S">Sell</option>
</select></TD>
</TR>
<TR  align="left">
<TD>Price:</TD><TD><input type="text" size="15" name="orderPrice" id="orderPrice" value=""  />&nbsp;<span style="font-size:10px;color:#999999" id="orderopen"></span></TD>
</TR>
<TR  align="left">
<TD>Volume:</TD><TD><input type="text" size="15" name="orderVol" id="orderVol" value=""  /></TD>
</TR>
<TR  align="left">
<TD>Trader:</TD><TD><input type="text" size="15" name="orderTrader" id="orderTrader" value=""  /></TD>
</TR>
<TR  align="left">
<TD>Price Type:</TD><TD><select name="orderPrType" id="orderPrType">
<option value="L">Limit</option>
<option value="M">Market</option>
</select></TD>
</TR>
</TABLE>
</FORM>
</div>
<div class="ft"></div>
</div>

<div id="OrderSurv" class="yui-pe-content">
<div class="hd">
  <div class="tl"></div><span>Order Scenario</span><div class="tr"></div>
</div>
<div class="bd">
<FORM name="OrderSurvForm" width="450" id="OrderSurvForm">
<TABLE border=0  align="left" class="secform_tablestyle">
<TR  align="left">
<TD>Field:&nbsp;<select name="filt_field" id="filt_field"></select></TD>
<TD>Condition:&nbsp;<select name="filtcond" id="filtcond">
<option value="==">Equal</option>
<option value=">">Greater</option>
<option value=">=">GreaterEq</option>
<option value="<">Less</option>
<option value="<=">LessEq</option>
<option value="<=">LessEq</option>
</select><input type="hidden" name="condhidden" id="condhidden" value="" /></TD>
<TD>Value:&nbsp;<input type="text" size="15" name="sordnum" id="sordnum" value="" /><input type="hidden" name="sordfield" id="sordfield" value="" /></TD></TR>

<TR  align="left">
<TD>Filter:</TD>
<TD><input type="radio" name="scenario" value="filter" /></TD></TR>
<TR  align="left">
<TD>Pause:</TD>
<TD><input type="radio" name="scenario" value="pause" /></TD></TR>
</TABLE>
</FORM>
</div>
<div class="ft"></div>
</div>

<div id="TraderSurv" class="yui-pe-content">
<div class="hd">
  <div class="tl"></div><span>Traders Survailance</span><div class="tr"></div>
</div>
<div class="bd">
<FORM name="TraderSurvForm" id="TraderSurvForm">
<TABLE border=0  align="left" class="secform_tablestyle">
<TR align="left">
<TD >Top:&nbsp;<input type="text" size="15" name="top10" id="top10" value="10" /></TD>
<TD>Side:&nbsp;<select name="tradside" id="tradside">
<option value="B">Bid</option>
<option value="S">Ask</option>
</select></TD></TR>
<TR align="left">
<TD><button type='button' id='bt_traders' name='bt_traders' >Traders</button></TD>
</TR>
<TR>
<TD>Traders:&nbsp;<input type='text' name="traders_field" id="traders_field"></TD>
</TR>
</TABLE>
</FORM>
</div>
<div class="ft"></div>
</div>

<div id="SecInfo" class="yui-pe-content">
<div class="hd">
  <div class="tl"></div><span>NAViTRAD 1.0</span><div class="tr"></div>
</div>
<div class="bd">

<div id="SecInfoTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Trade info</em></a></li>
        <li><a href="#tab2"><em>Timespan</em></a></li>
        <li><a href="#tab3"><em>Traders</em></a></li>
		<li><a href="#tab4"><em>BookMarks</em></a></li>
    </ul>            

<div class="yui-content">
<div>

<FORM name="SecInfoForm" id="SecInfoForm">
<TABLE  border=0  align="left" class="secform_tablestyle" width=450>
<TR align="center"><td></td><td></td><td rowspan="10"><div id="vsliderbg" class="yui-v-slider" tabindex="-1"><div id="mspeed" class="mspeed"></div><div id="vsliderthumb" title='Indicator to Market clock. Moving Up Speedup<br> Moving Down: Slow speed' class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-e.gif"></div>
</div></td>
<TR  align="left">
<TD>Source:</TD>
<TD><span class="sf-text" id="source">&nbsp;</span><!--input type="text" size="15" name="source" id="source" title='Trading Source' value="" readonly /--></TD></TR>
<TR align="left">
<TD>Market:</TD>
<TD><span class="sf-text" id="market">&nbsp;</span><!--input type="text" size="45" name="market" id="market" value="" readonly /--></TD></TR>
<TR  align="left">
<TD>Board:</TD>
<TD><span class="sf-text" id="board">&nbsp;</span><!--input type="text" size="15" name="board" id="board" value="" readonly /--></TD></TR>
<TR  align="left">
<TD>Active Symbol:</TD>
<TD><span class="sf-text" id="symbol">&nbsp;</span><!--input type="text" size="15" name="symbol" id="symbol" value="" readonly /-->
&nbsp;&nbsp;
<select name="isin" title='Securities participating to this session' id="isin"></select>
&nbsp;&nbsp;<button type='button' title='View in detail all the prices participated to Open Price calculation' id='submit' name='submit' onclick='App.showOpenPrice()'>Method</button>

<!--input type="text" size="15" name="isin" id="isin" value="" readonly /!--></TD></TR>
<TR  align="left">
<TD><input type="text" size="8" name="otime" title='Last Order Time' class="otime" id="otime" value="00:00:00:00" readonly /><br /><input type="text" size="8" title='Last Trade Time' name="ttime" class="ttime" id="ttime" value="00:00:00:00" readonly /></TD>
<TD><div id="hsliderbg" class="yui-h-slider" tabindex="-1"><div id="hsliderthumb" title='Index to current Trading position' class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-s.gif"></div>
</div></TD></TR>
<TR  align="left">
<TD>Open:</TD>
<TD>
<TABLE><TR><TD><span class="sf-price" id="price"></span></TD><!--input type="text" size="10" title='Open Price' name="price" id="price" value="" readonly /-->
<TD><span class="sf-legent">&nbsp;%</span>
<span class="sf-price" id="pct_var"></span></TD><!--input type="text" size="4" name="pct_var" title='Price Variation' id="pct_var" value="" readonly /-->
<TD><span class="sf-legent">&nbsp;</span>
<img src="../images/stable.gif" border="0" NAME="index_img" id="index_img" /></TD>
<TD><span class="sf-legent">&nbsp;
Hi:</span>
<span class="sf-price" id="high"></span></TD><!--input type="text" size="3" name="lo" CLASS="hi" title='The Lowest Price during Trading' id="high" value="" readonly /-->
<TD><span class="sf-legent">&nbsp;&nbsp;Lo:</span>
<span class="sf-price" id="low"></span><!--input type="text" size="3" name="hi" CLASS="lo" title='The Hiest Price during Trading' id="low" value="" readonly /-->
</TD>
</TR></TABLE></TD>
</TR>
<div class="clear"></div>
<TR  align="left"><TD>Auction:</TD><TD><span class="sf-price" id="oprice"></span><!--input type="text" size="10" title='Auction price' name="oprice" id="oprice" value="" readonly /-->
<span class="sf-legent">&nbsp;&nbsp;</span>
<span class="sf-price" id="totalOrders"></span>  
<!--input type="text" size="2" name="totalOrders" title='Total Orders of this Trading day' id="totalOrders" value="" readonly /-->
<span class="sf-legent"><img src="css/images/next_rec.png" border="0" align="absbottom" NAME="next_img" /></span>
<span class="sf-price" id="next_order"></span> <!--input type="text" size="2" title='Current Order index of this Trading day' name="next_order" id="next_order" value="" readonly /-->
<span class="sf-legent">&nbsp;&nbsp;</span>
<span class="sf-price" id="totalTrades"></span><!--input type="text" size="2" name="totalTrades" title='Total Trades of this Trading day' id="totalTrades" value="" readonly /-->
<span class="sf-legent"><img src="css/images/next_rec.png" border="0" align="absbottom" NAME="next_img" /></span>
<span class="sf-price" id="next_trade"></span><!--input type="text" size="2" name="next_trade" title='Current Trade index of this Trading day' id="next_trade" value="" readonly /-->
</TD>
</TR>
<div class="clear"></div>
<TR  align="left">
<TD>Last Trade:</TD>
<TD><span class="sf-price" id="last"></span><!--input type="text" size="5" name="last" title='Price of the Last Trade' id="last" value="" readonly /-->
<span class="sf-legent">&nbsp;Trade:</span>
<span class="sf-price" id="trade"></span><!--input type="text" size="2" name="trade"  title='Current Trade price' id="last" value="" readonly /-->
<span class="sf-legent">&nbsp;&nbsp;Buy:</span>
<span class="sf-price" id="buy"></span><!--input type="text" size="2" name="buy"  title='Price of the Current Trade Buy side' id="buy" value="" readonly /-->
<span class="sf-legent">&nbsp;Sell:</span>
<span class="sf-price" id="sell"></span><!--input type="text" size="2" name="sell"  title='Price of the Current Trade Sell side' id="sell" value="" readonly /-->
<span style="font-size:10px;color:#999999" id="channelsBuffer"></span>
</TD>
</TR>

</TABLE>
</FORM>
</div>
 

<div id="CustomPeriod">
<div class="hd">
  <span>Custom period</span> 
</div>

<div>
<FORM name="customPeriodForm" id="customPeriodForm">
<p>&nbsp;</p>
<TABLE border=0  align="left" width=450>
<TR   align="left">
<TD><span style='float:left;width="50px"'>Start Time:</span></TD>
<TD><span style='float:left;'><input type="text" size="20" name="starttime" id="st" value="00:00:00:00" /></span></TD></TR>
<TR  align="left">
<TD><span style='float:left;width="50px"'>End Time:</span></TD>
<TD><input type="text" size="20" name="endtime" id="et" value="00:00:00:00" /></TD></TR><br />
<TR><TD colspan="2">&nbsp;</TD></TR>
<p>&nbsp;</p>
<TR   align="left">
<TD><span style='float:left;width="50px"'>Set Range:</span></TD>
<TD  align="left"><div id="custom_sliderbg" class="yui-h-slider" tabindex="-1">
<div id="sliderRangeMinThumb" class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-s.gif"></div>
<div id="sliderRangeMaxThumb" class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-n.gif"></div>
</div></TD></TR>
<TR   align="left">
<TD>&nbsp;&nbsp;</TD>
<TD colspan="3">
&nbsp;&nbsp;
</TD>
</TR>
</TABLE>
</FORM>
</div>
<div><button type='button' class='btn' id='cperiod' name='customperiod' onMouseOver="this.className='btn btnhov'" onMouseOut="this.className='btn'" onclick='App.customPeriod_dialog.start()'>Run period</button>
 <!--button type='button' class='btn' id='cgraph' name='customgraph' onmouseover="this.className='btn btnhov'" onmouseout="this.className='btn'" onclick='App.customPeriod_dialog.graph()'>Period Graph</button-->
</div>
</div>

        
<div><p>Tab Three Content</p></div>
<div id="div_bookmarks"></div>

	 </div> 
    </div>
</div>
<div class="ft"><div id="toolbar"></div></div>
</div>


<div id="div_MktInfo"></div>

<div id="div_MktInfo_main">
<FORM name="MktInfoForm" class='mkt_val' id="MktInfoForm">
<TABLE border=2 cellspacing="2"  cellpadding="2" align="left" class="MktInfo"  hspace="5px" vspace="5px" >
<tr>
<td></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Price</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Investor</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Price</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Investor</span></td>
</tr>
<TR align="right">
<TD width="8px">min:</TD>
<TD width="15px"><span id="minPrice" class='buy_amt mkt_val'>0.0</span></TD>
<TD width="105px"><span id="minPriceTrader" class='buy_str mkt_val'> </span></TD>
<TD width="15px"><span id="minSellPrice" class='sell_amt mkt_val'>0.0</span></TD>
<TD width="105px"><span id="minPriceSellTrader" class='sell_str mkt_val'> </span></TD>
</TR>
<TR align="right">
<TD>max:</TD>
<TD><span id="maxPrice" class='buy_amt mkt_val'>0.0</span></TD>
<TD><span id="maxPriceTrader" class='buy_str mkt_val'> </span></TD>
<TD><span id="maxSellPrice" class='sell_amt mkt_val'>0.0</span></TD>
<TD><span id="maxPriceSellTrader" class='sell_str mkt_val'> </span></TD>
</TR>
<tr>
<td></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Volume</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Investor</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Volume</span></td>
<td align="center" class='mkt_title'><span class='mkt_title'>Investor</span></td>
</tr>
<TR align="right">
<TD>min:</TD>
<TD><span id="minVol" class='buy_amt mkt_val'>0.0</span></TD>
<TD><span id="minVolTrader" class='buy_amt mkt_val'> </span></TD>
<TD><span id="minSellVol" class='sell_amt mkt_val'>0.0</span></TD>
<TD><span id="minVolSellTrader" class='sell_amt mkt_val'> </span></TD>
</TR>
<TR  align="right">
<TD>max:</TD>
<TD><span id="maxVol" class='buy_amt mkt_val'>0.0</span></TD>
<TD><span id="maxVolTrader" class='buy_amt mkt_val'> </span></TD>
<TD><span id="maxSellVol" class='sell_amt mkt_val'>0.0</span></TD>
<TD><span id="maxVolSellTrader" class='sell_amt mkt_val'> </span></TD>
</TR>
<TR></TR>
<TR align="center">
<TD  align="center" colspan="5" class='mkt_title'><span class='mkt_title'>Trade</span></TD>
</TR>
<TR  align="right">
<TD>min:</TD>
<TD colspan="2"><span class='mkt_val' id="minTradePrice">0.0</span></TD>
<TD colspan="2"><span class='mkt_val' id="minTradeVol">0</span></TD>
</TR>
<TR  align="right">
<TD>max:</TD>
<TD colspan="2"><span class='mkt_val' id="maxTradePrice">0.0</span></TD>
<TD colspan="2"><span class='mkt_val' id="maxtradeVol">0</span></TD>
</TR>
<TR  align="right">
<TD>Total:</TD>
<TD colspan="2"><span class='mkt_val' id="totTradeVol">0</span></TD>
<TD colspan="2"><span class='mkt_val' id="totTradeValue">0</span></TD>
</TR>

</TABLE>
</FORM>
<div class='mkt_val' id="mkt_query" style="float:left;width:100%;height:22px; background-color:#E0D0A0;font-size:11px;color:#777777;padding-left:5px;">
<form name="hourgraphform">
Trader:<input type="text" class="hourgraph" size="15" name="hourtrader" id="hourtrader" value="" />&nbsp;Investor:<input type="text" class="hourgraph" size="10" name="hourinvestor" id="hourinvestor" value="" />
<button type='button'  name='refreshGraph' id='refreshGraph' disabled=="disabled" onclick='App.refreshHourTrades()'>Refresh</button>
</form>
</div>

</div>

<div class='mkt_val__' id="div_hourTradesGraph___"></div>
<!--
<div id="customPeriod"  class="yui-panel">
<div class="hd">
  <div class="tl"></div><span>Custom period</span><div class="tr"></div>
</div>
<div class="bd">
<FORM name="customPeriodForm" id="customPeriodForm">
<TABLE border=0  align="left" width=410>
<TR   align="left">
<TD>Start Time:</TD>
<TD><input type="text" size="20" name="starttime" id="st" value="00:00:00:00" /></TD></TR>
<TR  align="left">
<TD>End Time:</TD>
<TD><input type="text" size="20" name="endtime" id="et" value="00:00:00:00" /></TD></TR>
<TR   align="left">
<TD width="50px">Set Range:</TD>
<TD  align="left"><div id="custom_sliderbg" class="yui-h-slider" tabindex="-1">
<div id="sliderRangeMinThumb" class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-s.gif"></div>
<div id="sliderRangeMaxThumb" class="yui-slider-thumb"><img src="yui/build/slider/assets/thumb-n.gif"></div>
</div></TD></TR>
<TR   align="left">
<TD>&nbsp;&nbsp;</TD>
<TD colspan="3">
&nbsp;&nbsp;
</TD>
</TR>
</TABLE>
</FORM>
</div>
<div class="ft"><button type='button' id='cperiod' name='customperiod' onclick='App.customPeriod_dialog.start()'>Run period</button>
<button type='button' id='cgraph' name='customgraph' onclick='App.customPeriod_dialog.graph()'>Period Graph</button></div></div>
</div-->


<div id="top_1"><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p></div>

<div id="bottom1"><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p></div>

<div id="win"  class="yui-panel"></div>
<div  id="win-hd" class="hd1">
  <div class="tl"></div><span>Custom period</span><div class="tr"></div>
</div>
<div id="win-ft" class="ft">
<button type='button' id='cperiod' name='customperiod' onclick='App.customPeriod_dialog.start()'>Run period</button>
</div>

<div id="win1">

    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>

    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>

    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse justo nibh, pharetra at, adipiscing ullamcorper.</p>
	
</div>


<div id="left1">
    <div id="folder_top">
        <div class="wrap">
            <div id="check_buttons"></div>
        </div>
    </div>
    <div id="folder_list">
        <div id="wrap" class="wrap">

            <ul>
                <li class="inbox selected"><em></em><a href="javascript:App.startUploader();">UpLoader</a></li>
                <li class="drafts"><em></em><a href="javascript:App.createOrderBookWin();">Market Depth</a></li>
                <li class="sent"><em></em><a href="javascript:App.querySec_dialog.Open();">Query Security</a></li>
                <li class="spam"><em></em><a href="#">Sec history</a></li>
                <li class="trash"><em></em><a href="javascript:App.createBuySellWin();" target="_self">Security Graph</a></li>

            </ul>
            <ul>
                <li class="contacts"><em></em><a href="javascript:YAHOO.mainTradeReplay.app.getConfig();" title='View/Set Trade Options' target="_self">Options</a></li>
                <li class="calendar"><em></em><a href="http://calendar.yahoo.com/" title='Select a Trading Date from a Calendar' >Calendar</a></li>
                <li class="notepad"><em></em><a href='javascript:App.showProjectedGraph();' title='View Projected Prices in a Graph'  target="_self">Projected</a></li>
				<li class="notepad"><em></em><a href='javascript:App.showBidAskGlobalGraph();' title='View Bid/Ask Graph' target="_self">Bid/Ask Chart</a></li>
				<li class="notepad"><em></em><a href='javascript:App.showBidAskCompareGraph();' title='Compare Bid/Ask Graph' target="_self">Comparison Chart</a></li>
            </ul>
            <ul>

                <li class="ydn-patterns"><em></em><a href="javascript:App.createSmartTradesWin();" title='View All the Trades' target="_self">Recall Trades</a></li>
                <li class="ydn-mail"><em></em><a href="javascript:App.createSmartOrdersWin();" title='View All the Orders' target="_self">Recall Orders</a></li>
                <li class="yws-maps"><em></em><a href='javascript:App.traderSurv_dialog.show();'>Traders Surv</a></li>
                <li class="ydn-delicious"><em></em><a href="javascript:App.createSmartTradersWin();" title='View All the Traders &#10;&#13;and Market participation' >Traders</a></li>
                <li class="yws-flickr"><em></em><a href="#">User Notes</a></li>
                <li class="yws-events"><em></em><a href="javascript:App.createOrderEntryWin();" title='Create an embedding Order' >Order Entry</a></li>

            </ul>
            
			<div id="calBox" title="Set a New Trading Day"><span class="icon"></span><em id="trade_date">10/03/2009</em></div><div id="calBox2"><span class="icon"></span><em id="calDateStr2">Help!</em></div>
            <div><div id="calContainer"><div id="cal"></div></div>
			<div id="protect"><center><div id="protect_internal"></center></div>
			</center></div>
			</div>
	  </div>
			<div id="calContainer2">
			<div class="hd"><div class="tl"></div>
			<div class="ct">DOCiNav<span id="closedoc" class="icon" onMouseOver="this.className='icon_over'" onMouseOut="this.className='icon'"onClick="javascript:App.toggleCal2();"></span></div>
			  <div class="tr"></div>
			</div>
			
			<div id="help-tree">
			
			</div></div></div>
			
</div>
</div>
</div>
</div>


<div id="scrollerTabs" class="yui-navset">
    <ul style="float:right" class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Status</em></a></li>
        <li><a href="#tab2"><em>Charts</em></a></li>
        <li><a href="#tab3"><em>Session</em></a></li>
    </ul>            
    <div class="yui-content">
        <div class="scroller-content">
<TABLE border="0" align="center" class="scroller" height="18px" bordercolor="#999999" cellspacing="5" frame="vsides" cellpadding="5">
<TR><TD><div style="padding-left:15px"><button id="scroller_tm" title="- Toggle Navi Panel..." onClick="this.className='navi_clicked'; javascript:App.toggleNavi();">NAViTRAD 1.0 &copy;</button></div>
</TD><TD><em title="- Current Session Id">Session:</em><span id="scroller_session"></span>
</TD><TD><em title="- Current Session Date">Date:</em><span id="scroller_date"></span>
</TD><TD><em title="- Current Trading Security">Sec:</em><span id="scroller_isin"></span>
</TD><TD><em title="- Market Phase">Phase:</em><span id="scroller_phase"></span>
</TD><TD><em title="- Last Price">Price:</em><span id="scroller_price"></span>
</TD></TR>
 </TABLE>
</div>
        <div class="scroller-content">
		<TABLE id="scroller_chart" border="0" align="center" class="scroller" height="18px" bordercolor="#999999" cellspacing="5" frame="vsides" cellpadding="5">
		<TR><TD rowspan="2"><div style="padding-left:15px"><button id="scroller_tm" title="- Toggle Navi Panel..." onClick="this.className='navi_clicked'; javascript:App.toggleNavi();">NAViTRAD 1.0 &copy;</button></div>
		</TD>

		<TD rowspan=2><em title="- Selected Isin Id">Isin:</em></TD><TD><TABLE><TR class="s_chart2"><TD><span id="chartA_isin"></span></TD></TR><TR class="s2_chart2"><TD><span id="chartB_isin"></span></TD></TR></TABLE></TD>
		<TD rowspan=2><em title="- Selected Isin Id">Member:</em></TD><TD><TABLE><TR class="s_chart2"><TD><span id="chartA_mem"></span></TD></TR class="s2_chart2"><TR><TD><span id="chartB_mem"></span></TD></TR></TABLE></TD>
		<TD rowspan=2><em title="- Selected Isin Id">Trader:</em></TD><TD><TABLE><TR class="s_chart2"><TD><span id="chartA_trader"></span></TD></TR><TR class="s2_chart2"><TD><span id="chartB_trader"></span></TD></TR></TABLE></TD>
		<TD rowspan=2><em title="- Selected Isin Id">Client:</em></TD><TD><TABLE><TR class="s_chart2"><TD><span id="chartA_client"></span></TD></TR><TR class="s2_chart2"><TD><span id="chartB_client"></span></TD></TR></TABLE></TD>
		<TD rowspan=2><em title="- Selected Isin Id">Date:</em></TD><TD><TABLE><TR class="s_chart2"><TD><span id="chartA_date"></span></TD></TR><TR class="s2_chart2"><TD><span id="chartB_date"></span></TD></TR></TABLE></TD>
		</TR>
		</TABLE>
		</div>
        <div class="scroller-content">
		<TABLE border="0" align="center" class="scroller" height="18px" bordercolor="#999999" cellspacing="5" frame="vsides" cellpadding="5">
		<TR><TD><div style="padding-left:15px"><button id="scroller_tm" title="- Toggle Navi Panel..." onClick="this.className='navi_clicked'; javascript:App.toggleNavi();">NAViTRAD 1.0 &copy;</button></div>
		</TD><TD><em></em><a href='javascript:App.showOraGrid(0);' class="scroller_href" title='Show Selected Dates'  target="_self">&nbsp;Selected Dates&nbsp;</a>
		</TD><TD><em></em><a href='javascript:App.querySec_dialog.Open();' class="scroller_href" title='Search Past Dates'  target="_self">&nbsp;Search Dates&nbsp;</a>
		</TD><TD>&nbsp;
		</TD><TD>&nbsp;
		</TD><TD>&nbsp;
		</TD></TR>
		</TABLE>
		</div>
    </div>
</div>


</body>
</html>
