YAHOO.namespace("mainTradeReplay");
 _TOOLBAR_START_PAUSE = 51;
 _TOOLBAR_START_PLAY = 41;
 _TOOLBAR_START_START = 31;
 _TOOLBAR_START_OPEN = 21;
 _TOOLBAR_STOP = 22;
 _TOOLBAR_BACK = 23;
 _TOOLBAR_NEXT = 24;

 _SEC_TABLE_ISIN = 0;
 _SEC_TABLE_SYMBOL = 1;
 _SEC_TABLE_ORDERS = 2;
 _SEC_TABLE_PRICE = 3;
 _SEC_TABLE_SECONDS = 4; 
 _SEC_TABLE_TRADES = 5;
 _SEC_TABLE_FLASHOBJ = 6;
 _SEC_TABLE_ORDERS_IDX = 7; 
 _SEC_TABLE_TRADES_IDX = 8;
 _SEC_TABLE_MARKET_STEPS = 9; 

/**
*
*/
 _DOC_ID = 0;
 _DOC_TYPE = 1;
 _DOC_TITLE = 2;
 _DOC_DATE = 3;
 _DOC_ROOT = 4;
 
 _DOC_TYPE_DOC = 1;
 _DOC_TYPE_CHAPTER = 2;
 _DOC_TYPE_PARAGRAPH = 3;
/**
*
*/
 _TRADER_TYPE_BASE = 0;
 _TRADER_TYPE_SEC = 1;
 _TRADER_TYPE_MEMBER = 2;
 _TRADER_TYPE_TRADER = 3;
 _TRADER_TYPE_INVESTOR = 4;
 
 _DATES_TYPE_BASE = 0;
 _DATES_TYPE_DATE = -1;
 _DATES_TYPE_SEC = 1;
 

function Variable(initVal, onChange)
{
  this.val = initVal;
  this.onChange = onChange;

  this.GetValue = function()
  {
   return this.val;
   }
            
  this.SetValue = function(value)
  {
   this.val = value;
   if (this.val!=10) 
	   this.onChange();
   }    
 } 
 
function systemReset()
{
	  YAHOO.mainTradeReplay.app.stopMarket(0);
 }

(function() {
    YAHOO.mainTradeReplay.app = {
        inboxLoaded: false,
        inboxLoading: false,
		
		ses_str: '',
		ses: 0,
		ses_title: '',
		ses_days_left: 0,
		elapsed_time: '',
		myVar:'',
		
		err: 0,
		proxy_error: '',
		ans: '',
		
		vieworders: 0,
		file_exists: false,
		delay: 400000,
		slider2: 300,
		step: 1, 		//  mktSlider step
		
		marketStatus:" ",
		signal:"",
		sub_signal:"",
		phasesAr: [],
		isinAr: [],
		qparams: [],
		secsList: [],
		datesList: [],
		qparam: '',
		prevActiveIsin:'',
		start_price: "",
		prev_price: "",
		bid_1: 0,
		ask_1: 0,
		total_secs: 0,
		mkt_secs: 0,
		sTime: '',
		last_order_time: '',
		market_steps: 0,
		stop_steps: 0,
		xOrder: 0,
		nOrders: 0,
		maxOrders: 6,
		total_trades: 0,
		showGraph:1,
		nTrades: 0,
		xTrade: 0,
		maxTrades: 6,
		
//		oButtonStart: '',
		oButtonStop: '',
		oButtonBack: '',
		oButtonNext: '',
		mkt_slider: "",
		vslider:"",
		cuslider:"",
		
		columnsIds: "",
		columnsDef: "",
		IScolumnsDef:"",
		IScolumnsIds: "",
		ITcolumnsDef:"",
		ITcolumnsIds: "",
		mygrid_orders: "",
		mygrid_events: "",
		mygrid_trades: "",
		mygrid_mdb: "",
		mygrid_mds: "",
		DlgCalendar:'',
		tabView:'',
		progress_win:'',
		progressMax:0,
		
		test1: "test1.php",
		aux: "auxDispatcher.php",
		sg_signal: "signal.php",
		rbdata: "rbdata.php",
		smartTrades: "smartTrades.php",
		smartOrders: "smartOrders.php",
		smartTraders: "smartTraders.php",
		smartOraSecs: "smartOraSecs.php",
		tradeOrders: "tradeOrders.php",
		Gdispatch: "Gdispatcher.php",
		mktResol: "mktResol.php",
		images :  "codebase/imgs/",
		graphs:  "js/graphs/",
		graphdata:  "graph-data.php/",
		Serv1_dispatcher: "Serv1dispatcher.php",
				
		fdate: '',
		oradates_id: 0,
		filepath: '',
		mode: '',
		board: '',
		market: '',
		market_name: '',
		isin: '',
		hsym: '',
		esym: '',
		custom_start: '00:00:00.000',
		custom_end: '99:99:99.000',
		
		argumentAjax: '',
		ajaxobj:'',
		fldTypes: [],
		dialogHandlers:[],
		dialog_ar: [],
		events_xml:'',
		XML_var: "",
		JSON_var: "",
		XML_var_mda: "",
		XML_var_mdb: "",
		flashBidAsk: "",
		channelsTable: [],
		helpDocs: [],
		helpRoot:'',
		helpNode:'',
		zoomMarket: false,
		targ: "",
		
		feedURL: 'http:/'+'/rss.groups.yahoo.com/group/ydn-javascript/rss?count=50'
       };

  App = YAHOO.mainTradeReplay.app;
  
    
  App.AjaxObject = {
		handleSuccess:function(o){
			//YAHOO.log("Ajax handleSuccess:"+o.status+" - Status: " + o.statusText, "error","main.js");
			this.processResult(o);
			},
		handleFailure:function(o){
			YAHOO.log("Ajax failed:"+o.status+" - Status: " + o.statusText, "error","main.js");
			},
		
		Tid:0,
		
		Start: function(type, args) {
				//YAHOO.log("Ajax :"+type+" - Trans: " + args[0].tId, "warn","main.js");
				this.Tid=args[0].tId;
			},
		
		mktSuccess: function(o) {	
				App.JSON_var=o.responseText;
				App.fillMktInfoForm();
		},
		Success: function(type, args) {
			
				//YAHOO.log("Ajax Success:"+type+" - Trans: " +this.Tid+"=="+ args[0].tId+"=="+args[0].argument[0], "error","main.js");
				if (args[0].argument[0]=='JSON'){
				
				App.JSON_var=args[0].responseText;
				//o.responseText=null;
				var callback=args[0].argument[1];
				//o.argument=null;
				callback();
				}
				else
				if (args[0].argument[0]=='FIX'){
					ar=args[0].responseText.split(";");
					for (i=0; i<ar.length && ar[i]; i++)
					{
			     	if (ar[i].search("<")==-1) {
					 	ev = "App."+ar[i]+";";
						
				 	 	eval(ev);
				 		}
					}
				}
				else
				this.processResult(args[0]);
			},
		complete: function(type, args) {
			
			//YAHOO.log("Ajax complete:"+type+" - Trans: " + args[0].tId, "error","main.js");
			},
	
		processResult:function(o){
	 		//eval(o.responseText);
			//YAHOO.log("processResult:"+o.argument[0], "error","main.js");
			if (o.argument!='' && o.argument[0]=="XMLnoParse"){
				if (o.argument[1]=='mda')
					App.XML_var_mda = o.responseText;
				 else if (o.argument[1]=='mdb')
					App.XML_var_mdb = o.responseText;
				  else	
						App.XML_var = o.responseText;
				//YAHOO.log("XMLnoParse:"+o.status+" - Status: " + o.statusText, "error","main.js");
				//o.argument[0]=null;
				//return;
			}
			else 
			if (o.argument[0]=='AR'){ // return data as JS object array
				//YAHOO.log("AR:"+o.responseText, "warn");
				eval("App."+o.responseText);
				
				for (i=0; i<App.ajaxobj.fields.length; i++)
					 eval("App."+App.ajaxobj.fields[i].varname+"='"+App.ajaxobj.fields[i].vardata+"';");
			  // YAHOO.log("AR:"+App.ajaxobj.fields.length+"=c="+App.ajaxobj.call, "warn");
			   if (App.ajaxobj.call.match(/[!\\]/g))
					{
			   		try
					   {
						eval("App."+App.ajaxobj.call.replace( /\\/g,"" ));
						}
					   catch(e)
					   {
						 YAHOO.log("AR-AjaxObject:"+App.ajaxobj.call+" - "+e, "error");
					    }
					}
					else
					   try
					   {
					    eval("App."+App.ajaxobj.call);
					   }
					   catch(e)
					   {
						 YAHOO.log("AR-AjaxObject:"+App.ajaxobj.call+" - "+e, "error");
					    }
				App.ajaxObj=null;
				}
			else
			if (o.argument[0]=='SR'){ // return data as Serialized string
				//YAHOO.log("SR:"+o.responseText, "warn");
				eval(o.responseText);
				//YAHOO.log("SR:"+App.secsList.length, "warn");
				}	
			else 
			if (o.argument[0]=='JS'){
				//YAHOO.log("JS:", "info");
				ar=o.responseText.split(";");
				// YAHOO.log("AjaxObject:"+ar, "info");
				for (i=0; i<ar.length && ar[i]; i++)
				{					
			     if (!ar[i].match(/^[!\<]/g)) { // changed to match from   .indexOf('<'>)==-1
					 ev = "App."+ar[i]+";";								 
				 	 try
					 {
					  // YAHOO.log("JS-AjaxObject:"+o.argument[0]+" "+ev, "error");
					  if (ev.match(/[!\\]/g)){ 
					   	   ev.replace( /\\/g,"" );
					   	   eval(ev);
					  }
						 else
						   eval(ev);
					 }
					 catch(e)
					 {
						 YAHOO.log("JS-AjaxObject:"+o.argument[0]+" "+ev+" - "+e, "error");
					 }
				 	}
				 }
				}
			else
			
			switch (navigator.appName)
				{
				case  "Microsoft Internet Explorer"	:
					//YAHOO.log("IE:"+o.responseXML+"=="+ o.argument[0], "info"); 
			     if (o.responseXML!='' && o.argument[0]){ //o.responseXML!='' || 
				
					//YAHOO.log("XML AjaxObject:", "info");
				//alert(o.argument[0]);
					App.parseXML(o.responseXML);
					o.argument[0](o.argument[1],o.argument[2],o.argument[3],o.argument[4],o.argument[5],o.argument[6]); // Call as function the first arg
					}
				 break;
				default:
				
					//YAHOO.log("FF:"+o.responseXML+"=="+ o.argument[0], "info"); 
				 if (o.responseXML && o.argument[0]){ //o.responseXML!='' || 
				
				//	YAHOO.log("XML AjaxObject:", "info");
				//alert(o.argument[0]);
					App.parseXML(o.responseXML);
					o.argument[0](o.argument[1],o.argument[2],o.argument[3],o.argument[4],o.argument[5],o.argument[6]); // Call as function the first arg
					}
				break;
				}
			 
			
			
			   
			if (App.proxy_error!='') {
			    App.alert("proxy error:"+App.proxy_error);
				App.proxy_error='';
				}
	 		  // YAHOO.log("AjaxObject:"+o.responseXML+"=="+o.argument, "info");
			},
		startRequest:function(caller,msg) {
			//YAHOO.log("Ajax startRequest:"+msg, "info");
	   		cObj=YAHOO.util.Connect.asyncRequest('POST', caller, YAHOO.mainTradeReplay.app.callback, msg);
			},
			
		syncRequest:function(caller,msg, callback) {
			//YAHOO.log("Ajax startRequest:"+msg, "info");
			callback = (callback==null) ?YAHOO.mainTradeReplay.app.callback :callback;
	   		var transaction = YAHOO.util.Connect.asyncRequest('GET', caller+"?"+msg, callback, null);
			}
		};

  					
		YAHOO.mainTradeReplay.app.callback= {
					//success:YAHOO.mainTradeReplay.app.AjaxObject.handleSuccess,
					failure:YAHOO.mainTradeReplay.app.AjaxObject.handleFailure,					
					scope: YAHOO.mainTradeReplay.app.AjaxObject,
					customevents: {
									onComplete:YAHOO.mainTradeReplay.app.AjaxObject.complete,
									onSuccess:YAHOO.mainTradeReplay.app.AjaxObject.Success
									},
					argument: []
					};
		YAHOO.mainTradeReplay.app.callback2= {
					//success:YAHOO.mainTradeReplay.app.AjaxObject.handleSuccess,
					failure:YAHOO.mainTradeReplay.app.AjaxObject.handleFailure,					
					scope: YAHOO.mainTradeReplay.app.AjaxObject,
					customevents: {
									onComplete:YAHOO.mainTradeReplay.app.AjaxObject.complete,
									onSuccess:YAHOO.mainTradeReplay.app.AjaxObject.Success
									},
					argument: []
					};
		YAHOO.mainTradeReplay.app.callback_mkt= {
					success:YAHOO.mainTradeReplay.app.AjaxObject.mktSuccess,
					failure:YAHOO.mainTradeReplay.app.AjaxObject.handleFailure,					
					scope: YAHOO.mainTradeReplay.app.AjaxObject,
					argument: []
					};
		YAHOO.mainTradeReplay.app.callbackFixValues= {
					failure:YAHOO.mainTradeReplay.app.AjaxObject.handleFailure,					
					scope: YAHOO.mainTradeReplay.app.AjaxObject,			
					customevents: {
									onComplete:YAHOO.mainTradeReplay.app.AjaxObject.complete,
									onSuccess:YAHOO.mainTradeReplay.app.AjaxObject.Success,
									onStart: YAHOO.mainTradeReplay.app.AjaxObject.Start
									},
					argument: []
					};
		//YAHOO.util.Connect.completeEvent.subscribe(YAHOO.mainTradeReplay.app.AjaxObject.complete, YAHOO.mainTradeReplay.app.AjaxObject);

 
 YAHOO.mainTradeReplay.app.purge=function (d) {    var a = d.attributes, i, l, n;    if (a) {        l = a.length;        for (i = 0; i < l; i += 1) {            n = a[i].name;            if (typeof d[n] === 'function') {                d[n] = null;            }        }    }    a = d.childNodes;    if (a) {        l = a.length;        for (i = 0; i < l; i += 1) {            purge(d.childNodes[i]);        }    }};
 

var handleCancel = function() {
	if (App.configDialog.cfg.getProperty('cal_trap')!='')
		if (App.configDialog.cfg.getProperty('cal_hidden')==false)
		    App.DlgCalendar.animate(false);
    this.cancel();
}

var handleClose = function() {
	if (App.configDialog.cfg.getProperty('cal_trap')!='')
		if (App.configDialog.cfg.getProperty('cal_hidden')==false)
		    App.DlgCalendar.animate(false);

    this.cancel();
}

var handleHide = function() {
   	if (App.configDialog.cfg.getProperty('cal_trap')!='')
		if (App.configDialog.cfg.getProperty('cal_hidden')==false)
		    App.DlgCalendar.animate(false);

	this.hide();
}
var handleSubmit = function() {
//	alert(document.dlgForm.securities.value);
	if (App.configDialog.cfg.getProperty('cal_trap')!='')
		if (App.configDialog.cfg.getProperty('cal_hidden')==false)
		    App.DlgCalendar.animate(false);

	this.submit();
}

var handleSubmitErase = function() {
}

var handleSubmitRestart = function() {
	if (App.configDialog.cfg.getProperty('cal_trap')!='')
		if (App.configDialog.cfg.getProperty('cal_hidden')==false)
		    App.DlgCalendar.animate(false);

	this.submit();
	var data = this.getData();
	
	App.stopMarket(0);
											
	App.oButtonStart.set("disabled",true);
	setTimeout(function() {								
				setTimeout(function() {
										var execCode="fillDate();";	
										App.callback.argument[0] = 'JS';
 										msg='task=getConfigDate'+'&objId='+App.fdate+'&ses='+App.ses+'&execWhenReturn='+execCode;
 										App.AjaxObject.syncRequest(App.aux,msg);
									    }, 1000);
			
										// MARKET CREATION===============			
				setTimeout(function() {
										App.callback.argument[0] = 'JS';
										var execCode="releaseStart();";	
										msg='task=createMarket'+'&execWhenReturn='+execCode+"&data="+App.delay+App.ses_str;
										App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
										}, 1000);											
			}, 500);
											
//alert("handleSubmitRestart "+data.securities);	

}
  
  App.dialogHandlers['Cancel']=handleCancel;
  App.dialogHandlers['Close']=handleClose;
  App.dialogHandlers['Submit']=handleSubmit;
  App.dialogHandlers['Apply']=handleSubmit;
  App.dialogHandlers['Restart']=handleSubmitRestart;
  App.dialogHandlers['Erase']=handleSubmitErase;
  App.dialogHandlers['Save']=handleSubmit;
  App.dialogHandlers['Hide']=handleHide;
    
 YAHOO.mainTradeReplay.app.wait_for_signal = function() 
 { 
 	    App = YAHOO.mainTradeReplay.app;
		if (App.ans=="ok")
   			{ 		 // ctrlThread is up and Ready!
			App.signal="pre-info-ok";
	
			App.oButtonStart.set("id","button_start"); 
			App.oButtonStart.set("value",_TOOLBAR_START_START);

//	webBar.setItemText('START','Start');
//	webBar.setItemImage('START', 'starttrade.gif');
	
			var execCode="fillDate();";	
			App.callback.argument[0] = 'JS';
			msg='task=getConfigDate'+'&ses='+App.ses+'&execWhenReturn='+execCode;
			App.AjaxObject.syncRequest(App.aux,msg);
			
			App.connectMeteor(); 
			App.callback.argument[0] = 'JS';
			msg='task=openMeteor'+'&data=startup'+App.ses_str;
 			App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
			App.ans="";		  
			}
	};

	

//================== APPLICATION MAIN CODE===========================================//
 
 YAHOO.mainTradeReplay.app.checkLinuxService = function()
 {
  alert("err="+err);
 }
 
 
 YAHOO.mainTradeReplay.app.fillDate = function() 
 {
  Dom = YAHOO.util.Dom;
  App = YAHOO.mainTradeReplay.app;

//  YAHOO.log("fillDate:"+App.fdate+"="+App.file_exists+"="+App.signal,"info");
  Dom.get('trade_date').innerHTML=App.fdate;
  
  YAHOO.util.Event.onAvailable("scroller_date", function(){
  			Dom.get('scroller_date').innerHTML = App.fdate;
														  });
	
//  App.calendar.cfg.setProperty("pagedate", App.fdate.substr(5,2)+"/"+App.fdate.substr(0,4));
//  App.calendar.render();
// YAHOO.log("fillDate:"+App.file_exists+"="+App.signal,"warn"); 
  if (App.file_exists==true) {

	 App.oButtonStart.set("disabled",false);
	 App.oButtonStop.set("disabled",false);

	 if (App.signal!="") {  // ctrlThread is running and info tables are ready!
	 
	//	dhxPlayWins.setSkin("aqua_orange");
 		
		execCode="fillSecForm();";	
		App.callback.argument[0] = 'JS';
		msg='task=getSecurityInfo'+'&ses='+App.ses+'&execWhenReturn='+execCode;		
		App.AjaxObject.syncRequest(App.aux,msg);
		}
	}
	else {
	  App.oButtonStart.set("disabled",true);
	  App.oButtonStop.set("disabled",true);
	  }
   };
 
 YAHOO.mainTradeReplay.app.updateScroller = function()
 {
  YAHOO.util.Event.onAvailable("scroller_session", function(){
			var myTabs = new YAHOO.widget.TabView("scrollerTabs");
			myTabs.set('orientation','bottom');
  			Dom.get('scroller_session').innerHTML = App.ses;
															});
  }

 YAHOO.mainTradeReplay.app.fillSecForm = function() 
 {
	Dom = YAHOO.util.Dom;
    App = YAHOO.mainTradeReplay.app;
//	alert(App.isin);
  //  Dom.get('cal_fdate').innerHtml=App.fdate;
	Dom.get('trade_date').innerHTML = App.fdate;
	Dom.get('scroller_date').innerHTML = App.fdate;
	Dom.get('source').innerHTML = (App.mode==0 ?"Log File" :"Database");
	Dom.get('board').innerHTML = App.board;
	Dom.get('market').innerHTML = App.market_name;
//	Dom.get('symbol').value = App.hsym;
	Dom.get('isin').value = App.isin;
	
	YAHOO.util.Event.onAvailable("isin", function(){
		if (App.oradates_id>0 && App.isinAr.length) {
			 App.callback.argument[0] = 'JS'; 
			 var isin=document.getElementById('isin').value;
			 msg='task=getOraDates'+'&isin='+App.isinAr[isin][_SEC_TABLE_ISIN]+'&crid='+App.oradates_id+'&ses='+App.ses;
			 App.AjaxObject.syncRequest(App.aux,msg);
		}
	 });
	
	if (App.tabView!='') //App.oButtonStart.get("value")!=_TOOLBAR_START_OPEN)
	{
		for(var i=0;i<App.tabView.length;i++) 
			App.tabView.removeTab(App.tabView.get('activeTab'));
	}
	
	App.createStatisticsTabView();   // statsTab.js

	var selectbox = document.forms.SecInfoForm.elements.isin;
	for(var i=selectbox.options.length-1;i>=0;i--)
	{
	selectbox.remove(i);
	}
	var temp = new Array(); 
	temp = App.isin.split(',');

	App.secs=temp;
	for(i=0;i<temp.length;i++) {
	    selectbox.options[selectbox.options.length] = new Option(App.isinAr[temp[i]][_SEC_TABLE_SYMBOL], temp[i] ); //, true, true
		App.addNewTab('div_MktInfo_main_'+(i+1),temp[i],App.tradeOrders+'?task=mkt_statistics'+'&format=JSON'+'&id=1'+'&session='+App.ses);
		App.createHourTradesGraph(i,temp[i]);
		
		}
	selectbox.options[0].selected=true;
	Dom.get('symbol').innerHTML = App.isinAr[selectbox.value][_SEC_TABLE_SYMBOL];
	
	App.prevActiveIsin = selectbox.value;
	
	Dom.get('scroller_isin').innerHTML = App.isinAr[selectbox.value][_SEC_TABLE_SYMBOL];
	Dom.get('scroller_session').innerHTML = App.ses;
	//for (i=0; i<selectbox.options.length;i++)
	//    if (selectbox.options[i].selected==true) alert(App.isinAr[selectbox.options[i].value][1]);
		
	selectbox.onchange=function(){ //run some code when "onchange" event fires
 							var chosenoption=this.options[this.selectedIndex];
							Dom.get('symbol').innerHTML = App.isinAr[this.value][_SEC_TABLE_SYMBOL];
							Dom.get('scroller_isin').innerHTML = App.isinAr[selectbox.value][_SEC_TABLE_SYMBOL];
							
							Dom.get('price').innerHTML =  App.isinAr[this.value][_SEC_TABLE_PRICE];
							Dom.get('totalOrders').innerHTML =  App.isinAr[this.value][_SEC_TABLE_ORDERS];
							Dom.get('totalTrades').innerHTML =  App.isinAr[this.value][_SEC_TABLE_TRADES];
							App.clearGrids(2);
							  // set different scalFactor for every security isin
							App.cuslider.FixScale(); 
							App.mkt_slider.FixScale();
							
						    App.removeFirstLevel();
							execCode="newIsinActivated();";	
							App.callback.argument[0] = 'JS';
							msg='task=setIsinActive'+'&execWhenReturn='+execCode+'&data='+this.value+App.ses_str;
 							App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
							};	
	};

 
 App.newIsinActivated = function()
 {
	 var isin=document.getElementById('isin').value;	// get Active isin
	
	 App.prevActiveIsin = isin;	
	 
  }
 
 App.wait_for_DataSignal = function()
 {
	Dom = YAHOO.util.Dom;
	var ar_ans=App.ans.split(":");
	if (ar_ans[0]=="ok" && ar_ans[1]=="partial") {
	
		execCode="";	
		App.callback.argument[0] = 'JS';
		msg='task=getSecurityInfo'+'&ses='+App.ses+'&execWhenReturn='+execCode;		
		App.AjaxObject.syncRequest(App.aux,msg);
	}
	
	if (ar_ans[0]=="ok") {
    	App.Datasignal="pre-data-ok";
   App.tabView.selectTab(0);
		App.oButtonStart.set("id","button_play"); 
		App.oButtonStart.set("value",_TOOLBAR_START_PLAY);	
//YAHOO.log("wait_for_DataSignal :"+App.total_secs,"error");		
		execCode="marketFixValues();";
		App.callbackFixValues.argument[0] = 'FIX';
		msg='task=computeMarket'+'&ses='+App.ses+'&objId='+App.slider2+'&execWhenReturn='+execCode;
		App.AjaxObject.syncRequest(App.aux,msg,App.callbackFixValues);		
		
		if (App.showGraph){	
			var t=App.layout3.getUnitByPosition('top');
			if (App.maxOrders>10) {
				t.set('scroll',true);
				t.set('height',240);
				App.resizeTabView();
				}
			App.createBidAskGraph();
			}
			else
				App.setNoBidAskGraph();
		}
  };
  
  YAHOO.mainTradeReplay.app.setNoBidAskGraph = function() 
  {
	var c=	App.layout3.getUnitByPosition('center');
	
	//c.set('height',1);
	var t=App.layout3.getUnitByPosition('top');
	t.set('height',810);
	t.set('scroll',true);
	App.resizeTabView();
  }
  
  YAHOO.mainTradeReplay.app.setWithBidAskGraph = function() 
  {
	var c=App.layout3.getUnitByPosition('center');
	c.set('height',430);
	var t=App.layout3.getUnitByPosition('top');
	t.set('height',240);
	App.resizeTabView();
	//t.set('scroll',false);
	//App.maxOrders=10;
	//App.maxTrades=10;
	App.createBidAskGraph();
  }
			
  YAHOO.mainTradeReplay.app.fillMktInfoForm = function() 
 {	
	if (App.JSON_var==null)
	    return;
	var json_array = App.JSON_var;
	
	App.JSON_var=null;
	Dom = YAHOO.util.Dom;
    App = YAHOO.mainTradeReplay.app;
	
	//var json_array = eval('(' + data + ')'); 
	if (!YAHOO.lang.JSON.isSafe(json_array))
		{
		    App.alert("Mkt Info: JSON invalid!");
			return;
		}
	//	App.alert("Mkt Info: JSON OK!");
	//Dom.get("refreshGraph").disabled=false;
	var idx=0;
	YAHOO.lang.JSON.parse(json_array, function (key, value) 
		{
   		 
		 var fld='';
		 var vid='';
		 
		    if (value && typeof value === 'object') 
				return value;
			if (key=='id' || key=='fdate' || key=='session')
			    return value;
			if (key=='security_isin') {
			    idx = App.secs.indexOf(value);
				//YAHOO.log("security_isin :"+value+"=="+idx,"error");
				 return value;
			}
			if (key=='totalTrades' || key=='totalOrders')
				;//Dom.get(key).value=value;					   
			else	
			   {
				fld=key+'_'+(idx+1);
				vid = Dom.get(fld);
				if (vid) {
				//YAHOO.log("fillMktInfoForm :fld="+fld+"="+value,"error");
			    		vid.innerHTML=value;
						}
					else
				   		;//YAHOO.log("fillMktInfoForm :Ivalid fld="+fld,"error");
			   }
		    return value;
		 });
	//App.total_secs=	parseInt(Dom.get('totalOrders_1'));
	//App.total_trades=	parseInt(Dom.get('totalTrades_1'));
//YAHOO.log("fillMktInfoForm :"+json_array.length,"warn");
	
	}
 
 App.marketFixValues = function()
 {
	Dom = YAHOO.util.Dom;
	 //+App.phasesAr[1][0]+"=="+App.phasesAr[1][1]+"=="+App.phasesAr[1][2]+"=="+App.phasesAr[2][0]
	setTimeout(function() {
								
	var isin=document.getElementById('isin').value;
	//YAHOO.log("marketFixValues","warn");
	if (App.isinAr.length)
	{
	Dom.get('price').innerHTML =  App.isinAr[isin][3];
	Dom.get('totalOrders').innerHTML =  App.isinAr[isin][_SEC_TABLE_ORDERS];
	Dom.get('totalTrades').innerHTML =  App.isinAr[isin][_SEC_TABLE_TRADES];

	var selectbox = document.forms.SecInfoForm.elements.isin;
	for (var i=0; i<selectbox.options.length;i++){
		 App.isinAr[selectbox.options[i].value].push(0);  // orders_idx
		 App.isinAr[selectbox.options[i].value].push(0);  // trades_idx
		 App.isinAr[selectbox.options[i].value].push(0);  // market_steps		 
		 }
	}
    
//	Dom.get('price').value = App.start_price;
	Dom.get('market').innerHTML = App.market_name;
//	Dom.get('ostop').value = App.total_secs;
//	Dom.get('tot_trades').value = App.total_trades;
	
	App.mkt_slider.FixScale();
	YAHOO.log("marketFixValues - "+App.mkt_secs,"warn");
	App.cuslider.FixScale();  // custom period
	
//	App.createHourTradesGraph();
	Dom.get("refreshGraph").disabled=false;
	App.createBmarksGrid();
	}, 1000);	
	
	if (App.protOpen==true) // It is Closed, so Opene it!
		App.toggleProtection();
	//document.hourgraphform.refreshGraph.disabled = false;
  }

//==============
App.getConfig = function()
{
 App.callback.argument = [App.createDialog,"config_dialog",'Session:'+App.ses+' Configuration Options',App.aux+'?task=updateMarketOptions&ses='+App.ses, ['Apply','Restart','Cancel'],300,300];
 App.AjaxObject.syncRequest(App.aux,"task=config&format=XML&ses="+App.ses);	
 }

App.tuneApp = function()
{
 App.callback.argument = [App.createDialog,"config_dialog",'Session:'+App.ses+' Tune the application',App.aux+'?task=updateTuneOptions&ses='+App.ses, ['Apply','Cancel'],300,300];
 App.AjaxObject.syncRequest(App.aux,"task=tuneApp&format=XML&ses="+App.ses);	
 }
 
App.createTradeOrdersWindow_bid = function(ttime,trade_no,bid,ask,dialog_title,type,mode)
{
 mode=(typeof(mode)=='undefined' ?'OB' :mode);
 App.callback.argument = [App.createDialog, "order_dialog", dialog_title, '', ['Close','Hide'],500,10];
 bid=(type=="order_ftr" ?trade_no :bid);
 App.AjaxObject.syncRequest(App.tradeOrders,'task=tradeOrders'+'&'+type+'='+bid+'&ask='+ask+'&mode='+mode+'&time='+ttime+'&session='+App.ses);	
 }

App.createTradeWindow = function(ttime,trade_no,dialog_title,type)
{
 App.callback.argument = [App.createDialog, "trade_dialog", dialog_title, '', ['Close','Hide'],500,10];
 App.AjaxObject.syncRequest(App.tradeOrders,'task=tradeOrders'+'&'+type+'='+trade_no+'&time='+ttime+'&session='+App.ses);	
 }


App.createSmartTradesWin = function()
{
 App.createSmartTradesGrid();
 App.smartTrades_win.show();
 }

App.createSmartTradersWin = function()
{
 App.createTradersGraph();
 App.createSmartTradersGrid();
 App.smartTraders_win.show();
 }

App.createOrderBookWin = function()
{
 App.createOBGrid();
 App.orderBook_win.show();
 }

 
App.createOrderEntryWin = function()
{
 Dom.get('orderTime').value=Dom.get('otime').value;
 Dom.get('orderMkt').value=App.market;
 Dom.get('orderBrd').value=App.board;
 Dom.get('orderSec').value=App.hsym;
 Dom.get('orderisin').innerHTML=App.isin;
 Dom.get('orderPrice').value=(Dom.get('trade').value=='' ?App.start_price :Dom.get('trade').value); 
 Dom.get('orderopen').innerHTML=App.start_price;
 Dom.get('orderTrader').value='me';
 App.orderEntry_dialog.show();
 }
 
App.createBuySellWin = function()
{
 App.createBuySellGraph();
 App.buysell_win.show();
 }
 
App.createSmartOrdersWin = function()
{
// if (App.mygrid_smartOrders=='')
     App.createSmartOrdersGrid();
 App.smartOrders_win.show();
 }
 
App.memberInfo = function(msgId,time,msg_num,id) 
{
 var msg = 'task=getMemberInfo'+'&objId='+msgId+'&msg_num='+msg_num+'&time='+time+'&trader='+id+'&session='+App.ses;
 App.callback.argument = [App.createDialog, "memberinfo_dialog", 'Member Info', '', ['Close','Hide'],400,400];
 App.AjaxObject.syncRequest(App.tradeOrders,msg);
 };

App.createTradeOrdersPanel = function(title,action)
{
App.openprice_win.setHeader("<div style=\"color:red\">"+title+"</div>");

var additional_colLen = (App.dialog_ar[0].length-1)*110;
var tablen=300+additional_colLen;

str=App.createXMLFields(tablen);

App.openprice_win.setBody(str);
App.openprice_win.render();
App.openprice_win.show();

//alert(str);
App.dialog_ar=null;
str=null;
 }

App.showProjectedGraph = function()
{
 if (App.mygrid_trades.projected_so=='')
	 App.mygrid_trades.createProjectedGraph();

 if (App.mygrid_trades.flashProjected) 
 	 App.mygrid_trades.flashProjected.reloadData();
 App.projected_win.show();
 }

App.showBidAskGlobalGraph = function()
{
 if (App.bidaskGlobal_so=='')
	 App.createBidAskGlobalGraph();

 if (App.flashBidAskGlobal) 
 	 App.flashBidAskGlobal.reloadData();
 App.bidaskGlobal_win.show();
 }
 
App.showBidAskCompareGraph = function()
{
 if (App.bidaskCompare_so=='')
	 App.createBidAskCompareGraph();

 if (App.flashBidAskCompare) 
 	 App.flashBidAskCompare.reloadData();
 App.bidaskCompare_win.show();
 }
 
App.showOpenPrice = function()
{
// App.callback.argument = [App.createOpenpricePanel,'Open Price Details',''];
// App.AjaxObject.syncRequest(App.tradeOrders,"task=openPrice&session="+App.ses);
 App.createOpenPriceGrid();
 Dom.get('openprice').innerHTML="Price: "+ Dom.get('oprice').value;
 App.openprice_win.render();
 App.openprice_win.show();
}

App.parseXML = function(responseXML)
{
 var x=responseXML.getElementsByTagName("row");
 if (App.fldTypes.length==0)
	{
	App.fldTypes=[];
	App.fldTypes['button']='button';
	App.fldTypes['ch']='checkbox';
	App.fldTypes['co']='select';
	App.fldTypes['combo']='select';
	App.fldTypes['span']='span';
	App.fldTypes['img']='image';
	App.fldTypes['link']='link';
	App.fldTypes['password']='password';
	App.fldTypes['ro']='readonly';
	App.fldTypes['ra']='radio';
	App.fldTypes['ed']='text';
	App.fldTypes['hidden']='hidden';
	App.fldTypes['dhxCalendarA']='calendar';
	}

var ar = [];
App.dialog_ar=[];
//if(x[0].getElementsByTagName("cell").length==3)					
 for (var i=0; i < x.length; i++)
	{
	 App.dialog_ar[i]=[];
	 App.dialog_ar[i][0] = [ x[i].getAttribute("id"), 	// name - id
						     x[i].getElementsByTagName("cell")[0].childNodes[0].nodeValue ]; // label
	 
	 for (var c=1; c <= x[i].getElementsByTagName("cell").length-1; c++)
	 {
		ar = [];
		if (x[i].getElementsByTagName("cell")[c].getAttribute("xmlcontent")=='1'){
		    cell_options=x[i].getElementsByTagName("option");
			for (var j=0;j<cell_options.length;j++) {
				 ar[j]=[];
			     ar[j][0]=cell_options[j].childNodes[0].nodeValue;
				 ar[j][1]=cell_options[j].getAttribute("value");
				 ar[j][2]=cell_options[j].getAttribute("selected");
				 }
				 //alert("ar="+ar[0]);
		}
	 App.dialog_ar[i][c] = [];
	 App.dialog_ar[i][c] = [
						x[i].getElementsByTagName("cell")[c].getAttribute("xmlcontent"), 
	 					App.fldTypes[x[i].getElementsByTagName("cell")[c].getAttribute("type")],
	 					x[i].getElementsByTagName("cell")[c].childNodes[0].nodeValue,	// value
						x[i].getElementsByTagName("cell")[c].getAttribute("source"),
						ar
	 					];
	 //if (App.dialog_ar[i][0]=='1')
		// alert("dialog_ar="+App.dialog_ar[i][6][0]);
	 }
	}
//App.createDialog();	
}

App.releaseStart = function()
{
	App.oButtonStart.set("disabled",false);
 }

App.createDialog = function(div_id,title,action,buttons,x,y)
{
//xmlDoc=responseXML;
// Instantiate the Dialog
var myButtons = [];
//var myButtons = [ { text:buttons[0], handler:App.dialogHandlers[buttons[0]], isDefault:true },
//                  { text:buttons[1], handler:App.dialogHandlers[buttons[1]] } ];

for (var i=0; i<buttons.length; i++){
	if (i==0)
        myButtons[i]={ text:buttons[i], handler:App.dialogHandlers[buttons[i]], isDefault:true }
		else
			 myButtons[i]={ text:buttons[i], handler:App.dialogHandlers[buttons[i]] }
	}

var additional_colLen = (App.dialog_ar[0].length-1)*60;


App.configDialog = new YAHOO.widget.Dialog(div_id, 
			{ width : (370+additional_colLen)+"px",
			  //fixedcenter : true,
			  xy:[x,y],
			  visible : false,
			  draggable: true,
			  zIndex:1000,
			  constraintoviewport : false,
			  buttons : myButtons,
			  //[ { text:"Submit", handler:handleSubmit, isDefault:true }, { text:"Cancel", handler:handleCancel } ],
			  effect:[{effect:YAHOO.widget.ContainerEffect.SLIDE,duration:0.35}] //{effect:YAHOO.widget.ContainerEffect.SLIDE,duration:0.35}
			 } );
//App.configDialog.cfg.applyConfig({ effect: { effect:  dAnim , duration: 3} });

//App.configDialog.beforeShowEvent.subscribe( FadeMaskIn(div_id) );

//App.configDialog.cfg.queueProperty("buttons", myButtons);
App.configDialog.setHeader("<div style=\"color:red\">"+title+"</div>");
App.configDialog.cfg.addProperty('list_trap',{list_trap:''});
App.configDialog.cfg.addProperty('cal_trap',{cal_trap:''});
App.configDialog.cfg.addProperty('cal_hidden',{cal_hidden:false});


App.configDialog.validate = function() {
	Dom = YAHOO.util.Dom;
		var data = this.getData();
		
				//document.dlgForm.init.value='1';
//		alert(" Session."+data.sessions);
		if (typeof(data.sessions) != 'undefined' && data.sessions!=App.ses) 
		{
			App.alert('Session: '+App.ses+' Title: ['+App.ses_title+'] is going to Stop!');
			}
		if (data.sessions==1) 
		{
			App.alert('Session: '+data.sessions+' Title: [SYSTEM] is not an Option!');
			return;
			}	
		else if (data.filepath == '') {
			App.alert("Please enter filepath!");
			return false;
		} 
		else  if ( data.date == '') {
				App.alert("Please enter date!");
			return false;
		}
		else  if ( data.market == '') {
				App.alert("Please enter market!");
			return false;
		}
		else  if ( data.securities == '') {
				App.alert("Please enter a Security!");
			return false;
		}else {
			return true;
		}
	};
	
var xmlcontent='0';
var combo_str='';
var tablen=375+additional_colLen;
//var DlgCalendar='';

var str="<div><form id=\"form_dialog\" name=\"dlgForm\" method=\"POST\" action=\"" + action + "\">";
str+=App.createXMLFields(tablen);
str+= "</form></div>";
App.configDialog.showEvent.subscribe( function() {
												 var y = Dom.get('anim_item').value;
												 y = y *38;
												 var myAnim = new YAHOO.util.Scroll('panel-list', {
   													 		scroll: {
        														to: [0, y] 
   															 	} 
															}, 1, YAHOO.util.Easing.easeOut);
												 myAnim.animate();
												 });
App.configDialog.setBody(str);
App.configDialog.render();

App.configDialog.show();
var attr = {
                        left: {
                           // from:0,
							to: 0
                        }
                    };

if (App.configDialog.cfg.getProperty('cal_trap')!=''){
	var showBtn = Dom.get("oasisdates");

    Event.on(showBtn, "click", function() {
           // Lazy Dialog Creation - Wait to create the Dialog, and setup document click listeners, until the first time the button is clicked.
        if (App.DlgCalendar=='') {
                var fdate=App.configDialog.cfg.getProperty('cal_trap');
				ar=fdate.split('-');
				var pg=ar[1]+'/'+ar[0];
				App.DlgCalendar = new YAHOO.widget.Calendar("dlgcal", {
                    iframe:false,
					pagedate: pg, //"5/2010",
    				//selected: "1/1/2010-1/2/2010,2/22/2010", // ar[1]+'/'+ar[2]+'/'+ar[0],
                    hide_blank_weeks:true  // Enable, to demonstrate how we handle changing height, using changeContent
                });
				
				//App.DlgCalendar.cfg.setProperty('selected', "1/1/2010-1/2/2010,2/22/2010", true);
				//
              
                App.configDialog.cfg.setProperty('cal_hidden',false);
				App.DlgCalendar.render();
			}
			
			App.DlgCalendar.animate = function(flag_show)
			{
			 var attr = {
                        left: {
							to: 0
                        }
                    };
			 if (flag_show==false)
			 	{
				 attr.left.to=Dom.getX('DlgCalendar'); //Dom.get('DlgCalendar').offsetWidth*-1; //
				 attr.left.to*=-1;
			 	 }
				 else
				   attr.left.to = Dom.getX('DlgCalendar')+20;
			 var anim = new YAHOO.util.Anim('DlgCalendar', attr,1, YAHOO.util.Easing.easeOut);
             anim.animate();
			 App.configDialog.cfg.setProperty('cal_hidden',!flag_show);
			 }
			 
			App.DlgCalendar.selectEvent.subscribe(function(ev,args) {
                  
        			var d = args[0][0];
				
                    Dom.get("fdate").value = d[0] + '-' + d[1] + '-' + d[2];             
					App.DlgCalendar.animate(false);
               		 });
			
					//alert(App.configDialog.cfg.getProperty('x')+"=="+App.configDialog.cfg.getProperty('width'));
              var selected_dates='';
			 var fdate=App.configDialog.cfg.getProperty('cal_trap');
			 ar=fdate.split('-');
			 selected_dates=ar[1]+'/'+ar[2]+'/'+ar[0];
			// App.DlgCalendar.cfg.setProperty("selected",ar[1]+'/'+ar[2]+'/'+ar[0],false);
			
			if (App.datesList && App.datesList.length>0)
			{
			 if (App.datesList.length>2)
			 {
			  ar=App.datesList[0].FDATE.split('/');
			  var fromdate = ar[1]+'/'+ar[2]+'/'+ar[0];
			
			  ar=App.datesList[App.datesList.length-1].FDATE.split('/');
			  var todate = ar[1]+'/'+ar[2]+'/'+ar[0];
			 
			  App.DlgCalendar.cfg.setProperty('mindate', fromdate, false);
			  App.DlgCalendar.cfg.setProperty('maxdate', todate, false);
			  			 			
			  for (var i=1; i<App.datesList.length-1;i++){
				 	ar=App.datesList[i].FDATE.split('/');
				 	if (selected_dates!='')
						selected_dates+=',';
					selected_dates += ar[1]+'/'+ar[2]+'/'+ar[0];
			 		}
			  }
			  else{
				  ar=App.datesList[App.datesList.length-1].FDATE.split('/');
			 	  selected_dates += ar[1]+'/'+ar[2]+'/'+ar[0];
			 	 }
			  
			 }
			//alert(selected_dates);
			App.DlgCalendar.cfg.setProperty('selected', selected_dates, false);
			 selected_dates='';
			 //}
			 
			 App.DlgCalendar.render();
			 var width=App.configDialog.cfg.getProperty('width');
			 Dom.get('DlgCalendar').style.position='absolute';
			 Dom.get('DlgCalendar').style.zIndex=1000;
			 Dom.get('DlgCalendar').style.left=parseInt(App.configDialog.cfg.getProperty('x')) + parseInt(width.substr(0,width.indexOf('px')))+'px';
			 Dom.get('DlgCalendar').style.top=App.configDialog.cfg.getProperty('y')+'px';

			 App.DlgCalendar.animate(true);
		    });	
 }
 
if (App.configDialog.cfg.getProperty('list_trap')!=''){
     var _selectbox = Dom.get(App.configDialog.cfg.getProperty('list_trap')); //document.forms.form_dialog.elements.sessions;
	 _selectbox.onchange=function(){ //run some code when "onchange" event fires
 										chosenoption=this.options[this.selectedIndex].value;
										if (chosenoption!=App.ses) {
										    Dom.get('comments_config').innerHTML='Session: '+App.ses+' Title: ['+App.ses_title+'] is going to Stop!';
											App.configDialog.cancel();
											App.stopMarket(0);
											
											App.oButtonStart.set("disabled",true);
											setTimeout(function() {												
											App.ses=chosenoption;
											//App.joinMeteor();
											Dom.get('scroller_session').innerHTML = App.ses;
											
											msg='task=getSessionId&ses='+App.ses;
 											App.callback.argument[0] = 'JS';
											App.AjaxObject.syncRequest(App.aux,msg);
											
											YAHOO.log("getSessionId...:"+App.ses_str,"warn");
				
											setTimeout(function() {
												var execCode="fillDate();";	
												App.callback.argument[0] = 'JS';
 												msg='task=getConfigDate'+'&objId='+App.fdate+'&ses='+App.ses+'&execWhenReturn='+execCode;
 												App.AjaxObject.syncRequest(App.aux,msg);
												}, 1000);
			
												// MARKET CREATION===============			
											setTimeout(function() {
											App.callback.argument[0] = 'JS';
											var execCode="releaseStart();";	
											msg='task=createMarket'+'&execWhenReturn='+execCode+"&data="+App.delay+App.ses_str;
											App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
											}, 1500);											
												}, 500);
											//App.callback.argument = [App.createDialog,"config_dialog",'Session:'+App.ses+' Configuration Options','test1.php?task=updateMarketOptions', ['Apply','Apply Restart','Cancel'],300,300];									
											//setTimeout(function() {	
											//	App.AjaxObject.syncRequest(App.test1,"task=config&ses="+App.ses);
											//	}, 1000);
											}
										  else
										     Dom.get('comments_config').innerHTML='Session: '+App.ses+' Title: ['+App.ses_title+'] is Active!';
										
										//alert(chosenoption);
							};
    }
//alert(str);
App.dialog_ar=null;
str=null;
}

App.HighLightTR = function(el, backColor,textColor,id){
/*  if(typeof(preEl)!='undefined') {
     preEl.bgColor=orgBColor;
     try{ChangeTextColor(preEl,orgTColor);}catch(e){;}
  }
  orgBColor = el.bgColor;
  orgTColor = el.style.color;
  el.bgColor=backColor;

  try{ChangeTextColor(el,textColor);}catch(e){;}
  preEl = el;
*/  
//  for (i = 0; i < el.parentNode.childNodes.length; i++)
//el.parentNode.childNodes[1].style.backgroundColor=backColor;
//el.firstChild.style.color=textColor;
//el.firstChild.style.backgroundColor=backColor;

var cell=el.cells[1];
//cell.style.backgroundColor=backColor;
//cell.style.color=textColor;

var cell0=el.cells[0];
for (j=0; j<cell0.childNodes.length; j++)
                    {           
                        //if childNode type is CheckBox                 
                        if (cell0.childNodes[j].type =="checkbox")
                        {
                        //assign the status of the Select All checkbox to the cell checkbox within the grid
						   
                            cell0.childNodes[j].checked = !cell0.childNodes[j].checked;
							if (cell0.childNodes[j].checked==true){
								cell.style.backgroundColor=backColor;
								cell.style.color=textColor;
								if (Dom.get(id).value!='')
							    	Dom.get(id).value+=",";
								Dom.get(id).value+=cell0.childNodes[j].value;
								
								}
								else{
								cell.style.backgroundColor='#ffffff';
								cell.style.color='#000000';
								var ar = Dom.get(id).value.split(',');
								ar.splice(ar.indexOf(cell0.childNodes[j].value),1);
								Dom.get(id).value = ar.join(',');
								}							
                        }
                    }
}

App.createXMLFields = function(tablen)
{
 var xmlcontent='0';
 var combo_str='';
 var check_str='';
 var ro='';
 var comments='';
 var hide='';
 var value='';
 hiddenName='';
 var calendar_str='';
 var animpos=0;
 
 var str="<TABLE border=0  align=\"left\"  width="+ tablen +">"; //width= + tablen
//alert(App.dialog_ar.length+"=="+App.dialog_ar[0].length+"=="+App.dialog_ar[1].length);	
	//for (var i=0;i<x.length;i++)
	for (var i=0; i< App.dialog_ar.length; i++)
	{
	 name = App.dialog_ar[i][0][0];
	 var label = App.dialog_ar[i][0][1];
	 ftype='';
/**
* check if this field is hidden
*/
/*
<td nowrap="" height="0px" width="100%" valign="top" align="right" class="scGridFieldOddVert">
     <span style="text-align: right; vertical-align: middle; font-weight: bold;">Supplier</span><br>
     Pavlova, Ltd.
    </td>
*/

	 for (var c=1; c< App.dialog_ar[i].length; c++) 
		  ftype =  App.dialog_ar[i][c][1];
	 if (ftype && ftype=='hidden')
	    str+= "<tr><td>&nbsp;</td>";
	  else
	 	{
	 	var ar = label.split(' ');
	 	label='';
	 	for (var c=0; c<ar.length; c++)
	      label+=ar[c]+((c < ar.length-1) ?"&nbsp;" :'');
//	 alert(App.dialog_ar.length);
	 	str+= "<tr height=\"" + (App.dialog_ar.length>12 ?"12\"" :"30\"") + "align=\"left\">";

	 	if (App.dialog_ar[i][1][1]=='span')
			str+= "<td>" + "<span>" + ":</span>" + "</td>";	 
			else
	       		str+= "<td>" + "<span id='dlg_field'>" + label + ":</span>" + "</td>"; //"<label  class='dialog_label' for=\"" + name + "\">" +":</label>" +
		}
		
	 for (var c=1; c< App.dialog_ar[i].length; c++)
	 {		 
	  xmlcontent = App.dialog_ar[i][c][0]; 
	  ftype = App.dialog_ar[i][c][1];
	  value = App.dialog_ar[i][c][2];
	  sourceScript = App.dialog_ar[i][c][3];

	if (ftype=='readonly')
	     ro='readonly';
		 else
		   ro='';
			   
	 if (ftype=='checkbox') {
		 check_str=(value==1 ?"checked" :"");
		 value='1';
		 //value=(value=='1' ?'on' :'off');
	 	}
	if (ftype=='radio') {
		 check_str=(value==1 ?"1" :"0");
		 //value='1';
		 //value=(value=='1' ?'on' :'off');
	 	}
	 
	if (xmlcontent=='1') {
		 ftype=App.fldTypes['combo'];
		 if (name=='securities') {
		     hiddenName=name;
			 combo_str="<div id=\"panel-list\" style=\"border:1px black solid; width:200px; height:100px; overflow:auto;\"><TABLE cellpadding=\"5\" cellspacing=\"4\">";
			   // ==> Create a hidden to hold the multiple select result, only once!
			 combo_str+='<input type="hidden" name=\"'+hiddenName+'\" id=\"'+hiddenName+'\" value=\"'+App.dialog_ar[i][c][2]+'\" />';
			 
		 	 }
			 else {
			 combo_str="<select name=\"" + name + "\" id=\"" + name + "\" style=\"width:150px;\">";
			 combo_str+=value;
			 }
		 
		    
		 for(var j=0; j<App.dialog_ar[i][c][4].length; j++)
		 {
		 	var checked=(App.dialog_ar[i][c][4][j][2]==null ?'' :App.dialog_ar[i][c][4][j][2]);
			if(checked)
				animpos=j;
				
			if (name=='securities'){
				
				var checked_color=(checked=='' ?'' :'style=\"background-color:#c9cc99;color:#cc3333;\"');
				
				combo_str+="<tr onclick=\"App.HighLightTR(this,'#c9cc99','cc3333',hiddenName);\"><td><INPUT type=CHECKBOX  value=\""+App.dialog_ar[i][c][4][j][1]+"\""+checked+">"+"</td><td "+checked_color+">"+App.dialog_ar[i][c][4][j][0]+"</td></tr>";
				}
				else
		 		combo_str+="<option value="+ App.dialog_ar[i][c][4][j][1] +" "+ checked +">"+App.dialog_ar[i][c][4][j][0]+"</option>";
		    }
		 combo_str+=(name=='securities' ?"</table></div>" :"</select>");
		 if (animpos>0)
		     combo_str+='<input type="hidden" id=\"anim_item\" value="' + animpos+1 + '" />';
		 App.callback.argument = ['options'];
		// App.AjaxObject.startRequest(sourceScript);
	 	 }
		 
	 if (start=value.indexOf('<') > -1){
		 var last=value.lastIndexOf('>');
	     comments=value.substring(start+1,last);
		 value=value.substring(0,start);
	 	}
	 else
	 	comments='';
	 
	 if ((start=value.indexOf('^')) > -1){
		 href=value.substring(start+1,value.lastIndexOf(';')+1);
		 target=' target="' + value.substring(value.indexOf('^_')+1,value.length) + '"';
		// YAHOO.log(start+"==="+href+"==="+target,"warn");
		 link_str="<a id='dialog_href' href='"+href+"'>"+value.substring(0,start)+'</a>';
	 }
	 else
	 	link_str='';
	
	 if (ftype=='span'){ 
		 App.configDialog.cfg.setProperty('list_trap',value);
		 span_str = "<span id="+name+"></span>";
	 	 }
	 
	 if (ftype=='calendar'){ 
		 App.configDialog.cfg.setProperty('cal_trap',value);
		 calendar_str='<button type="button" id="oasisdates" title="Oasis Calendar"><img src="yui/examples/calendar/assets/calbtn.gif" width="18" height="18" alt="Calendar" ></button>';
		 ftype='text';
	 	 }
	 
	 input_str =  "<input type=" + ftype + " name=\"" + name + "\"" + " id=\"" + name +"\"" + " value=\"" + value + "\"" + check_str + ro + " />";
	 str+= "<td>" + ((ftype=="select") ?combo_str :((ftype=='link') ?link_str :((ftype=='span') ?span_str :input_str+calendar_str)));
	 if (comments) {
		//  YAHOO.log("comments:"+"=="+App.dialog_ar[i].length+"=="+c,"warn");
	     if (App.dialog_ar[i].length>2)
		 {
		    if (c==2)
  	      		str+= "</td><td>" + "<span class='dialog_comments'>" + comments + "</span>" + "</td>";
		 }
		  else
		    str+= "</td><td>" + "<span class='dialog_comments'>" + comments + "</span>" + "</td>";
	 	}
	 }
	 str+= "</tr>";
	 combo_str='';
	 check_str='';
	 calendar_str='';
	}
	
  str+= "</TABLE>";
  
  return str;
 }

//==============
//document.getElementById("to").innerHTML=xmlDoc.getElementsByTagName("to")[0].childNodes[0].nodeValue;
//document.getElementById("from").innerHTML=xmlDoc.getElementsByTagName("from")[0].childNodes[0].nodeValue;
//document.getElementById("message").innerHTML=xmlDoc.getElementsByTagName("body")[0].childNodes[0].nodeValue;
		  
    //Call loader the first time
    var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        //Get these modules
        require: ['reset-fonts-grids', 'animation', 'dragdrop', 'utilities', 'connection', 'datasource', 'datatable','container','logger', 'button', 'menu', 'calendar','event', 'selector', 'resize', 'layout','tabview','treeview'],
        rollup: true,
        onSuccess: function() {
            //Use the DD shim on all DD objects
            YAHOO.util.DDM.useShim = true;
            //Load the global CSS file.
            YAHOO.log('Main files loaded..', 'info', 'main.js');
            YAHOO.util.Get.css('yui/examples/layout/assets/css/example1.css');
		//	YAHOO.util.Get.css('yui/examples/treeview/assets/css/default/tree.css');
			YAHOO.util.Get.css('codebase/marketplay.css');

            YAHOO.log('Create the first layout on the page', 'info', 'main.js');

		App = YAHOO.mainTradeReplay.app;    
		Dom=YAHOO.util.Dom;
		Event=YAHOO.util.Event;
		DDM = YAHOO.util.DragDropMgr;
		
		// Check service3 is up !!!!
				App.callback.argument[0] = 'JS';
 				App.AjaxObject.syncRequest('../../cgi-bin/check3-run','');
		
		setTimeout(function() {
                    App.callback.argument[0] = 'JS';
 				    App.AjaxObject.syncRequest('../../cgi-bin/check3','');
                	}, 1000);	
		
		
                    if (App.err==1) // Service is Down ...try again after 10 secs!
						setTimeout(function() {
						  App.callback.argument[0] = 'JS';
 				    	  App.AjaxObject.syncRequest('../../cgi-bin/check3-run','');
						  }, 10000);
					
        
			
		 if (App.err==1) // for some reason Service cannot Run ...!
			{
			alert("Please contact administrators, Service is Down!");
			return(0);
			}
/*
	 App.DDList = function  (id, sGroup, config) 
	 {   	    
      App.DDList.superclass.constructor.call(this, id, sGroup, config);   
   
     this.logger = this.logger || YAHOO;   
     var el = this.getDragEl();   
     Dom.setStyle(el, "opacity", 0.67); // The proxy is slightly transparent  
	 Dom.setStyle(el, "color", "red");
 //this.logger.log(Dom.getAttribute(el,"id") + "=="+this.id+" initDrag","warn"); 
     this.goingUp = false;   
     this.lastY = 0;   
 	 };   
   
	YAHOO.extend(App.DDList, YAHOO.util.DDProxy, {   
    
     startDrag: function(x, y) { 
	 
         this.logger.log(this.id + " startDrag");   
   
         // make the proxy look like the source element   
         var dragEl = this.getDragEl();   
         var clickEl = this.getEl();   
         Dom.setStyle(clickEl, "visibility", "hidden");   
   
     	},   
  
     onDrag: function(e) {   
         // Keep track of the direction of the drag for use during onDragOver   
         var y = Event.getPageY(e);   
   //		 this.logger.log(y + " onDrag","warn");    
         if (y < this.lastY) {   
             this.goingUp = true;   
         } else if (y > this.lastY) {   
             this.goingUp = false;   
         }   
   
         this.lastY = y;   
     },   
   
     onDragDrop: function(e, id) {      
         // If there is one drop interaction, the li was dropped either on the list,   
         // or it was dropped on the current location of the source element.   
         if (DDM.interactionInfo.drop.length === 1) {   
   
             // The position of the cursor at the time of the drop (YAHOO.util.Point)   
             var pt = DDM.interactionInfo.point;    
   
             // The region occupied by the source element at the time of the drop   
             var region = DDM.interactionInfo.sourceRegion;    
   
             // Check to see if we are over the source element's location.  We will   
             // append to the bottom of the list once we are sure it was a drop in   
             // the negative space (the area of the list without any list items)   
             if (!region.intersect(pt)) {   
                 var destEl = Dom.get(id);   
                 var destDD = DDM.getDDById(id);
				  this.logger.log(destEl.id + " destEl","warn");
				  var ar=destEl.id.split('-');
				//App.alert("A=="+ar[ar.length-1]);
				//this.logger.log(destDD.id + " onDragDrop: "+destEl.id,"warn"); 
				 if (destDD.id=="bottom1")
				 {
				 this.logger.log(destDD.id + " onDragDrop: "+destEl.id,"warn"); 
				 destEl.appendChild(document.createTextNode(this.getEl().innerHTML));
				 }
				 
				 if (destDD.id=="trg_ord_trader")
				 {
				 this.logger.log(destDD.id + " onDragDropog trg_ord_trader: "+this.getEl().innerHTML,"warn"); 
				 //destEl.appendChild=appendChild(document.createTextNode(this.getEl().innerHTML));
				 destEl.value=this.getEl().innerHTML.substr(0,2);
				 App.mygrid_smartOrders.fireDT(true);
				 }
				 
				 if (destDD.id=="trg_buyer")
				 {
//				 this.logger.log(destDD.id + " onDragDropog trg_ord_trader: "+this.getEl().innerHTML,"warn"); 
				 destEl.value=this.getEl().innerHTML.substr(0,2);
				 App.mygrid_smartTrades.fireDT(true);
				 }
				 if (destDD.id=="trg_seller")
				 {
				 destEl.value=this.getEl().innerHTML.substr(0,2);
				 App.mygrid_smartTrades.fireDT(true);
				 }
				 
				 if (ar[ar.length-1]=="TRADER_ID")
				 {
					  
				 //this.logger.log(destDD.id + " onDragDrop","warn"); 
				 var tar = YAHOO.util.Event.getTarget(e);
				 //srcData = App.mygrid_orders.getRecord(destEl).getData();
	//			  App.alert(tar.innerHTML+"B=="+tar.id+"=="+this.getEl().innerHTML);
				  App.createOrdersGrid.filter=this.getEl().innerHTML;
                 //destEl.appendChild(this.getEl());
				 }
				 else if (ar[ar.length-1]=="BUY_TRADER_ID")
				 {
				  App.createTradesGrid.Buyfilter=this.getEl().innerHTML;
				 }
				 else if (ar[ar.length-1]=="SELL_TRADER_ID")
				 {
				  App.createTradesGrid.Sellfilter=this.getEl().innerHTML;
				 }
                 destDD.isEmpty = false;   
                 DDM.refreshCache();   
				 
             }   
   
         }   
     },  

	 endDrag: function(e) {   
         var srcEl = this.getEl();   
         var proxy = this.getDragEl();   
   
         // Show the proxy element and animate it to the src element's location   
         Dom.setStyle(proxy, "visibility", "");   
         var a = new YAHOO.util.Motion(    
             proxy, {    
                 points: {    
                     to: Dom.getXY(srcEl)   
                 }   
             },    
             0.2,    
             YAHOO.util.Easing.easeOut    
         )   
         var proxyid = proxy.id;   
         var thisid = this.id;   
   
         // Hide the proxy and show the source element when finished with the animation   
         a.onComplete.subscribe(function() {  
										// this.logger.log(" endDrag="+srcEl.id+"=="+proxyid+"=="+thisid,"warn");
                 Dom.setStyle(proxyid, "visibility", "hidden");
				 Dom.setStyle(srcEl.id,"visibility", "");
                 Dom.setStyle(thisid, "visibility", "");   
             });   
         a.animate();   
     }, 

	 onDragOver: function(e, id) {   
        
         var srcEl = this.getEl();   
         var destEl = Dom.get(id);   
   //this.logger.log(" onDragOver="+id,"warn");
         // We are only concerned with list items, we ignore the dragover   
         // notifications for the list.   
         if (destEl.nodeName.toLowerCase() == "div") {   
             var orig_p = srcEl.parentNode;   
             var p = destEl.parentNode;   
   
         }   
	 }
   

	});*/
	
		
		 
		function onButtonClick(p_oEvent) {
          /*  YAHOO.log */ alert("You clicked button: " + this.get("id"), "info", "example1");       
        		}
				
			App.layout = new YAHOO.widget.Layout({
            minWidth: 1000,
            //minHeight: 500,
            units: [
					{ position: 'top', height: 40 , resize: false, body: 'top1'},
                    { position: 'right', width: 305, resize: true},
                    { position: 'left', width: 170, resize: true, body: 'left1', gutter: '0px 5px 0px 5px', minWidth: 150 },
                    { position: 'center', gutter: '0 5px 0 2' }
            ]
        });
       	
		App.layout.on('resize', function() {
                var l = this.getUnitByPosition('left');
                var th = l.get('height') - YAHOO.util.Dom.get('folder_top').offsetHeight;
                var h = th - 4; //Borders around the 2 areas
                h = h - 9; //Padding between the 2 parts
                YAHOO.util.Dom.setStyle('folder_list', 'height', h + 'px');
            }, App.layout, true);
            //On render, load tabview.js and button.js
        
		App.layout.on('render', function() {
 
           YAHOO.util.Get.script('js/detailsPanel.js'); 
		   YAHOO.util.Get.script('js/buttons.js'); 
		   YAHOO.util.Get.script('js/calendar.js');
		   
//		   YAHOO.util.Get.script('js/trailer.js');
		   			
            //    App.layout.getUnitByPosition('right').collapse();
            setTimeout(function() {
                    YAHOO.util.Dom.setStyle(document.body, 'visibility', 'visible');
                    App.layout.resize();
                	}, 1000);
		   	
				
				msg='task=getSessionId'+'&execWhenReturn=updateScroller();';
 				App.callback.argument[0] = 'JS';
				App.AjaxObject.syncRequest(App.aux,msg);
				YAHOO.log("getSessionId...:"+App.ses_str,"warn");
						
			setTimeout(function() {
				var execCode="fillDate();";	
				App.callback.argument[0] = 'JS';
 				msg='task=getConfigDate'+'&objId='+App.fdate+'&ses='+App.ses+'&execWhenReturn='+execCode;
 				App.AjaxObject.syncRequest(App.aux,msg);
				}, 1000);
			
			setTimeout(function() {
					YAHOO.log("Before Channels - ses="+App.ses_str,"info");
					YAHOO.util.Get.script('js/channels.js');
					}, 1000);

// MARKET CREATION===============			
			setTimeout(function() {
			App.callback.argument[0] = 'JS';
			msg='task=createMarket'+"&data="+App.delay+App.ses_str;
			App.AjaxObject.syncRequest(App.Serv1_dispatcher,msg);
			 YAHOO.mainTradeReplay.app.alert('This is a product of <b>Hellenic Exchanges</b></br>');
			}, 1000);
//===============================

			var el5 = YAHOO.mainTradeReplay.app.layout.getUnitByPosition('right').get('wrap');
		    App.layout5 = new YAHOO.widget.Layout(el5, {
                parent: YAHOO.mainTradeReplay.app.layout,
               // minHeight: 400,
				minWidth:100,
                units: [
					{ position: 'top', body: 'div_events',  height:250, width: 300, gutter: '1px 1px 1px 1px', scroll: false, maxWidth: 300,resize: true }, //header: 'Events Console',
					{ position: 'bottom',  body: '', header: 'Logger Console', height:200,collapse: true,width: 300, gutter: '1px 1px 1px 1px',resize: true}, //,  gutter: '5px'					
					{ position: 'center', body:'div_MktInfo', header: 'Statistics', gutter: '1px 1px 1px 1px'}
                ]
            });
			
		
			App.layout5.render();
			
			var el2 = YAHOO.mainTradeReplay.app.layout.getUnitByPosition('center').get('wrap');
		    App.layout2 = new YAHOO.widget.Layout(el2, {
                parent: YAHOO.mainTradeReplay.app.layout,
               // minHeight: 400,
				minWidth:200,
                units: [
						{ position: 'left', width: 370 },//, width: 370
					//{ position: 'top', height: 240,gutter: '1px 1px 1px 1px'},
					
					//{ position: 'right',  height: 130, resize: true,  collapse: false, gutter: '2px 2px 2px 2px', scroll: false },
					{ position: 'center'},
					{ position: 'right'}
                ]
            });
			
			App.layout2.render();
/*			
			var el21 = YAHOO.mainTradeReplay.app.layout2.getUnitByPosition('bottom').get('wrap');
		    App.layout21 = new YAHOO.widget.Layout(el21, {
                parent: YAHOO.mainTradeReplay.app.layout2,
               // minHeight: 400,
				minWidth:200,
                units: [									//div_asksurv
					{ position: 'left', width: 370,body: 'div_surv', collapse: false, height:130,resize: false,  gutter: '2px 2px 2px 1px', scroll: false},
					{ position: 'right', width: 800,body: 'div_MdAskTraders', collapse: false, height:130,resize: true,  gutter: '2px 2px 2px 2px', scroll: false},// header: 'Top 10 Sellers',
					{ position: 'center',  scroll: false }
                ]
            });
			
			App.layout21.render();
*/	
			var el31 = YAHOO.mainTradeReplay.app.layout2.getUnitByPosition('left').get('wrap');
		    App.layout31 = new YAHOO.widget.Layout(el31, {
                parent: YAHOO.mainTradeReplay.app.layout3,
                minHeight: 80,
				minWidth:200,
                units: [
					 { position: 'top', width: 370, height:140, resize: false,  body:'div_members', gutter: '1px 1px 1px 1px', collapse: false, scroll: false  },
					 { position: 'center',  width: 370,resize: true,  body:'div_mktdepth', header:'Market Depth', gutter: '1px 1px', collapse: false},
					//{ position: 'center', width: 370, body: 'div_surv', collapse: false, height:200,resize: false,  gutter: '1px 1px 1px 1px', scroll: false},
					//{ position: 'left', width: 370,body: 'div_surv', collapse: false, height:230,resize: false,  gutter: '1px 1px 1px 1px', scroll: false}, //
					//{ position: 'right', width: 800,body: 'div_MdBidTraders', collapse: false, height:130,resize: true,  gutter: '2px 2px 2px 2px', scroll: false},//header: 'Top 10 Buyers',
				//	{ position: 'bottom', height:240}
					 {position: 'bottom', width: 370,body: 'div_surv', collapse: false, height:240,resize: true,  gutter: '1px 1px 1px 1px', scroll: false}
					//{ position: 'center'}
                ]
            });
			App.layout31.render();
			
			var el3 = YAHOO.mainTradeReplay.app.layout2.getUnitByPosition('center').get('wrap');
		    App.layout3 = new YAHOO.widget.Layout(el3, {
                parent: YAHOO.mainTradeReplay.app.layout2,
                minHeight: 400,
				minWidth:200,
                units: [
					//{ position: 'top', height: 100, resize: true},
		//			{ position: 'left', width: 370 },
					//{ position: 'right', width: 130,body: 'div_bookmarks', collapse: false, height:100,resize: true,  gutter: '1px 1px 1px 1px', scroll: false},
					{ position: 'top', height:810, resize: false, body:'', collapse: false, gutter: '1px 1px 1px 1px', scroll: true  },
					{ position: 'center', height:430, body: 'bidask_flashcontent',resize: true, gutter: '2px', scroll: true }
					//{ position: 'bottom', gutter: '2px'} //, height:130,resize: false,  gutter: '2px 2px 2px 1px', scroll: true},
                ]
            });
			
			App.layout3.render();
/*			
			var el32 = YAHOO.mainTradeReplay.app.layout3.getUnitByPosition('top').get('wrap');
		    App.layout32 = new YAHOO.widget.Layout(el32, {
                parent: YAHOO.mainTradeReplay.app.layout3,
                minHeight: 400,
				minWidth:200,
                units: [
					//{ position: 'top', height: 100, resize: true},
		//			{ position: 'left', width: 370 },
					//{ position: 'right', width: 130,body: 'div_bookmarks', collapse: false, height:100,resize: true,  gutter: '1px 1px 1px 1px', scroll: false},
					//{ position: 'top'}, //height:240, resize: false, body:'div_trades', collapse: false, gutter: '1px 1px 1px 1px', scroll: false  },
					{ position: 'center', height:430, body: 'bidask_flashcontent',resize: true, gutter: '2px', scroll: true },
					{ position: 'bottom', gutter: '2px'} //, height:130,resize: false,  gutter: '2px 2px 2px 1px', scroll: true},
                ]
            });
			
			App.layout32.render();
*/			
			
			
//			App.layout2.on('render', function() {
			
										
/*			
			
			var el32 = YAHOO.mainTradeReplay.app.layout31.getUnitByPosition('bottom').get('wrap');
		    App.layout32 = new YAHOO.widget.Layout(el32, {
                parent: YAHOO.mainTradeReplay.app.layout31,
                minHeight: 80,
				minWidth:200,
                units: [
					{ position: 'center', width: 370,body: 'div_bidsurv', collapse: false, height:120,resize: false,  gutter: '1px 1px 1px 1px', scroll: false},
					//{ position: 'center', width: 370, body: 'div_surv', collapse: false, height:200,resize: false,  gutter: '1px 1px 1px 1px', scroll: false},
					//{ position: 'left', width: 370,body: 'div_surv', collapse: false, height:230,resize: false,  gutter: '1px 1px 1px 1px', scroll: false}, //
					//{ position: 'right', width: 800,body: 'div_MdBidTraders', collapse: false, height:130,resize: true,  gutter: '2px 2px 2px 2px', scroll: false},//header: 'Top 10 Buyers',
					{ position: 'bottom', width: 370,body: 'div_asksurv', collapse: false, height:120,resize: false,  gutter: '1px 1px 1px 1px', scroll: false}
					//{ position: 'center'}
                ]
            });
			
			App.layout32.render();
*			
	/*		
			var el4 = YAHOO.mainTradeReplay.app.layout2.getUnitByPosition('top').get('wrap');
		    App.layout4 = new YAHOO.widget.Layout(el4, {
                parent: YAHOO.mainTradeReplay.app.layout2,
                minHeight: 100,
				minWidth:400,
                units: [
					//{ position: 'left', width: 370, resize: false,  body:'div_members', gutter: '1px 1px 1px 1px', collapse: false, scroll: false  },
					{ position: 'right',width: 810, resize: true, body:'div_trades', collapse: false, gutter: '1px 1px 1px 1px', scroll: false  } //size:1040
                ]
            });
			
			App.layout4.render();
	*/		
        
        });
		
			
	    YAHOO.mainTradeReplay.app.layout.render();
	    YAHOO.util.Get.script('js/logger.js');
		YAHOO.util.Get.script('js/tabView.js');
		YAHOO.util.Get.script('js/mktGrids.js');
		
		YAHOO.util.Get.script('js/tradersGrid.js');
		YAHOO.util.Get.script('js/bmarkGrid.js');
		YAHOO.util.Get.script('js/obGrid.js');
		YAHOO.util.Get.script('js/statsTabs.js');
		YAHOO.util.Get.script('js/helptree.js');
		YAHOO.util.Get.script('js/docUploader.js');
		YAHOO.util.Get.script('js/ora_secsgrid.js');
		YAHOO.util.Get.script('js/traders_tv.js');
		YAHOO.util.Get.script('js/unserialize.js');
		
		

	   //Create a SimpleDialog used to mimic an OS dialog
            App.panel = new YAHOO.widget.SimpleDialog('alert', {
                fixedcenter: true,
                visible: false,
                modal: true,
                width: '300px',
                constraintoviewport: true, 
                icon: YAHOO.widget.SimpleDialog.ICON_WARN,
                buttons: [
                    { text: 'OK', handler: function() {
                        App.panel.hide();
						YAHOO.util.Event.onAvailable("wrap", function(){
							App.toggleProtection();   
                           });
						
                    }, isDefault: true }
                ]
            });
            //Set the header
            App.panel.setHeader('Alert');
            //Give the body something to render with
            App.panel.setBody('Notta');
			App.panel.cfg.addProperty('ret',{ret:true});
            //Render the Dialog to the body
            App.panel.render(document.body);

            //Create a namepaced alert method
            YAHOO.mainTradeReplay.app.alert = function(str) {
                YAHOO.log('Firing panel setBody with string: ' + str, 'info', 'main.js');
                //Set the body to the string passed
                App.panel.setBody(str);
                //Set an icon
                App.panel.cfg.setProperty('icon', YAHOO.widget.SimpleDialog.ICON_WARN);
                //Bring the dialog to the top
                App.panel.bringToTop();
                //Show it
                App.panel.show();
            };
			YAHOO.mainTradeReplay.app.alert2 = function(str,callback) {
                //YAHOO.log('Firing panel setBody with string: ' + str, 'info', 'main.js');
                //Set the body to the string passed
                App.panel.setBody(str);
				//YAHOO.log("alert2 - new Variable "+callback,'info');
				if (callback!=null) {
					
					App.myVar = new Variable(10, callback);
				}
                //Set an icon
                App.panel.cfg.setProperty('icon', YAHOO.widget.SimpleDialog.ICON_WARN);
				App.panel.cfg.setProperty('buttons', [
                    								{ text: 'Yes', handler: function() {
                        																App.panel.hide();
																						App.panel.cfg.setProperty('ret', true);
																						App.myVar.SetValue(App.panel.cfg.getProperty('ret'));
																						}, 
														isDefault: true },
														{ text: 'No', handler: function() {
                        																App.panel.hide();
																						App.panel.cfg.setProperty('ret', false);
																						App.myVar.SetValue(App.panel.cfg.getProperty('ret'));
																						//myVar='';
                    																	}
														}
                									]);
                //Bring the dialog to the top
                App.panel.bringToTop();
                //Show it
                App.panel.show();
            };
			

//            YAHOO.mainTradeReplay.app.alert('This is a product of <b>Hellenic Exchanges</b></br>');
			//App.targ = new YAHOO.util.DDTarget("bottom1","buyers");
			//var targorders = new YAHOO.util.DDTarget("div_orders","buyers");
/*			var El = App.targ.getEl();
			YAHOO.util.Dom.setStyle(El, "color", 'red');
			dd = new App.DDList("div_events","buyers");
			//Dom.setStyle(dd.getEl(), "color", '#2200cc');
			Dom.setStyle(dd.getEl(), "font-weight", 'bold');
			App.targ.on('endDragEvent', function() { 
									   YAHOO.log('Element is Dropped.');
                 Dom.setStyle(this.getDragEl(), 'height', '20px');   
                 Dom.setStyle(this.getDragEl(), 'width', '100px');   
                 Dom.setStyle(this.getDragEl(), 'backgroundColor', 'red');   
                 Dom.setStyle(this.getDragEl(), 'color', 'white');   
                 this.getDragEl().innerHTML = 'Custom Proxy';   
             }); */

      }
    });
    loader.insert();
	
	 //initStart(); 
})();