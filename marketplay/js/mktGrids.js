// JavaScript Document
function amChartInited(chart_id) {
//	YAHOO.log("amChartInited: "+chart_id+'=='+chart_id.split('_')[0],"error");
			for(i in App.isinAr)
			   if (chart_id==App.isinAr[i][_SEC_TABLE_ISIN]){
	  				//App.flashBidAsk = document.getElementById(chart_id);
					App.isinAr[i][_SEC_TABLE_FLASHOBJ]=document.getElementById(chart_id);
				//App.flashBidAsk.setSettings("<charts><chart cid='0'><graphs><graph gid='3'><axis>left</axis><type>line</type><connect>true</connect><cursor_color>2b006d</cursor_color><positive_color>7f8da9</positive_color><color>00aacc</color><data_sources><close>trade2</close></data_sources><bullet>round_outline</bullet><bullet_size>6</bullet_size><legend><date key='true' title='true'><![CDATA[Trade2: <b>{close}</b>]]></date><period key='true' title='true'><![CDATA[Trade2:<b>{close}</b>]]></period></legend></graph></graphs></chart></charts>",true,false);	
				//YAHOO.mainTradeReplay.app.flashBidAsk.setSettings(App.XML_var);
				App.XML_var=null;
				//setZoom();
				YAHOO.log("amChartInited:"+chart_id,"warn");
				return;
				}
		  if (chart_id=="bidaskGlobal"){
				   App.flashBidAskGlobal = document.getElementById(chart_id);
				   YAHOO.log("amChartInited "+chart_id+": "+App.fdate+' '+App.custom_start+"=="+App.fdate+' '+App.custom_end,"error");
				   App.flashBidAskGlobal.setZoom(App.fdate+' '+App.custom_start, App.fdate+' '+App.custom_end);
		  		}
		    else if (chart_id=="bidaskCompare"){
				   App.flashBidAskCompare = document.getElementById(chart_id);
				   YAHOO.log("amChartInited "+chart_id,"error");
		  		}
			else if (chart_id=="traders_chart1"){
				   App.flash_traders_chart1 = document.getElementById(chart_id);
				  
           			App.flash_traders_chart1.setParam("values.y_right.min",App.traders_chart1_min);
					App.flash_traders_chart1.setParam("values.y_right.max",App.traders_chart1_max);
           			App.flash_traders_chart1.setParam("values.y_right.strict_min_max",1);
                    //App.flash_traders_chart1.rebuild();
				   YAHOO.log("traders_chart1 "+chart_id,"error");
		  		}
			else if (chart_id=="traders_chart2"){
				    App.flash_traders_chart2 = document.getElementById(chart_id);
				    App.flash_traders_chart2.setParam("values.y_right.min",App.traders_chart2_min);
					App.flash_traders_chart2.setParam("values.y_right.max",App.traders_chart2_max);
           			App.flash_traders_chart2.setParam("values.y_right.strict_min_max",1);
					//alert(App.traders_chart2_min+"=="+App.traders_chart2_max);
				   YAHOO.log("traders_chart2 "+chart_id,"error");
		  		}
			else if (chart_id=="projected"){
	  			YAHOO.mainTradeReplay.app.flashProjected = document.getElementById(chart_id);
				YAHOO.log("amChartInited:"+chart_id,"warn");
				}
			 else if (chart_id.split('_')[0]=="hourtrades"){
	  			  YAHOO.mainTradeReplay.app.flashhourtrades = document.getElementById(chart_id);
				  YAHOO.log("amChartInited:"+chart_id,"warn");
				  }
			  else if (chart_id=="buysell"){
	  				YAHOO.mainTradeReplay.app.flashBuySell = document.getElementById(chart_id);
					YAHOO.log("amChartInited:"+chart_id,"warn");
				}	
			 else if (chart_id=="mdb"){
						YAHOO.mainTradeReplay.app.flashMdb = document.getElementById(chart_id);
						YAHOO.mainTradeReplay.app.flashMdb.setSettings(App.XML_var_mda);
						App.XML_var_mda=null;
						App.flashMdb.rebuild();
						YAHOO.log("amChartInited:"+chart_id,"warn");
			 			}
				else if (chart_id=="mda"){
						YAHOO.mainTradeReplay.app.flashMda = document.getElementById(chart_id);
						YAHOO.mainTradeReplay.app.flashMda.setSettings(App.XML_var_mdb);
						App.XML_var_mdb=null;
						App.flashMda.rebuild();
						YAHOO.log("amChartInited:"+chart_id,"warn");
			 			}
}

var init_from = 0;
var init_to = 0;
var zoom_from = '10:00:00:000';
var zoom_to = '18:00:00:000';
var flagShow=true;

function amClickedOnBullet(chart_id, graph_index, value, series, url, description)
{
	alert (graph_index+"=="+ value+"=="+ series);
 }

function amClickedOnEvent(chart_id, date, description, id, url)
{
	 if (chart_id=="bidaskGlobal" ||  matchGraphId(chart_id)==true)	  
	  {
		var ttime = date.split(' ');
		var stime=ttime[1];
		stime=App.split_time(stime);
		var tradeno = description.split("Trade:");
		if (typeof(tradeno)!='undefined')
		{
		YAHOO.log( chart_id+"=="+ stime+"=="+ tradeno[1],"warn");
		App.createTradeOrdersWindow_bid(stime, tradeno[1],"", "",'Buy/Sell Detail-1','order_ftr','noOB');
		}
	  }
}

function amClickedOn_1(chart_id, date, period, data_object)
{
	YAHOO.log("amClickedOn:"+data_object.toSource()+"=="+date,"warn");
	if (chart_id=='bidaskg'){
	
		if (flagShow==true) {
		    App.flashBidAsk.hideGraph('0', '0');
			App.flashBidAsk.hideGraph('0', '1');
			App.flashBidAsk.hideGraph('0', '2');
			flagShow=false;
			}
			else {
				App.flashBidAsk.showGraph('0', '0');
				App.flashBidAsk.showGraph('0', '1');
				App.flashBidAsk.showGraph('0', '2');
				flagShow=true;
				}
		}
 }

var zoomInit=false;
App.applyEvents=function(isin) 
{
	
	try
	{
	// YAHOO.log("applyEvents:"+isin+"=="+App.events_xml,"warn");
	 zoomInit=true;
	 document.getElementById(isin).setEvents(App.events_xml,true); //App.events_xml
	 //App.events_xml="";
	 
	}
	catch(e)
	{
		YAHOO.log("applyEvents:"+isin+" - "+e,"error");
	 }
 
 }
 
// function to set current zoom to
function amGetZoom (chart_id, from, to)
{
// YAHOO.log("amGetZoom:"+chart_id+"=="+from+"=="+to,"error");
 if (chart_id=="bidaskGlobal" ||  matchGraphId(chart_id)==true)				   
	  {
		if ( App.zoomMarket==true)
		{
		//  YAHOO.log("amGetZoom:"+from+"=="+to,"warn");
		  ar_from=from.split(' ');
		  ar_to=to.split(' ');
  
		  App.custom_start=ar_from[1];
		  App.custom_start=App.custom_start.substr(0,2)+App.custom_start.substr(3,2)+App.custom_start.substr(6,2)+App.custom_start.substr(9,2);
  
		  App.custom_end=ar_to[1];
		  App.custom_end=App.custom_end.substr(0,2)+App.custom_end.substr(3,2)+App.custom_end.substr(6,2)+App.custom_end.substr(9,2);
//	  YAHOO.log("amGetZoom:"+App.custom_start+"=="+App.custom_end,"warn");
		  App.startGraphZoomCustomTrading();  // detailsPanel.js
	      }
	 //YAHOO.log("amGetZoom:"+i+" - "+chart_id,"warn");
	if (zoomInit==true)
		 zoomInit=false;
	  else {	 
	 		var execCode='applyEvents("'+chart_id+'");';	
	 		App.callback.argument[0] = 'AR';
			var array = App.fdate.split("-");
 //App.isin=chart_id;
	 //		if (chart_id=="bidaskGlobal")
			var isin=(chart_id=="bidaskGlobal") ?document.getElementById('isin').value :chart_id;
			msg='task=getEvents'+'&mktGrids=yes'+'&fdate='+array[0] + array[1] + array[2]+'&isin='+isin+'&from='+from+'&to='+to+'&callWhenReturn='+execCode;
		//YAHOO.log("amGetZoom:"+msg,"warn");
	 		App.AjaxObject.syncRequest(App.aux,msg);
	 		}
	 }
 }

function matchGraphId(chart_id)
{
 for(i in App.isinAr)
	  if (chart_id==App.isinAr[i][_SEC_TABLE_ISIN])
	  	  return true;

return false;	  
}

		  
function SetMarketToGraph()
{
	App.zoomMarket=true;
	var obj=document.getElementsByName('sync');
	obj[0].checked=true;
 }

function UnSetMarketToGraph()
{
	App.zoomMarket=false;
	var obj=document.getElementsByName('sync');
	obj[1].checked=true;
 }

function RefreshBidAsk()
{
 for(i in App.isinAr)
	  {
	   App.isinAr[i][_SEC_TABLE_FLASHOBJ].reloadData();
	  }
 }

function setZoom1(){
  App.flashBidAsk.setZoom(zoom_from, zoom_to); 
}
/*
// this function is invoked whenever zoom level changes 
function amGetZoom(chart_id, from, to){
    // set maximum zoom 
	if (chart_id!='bidask')
		return;
	if (init_from == 0 && init_to == 0) {
        init_from = from;
        init_to = to;
    }
    // set manual zoom
    else if (from != init_from || to != init_to) {
      zoom_from = from;
      zoom_to = to;
  }
}
*/

// set zoom on reload as well
function amProcessCompleted(chart_id, process_name){
//	YAHOO.log("process: "+chart_id+'=='+process_name,"error");
  if (process_name=='traders_chart2')
 	YAHOO.log("traders_chart2 "+process_name,"info");
	
  for(i in App.isinAr){

	  if (chart_id==App.isinAr[i][_SEC_TABLE_ISIN]){
 // if (chart_id=='bidaskg') { //process_name == 'appendData' && 
     //zoom_to=App.sTime;
	 //setZoom();
	// YAHOO.log("amProcessCompleted "+"=="+chart_id,"info");
	 App.processIsFree=true;
  	}
	  }
	  
	if (process_name == 'setData' && (chart_id=='mda' || chart_id=='mdb')) {
		  App.processIsFree=true;
  		}
	  else
		if (process_name == 'reloadData' && (chart_id=='bidaskGlobal')) {
			YAHOO.log("amProcessCompleted "+App.custom_start+"=="+App.custom_end,"info");
		    App.flashBidAskGlobal.setZoom(App.fdate+' '+App.custom_start, App.fdate+' '+App.custom_end);
  		}
}

function amError(chart_id, message) 
	{
	 alert('Error from chart_id ' + chart_id + message);
	  if (chart_id=="bidask") {
	  	  //so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+escape(App.graphs+"bidask_data_" +App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");
	//	  YAHOO.mainTradeReplay.app.flashBidAsk.rebuild();
	  	  }
	  else {
		App.alert('Error from chart_id ' + chart_id + "  "  +': ' + message);  
	  	}
	 }

		
(function() {
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
    
	App.flashBidAskGlobal='';
	App.bidaskGlobal_so='';
	App.bidaskCompare_so='';
	
    YAHOO.log('mktGrids.js file loaded...', 'info', 'mktGrids.js');


	App.createBuySellGraph = function()  
		{	
 		var so = new SWFObject(App.graphs+"amstock.swf", "buysell", "98%", "400", "8", "#FFFFFF");
		
 		so.addVariable("chart_id", "buysell");
 		so.addVariable("path", App.graphs);
 		so.addVariable("settings_file", escape(App.graphs+"buysell_settings.xml"));                // you can set two or more different settings files here (separated by commas)
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		so.write("buysell_flashcontent");
 		}
	
	App.createTradersGraph = function()  
		{	
 		var so = new SWFObject(App.graphs+"ampie.swf", "traders", "100%", "240", "8", "#FFFFFF");
		
 		so.addVariable("chart_id", "traders");
 		so.addVariable("path", App.graphs);
 		so.addVariable("settings_file", escape(App.graphs+"traders_settings.xml"));                // you can set two or more different settings files here (separated by commas)
		//so.addVariable("data_file", escape(App.graphs+"tradersgraph_" +App.ses +'B' + ".txt"));
		so.addVariable ("data_file", escape(App.graphs+'B'+"traders_data_" +App.secs[0]+'_'+ App.ses  + '.txt'));
		//so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+escape("tradersgraph_" +App.ses +'B' + ".txt")+"</file_name></data_set></data_sets></settings>");		
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		so.write("div_traders_graph");
		
		var so = new SWFObject(App.graphs+"ampie.swf", "asktraders", "100%", "240", "8", "#FFFFFF");
		
 		so.addVariable("chart_id", "asktraders");
 		so.addVariable("path", App.graphs);
 		so.addVariable("settings_file", escape(App.graphs+"traders_settings.xml"));                // you can set two or more different settings files here (separated by commas)
		//so.addVariable("data_file", escape(App.graphs+"tradersgraph_" +App.ses +'S' + ".txt"));
		so.addVariable ("data_file",escape(App.graphs+'S'+"traders_data_" +App.secs[0]+'_'+ App.ses  + '.txt'));
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		so.write("div_ask_traders_graph");
 		}
		
	App.createHourTradesGraph = function(tabIdx,label)  
		{	
 		var so = new SWFObject(App.graphs+"amstock.swf", "hourtrades_"+(tabIdx+1), "100%", "350", "8", "#FFFFFF");
//		YAHOO.log("createHourTradesGraph:"+tabIdx+"=="+label,"warn");
 		so.addVariable("chart_id", "hourtrades_"+(tabIdx+1));
 		so.addVariable("path", App.graphs);
 		so.addVariable("settings_file", escape(App.graphs+"hourtrades.xml"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+escape("hourtrades_data_" +label+'_' +App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		so.write("div_hourTradesGraph_"+(tabIdx+1));
 		}
	 
	 App.createBidAskGlobalGraph = function()  
		{	
 		App.bidaskGlobal_so = new SWFObject(App.graphs+"amstock.swf", "bidaskGlobal", "100%", "350", "8", "#FFFFFF");
		YAHOO.log("createBidAskGlobalGraph...","warn");
 		App.bidaskGlobal_so.addVariable("chart_id", "bidaskGlobal");
 		App.bidaskGlobal_so.addVariable("path", App.graphs);

		
		var isin=document.getElementById('isin').value;
		for(i in App.isinAr)
	  		if (isin==App.isinAr[i][_SEC_TABLE_ISIN])
				var sym=App.isinAr[i][_SEC_TABLE_SYMBOL];
		App.bidaskGlobal_so.addVariable("settings_file", encodeURIComponent(App.graphs+"bidask_settings.php?ses="+App.ses+"&sec="+isin+"&sym="+sym)); //encodeURIComponent
		//App.bidaskGlobal_so.addVariable("settings_file", encodeURIComponent(App.graphs+"bidaskglob_settings.php?ses="+App.ses+"&sec="+isin));
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		App.bidaskGlobal_so.write("bidaskGlobal_flashcontent");
 		}
	
	App.createBidAskCompareGraph = function()  
		{	
 		App.bidaskCompare_so = new SWFObject(App.graphs+"amstock.swf", "bidaskCompare", "100%", "350", "8", "#FFFFFF");
		YAHOO.log("createBidAskCompareGraph...","warn");
 		App.bidaskCompare_so.addVariable("chart_id", "bidaskCompare");
 		App.bidaskCompare_so.addVariable("path", App.graphs);

		App.bidaskCompare_so.addVariable("settings_file", encodeURIComponent(App.graphs+"bidaskcomp_settings.php?ses="+App.ses+"&sec="+escape(App.isin)));
		//so.addVariable("data_file", escape(graphs+"amline_data.xml"));
 		App.bidaskCompare_so.write("bidaskCompare_flashcontent");
 		}
		
	 App.refreshHourTrades=function()
	 {
	 	var trader=Dom.get('hourtrader').value;
		var investor=Dom.get('hourinvestor').value;
		trader=trader.replace(/,/gi,'^');
		investor=investor.replace(/,/gi,'^');
		msg="task=hour_trades&execWhenReturn=callBackRefreshHourTrades();&format=JSON&trader="+trader+"&investor="+investor+"&session="+App.ses
		App.callback.argument[0] = 'JS';
		App.AjaxObject.syncRequest(App.tradeOrders,msg);
	 }
	
	App.callBackRefreshHourTrades=function()
	{
		App.flashhourtrades.rebuild();
		YAHOO.log("callBackRefreshHourTrades","warn");
	}
	
	App.createBidSurvGrid = function()
	{
		bidsurvDataSource = new YAHOO.util.DataSource({});   
 		bidsurvDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		bidsurvDataSource.responseSchema = {  fields: ["LEVEL","t1","t2","t3","t4","t5"]   };  
 // resultsList: "data",
 		var colsDef=[{key:"LEVEL", label:"Level", width:12},{key:"t1", label:"t1",width:50},{key:"t2", label:"t2",width:50},{key:"t3", label:"t3",width:50},{key:"t4", label:"t4",width:50},{key:"t5", label:"t5",width:50}];
 		
		var myConfigs = {   
     		//scrollable:true,
	 		MSG_EMPTY:  "No Survailance found... ",
			height: "17em", 
	 		renderLoopSize: 0, 
	 		initialLoad:false  
 			};  
		
 //var obj = Dom.get('div_events'); //document.getElementById
 //dhxPlayLayout.cells("c").attachObject(obj);
 		App.mygrid_bidsurv = new YAHOO.widget.DataTable("div_bidsurv", colsDef, bidsurvDataSource, myConfigs); //scrollable:true
 		App.mygrid_bidsurv.subscribe("rowClickEvent", App.mygrid_bidsurv.onEventSelectRow);
		App.mygrid_bidsurv.subscribe("rowMouseoverEvent", App.mygrid_bidsurv.onEventHighlightRow);
 		App.mygrid_bidsurv.subscribe("rowMouseoutEvent", App.mygrid_bidsurv.onEventUnhighlightRow);
	 }
	 
	App.createAskSurvGrid = function()
	{
		asksurvDataSource = new YAHOO.util.DataSource({});   
 		asksurvDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		asksurvDataSource.responseSchema = {  fields: ["LEVEL","t1","t2","t3","t4","t5"]   };  
 // resultsList: "data",
 		var colsDef=[{key:"LEVEL", label:"Level", width:12},{key:"t1", label:"t1",width:50},{key:"t2", label:"t2",width:50},{key:"t3", label:"t3",width:50},{key:"t4", label:"t4",width:50},{key:"t5", label:"t5",width:50}];
 		
		var myConfigs = {   
     		//scrollable:true,
	 		MSG_EMPTY:  "No Survailance found... ",
			height: "17em", 
	 		renderLoopSize: 0, 
	 		initialLoad:false  
 			};  
		
 //var obj = Dom.get('div_events'); //document.getElementById
 //dhxPlayLayout.cells("c").attachObject(obj);
 		App.mygrid_asksurv = new YAHOO.widget.DataTable("div_asksurv", colsDef, asksurvDataSource, myConfigs); //scrollable:true
 		App.mygrid_asksurv.subscribe("rowClickEvent", App.mygrid_asksurv.onEventSelectRow);
		App.mygrid_asksurv.subscribe("rowMouseoverEvent", App.mygrid_asksurv.onEventHighlightRow);
 		App.mygrid_asksurv.subscribe("rowMouseoutEvent", App.mygrid_asksurv.onEventUnhighlightRow);
	 }
	 
	App.createOpenPriceGrid = function()
	{
	 	filter:null;
		var openpriceDataSource = new YAHOO.util.DataSource(App.tradeOrders+"?task=openPrice&format=JSON&session="+App.ses);   
 		openpriceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 

 		openpriceDataSource.responseSchema = { 
					resultsList: "records",
					fields: [ "ID", "SOE_B", "OE_B", "YTA", "OE_S", "SOE_S", "YO"]  
					 };
		
		var myColumnDefs = [
   			 { key: "ID", label:"ID" },
			 { key: "SOE_B" },
   			 { key: "OE_B" },			 
   			 { key: "YTA" },			 
			 { key: "OE_S" },			 
			 { key: "SOE_S" },
			 { key: "YO" }
			];
		
		// Custom parser
    	var stringToDate = function(sData) {
        		var array = sData.split("-");
       			 return new Date(array[1] + " " + array[0] + ", " + array[2]);
   				 };

 // resultsList: "data",
 		var myConfigs = {   
     		draggableColumns:true,
			paginator: new YAHOO.widget.Paginator({   
                     rowsPerPage: 15   
                 }),   

			 
	 		dynamicData : false,
	 		height: "230px", 
			width: "360px", 
			//initialRequest:"task=openPrice&session="+App.ses,
			MSG_EMPTY:  "Empty OP.. ",   //click <a href=\"abc.php\">here</a>",
            caption:"Open Price Precall", summary: "OPP",
            sortedBy:{key:"ID"}           
	 		//initialLoad:false  
			 };  
		
		 App.mygrid_openprice = new YAHOO.widget.DataTable( "div_openprice", myColumnDefs, openpriceDataSource, myConfigs); 
		 App.mygrid_openprice.subscribe("rowClickEvent", App.mygrid_openprice.onEventSelectRow);
 		 App.mygrid_openprice.subscribe("rowMouseoverEvent", App.mygrid_openprice.onEventHighlightRow);
		 App.mygrid_openprice.subscribe("rowMouseoutEvent", App.mygrid_openprice.onEventUnhighlightRow);
		 openpriceDataSource.subscribe("dataErrorEvent", function( orequest , oresponse , ocallback , ocaller , omessage )
																   {
																	   App.alert("openpriceDataSource - "+omessage);
																   });
		 openpriceDataSource.subscribe("requestEvent", function( orequest , ocallback , otId , ocaller )
																{
																	App.alert("openpriceDataSource - requestEvent:"+orequest);
																});
	};
	
	App.createSmartTradesGrid = function()
	{
	 	Paginator = YAHOO.widget.Paginator;
		ButtonGroup = YAHOO.widget.ButtonGroup;
		
		filter:null;
		App.createSmartTradesGrid.settings= {
				phase: 'All'
			};
		
		AppSettings = App.createSmartTradesGrid.settings;
		AppTrGrid = App.createSmartTradesGrid;
		
		var smartTradesDataSource = new YAHOO.util.XHRDataSource(App.smartTrades+"?format=JSON&session="+App.ses); //+"?format=JSON&session="+App.ses+'&');   
 		smartTradesDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 

 		
		smartTradesDataSource.responseSchema = { 
					resultsList: "records",
					fields: App.IScolumnsIds,
		 			metaFields: {   
					             totalRecords: "totalRecords", // Access to value in the server response   
								 pageSize: "pageRecords"
        					    } 
					 };
		
		
		
		AppTrGrid.phaseButtonsCheckedChange= function(e) {
				AppSettings.phase = e.newValue.get('value');
				if (AppSettings.phase=='All')
					Dom.get('trg_ord_trader').value='';
				else
					AppSettings.phase = AppSettings.phase.substr(AppSettings.phase.indexOf('(')+1,1);
				//YAHOO.log("phaseButtonsCheckedChange - "+AppSettings.phase,"warn");
				App.mygrid_smartTrades.fireDT(true);
			}
			
		AppTrGrid.phaseButtons = new ButtonGroup('smartTradesFilterContainer');
		var el_buyer=Dom.get('trg_buyer');
		var el_seller=Dom.get('trg_seller');
															 // YAHOO.log(el_trader.innerHTML+"=="+el_trader.id,"warn");
		var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el_buyer,"id"),"buyers");
		var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el_seller,"id"),"buyers");
				// Subscribe to the checked change event which enables us
				// to save the new setting and request new data for the DataTable.
		AppTrGrid.phaseButtons.subscribe('checkedButtonChange', AppTrGrid.phaseButtonsCheckedChange);
		
		var requestBuilder = function (oState, oSelf) {
				/* We aren't initializing sort and dir variables. If you are
				using column sorting built into the DataTable, use this
				set of variable initializers.
				var sort, dir, startIndex, results; */
				var startIndex, results;
				//YAHOO.log("requestBuilder - "+oState,"warn");
				oState = oState || {pagination: null, sortedBy: null};
	            
				/* If using column sorting built into DataTable, these next two lines
				will properly set the current _sortedBy_ column and the _sortDirection_*/
				sortcol = (oState.sortedBy) ? oState.sortedBy.key : oSelf.getColumnSet().keys[0].getKey();
				dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc"; 
				startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
				results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
				//YAHOO.log("requestBuilder - "+results+"=="+App.settings.phase,"warn");
				return  "&results=" 	+ results +
						"&startIndex=" 	+ startIndex +
					    "&phase=" 		+ AppSettings.phase+
						"&buyer=" 		+ Dom.get('trg_buyer').value+
						"&seller=" 		+ Dom.get('trg_seller').value+
						"&sort=" 		+ sortcol +
						"&dir=" 		+ dir;
			};		
 // resultsList: "data",
 		var myConfigs = {   
     		draggableColumns:true,
			paginator: new Paginator({
						rowsPerPage: 25,
						containers: 'smartTradespaginated',
						template : "{PreviousPageLink} {PageLinks}  <strong>{CurrentPageReport}</strong> {NextPageLink} {RowsPerPageDropdown}",
						rowsPerPageOptions : [ 10, 25, 40, 60 ]
					}), 
	 		dynamicData : true,
	 		height: "230px", 
			width: "360px",
			initialLoad: true,
			initialRequest: "startIndex=0&results=25&format=JSON&session="+App.ses,
			MSG_EMPTY:  "Loading... ",   //click <a href=\"abc.php\">here</a>",
            caption: " History Trades", summary: "Tr",
            sortedBy:{key:"OASIS_TIME", dir:YAHOO.widget.DataTable.CLASS_ASC},           
	 		generateRequest: requestBuilder
			};  
 				
		 App.mygrid_smartTrades = new YAHOO.widget.DataTable( "div_smartTrades", App.IScolumnsDef, smartTradesDataSource, myConfigs); 
		 // Update totalRecords on the fly with value from server   
     	 App.mygrid_smartTrades.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {   
         		oPayload.totalRecords = oResponse.meta.totalRecords; 
				oPayload.pageRecords = oResponse.meta.pageSize; 
         		return oPayload;   
     			}   

		 App.mygrid_smartTrades.subscribe("rowClickEvent", App.mygrid_smartTrades.onEventSelectRow);
 		 App.mygrid_smartTrades.subscribe("rowMouseoverEvent", App.mygrid_smartTrades.onEventHighlightRow);
		 App.mygrid_smartTrades.subscribe("rowMouseoutEvent", App.mygrid_smartTrades.onEventUnhighlightRow);
		 smartTradesDataSource.subscribe("dataErrorEvent", function( orequest , oresponse , ocallback , ocaller , omessage )
																   {
																	   App.alert("smartTradesDataSource -"+omessage);
																   });
		/* smartTradesDataSource.subscribe("requestEvent", function( orequest , ocallback , otId , ocaller )
																{
																	//App.alert(orequest);
																});*/
		 App.mygrid_smartTrades.fireDT = function (resetRecordOffset) {
	            var oState = App.mygrid_smartTrades.getState(),
	            	request,
	            	oCallback;
	        	
				/* We don't always want to reset the recordOffset.
				If we want the Paginator to be set to the first page,
				pass in a value of true to this method. Otherwise, pass in
				false or anything falsy and the paginator will remain at the
				page it was set at before.*/
	            if (resetRecordOffset) {
	            	oState.pagination.recordOffset = 0;
	            }
				
				/* If the column sort direction needs to be updated, that may be done here.
				It is beyond the scope of this example, but the DataTable::sortColumn() method
				has code that can be used with some modification. */
	            
				/*
				This example uses onDataReturnSetRows because that method
				will clear out the old data in the DataTable, making way for
				the new data.*/
				oCallback = {
				    success : App.mygrid_smartTrades.onDataReturnSetRows,
				    failure : App.mygrid_smartTrades.onDataReturnSetRows,
	                argument : oState,
				    scope : App.mygrid_smartTrades
				};
	            //YAHOO.log("fireDT - "+App.mygrid_smartTrades.get("generateRequest"),"warn");
				// Generate a query string
	            request = App.mygrid_smartTrades.get("generateRequest")(oState, App.mygrid_smartTrades);
				//YAHOO.log("fireDT - "+request,"warn");
				// Fire off a request for new data.
				smartTradesDataSource.sendRequest(request, oCallback);
			}
		 // ===== Attach		TRADES MENU ===========
		 onsmartTradesContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];   
             if(task) {   
                 // Extract which TR element triggered the context menu   
                 var elRow = this.contextEventTarget;   
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {   
                         case 0:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow); 
							var te = oRecord.getData("OASIS_TIME").split(':');
							var trade_time='';
							for(var i=0; i<te.length; i++)
			    				trade_time+=te[i];
							App.createTradeOrdersWindow_bid(trade_time, oRecord.getData("TRADE_NUMBER"),oRecord.getData("BUY_ORDER_NUMBER"),oRecord.getData("SELL_ORDER_NUMBER"),																	   'Orders Details of trade: '+oRecord.getData("TRADE_NUMBER"),'order','noOB');
							break;
						case 1:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow); 
							var te = oRecord.getData("OASIS_TIME").split(':');
							var trade_time='';
							for(var i=0; i<te.length; i++)
			    				trade_time+=te[i];
							App.createTradeWindow(trade_time, oRecord.getData("TRADE_NUMBER"), 'Trade Details of: '+oRecord.getData("BUY_TRADER_ID")+"/"+oRecord.getData("SELL_TRADER_ID"),'trade','noOB');
							break;
						case 2:
							var oRecord = p_myDataTable.getRecord(elRow); 
							var trader = oRecord.getData("BUY_TRADER_ID")
							var investor = oRecord.getData("BUY_CSD_ACCOUNT_ID")
							Dom.get('hourtrader').value="B@"+trader;
							Dom.get('hourinvestor').value=investor;
							break;
						case 3:
							var oRecord = p_myDataTable.getRecord(elRow); 
							var trader = oRecord.getData("SELL_TRADER_ID")
							var investor = oRecord.getData("SELL_CSD_ACCOUNT_ID")
							Dom.get('hourtrader').value="S@"+trader;
							Dom.get('hourinvestor').value=investor;
							break;
                     }   
                 }   
             }   
         };  
		 
		 var osmartTradesContextMenu = new YAHOO.widget.ContextMenu("smarttradescontextmenu",   
                {trigger:App.mygrid_smartTrades.getTbodyEl()});   

		
		 osmartTradesContextMenu.addItems([
			"Buy/Sell Details", 
            "Trade Details",
			"Copy Buy Trader/Investor",
			"Copy Sell Trader/Investor"
        	]);

         osmartTradesContextMenu.render("div_smartTrades");
		 osmartTradesContextMenu.clickEvent.subscribe(onsmartTradesContextMenuClick, App.mygrid_smartTrades);  
// ===== END		TRADES MENU       ===========
	};
	
	App.createSmartOrdersGrid= function()
	{
	 	ButtonGroup = YAHOO.widget.ButtonGroup;
		Paginator = YAHOO.widget.Paginator;
		
		filter:null;
		App.settings= {
				phase: 'All'
			};
			
		var smartOrdersDataSource = new YAHOO.util.XHRDataSource(App.smartOrders+"?format=JSON&session="+App.ses); //+"?format=JSON&session="+App.ses+'&');   
 		smartOrdersDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
 		
		smartOrdersDataSource.responseSchema = { 
					resultsList: "records",
					fields: App.ITcolumnsIds,
		 			metaFields: {   
					             totalRecords: "totalRecords", // Access to value in the server response   
								 pageSize: "pageRecords"
        					    } 
					 };		
		
		App.createSmartOrdersGrid.phaseButtonsCheckedChange= function(e) {
				App.settings.phase = e.newValue.get('value');
				if (App.settings.phase=='All')
					Dom.get('trg_ord_trader').value='';
				else
					App.settings.phase = App.settings.phase.substr(App.settings.phase.indexOf('(')+1,1);
				YAHOO.log("phaseButtonsCheckedChange - "+App.settings.phase,"warn");
				App.mygrid_smartOrders.fireDT(true);
			}
			
		phaseButtons = new ButtonGroup('phaseRadioContainer');
		var el_trader=Dom.get('trg_ord_trader');
															 // YAHOO.log(el_trader.innerHTML+"=="+el_trader.id,"warn");
		var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el_trader,"id"),"buyers");		
				// Subscribe to the checked change event which enables us
				// to save the new setting and request new data for the DataTable.
		phaseButtons.subscribe('checkedButtonChange', App.createSmartOrdersGrid.phaseButtonsCheckedChange);
 // resultsList: "data",
 		var requestBuilder = function (oState, oSelf) {
				/* We aren't initializing sort and dir variables. If you are
				using column sorting built into the DataTable, use this
				set of variable initializers.
				var sort, dir, startIndex, results; */
				var startIndex, results;
				//YAHOO.log("requestBuilder - "+oState,"warn");
				oState = oState || {pagination: null, sortedBy: null};
	            
				/* If using column sorting built into DataTable, these next two lines
				will properly set the current _sortedBy_ column and the _sortDirection_*/
				sortcol = (oState.sortedBy) ? oState.sortedBy.key : oSelf.getColumnSet().keys[0].getKey();
				dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc"; 
				startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
				results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
	//		YAHOO.log("requestBuilder - "+results+"=="+App.settings.phase,"warn");
				return  "&results=" 	+ results +
						"&startIndex=" 	+ startIndex +
					    "&phase=" 		+ App.settings.phase+
						"&trader=" 		+ Dom.get('trg_ord_trader').value+
						"&sort=" 	+ sortcol +
						"&dir=" 	+ dir
						;
			};
			
		var myConfigs = {   
     		draggableColumns:true,
			paginator: new Paginator({
						rowsPerPage: 25,
						containers: 'smartOrderspaginated',
						template : "{PreviousPageLink} {PageLinks}  <strong>{CurrentPageReport}</strong> {NextPageLink} {RowsPerPageDropdown}",
						rowsPerPageOptions : [ 10, 25, 40, 60 ]
					}), 
	 		dynamicData : true,
	 		height: "230px", 
			width: "360px",
			initialLoad: true,
			initialRequest: "startIndex=0&results=25&format=JSON&session="+App.ses,
			MSG_EMPTY:  "Loading... ",   //click <a href=\"abc.php\">here</a>",
            caption:" History Orders", summary: "Or",
            sortedBy:{key:"OASIS_TIME", dir:YAHOO.widget.DataTable.CLASS_ASC},           
	 		generateRequest: requestBuilder
			 };  
		
		 App.mygrid_smartOrders = new YAHOO.widget.DataTable( "div_smartOrders", App.ITcolumnsDef, smartOrdersDataSource, myConfigs); 
		 // Update totalRecords on the fly with value from server   
     	 App.mygrid_smartOrders.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {   
         		oPayload.totalRecords = oResponse.meta.totalRecords; 
				oPayload.pageRecords = oResponse.meta.pageSize; 
         		return oPayload;   
     			}   

		 App.mygrid_smartOrders.subscribe("rowClickEvent", App.mygrid_smartOrders.onEventSelectRow);
 		 App.mygrid_smartOrders.subscribe("rowMouseoverEvent", App.mygrid_smartOrders.onEventHighlightRow);
		 App.mygrid_smartOrders.subscribe("rowMouseoutEvent", App.mygrid_smartOrders.onEventUnhighlightRow);
		 smartOrdersDataSource.subscribe("dataErrorEvent", function( orequest , oresponse , ocallback , ocaller , omessage )
																   {
																	   App.alert(omessage);
																   });
		/* smartOrdersDataSource.subscribe("requestEvent", function( orequest , ocallback , otId , ocaller )
																{
																	//App.alert(orequest);
																	return true;
																});*/
		 
		 
			
			/**
			 * This method is used to fire off a request for new data for the
			 * DataTable from the DataSource. The new state of the DataTable,
			 * after the request for new data, will be determined here.
			 * @param {Boolean} resetRecordOffset
			 */
			App.mygrid_smartOrders.fireDT= function (resetRecordOffset) {
	            var oState = App.mygrid_smartOrders.getState(),
	            	request,
	            	oCallback;
	        	
				/* We don't always want to reset the recordOffset.
				If we want the Paginator to be set to the first page,
				pass in a value of true to this method. Otherwise, pass in
				false or anything falsy and the paginator will remain at the
				page it was set at before.*/
	            if (resetRecordOffset) {
	            	oState.pagination.recordOffset = 0;
	            }
				
				/* If the column sort direction needs to be updated, that may be done here.
				It is beyond the scope of this example, but the DataTable::sortColumn() method
				has code that can be used with some modification. */
	            
				/*
				This example uses onDataReturnSetRows because that method
				will clear out the old data in the DataTable, making way for
				the new data.*/
				oCallback = {
				    success : App.mygrid_smartOrders.onDataReturnSetRows,
				    failure : App.mygrid_smartOrders.onDataReturnSetRows,
	                argument : oState,
				    scope : App.mygrid_smartOrders
				};
	            
				// Generate a query string
	            request = App.mygrid_smartOrders.get("generateRequest")(oState, App.mygrid_smartOrders);
				//YAHOO.log("fireDT - "+request,"warn");
				// Fire off a request for new data.
				smartOrdersDataSource.sendRequest(request, oCallback);
			}
		
		 // ===== Attach		TRADES MENU ===========
		 onSmartOrdersContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];   
             if(task) {   
                 // Extract which TR element triggered the context menu   
                 var elRow = this.contextEventTarget;   
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {   
                         case 0:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow); 
							
							App.createTradeOrdersWindow_bid(oRecord.getData("OASIS_TIME"), 0,oRecord.getData("ORDER_NUMBER"),0, 'Order Details of: '+oRecord.getData("TRADER_ID"),'order');
							break;
						case 1:
							//this.filter=null;
							Dom.get('trg_ord_trader').value='';
							break;
						case 2:
							break;
						case 3:
							var oRecord = p_myDataTable.getRecord(elRow);
							Dom.get('trg_ord_trader').value=oRecord.getData("TRADER_ID");
							App.mygrid_smartOrders.fireDT(true);
							break;
						case 3:
							var oRecord = p_myDataTable.getRecord(elRow);
							var order = oRecord.getData("ORDER_NUMBER");
							//msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn=createOrdersGrid();';	
							//App.AjaxObject.syncRequest(App.test1,msg);
							break;
                     }   
                 }   
             }   
         };  
		 
		 var oSmartOrdersContextMenu = new YAHOO.widget.ContextMenu("SmartOrderscontextmenu",   
                {trigger:App.mygrid_smartOrders.getTbodyEl()});   

		
		 oSmartOrdersContextMenu.addItems([
			"Order Details",
			"Clear filter",
            "Show Order Trades",
			"Show Trader Orders",
			"Order's history"
        	]);

         oSmartOrdersContextMenu.render("div_smartOrders");
		 oSmartOrdersContextMenu.clickEvent.subscribe(onSmartOrdersContextMenuClick, App.mygrid_smartOrders);  
// ===== END		TRADES MENU       ===========
	};
	
	 
	 
	 

	
	 
	
	 
    //Create this loader instance and ask for the Calendar module
    var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        require: ['datasource', 'paginator', 'button','menu','datatable', "fonts", "dragdrop"], //,'charts','swf', "container"
        onSuccess: function() {
			
            //Set a flag to show if the calendar is open or not
		eventsDataSource = new YAHOO.util.DataSource({});   
 		eventsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		eventsDataSource.responseSchema = {  fields: ["TIME","PHASE"]   };  
 // resultsList: "data",
 		var timeFormat = function (elCell, oRecord, oColumn, oData) {     	
		elCell.innerHTML = '<span style="font:Georgia;font-weight:bold;font-size:11px;padding-left:5px">'+oData+'</span>';
	 	};
		
		var eventsDef=[{key:"TIME", label:"Time", width:85,formatter:timeFormat},{key:"PHASE", label:"Event",width:130}];
 		
		var myConfigs = {   
     		//scrollable:true,
	 		height: "17em", 
	 		renderLoopSize: 0, 
	 		initialLoad:false  
 			};  
		
 //var obj = Dom.get('div_events'); //document.getElementById
 //dhxPlayLayout.cells("c").attachObject(obj);
 		App.mygrid_events = new YAHOO.widget.ScrollingDataTable("div_events", eventsDef, eventsDataSource, myConfigs); //scrollable:true
 		App.mygrid_events.subscribe("rowClickEvent", App.mygrid_events.onEventSelectRow);
		App.mygrid_events.subscribe("rowMouseoverEvent", App.mygrid_events.onEventHighlightRow);
 		App.mygrid_events.subscribe("rowMouseoutEvent", App.mygrid_events.onEventUnhighlightRow);
			
		var failure_swfobjectHandler = function(o) {
	   		o.purge(); //removes the script node immediately after executing;
			YAHOO.log("swfobject Failed to load!","error"); //the data you passed in your configuration
			};
		var success_swfobjectHandler = function(o) {
	   		o.purge(); //removes the script node immediately after executing;
			YAHOO.log("swfobject Successfully loaded!","info"); //the data you passed in your configuration
			//msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn=createOrdersGrid();';				
			//App.AjaxObject.syncRequest(App.test1,msg);
			
			//YAHOO.util.Get.script("js/ordersGrid.js");
			YAHOO.util.Get.script("js/tradesGrid.js");
			
			YAHOO.util.Get.script("js/MdBidTradersGrid.js");
			YAHOO.util.Get.script("js/MdAskTradersGrid.js");
			
			//msg='task=getMsgColumns'+'&objId=IS'+'&execWhenReturn=createTradesGrid();';
		    //App.AjaxObject.syncRequest(App.test1,msg);
		
	//		App.createGridMarketDepth();
	//  INIT
			App.mygrid_smartOrders='';
			
			//App.callback.argument=["XMLnoParse",'mda'];
			//App.AjaxObject.syncRequest("js/graphs/mda_settings.xml");
			//App.callback.argument=["XMLnoParse",'mdb'];
			//App.AjaxObject.syncRequest("js/graphs/mdb_settings.xml");
			YAHOO.util.Get.script("js/mktDepthGrid.js");
			//App.createGridMarketDepth();
			App.createBidSurvGrid();
			App.createAskSurvGrid();
	//		App.createHourTradesGraph();
			};	

		var objTransaction = YAHOO.util.Get.script("js/graphs/swfobject.js", { 
				onFailure: failure_swfobjectHandler,
				onSuccess: success_swfobjectHandler
				});
				
			//App.callback.argument=["XMLnoParse",'']; // put in App.XML_var
		    //App.AjaxObject.syncRequest("js/graphs/bidask_settingsOLD.xml");
			
			
			},  // YUILoader onSuccess
		
		onFailure: function(msg, xhrobj) {   
         	var m = "LOAD FAILED: " + msg;   
         // if the failure was from the Connection Manager, the object   
         // returned by that utility will be provided.   
         	if (xhrobj) {   
             m += ", " + YAHOO.lang.dump(xhrobj);   
         	}   
         YAHOO.log(m);   
     	}  

		});
	
		App.clearGrids = function (flag_origin)
		{
			YAHOO.log("clearGrids","info"); 
			App.mygrid_events.getRecordSet().reset();
			
			App.mygrid_events.render();
			if (App.mygrid_trades){
				App.mygrid_trades.getRecordSet().reset();
				App.mygrid_trades.render();
			}
			if (App.mygrid_orders){
				App.mygrid_orders.getRecordSet().reset();
				App.mygrid_orders.render();
			}
			if (App.mygrid_mdb){
				App.mygrid_mdb.getRecordSet().reset();
				
				App.mygrid_mdb.render();
			}
			if (App.mygrid_MdBidTraders){
				App.mygrid_MdBidTraders.getRecordSet().reset();
				App.mygrid_MdBidTraders.render();
			}
			if (App.mygrid_MdAskTraders){
				App.mygrid_MdAskTraders.getRecordSet().reset();
				App.mygrid_MdAskTraders.render();			
			}
			//App.bmarkDataTable.getRecordSet().reset();
			//App.bmarkDataTable.render();
			if (App.mygrid_bidsurv){
				App.mygrid_bidsurv.getRecordSet().reset();
				App.mygrid_bidsurv.render();
			}
			if (App.mygrid_asksurv){
				App.mygrid_asksurv.getRecordSet().reset();
				App.mygrid_asksurv.render();
			}
		//	App.flashBidAsk.rebuild();
			//App.flashMdb.rebuild();
			if (flag_origin!=2) // 2=CustomPeriod
			{
			 if (App.flashMda)
			 {
				var l3=Dom.get('div_mktdepth');
				App.mygrid_mdb.createMDbGraph();
														   
		//		Dom.get('div_mktdepth2').innerHTML='&nbsp;';
				//l3.removeChild(ch);
				//var el = document.createElement('div'); 					//  Create a new DIV
	 			//el.setAttribute("id","div_mktdepth2");		// Give to new div an id  as the ISIN code
	 			//l3.appendChild(el);
			 }
			 if (App.flashMdb){
				var l3=Dom.get('div_mktdepth');
				App.mygrid_mdb.createMDaGraph();
	//			Dom.get('div_mktdepth3').innerHTML='&nbsp;';;	
			 }
				//App.flashMda.rebuild();
			
			 var l3=App.layout3.getUnitByPosition('center');  
 			 for(i in App.isinAr)
	  		 {
			 var ch=document.getElementById('div_'+App.isinAr[i][_SEC_TABLE_ISIN]);
			 if (ch) l3.body.removeChild(ch); 
	  		 }			 
			} //flag_origin
			
		 };
  loader.insert({}, 'js');
 })();
 