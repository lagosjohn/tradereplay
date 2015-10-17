// JavaScript Document
App = YAHOO.mainTradeReplay.app;
Dom = YAHOO.util.Dom;
var myApp=App;
var test = "lagos";
var dd='';
App.processIsFree=true;
baccs_ar=[];
saccs_ar=[];
App.channelsData=[];
App.channelsBufferPause=false;
App.pauseCount=10;

function createDOMElement(el) { 
  var el = document.createElement(el); 
 
  try { 
    return el; 
  } 
  finally { 
    el = null; 
  } 
} 

function fetchChannels()
 {
 if (App.channelsData.length == 0)
     return;
 
 if (App.channelsData.length > App.pauseCount ) 
 	{
	  YAHOO.log("fetchChannels ="+App.channelsData.length+"=="+App.pauseCount+"=="+App.channelsBufferPause+"=="+App.signal,"info", "channels.js"); 
	 if (App.channelsBufferPause==false){		
		 App.sendMarketPause();
		 App.channelsBufferPause=true;
	 	}
//	 YAHOO.log("fetchChannels ="+App.channelsData.length+"=="+App.processIsFree,"warn"); 
	 }
	 else
	    if (App.channelsBufferPause==true && (App.channelsData.length/2) <= 2 )
		   {
			 YAHOO.log("fetchChannels EMPTY"+App.channelsData.length+"=="+App.pauseCount+"=="+App.channelsBufferPause+"=="+App.signal,"info", "channels.js"); 
		    App.channelsBufferPause=false;
			if ( App.oButtonStart.get("value")==_TOOLBAR_START_PAUSE )
			    App.marketSpeed(); 
		    }
			
 if (App.channelsData.length>0 && App.processIsFree==true) {
      App.processIsFree=false;
	  
	  var ch = App.channelsData.shift();
	  if (typeof App.channelsTable[ch] != 'function')  
  YAHOO.log("!fetchChannels ="+ ch,"error" ); //+ "=="+App.channelsTable[ch]
 		else {
			// YAHOO.log("fetchChannels = shift"+ ch,"error" );
		  		App.channelsTable[ch](App.channelsData.shift());
			    Dom.get('channelsBuffer').innerHTML='<span>&nbsp;B:&nbsp;'+ (App.channelsData.length/2) +'</span>';
				}
 	 }
  }
  
function ch(id, channel, message)
 {
	 YAHOO.log("ch: "+ id +"=="+ channel+"=="+Meteor.status);
  }
//YAHOO.log("channels Successfully loaded!","info", "channels.js"); 
(function(){
//		YAHOO.log("channels Successfully loaded!","info", "channels.js");		  
		
		var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
		require: ['json','get','element','dom','event','container','selector','dragdrop','progressbar','animation'],
        onSuccess: function() {
			
  		var successMeteorHandler = function(o) {
	   		o.purge(); //removes the script node immediately after executing;
			YAHOO.log("Meteor Successfully loaded!","info"); //the data you passed in your configuration
		
			App.createChannelsTable();
  				
		//msg='task=openMeteor'+'&data=startup'+App.ses_str;
 		//App.AjaxObject.startRequest(App.Serv1_dispatcher,msg);
		
			App.joinMeteor();
		//App.connectMeteor();
		
			//YAHOO.log("Connect to Meteor port, session:"+App.ses_str,"info");
			};
  
	  	var failureMeteorHandler = function(o) {
	   		o.purge(); //removes the script node immediately after executing;
			YAHOO.log("Meteor Failed to loade!","info"); //the data you passed in your configuration
			};
		
  		var objTransaction = YAHOO.util.Get.script("http://comet-imarket2.helex.gr/meteor.js?v=5", { 
			    onSuccess: successMeteorHandler,
				onFailure: failureMeteorHandler,
			    data: {
		    	    field1: 100,
			        field2: 200
				    }
				});
 
 
 
 
 
 App.joinMeteor = function()
 {
  var now = new Date();
	Meteor.hostid = now.getTime()+""+Math.floor(Math.random()*1000000);
// Our Meteor server is on the data. subdomain
// App.alert("Host:"+Meteor+"=="+Meteor.hostid);
	Meteor.host = "comet-imarket2.helex.gr"; //"data."+location.hostname;
	//Meteor.polltimeout = 60000;
// Call the test() function when data arrives
	
	//document.getElementById('meteor').contentWindow
	Meteor.registerEventCallbackMeteor("process", channelSelector);
// Join the demo channel and get last five events, then stream
//	Meteor.mode = 'stream';
// Start streaming!
//YAHOO.log("c - "+"events_"+App.ses,"warn");
	Meteor.joinChannel("events_"+App.ses,1);						
	Meteor.joinChannel("orders_"+App.ses,1);
	Meteor.joinChannel("traders_"+App.ses,1);
	Meteor.joinChannel("trades_"+App.ses,1);
	Meteor.joinChannel("hiloPrices_"+App.ses,1);
	Meteor.joinChannel("accounts_"+App.ses,1);
//	Meteor.joinChannel("OBS",1);
	Meteor.joinChannel("MDB_"+App.ses,1);
	Meteor.joinChannel("messages_"+App.ses,1);
//	Meteor.joinChannel("MDS",1); 
	YAHOO.log("joinMeteor - host:"+Meteor.host+" Ses:"+App.ses, "info", "channels.js");
//	App.alert(Meteor.host);
 }

	
App.deleteMarketObject = function(originFlag)    
 {
	App.channelsData=[];
	if (App.tabView && App.secs) //App.oButtonStart.get("value")!=_TOOLBAR_START_OPEN)
	{
		for(var i=0;i<App.secs.length;i++) 
			App.tabView.removeTab(App.tabView.get('activeTab'));
	}
	
	if (Meteor.channelcount > 0)
		App.disconnectMeteor();
	
	/*App.panel.cfg.setProperty('ret',false);
	if (App.ses_days_left == 0)	 {
		
		varTime = setInterval ( App.alert2("<b>This Session has expired!</b>"+" &nbsp;Elapsed time: "+App.elapsed_time+"<br/> System will clear this session files. <br/> Continue deleting session data?"),5000);
		alert(App.panel.cfg.getProperty('ret')); 	
	}
	*/
	App.callback.argument[0] = '';
	msg='task=deleteMarket'+'&origin=deleteMarketObject&data='+(App.panel.cfg.getProperty('ret') ?'clear' :'no')+App.ses_str;
	App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
		
	App.signal="";
	App.sub_signal="";
	App.oButtonStart.set("id","button_open"); 
	App.oButtonStart.set("value",_TOOLBAR_START_OPEN);
	
	App.oButtonStart.tt_start.cfg.setProperty('text','Start a new Connection');	  
		
	//App.removeTopTen();
	App.removeFirstLevel();
	App.removeMembersList();
	App.clearGrids(originFlag);
	
	msg='task=closeSession&origin=deleteMarketObject&ses='+App.ses;
 	App.callback.argument[0] = 'JS';
	App.AjaxObject.syncRequest(App.aux,msg);
	console.log("deleteMarketObject - ch="+Meteor.channelcount); 
//alert("deleteMarketObject - ch="+Meteor.channelcount);	

	Dom.get('scroller_price').innerHTML = '-';
	Dom.get('scroller_isin').innerHTML = '-';
  }
				
 App.connectMeteor = function ()
 {
  Meteor.mode = 'stream'; //stream 'longpoll'; 
  Meteor.connect();
  YAHOO.log("connectMeteor mode:"+Meteor.mode,"info", "channels.js");
  }
  
 App.disconnectMeteor = function()
 {
	console.log("disconnectMeteor - Stat="+Meteor.status);
	Meteor.leaveChannel("events_"+App.ses);						
	Meteor.leaveChannel("orders_"+App.ses);
	Meteor.leaveChannel("trades_"+App.ses);
	Meteor.leaveChannel("hiloPrices_"+App.ses);	
	Meteor.leaveChannel("traders_"+App.ses);	
	Meteor.leaveChannel("accounts_"+App.ses);
//	Meteor.leaveChannel("OBS");
	Meteor.leaveChannel("MDB_"+App.ses);
	Meteor.leaveChannel("messages_"+App.ses);
//	Meteor.leaveChannel("MDS");	
	Meteor.disconnect();
  }
 
 App.createChannelsTable = function() 
 {
  if (App.channelsTable.length==0)
	{
	App.channelsTable=[];
	
	App.channelsTable['events_'+App.ses]=eventsService;
	App.channelsTable['events_'+App.ses][0]=[];
	App.channelsTable['orders_'+App.ses]=ordersService;
	App.channelsTable['orders_'+App.ses][0]=[];
	App.channelsTable['traders_'+App.ses]=tradersService;
	App.channelsTable['traders_'+App.ses][0]=[];
	App.channelsTable['trades_'+App.ses]=tradesService;
	App.channelsTable['trades_'+App.ses][0]=[];
	App.channelsTable['hiloPrices_'+App.ses]=hiloPricesService;
	App.channelsTable['hiloPrices_'+App.ses][0]=[];
	App.channelsTable['accounts_'+App.ses]=accountsService;
	App.channelsTable['accounts_'+App.ses][0]=[];
//	App.channelsTable['OBS']=OBSService;
	App.channelsTable['MDB_'+App.ses]=MDBService;
	App.channelsTable['MDB_'+App.ses][0]=[];
	App.channelsTable['messages_'+App.ses]=MessagesService;
	App.channelsTable['messages_'+App.ses][0]=[];
//	App.channelsTable['MDS']=MDSService;
	}
  }

 
  
 var channelSelector = function(datastr) 
 { 
  var channel = Meteor.currentChannel();
  if (!datastr)
     return;
//  YAHOO.log("channelSelector - "+channel,"info");
  
  YAHOO.lang.JSON.useNativeParse = false;
  if (!YAHOO.lang.JSON.isSafe(datastr))
       App.alert ("Channel "+channel +" data: is not Safe!");
	
   try {   
					var data = YAHOO.lang.JSON.parse(datastr); //'{"rows":[{"id":1,"data":[{"TIME":"08:05:28:34","PHASE":"PRECALLOPEN"}]}]}');    
					//alert(data1.toSource());
 					}   
 			catch (e) {   
    					 App.alert("Invalid JSON data from channel: "+channel+"=="+datastr);   
 						}   
// YAHOO.log("channelSelector - "+channel,"info");
 App.channelsData.push(channel);
 App.channelsData.push(data);
 //App.channelsTable[channel](data);
// alert(App.channelsData.shift());


// App.channelsTable[App.channelsData.shift()](App.channelsData.shift());
 
 datastr=null;
// App.purge(data);
 data=null;

// msg='task=setEngineAvail'+"&Ch="+channel+"&data=1"+App.ses_str;
// App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
 };

 var MessagesService = function (data) 
 { 
     var messages=data.rows;
	 if (messages[0].id==2){
		var msg=parseInt((parseInt(messages[0].data[0].MSG * 100) / App.progressMax));
		App.progress_win.setHeader('Progress... '+msg+'%');
	 }
	 else
	  if (messages[0].id==1)	{ 
		  App.progressMax=parseInt(messages[0].data[0].MSG);
		  
		  App.progress_win.setHeader("Start searching, please wait...");
		  App.progress_win.show();
	  }
	  else
	  if (messages[0].id==9){
	      App.progress_win.setHeader(messages[0].data[0].MSG);
		  App.progress_win.hide();
	  }
	else	
     for(var i = 0; i < messages.length; i++) 
      {
		  YAHOO.log(messages[i].data[0].MSG,"error");
	   }
  App.processIsFree=true;	   
 }
 
 
 var eventsService = function (data) 
 { 
     var events=data.rows;
     for(var i = 0; i < events.length; i++) 
      {
	    if (events[i].data[0].PHASE=='STEP_BACK') {
			 YAHOO.log(events[i].data[0].PHASE, "mkt_event");
			 App.StepBackSlider();
			 App.processIsFree=true;
			 return;
			}
		
		var ar_signal=App.sub_signal.split(":");
		if (ar_signal[0]=="CUSTOM_FWRD") {
			var ev_tr=App.mygrid_events.getLastTrEl();
			if (ev_tr) {
				var ar=Dom.getChildren(ev_tr);
				if (Dom.hasClass(ar[0],"timer"))
					Dom.removeClass(ar[0],"timer");
				}
		    }
		
		App.mygrid_events.addRow(data.rows[i].data[0]);
		Dom.get('scroller_phase').innerHTML = events[i].data[0].PHASE;
		//phase = events[i].data[0].PHASE;
		if (ar_signal[0]=="CUSTOM_FWRD") {
			var ev_tr=App.mygrid_events.getLastTrEl();
			var ar=Dom.getChildren(ev_tr);
			Dom.addClass(ar[0],"timer");
//			YAHOO.log("eventsService - "+ar.length+"=="+ar[0].innerHTML,"info");
		    }
		
		//alert(channel+"=="+events[i].data[0].PHASE);
	  	if (events[i].data[0].PHASE=='PRICE') {
		 	var execCode="";
		//	alert(events.toSource()+"=="+events[0].data[0].PHASE+"=="+events.length );
			App.marketPause();
			Dom.get('oprice').innerHTML = events[i].data[0].TIME;
			App.signal="PAUSE_OPENPRICE";
						
			var ar_signal=App.sub_signal.split(":");
		    if (ar_signal[0]!=="CUSTOM_FWRD")
			   {
				if (App.mygrid_orders.projected_so=='')
					App.mygrid_orders.createProjectedGraph();
				 else
				   if (App.mygrid_orders.flashProjected) 
				  		App.mygrid_orders.flashProjected.reloadData();
				App.projected_win.show();
				}
			}
		else if (events[i].data[0].PHASE=='PRICE') {
		 	     Dom.get('oprice').innerHTML = events[i].data[0].TIME;
			}
		else if (events[i].data[0].PHASE=='FWRD_PAUSED') {
			App.sub_signal="";
			App.marketSpeed();
			}
		else if (events[i].data[0].PHASE=='CUSTOM_PERIOD_START') {
			App.sub_signal+=":START";
				
			App.marketSlowTrade();
			
			//App.marketPause();
			//App.flashBidAsk.setZoom(App.fdate+' '+App.custom_start, App.fdate+' '+App.custom_end);
			App.bidask_lastTime=App.custom_end;	
			}
		else if (events[i].data[0].PHASE=='CUSTOM_PERIOD_STOP') {
			//YAHOO.log("CUSTOM_FWRD:STOP", "mkt_event");
			App.sub_signal=''; //"CUSTOM_FWRD:STOP";
			App.marketPause();
			var ev_tr=App.mygrid_events.getLastTrEl();
			if (ev_tr) {
				var ar=Dom.getChildren(ev_tr);
				if (Dom.hasClass(ar[0],"timer"))
					Dom.removeClass(ar[0],"timer");
				}
			//App.marketSlowTrade();
			}
		else if (events[i].data[0].PHASE=='MARKET_CLOSED') {
		 	var execCode="";
			App.signal="STOP";
	  		msg='task=stop_trade'+"&data=s"+App.ses_str;
			App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
			}
      }
  App.processIsFree=true;
 };

var ordersService = function (data)
{
  //YAHOO.log("Orders 1:","warn");
  App.AdvanceSlider(0);
 // YAHOO.log("Orders 2:","warn");  
	    var ar_signal=App.sub_signal.split(":");
		var rows=data.rows;
		var filt=true;
		var filtOrd=false;
		App.last_order_time=App.split_time(rows[0].data[0].OASIS_TIME);

	    if (App.sub_signal=="FWRD") {
			
		   if ( App.mkt_slider.getRealValue() >= App.stop_steps-App.maxOrders) 
 	 	       { //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
			      //App.mygrid_trades.addRow(data.rows[0].data[0]);
				  App.mygrid_trades.getRecordSet().addRecord(data.rows[0].data[0]);
			   }
		   if ( App.mkt_slider.getRealValue() == App.stop_steps)
		       App.sub_signal="";
		   }
		  else if (ar_signal[0]=="CUSTOM_FWRD") 
				{
				 if (ar_signal[1]=="START") 
			         //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
					// YAHOO.log("Orders 1:"+rows.length,"warn");
					 //App.mygrid_trades.addRow(data.rows[0].data[0]);
					 App.mygrid_trades.getRecordSet().addRecord(data.rows[0].data[0]);
					 //YAHOO.log("Orders 2:"+rows.length,"warn");
				 }    
		  else {
		     //mygrid_orders.parse(data,Orders_calculateFooterValues(1),"json");
			   filt=true;
			   if (App.createTradesGrid.filter!=null){	
			   //YAHOO.log("Orders filter:"+App.createOrdersGrid.filter,"warn");
				   filt=false;
				   if (data.rows[0].data[0].TRADER_ID.substr(0,2)==App.createTradesGrid.filter.substr(0,2))
				   		filt=true;
			   	   }
//				   YAHOO.log("Orders filter:"+App.createOrdersGrid.filterOrder+"=="+App.createOrdersGrid.scenarioOrder,"warn");
				if ( App.createTradesGrid.filterOrder !=null){
					if ( App.createTradesGrid.scenarioOrder == 'filter')
					     if (data.rows[0].data[0].ORDER_NUMBER == App.createTradesGrid.filterOrder){
					         filt=true;
					         }
					        else
					  		    filt=false;
					if ( App.createTradesGrid.scenarioOrder == 'pause') {
						      var jfld="data.rows[0].data[0]." + App.createTradesGrid.fieldOrder + ' '+App.createTradesGrid.fieldCond+' ' + "'"+App.createTradesGrid.filterOrder+"';";
					//	 YAHOO.log(jfld+ "=="+ App.createOrdersGrid.filterOrder,"warn");
						      filtOrd=eval(jfld);
							  }
				    }
				
			    if (filt==true)	 
					    //App.mygrid_trades.addRow(data.rows[0].data[0]);
						App.mygrid_trades.getRecordSet().addRecord(data.rows[0].data[0]);
				
			   }
		  
//({rows:[{id:"00012510", data:[{OASIS_TIME:"08:05:28:35", PHASE_ID:" ", SIDE:"B", ORDER_NUMBER:"00012510", VOLUME:"50", PRICE:"10.7", TRADER_ID:"EX00C", ORDER_STATUS:"O", M_C_FLAG:"C"}]}]})	
		//var rows=data.rows;
		var isin=document.getElementById('isin').value;
		
		App.isinAr[isin][_SEC_TABLE_ORDERS_IDX] += rows.length;
		App.xOrder = App.isinAr[isin][_SEC_TABLE_ORDERS_IDX];	// App.xOrder UPDATED falso from StepBack action
//	alert(rows[0].data[0].toSource() +"=="+App.xOrder+"=="+rows[0].id);
		Dom.get('otime').value = rows[0].data[0].OASIS_TIME;
		if (App.sub_signal=="FWRD") {
			Dom.get('totalOrders').innerHTML = App.stop_steps;
			}
		Dom.get('next_order').innerHTML = App.xOrder;
			
//	    nOrders=mygrid_orders.getRowsNum();
		recs = App.mygrid_trades.getRecordSet().getRecords();
		App.nOrders=recs.length;

	    if ( App.xOrder &&  App.nOrders > App.maxOrders) {
		
		//recs.splice(0,5);
		//mygrid_orders.deleteRows(1,9);
	 		for( i = 0; i < App.nOrders-App.maxOrders; i++) {
			   // mygrid_orders.selectRow(i);
			//++	App.mygrid_trades.deleteRow(i);
				App.mygrid_trades.getRecordSet().deleteRecord(i);
//	  			mygrid_orders.selectRow(i, false, true, false);
				//alert(i+"==="+mygrid_orders.getRowId(i)+" New:"+rows[0].id);	
				}
			
	//		mygrid_orders.deleteSelectedRows();
				
			//alert(xTrade +"=x=="+  nTrades+"==="+rows.length+"==="+mygrid_trades.getRowsNum()+"=="+mygrid_trades.getRowIndex(219));
		}
 
 App.mygrid_trades.render(); 

 if ( filtOrd==true && App.createTradesGrid.scenarioOrder == 'pause'){
	App.oButtonStart.set("id","button_play"); 
	App.oButtonStart.set("value",_TOOLBAR_START_PLAY);

	App.oButtonStart.tt_start.cfg.setProperty('text','Continues Trading into the current Market phase');
//alert("marketPause");			
	App.signal="PAUSE";		
	msg='task=pause_trade'+"&data=p"+App.ses_str;
	App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
	YAHOO.log("marketPause","info","main.js");					
	 }
 data=null;
 App.processIsFree=true;
 };

/*-----------TRADES---------------*/

var tradesService = function (data)
{
 var i=0;
 var filter=1;
 var rows=data.rows;

if (rows[0].id=="members") {
	App.getMembers(data);
	App.processIsFree=true; 
	return;
}

 	  var ar_signal=[];
	  ar_signal = App.sub_signal.split(":");
// YAHOO.log("Tradeof:"+rows.length+"=="+rows[0].data[0].SELL_ORDER_NUMBER+"=="+App.sub_signal+"=="+ar_signal[0],"warn" );  
	  if (App.sub_signal=="FWRD") {
		  if ( App.mkt_slider.getRealValue() >= App.stop_steps - App.maxTrades) 
	 			//mygrid_trades.parse(data, calculateFooterValues(rows.length), "json");
				for( i = 0; i < rows.length; i++)
					       //App.mygrid_trades.addRow(data.rows[i].data[0]);
						   //App.mygrid_trades.getRecordSet().addRecord(data.rows[i].data[0]);
						   App.mygrid_trades.getRecordSet().addRecord(data.rows[i].data[0]);
	 	}
		else if (ar_signal[0]=="CUSTOM_FWRD") 
				{
				 if (ar_signal[1]=="START") 
			        // mygrid_trades.parse(data, calculateFooterValues(rows.length), "json");
//				YAHOO.log("Tradeof CUSTOM_FWRD:"+rows.length+"=="+rows[0].data[0].SELL_ORDER_NUMBER,"warn" ); 	
					for( i = 0; i < rows.length; i++)
					       //App.mygrid_trades.addRow(data.rows[i].data[0]);
						   App.mygrid_trades.getRecordSet().addRecord(data.rows[i].data[0]);
				 } 
			 	else {
				       
					  //YAHOO.log("nTrades:"+App.nTrades+"=="+rows.length,"warn");	
					  for( i = 0; i < rows.length; i++)
					  	{
 						 filter=1;
	 				      if (App.createTradesGrid.Buyfilter!=null)	
				   			  if (data.rows[0].data[0].BUY_TRADER_ID.substr(0,2)==App.createTradesGrid.Buyfilter.substr(0,2)) filter=1; else filter=0;
						  if (filter && (App.createTradesGrid.Sellfilter!=null))	  
						      if (data.rows[0].data[0].SELL_TRADER_ID.substr(0,2)==App.createTradesGrid.Sellfilter.substr(0,2)) filter=1; else filter=0;
						  if (filter){	  
						  	  //App.mygrid_trades.addRow(data.rows[i].data[0]);
							  App.mygrid_trades.getRecordSet().addRecord(data.rows[i].data[0]);
							 // 	if (data.rows[i].data[0].SELL_ORDER_NUMBER=='30279')
	 							
							  }
						  }
					}
					
					
	
	var recs = App.mygrid_trades.getRecordSet().getRecords();
	App.nTrades=recs.length;
	var isin=document.getElementById('isin').value;
	App.isinAr[isin][_SEC_TABLE_TRADES_IDX] += rows.length;
	App.xTrade=App.isinAr[isin][_SEC_TABLE_TRADES_IDX];
	Dom.get('next_trade').innerHTML=App.xTrade;
		
	if (i)  // in above some case, a trade inserted!
	   {
		if ( App.xTrade > 0 &&  App.nTrades > App.maxTrades) {		 
			  qt=App.nTrades-App.maxTrades;
			  App.mygrid_trades.getRecordSet().deleteRecords(0,qt);
		 	  }
		
		var ul_blist = new YAHOO.util.Element('mem_blist');
		var ul_slist = new YAHOO.util.Element('mem_slist');
		for( i = 0; i < rows.length; i++)
			{
			 App.afterParseTrades(rows[i].id,rows[i].data[0].OASIS_TIME,rows[i].data[0].PRICE);
			 //App.xTrade++;
			 //App.nTrades++;
			 var memId=rows[i].data[0].BUY_TRADER_ID.substr(0,2);
			 li = Dom.get('B_'+memId);
			 if (!li) {
			     li = ul_blist.appendChild(createDOMElement('li'));
				 li.setAttribute("id","B_"+memId);
			 	}
			 li.innerHTML='<div id="memline">'+'<span id="memparticip">'+memId+':&nbsp;</span>'+'<a id="S'+memId+'" href=#>'+'<span id="memval">'+rows[i].data[0].MBVAL+'</span>'+"&nbsp;"+'<span id="mempct">'+rows[i].data[0].MBPCT+'%'+'</span></div>'+'</a>';
			 
			 memId=rows[i].data[0].SELL_TRADER_ID.substr(0,2);
			 li = Dom.get('S_'+memId);
			 if (!li) {
			 	li = ul_slist.appendChild(createDOMElement('li'));
				li.setAttribute("id","S_"+memId);
				 }
			 li.innerHTML='<div id="memline">'+'<span id="memparticip">'+memId+':&nbsp;</span>'+'<a id="S'+memId+'" href=#>'+'<span id="memval">'+rows[i].data[0].MSVAL+'</span>'+"&nbsp;"+'<span id="mempct">'+rows[i].data[0].MSPCT+'%'+'</span></div>'+'</a>';
			}
	    }
	 
     App.mygrid_trades.render();
	 
	 if (App.signal=="PAUSE_OPENPRICE") {
	        App.marketRun();
			}

 data=null;
 // SET by mktGrids - amProcessCompleted - Graph
 //App.processIsFree=true;
 };
    
var createDD = function (ID)
{
	dd = new App.DDList (ID,"buyers"); //App.DDList(rows[i].data[0].ACCOUNT_ID);
	  dd.useShim = true;
//	   YAHOO.log('createDD dragged.'+ID);
	/*  dd.on('startDragEvent', function() { 
									   YAHOO.log('Element is being dragged.');
                 Dom.setStyle(this.getDragEl(), 'height', '20px');   
                 Dom.setStyle(this.getDragEl(), 'width', '100px');   
                 Dom.setStyle(this.getDragEl(), 'backgroundColor', 'blue');   
                 Dom.setStyle(this.getDragEl(), 'color', 'white');   
                 this.getDragEl().innerHTML = 'Custom Proxy';   
             }); */
	  }



var tradersService = function (data)
{
 var rows=data.rows;
 
 YAHOO.log("tradersService="+rows.length+"=="+rows[0].data[0].SIDE,"warn");
// var div_buyers = new YAHOO.util.Element('div_buyers');
 var blist = new YAHOO.util.Element('blist');
 var mem_blist = new YAHOO.util.Element('mem_blist');
// var div_sellers = new YAHOO.util.Element('div_sellers');
 var slist = new YAHOO.util.Element('slist');
 var mem_slist = new YAHOO.util.Element('mem_slist');


 for(var i = 0; i < rows.length; i++)
 {
  if (rows[i].data[0].SIDE=="B")
     {
		//YAHOO.log("tradersService = "+rows[i].data[0].AID,"warn");
	  var li = blist.appendChild(createDOMElement('li'));
	  li.setAttribute("id","B_"+rows[i].data[0].AID);
	
	  var div_text=createDOMElement('div');
	    div_text.setAttribute('id', "div_B_"+rows[i].data[0].AID);
	  //	div_text.style.float="left";
   	  	div_text.style.position="absolute";
	  	div_text.style.top="0px";
		div_text.style.zIndex=1000;
		div_text.style.padding="2px 0 0 0";
	  	div_text.innerHTML="<span style='width:70px;text-align:center;color:#007700;'>"+rows[i].data[0].AID+"</span><br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	
	   
	   baccs_ar[rows[i].data[0].AID]=Array();
	   baccs_ar[rows[i].data[0].AID][0]=Array();
	   //baccs_ar[rows[i].data[0].AID][1]=Array();
	   //baccs_ar[rows[i].data[0].AID][1]="<span style='width:70px;text-align:center;color:#007700;'>"+rows[i].data[0].AID+"</span><br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	   //
	  // li.innerHTML=rows[i].data[0].AID+"<br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	   baccs_ar[rows[i].data[0].AID][0] = new YAHOO.widget.ProgressBar({
    		direction: "btt",
			anim: true,
    		minValue: 0,
			maxValue: 50,
			height: "50px",
    		width: "70px"
			}).render("B_"+rows[i].data[0].AID);
		var anim = baccs_ar[rows[i].data[0].AID][0].get('anim');
		anim.duration = 3;
		anim.method = YAHOO.util.Easing.bounceBoth;
		
		li.appendChild(div_text); 
//pb.setStyle('backgroundColor','green');

	 
	  
/*
	  divTag.setAttribute("id","div_B_"+rows[i].data[0].AID);
	  el.setStyle( "background-position", "left bottom");
	  el.setStyle( "position", "absolute");
	  el.setStyle( "background-repeat", "y");
	  el.setStyle( "width", "60px");
	  */
	  
	  
	//  createDD("B_"+rows[i].data[0].AID);
	  //YAHOO.log("tradersService = "+rows[i].data[0].AID+"=="+Dom.get("B_"+rows[i].data[0].AID),"warn");
	  }
  
  if (rows[i].data[0].SIDE=="MB")
     {
		//YAHOO.log("tradersService = "+rows[i].data[0].AID,"warn");
	  var li = mem_blist.appendChild(createDOMElement('li'));
	  
	  li.setAttribute("id","MB_"+rows[i].data[0].AID);
	  
	  li.innerHTML=rows[i].data[0].AID+"<br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	 // createDD("MB_"+rows[i].data[0].AID);
	  //YAHOO.log("tradersService = "+rows[i].data[0].AID+"=="+Dom.get("B_"+rows[i].data[0].AID),"warn");
	  }
  if (rows[i].data[0].SIDE=="S")
     {
		// YAHOO.log("tradersService="+rows.length+"=="+rows[i].data[0].SIDE,"warn");
	  var li = slist.appendChild(createDOMElement('li'));
	  li.setAttribute("id","S_"+rows[i].data[0].AID);
	  
	   var div_text=createDOMElement('div');
   	  	
		div_text.style.position="absolute";
	  	div_text.style.top="0px";
		div_text.style.padding="2px 0 0 0";
		div_text.style.zIndex=1000;
	  	div_text.innerHTML="<span style='width:70px;text-align:center;color:#770010;'>"+rows[i].data[0].AID+"</span><br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	
	   saccs_ar[rows[i].data[0].AID]=Array();
	   saccs_ar[rows[i].data[0].AID][0]=Array();
	   //
	  // li.innerHTML=rows[i].data[0].AID+"<br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	   saccs_ar[rows[i].data[0].AID][0] = new YAHOO.widget.ProgressBar({
    		direction: "btt",
			anim: true,
    		minValue: 0,
			maxValue: 50,
			height: "50px",
    		width: "70px"
			}).render("S_"+rows[i].data[0].AID);
		
		var anim = saccs_ar[rows[i].data[0].AID][0].get('anim');
		anim.duration = 3;
		anim.method = YAHOO.util.Easing.bounceBoth;
		
		li.appendChild(div_text); 
		
//	  li.innerHTML=rows[i].data[0].AID+"<br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	//  createDD("S_"+rows[i].data[0].AID);
	  	  
	  //li.innerHTML='<a href="javascript:">'+rows[i].data[0].MID+rows[i].data[0].AID+"</a>";
	  
	  }
   
   if (rows[i].data[0].SIDE=="MS")
     {
		//YAHOO.log("tradersService = "+rows[i].data[0].AID,"warn");
	  var li = mem_slist.appendChild(createDOMElement('li'));
	  li.setAttribute("id","MS_"+rows[i].data[0].AID);
	  
	  li.innerHTML=rows[i].data[0].AID+"<br> Vol:"+rows[i].data[0].VOL+"<br> %"+rows[i].data[0].PERC+"<br> Wish:"+rows[i].data[0].WANT;
	//  createDD("MS_"+rows[i].data[0].AID);
	  //YAHOO.log("tradersService = "+rows[i].data[0].AID+"=="+Dom.get("B_"+rows[i].data[0].AID),"warn");
	  }
  }
 App.processIsFree=true;
 }

App.removeTopTen = function()
{
 YAHOO.log("removeTopTen...","info"); 
 
  var blist = new YAHOO.util.Element('blist');
 var mem_blist = new YAHOO.util.Element('mem_blist');
// var div_sellers = new YAHOO.util.Element('div_sellers');
 var slist = new YAHOO.util.Element('slist');
 var mem_slist = new YAHOO.util.Element('mem_slist');
 
  var li;
  var li_child;
 //var ul_list = Dom.getElementsByClassName('mdfst_blist', 'ul');
  if (blist.hasChildNodes()) {
	  while( (li_child=blist.get('firstChild')) )
           blist.removeChild(li_child);
	 }
  if (slist.hasChildNodes()) {
	 while( (li_child=slist.get('firstChild')) )
           slist.removeChild(li_child);
	 }
 if (mem_blist.hasChildNodes()) {
	  while( (li_child=mem_blist.get('firstChild')) )
           mem_blist.removeChild(li_child);
	 }
  if (mem_slist.hasChildNodes()) {
	 while( (li_child=mem_slist.get('firstChild')) )
           mem_slist.removeChild(li_child);
	 }
 }

App.removeFirstLevel = function()
{
// YAHOO.log("removeFirstLevel...","info"); 
 
 var ul_list = new YAHOO.util.Element('mdfst_blist');
 var ul_slist = new YAHOO.util.Element('mdfst_slist');
 
  var li;
  var li_child;
 //var ul_list = Dom.getElementsByClassName('mdfst_blist', 'ul');
  if (ul_list.hasChildNodes()) {
//	  YAHOO.log("removeFirstLevel...mdfst_blist","info");
	  while( (li_child=ul_list.get('firstChild')) )
           ul_list.removeChild(li_child);
	 }
  if (ul_slist.hasChildNodes()) {
	 while( (li_child=ul_slist.get('firstChild')) )
           ul_slist.removeChild(li_child);
	 }
 }


var accountsService = function (data)
{
 if (!data)
 	 return;
	 
 var ul_list = new YAHOO.util.Element('mdfst_blist');
 var ul_slist = new YAHOO.util.Element('mdfst_slist');
  
 var rows=data.rows;

//YAHOO.log("accountsService..."+rows.length,"info"); 
 if (!rows.length)
 	 return;
	
 var key = rows[0].id.split('_');
 
 if (key[0]!='T')  // 1st MktDepth list
 {
  App.removeFirstLevel();
 }
	
 //for(var i=0; i<ul_list.length; i++)
   //  ul_list.removeChild();


// if (App.oButtonStart.get("value")==_TOOLBAR_START_PLAY)
//	 {
/*	 for (var n=0; n < baccs_ar.length && baccs_ar[n]; n++)
		 {
		  //YAHOO.log(" Baccs:" + "B_"+baccs_ar[n],"accbid");
		  Dom.setStyle(Dom.get("B_"+baccs_ar[n]), "background", "transparent url(yui/images/accs_bg.gif) repeat-x 0 0");
		  }
	 for (var n=0; n < saccs_ar.length && saccs_ar[n]; n++)
		 {
		  Dom.setStyle(Dom.get("S_"+saccs_ar[n]), "background", "transparent url(yui/images/accs_bg.gif) repeat-x 0 0"); 
		  }
*/		  
   // init again account array
	// baccs_ar=[];
	// saccs_ar=[];
 
 	 var b_str='';
	 for(var i = 0; i < rows.length; i++)
		 {
		  
		//	 YAHOO.log(" T:" + rows[i].data[0].T + " Acc:" + rows[i].data[0].ACC,"accbid");
		  //baccs_ar[i]=rows[i].data[0].ACC;
		  if (key[0]=='T') // TopTraders
		  {
		   var el = Dom.get("B_"+rows[i].data[0].ACC);
		   if (el!=null){
			  var pct=Math.round(50*parseFloat(rows[i].data[0].BPCT)/100);
			  //YAHOO.log(" B:" + rows[i].data[0].ACC+"=="+ pct,"accbid");
			  baccs_ar[rows[i].data[0].ACC][0].set('value',pct);
			 // Dom.get("div_B_"+rows[i].data[0].ACC).innerHTML=baccs_ar[rows[i].data[0].ACC][1];
		  	  }
		   }
		   else
			  if (rows[i].data[0].ACC != '')
			  {
			  	li = ul_list.appendChild(createDOMElement('li'));
				//YAHOO.log(" B:" + rows[i].data[0].ACC,"accbid");
	  	  	//li.setAttribute("id","B_"+rows[i].data[0].ACC);
	  	  		li.innerHTML='<span width="4" class="'+'colorlevel'+rows[i].data[0].L+'">&nbsp;</span>&nbsp;'+'<a id="colorlevel'+rows[i].data[0].L+'" href=#>'+rows[i].data[0].ACC+'</a>';
		    	}
	  
		//  baccs_ar[i][0]=new YAHOO.widget.Tooltip("B_"+rows[i].data[0].ACC+rows[i].data[0].V,    
         //               { context:"B_"+rows[i].data[0].ACC,    
         //                 text:"Vol:" + rows[i].data[0].V + " T:" + rows[i].data[0].T});   

		  	//YAHOO.log("Vol:" + rows[i].data[0].SV + " T:" + rows[i].data[0].ST + " Acc:" + rows[i].data[0].SACC,"accask");
		  //saccs_ar[i]=rows[i].data[0].SACC;
		  if (key[0]=='T') // TopTraders
		  {
		   var el = Dom.get("S_"+rows[i].data[0].SACC);
		   if (el!=null){
		   		var pct=Math.round(50*parseFloat(rows[i].data[0].SPCT)/100);
		   		saccs_ar[rows[i].data[0].SACC][0].set('value',pct);
		   		}
		   }
		   else
			  if (rows[i].data[0].SACC != '')
				  {
		  			li = ul_slist.appendChild(createDOMElement('li'));
		  	  	//li.setAttribute("id","S_"+rows[i].data[0].SACC);
			  	  	//li.innerHTML=rows[i].data[0].SACC;
					li.innerHTML='<span width="4" class="'+'colorlevel'+rows[i].data[0].L+'">&nbsp;</span>&nbsp;'+'<a id="colorlevel'+rows[i].data[0].L+'" href=#>'+rows[i].data[0].SACC+'</a>';
		    		}
			
		 // saccs_ar[i][0]=new YAHOO.widget.Tooltip("S_"+rows[i].data[0].SACC+rows[i].data[0].SV,    
         //               { context:"S_"+rows[i].data[0].SACC,    
          //                text:"Vol:" + rows[i].data[0].SV + " T:" + rows[i].data[0].ST });  
		  }
		  //Dom.get('div_mktdepth_bid_traders').innerHTML="<p>"+b_str+"</p>";
	 //}
	App.processIsFree=true; 
 }
 

App.getAccountInfo = function(id) {
	alert(id);
}


App.getMembers = function (data)
{
 var rows=data.rows;
 
 var ul_blist = new YAHOO.util.Element('mem_blist');
 var ul_slist = new YAHOO.util.Element('mem_slist');
		
 for (var i = 0; i < rows[0].data.length; i++){
			 if (rows[0].data[i].MBVAL!='0' && rows[0].data[i].MBVAL!='')
			 {
			 var memId=rows[0].data[i].MBKEY;
			 li = Dom.get('B_'+memId);
			 if (!li) {
			     li = ul_blist.appendChild(createDOMElement('li'));
				 li.setAttribute("id","B_"+memId);
			 	}
			 li.innerHTML='<div id="memline">'+'<span id="memparticip">'+memId+':&nbsp;</span>'+'<a id="S'+memId+'" href=#>'+'<span id="memval">'+rows[0].data[i].MBVAL+'</span>'+"&nbsp;"+'<span id="mempct">'+rows[0].data[i].MBPCT+'%'+'</span></div>'+'</a>';
			 }
			 if (rows[0].data[i].MSVAL!='0' && rows[0].data[i].MSVAL!='')
			 {
			 memId=rows[0].data[i].MSKEY;
			 li = Dom.get('S_'+memId);
			 if (!li) {
			 	li = ul_slist.appendChild(createDOMElement('li'));
				li.setAttribute("id","S_"+memId);
				}
			 li.innerHTML='<div id="memline">'+'<span id="memparticip">'+memId+':&nbsp;</span>'+'<a id="S'+memId+'" href=#>'+'<span id="memval">'+rows[0].data[i].MSVAL+'</span>'+"&nbsp;"+'<span id="mempct">'+rows[0].data[i].MSPCT+'%'+'</span></div>'+'</a>';
			 }
 }
}
 
App.removeMembersList = function()
{
 YAHOO.log("removeMembersList...","info"); 
 
 var mem_blist = new YAHOO.util.Element('mem_blist');
 var mem_slist = new YAHOO.util.Element('mem_slist');
 
  var li;
  var li_child;
 //var ul_list = Dom.getElementsByClassName('mdfst_blist', 'ul');
  
 if (mem_blist.hasChildNodes()) {
	  while( (li_child=mem_blist.get('firstChild')) )
           mem_blist.removeChild(li_child);
	 }
  if (mem_slist.hasChildNodes()) {
	 while( (li_child=mem_slist.get('firstChild')) )
           mem_slist.removeChild(li_child);
	 }
 }

App.afterParseTrades = function (Id,time,price)
{
	if (Id==null)
		return;

	Dom.get('price').innerHTML = App.start_price + " => "+ price;
	Dom.get('ttime').value = time;
	newImage = (price > App.prev_price) ?"../images/indices_up.gif" :((price < App.prev_price) ?"../images/indices_down.gif" :"../images/stable.gif");
	document['index_img'].src=newImage;
	//document.getElementById('index_img').src = newImage;
	App.prev_price=price;
	
	div = (parseFloat(price)/parseFloat(App.start_price))-1;
	num1 = Math.pow(10, 2);
	div = div*100;
	pct_price_var = Math.round(div*num1)/num1;
	Dom.get('pct_var').innerHTML = pct_price_var;
 }

var hiloPricesService = function (data)
{
 var rows=data.rows;
 var isin=document.getElementById('isin').value;
 
 if (rows[0].id != App.isinAr[isin][0])
 	 return;
	
 	Dom.get('low').innerHTML = rows[0].data[0].lo;
	Dom.get('high').innerHTML = rows[0].data[0].hi;
	Dom.get('trade').innerHTML = rows[0].data[0].trade;
	Dom.get('scroller_price').innerHTML = rows[0].data[0].trade;
	Dom.get('last').innerHTML = rows[0].data[0].last;
	Dom.get('buy').innerHTML = rows[0].data[0].bprice;
	Dom.get('sell').innerHTML = rows[0].data[0].aprice;
	App.bid_1 = rows[0].data[0].bid;
	App.ask_1 =  rows[0].data[0].ask;
	
	if (typeof(rows[0].data[0].rtrades) != 'undefined'){
		
		var isin=document.getElementById('isin').value;
	    App.isinAr[isin][_SEC_TABLE_TRADES_IDX] = rows[0].data[0].rtrades;
		App.xTrade = rows[0].data[0].rtrades;
		Dom.get('next_trade').innerHTML=App.xTrade;
		App.xOrder = rows[0].data[0].rorders;
		App.isinAr[isin][_SEC_TABLE_ORDERS_IDX] = rows[0].data[0].rorders;
		Dom.get('next_order').innerHTML = App.xOrder;
		App.isinAr[isin][_SEC_TABLE_MARKET_STEPS]=0;
		App.start_price = rows[0].data[0].price;
		Dom.get('price').innerHTML = App.start_price;
	YAHOO.log("hiloPricesService:  "+App.xTrade+"=="+App.xOrder,"mkt_event");
		App.mkt_slider.animate=true; //config.
		App.mkt_slider.animationDuration=2;
		App.AdvanceSlider(App.xOrder);
		App.mkt_slider.animate=false;
		//App.flashBidAsk.rebuild();
	}
 

data=null;
App.processIsFree=true;
};
 
var OBBService = function (data)
{
 };
 
var OBSService = function (data)
{
 };
 
var MDBService = function (data)
{
	var insert_flag='no';
	var rows=data.rows;

    if (rows[0].id=='SURV') {
		var cols = rows[0].data;
		//var oRec=App.mygrid_bidsurv.getRecordSet();
	//YAHOO.log("MDBService:"+oRec.length+"=="+cols[0].T,"mkt_event");
		for (var i = 0; i < cols.length; i++){
			App.mygrid_bidsurv.getThLinerEl("t"+(i+1)).innerHTML=cols[i].T;
			App.mygrid_asksurv.getThLinerEl("t"+(i+1)).innerHTML=cols[i].T;
			}
		for (var i = cols.length; i < 5; i++){
			App.mygrid_bidsurv.getThLinerEl("t"+(i+1)).innerHTML='t'+(i+1);
			App.mygrid_asksurv.getThLinerEl("t"+(i+1)).innerHTML='t'+(i+1);
			}
		//if (typeof(oRec.length)=='undefined')	
		App.mygrid_bidsurv.deleteRows(0,5);
		App.mygrid_asksurv.deleteRows(0,5);
		for(i=0; i<5; i++) {
		    App.mygrid_bidsurv.addRow({ LEVEL: i+1, t1: 0, t2: 0, t3: 0, t4: 0,t5: 0 });
			App.mygrid_asksurv.addRow({ LEVEL: i+1, t1: 0, t2: 0, t3: 0, t4: 0,t5: 0 });
			}
		App.processIsFree=true;	
		return;
	}
	
	var ar_signal=App.sub_signal.split(":");
		
		//if (mdb_so=='')		
		//	createMDbGraph();
				
		
 	 		  if (App.sub_signal=="FWRD") 
			     {
		  		  if ( App.mkt_slider.getRealValue() >= App.stop_steps-10) 
					 {
	 					insert_flag='ok';
					  }
	 			  }
				 else  
				 		if (ar_signal[0]=="CUSTOM_FWRD") 
						{
				 			 if (ar_signal[1]=="START")  
							    {
			        			 insert_flag='ok';
								 }
				 		 } 
						 else
	 						{
 	 		  				insert_flag='ok';
							}
	
	if ( insert_flag == 'ok')
	{
	 var oRec=App.mygrid_mdb.getRecordSet();
							//for (var i = 0; i < rows.length; i++)
								//App.mygrid_mdb.addRow(data.rows[i].data[0]);
	 App.bid_1 = rows[0].data[0].bid;
	 App.ask_1 = rows[0].data[0].ask;

	 for (var i = 0; i < rows.length; i++){
	  	 var exist=oRec.hasRecords(i,1);
		 if (exist==true){
			 surv=rows[i].data[0].surv;
			 if (surv.length) {
			 	 if (surv.SERB!=-1 && (typeof(surv[0].LB) != 'undefined') ){
				 	    var obRec=App.mygrid_bidsurv.getRecordSet();
						for( var v=0; v<surv.length; v++){
							if (parseInt(surv[v].SERB)>-1)
							{
							 for( var s=0; s<5; s++)
							    App.mygrid_bidsurv.updateCell(obRec.getRecord(s), 't'+surv[v].SERB, 0);
						     App.mygrid_bidsurv.updateCell(obRec.getRecord(parseInt(surv[v].LB)),'t'+surv[v].SERB, surv[v].SBP);
							
//							 YAHOO.log("MDBService:"+obRec.getRecord(parseInt(surv[v].LB))+"=="+parseInt(surv[v].LB)+"=="+'t'+surv[v].SERB,"mkt_event");
							 }
							}
						}
				  if (surv.SERS!=-1 && (typeof(surv[0].LS) != 'undefined') ){
						var osRec=App.mygrid_asksurv.getRecordSet();
						for( var v=0; v<surv.length; v++){
							if (parseInt(surv[v].SERS) >-1 )
							{
						     for( var s=0; s<5; s++)
							     App.mygrid_asksurv.updateCell(osRec.getRecord(s), 't'+surv[v].SERS, 0);
							 App.mygrid_asksurv.updateCell(osRec.getRecord(parseInt(surv[v].LS)),'t'+surv[v].SERS, surv[v].SSP);
							 //YAHOO.log("MDBServiceASK:"+surv[v].LB+"=="+'t'+surv[v].SERB,"mkt_event");
							 }
							}
						//YAHOO.log("MDBServiceASK:"+surv[0].LS+"=="+surv[0].SSUT+surv.length,"mkt_event");		
					    }
			 	}
						 //YAHOO.log("MDBService IDX:"+i+"=="+rows[i].data[0].P,"mkt_event");
						 var oData = {'ENTRIES':rows[i].data[0].E,
						 							   'BID':rows[i].data[0].B,
													   'PRICE':(rows[i].data[0].P=='99999' ?'0' :rows[i].data[0].P),
													   
													   'SPRICE':rows[i].data[0].SP,
													   'ASK':rows[i].data[0].A,
													   'SENTRIES':rows[i].data[0].SE,
													   
													   't':i };
																	  
						 App.mygrid_mdb.updateRow(i,oData); //oRec.getRecord(i)
																	   
					/*	 App.mygrid_mdb.updateCell(oRec.getRecord(i),'PRICE',(rows[i].data[0].P=='99999' ?'0' :rows[i].data[0].P) );
						  App.mygrid_mdb.updateCell(oRec.getRecord(i),'ENTRIES',rows[i].data[0].E);
						  App.mygrid_mdb.updateCell(oRec.getRecord(i),'BID',rows[i].data[0].B);  // BID
						  App.mygrid_mdb.updateCell(oRec.getRecord(i),'SPRICE',rows[i].data[0].SP);
						  App.mygrid_mdb.updateCell(oRec.getRecord(i),'SENTRIES',rows[i].data[0].SE);
						//  if(!i)
						  	 App.mygrid_mdb.updateCell(oRec.getRecord(i),'t',i); //(rows[0].data[0].t=='0' ?'n' :'t')
						  App.mygrid_mdb.updateCell(oRec.getRecord(i),'ASK',rows[i].data[0].A); // ASK
						*/  
					  	  }
						 else {
						  		App.mygrid_mdb.addRow(data.rows[i].data[0]);
						  		App.processIsFree=true;
							 	}
		   }		
	
	//if (App.mygrid_mdb.getRecordSet().getRecords()[0].getData("t")=='t')
	  //  YAHOO.log("MDBService:  1","mkt_event");
	}
							
 data=null;
// App.processIsFree=true;
 };
 
var MDSService = function (data)
{
 }; 
	


App.marketRun = function ()
{
	App.pauseCount=10;
	//webBar.setItemText('START','Run');
  	//webBar.setItemImage('START', 'playtrade.png');
	App.oButtonStart.set("id","button_pause"); 
	App.oButtonStart.set("value",_TOOLBAR_START_PAUSE);

	//	marketFastTrade();
	App.marketSpeed();
	App.signal="RUN";	
	YAHOO.log("marketRun","info","main.js");
 }


App.marketPause=function ()
{
	//webBar.setItemText('START','Pause');
  	//webBar.setItemImage('START', 'pausetrade.png');
	App.oButtonStart.set("id","button_play"); 
	App.oButtonStart.set("value",_TOOLBAR_START_PLAY);

	//alert("marketPause");			
	App.sendMarketPause();
}

App.sendMarketPause = function ()
{
	App.signal="PAUSE";		
	msg='task=pause_trade'+"&data=p"+App.ses_str;
	App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
	YAHOO.log("marketPause","info","main.js");
 }

App.marketSpeed = function ()
{
 if (App.sub_signal=="")
    var sp=60;
  else {
	    var ar_signal=App.sub_signal.split(":");
		if (ar_signal[0]=="CUSTOM_FWRD") 
			var sp=1;
		}
 if (sp==1)	
 	App.marketFastTrade();
  else
  	App.marketSlowTrade();
 }
 
App.marketSlowTrade = function()
{
// webBar.setValue("S1", 60);
 //App.delay = webBar.getValue("S1")*10000;
 App.vslider.setValue(60,true,true,false);
 App.delay=App.vslider.getRealValue();
// msg='task=set_delay'+"&data="+delay+":r"+ses_str;
 msg='task=run_trade'+"&data="+App.delay+":r"+App.ses_str;
 App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
 YAHOO.log("marketSlowTrade","info","main.js");
 }

App.marketFastTrade = function ()
{
 App.vslider.setValue(1,true,true,false);
 App.delay=App.vslider.getRealValue();
 //webBar.setValue("S1",  1);
 //App.delay=webBar.getValue("S1")*10000;
 msg='task=run_trade'+'&execWhenReturn='+execCode+"&data="+App.delay+":r"+App.ses_str;
 App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
 YAHOO.log("marketFastTrade","info","main.js");
 }
						
App.Orders_calculateFooterValues = function (nRows){
        var nrQ = Dom.get("nr_orders");
        nrQ.innerHTML = App.xOrder+App.nRows;      
    }   
	
App.calculateFooterValues = function (nRows){
        var nrQ = Dom.get("nr_q");
        nrQ.innerHTML = App.xTrade+App.nRows;
        var srQ = Dom.get("sr_q");
        srQ.innerHTML = App.sumColumn(7);
      
    }
    
App.sumColumn = function (ind){      
        for(var i=0;i<App.mygrid_trades.getRowsNum();i++){
            total_trades_val+= parseFloat(App.mygrid_trades.cells2(i,ind).getValue())
        }
        return total_trades_val;
    }
	
App.tradesClick = function (iteration)
{
var row = Dom.get( iteration );
row.style.backgroundColor= "#fefdc1";
}

App.StepBackSlider = function()
{
 var isin=document.getElementById('isin').value;
 App.mkt_slider.setRealValue(--App.isinAr[isin][_SEC_TABLE_MARKET_STEPS]);
 Dom.get('next_order').innerHTML = --App.isinAr[isin][_SEC_TABLE_ORDERS_IDX];
 }

App.AdvanceSlider = function (nTicks)
{
	//webBar.setValue("S2", market_steps+step);
	if (App.sub_signal=="SET_FWRD")
		return;
//YAHOO.log("AdvanceSlider - "+nTicks,"warn");
	var steps=(parseInt(nTicks)==0) ?App.step :nTicks;
	var isin=document.getElementById('isin').value;
	App.isinAr[isin][_SEC_TABLE_MARKET_STEPS] += steps;
	App.mkt_slider.setRealValue(App.isinAr[isin][_SEC_TABLE_MARKET_STEPS]);	
 }

		//App.createChannelsTable();
  		//App.joinMeteor();
		//App.connectMeteor();
  		} //onSuccess
	    }); //YUILoader
	 loader.insert({}, 'js');
  })();