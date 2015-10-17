// JavaScript Document
function exportmasterfile()
{   var url='../documenten/Master-File.xls';    
    window.open(url,'Download');  
}

function doDL(s)
{
	function dataUrl(data) {
		return "data:x-application/vnd.ms-excel; Charset=utf-8, " +   //data:x-application/text data:x-application/vnd.ms-excel
				encodeURIComponent(data.replace(/\r?\n/g,"\r\n"));
		}
		
	if("\v"=="v"){//IE?
		var d= document.open();
		d.write(s);
		d.execCommand( "saveAs", true, location.href.split("/").reverse()[0]); //
		d.close();
		return;
		}//end IE?
	window.open(dataUrl(s));
 }

function ExportToExcel(tabletoexport) {  
    input_box=true; //confirm("Export rows of table data to MS Excel?");  
        if (input_box==true) {  
			XlSheet='';
			var columns = tabletoexport.getColumnSet();
			var rows = tabletoexport.getRecordSet();

			for(i=0;i<columns.keys.length;i++)  {  
                label=tabletoexport.getColumn(i).label;
				XlSheet=XlSheet+(label ?label+";" :"");
			}
				

 var XlData='';
 

            //run over the dynamic result table and pull out the values and insert into corresponding Excel cells  
            var d = 0;  
            for (r=0;r<rows.getLength();r++) { // start at row 2 as we've added in headers - so also add in another row!  
                for (c=0;c<columns.keys.length;c++) {  
                   // XlSheet.cells(r,c).value = data[d].innerText;  
				   if (columns.keys[c].label) {
				   	   XlData=XlData+rows.getRecord(r).getData(columns.keys[c].key)+";";
				  
                    dd = d + 1;  
				   }
                }
				XlData+="\n";
            } 

  doDL(XlSheet+"\n"+XlData);
        }  
 } 

function amPeriodSelected(chart_id, type, count, id)
{
if (id == 'H2') 
{
 UnSetMarketToGraph();
 App.flashBidAsk.setZoom(App.fdate+' '+'15:00:00.000', App.fdate+' '+'15:10:00.000');
 }
 else if (id == 'H1') 
         {
		 UnSetMarketToGraph();
		 App.flashBidAsk.setZoom(App.fdate+' '+'10:30:00.000', App.fdate+' '+'11:00:00.000');
		 }
		else if (id == 'T1') 
         	{
		 	 UnSetMarketToGraph();
			 zTime = App.bidask_lastTime.substr(0,3) + (parseInt(App.bidask_lastTime.substr(3,2)) +4) + App.bidask_lastTime.substr(5,7);
			 App.bidask_lastTime = App.bidask_lastTime.substr(0,3) + (parseInt(App.bidask_lastTime.substr(3,2)) -4) + App.bidask_lastTime.substr(5,7);
			 App.flashBidAsk.setZoom(App.fdate+' '+App.bidask_lastTime, App.fdate+' '+zTime);
			 YAHOO.log("Period:"+App.bidask_lastTime+"=="+zTime,'error');
		 	 } 
}


(function() {   
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
	
	var loader = new YAHOO.util.YUILoader({   
        base: 'yui/build/',
		charset:'utf-8',
        require: ["datasource","datatable","json","menu", "fonts"],   
        //loadOptional: false,   
        //combine: true,   
        //filter: "MIN",   
        //allowRollup: true,   
        onSuccess: function() { 
		YAHOO.log("TradesGrid loaded successfully...",'error');
			App.callback.argument[0] = 'JS';
			msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn=createOrdersGrid();';
			App.AjaxObject.syncRequest(App.aux,msg);
			msg='task=getMsgColumns'+'&objId=IS'+'&execWhenReturn=createTradesGrid();';
		    App.AjaxObject.syncRequest(App.aux,msg);
			
            //you can make use of all requested YUI modules   
            //here.   
        }   
    });   
  
// Load the files using the insert() method.   
loader.insert({}, 'js');   
})(); 

App.createTradesGrid = function()
	{
		 Buyfilter:'';
		 Sellfilter:'';
		 
		 filter:null;
		 filterOrder:null;
		 scenarioOrder:null;
		 fieldOrder:null;
		 fieldCond:null;
		 
		 App.bidask_lastTime='10:15:00.000';
		 var orderSide = function(elLiner, oRecord, oColumn, oData) {           
                if (oData=='Sell')
					YAHOO.util.Dom.addClass(elLiner,'order-sell');
				   else
				   if (oData=='Buy')
					   YAHOO.util.Dom.addClass(elLiner,'order-buy');
				elLiner.innerHTML = oData; //((oData=='Buy') ?'<span style="color:#00aa00">' :((oData=='Sell') ?'<span style="color:#aa0000">' :'<span>'))+oData+'</span>';
                }
		
		var entryType = function(elLiner, oRecord, oColumn, oData) {           
                switch(oData){
					case 'TRADE':
					
						elLiner.innerHTML = '<span style="padding:2px;background-color:#FF6666;color:#ffffff">&nbsp;'+
						'<img src="css/images/trade.png" />'+oData+'</span>';
						//YAHOO.util.Dom.addClass(elLiner,'trade');
					break;
					case 'ORDER':
						if (oRecord.getData("FLAGS")[1]=='X')
							YAHOO.util.Dom.addClass(elLiner,'order-deleted');
						if (oRecord.getData("FLAGS")[0]=='Q')
							YAHOO.util.Dom.addClass(elLiner,'order-mm');
						if (oRecord.getData("FLAGS")[1]=='I')
							YAHOO.util.Dom.addClass(elLiner,'order-inactive');
						elLiner.innerHTML = oData;	
					break;
				}
				
				//elLiner.innerHTML = ((oData=='TRADE') ?'<span style="padding-left:2px;float:left;width:30px;background-color:#aa0000;color:#ffffff">&nbsp;' :((oData=='ORDER' && oRecord.getData("FLAGS")[1]=='X') ?'<span style="text-decoration:line-through">' :'<span>'))+oData+'<span style="height:3px;width:3px;float:right;background-color:#aaff00;">'+'&nbsp;</span></span>';
                }
				
		 YAHOO.widget.DataTable.Formatter.orderCustom = orderSide;
		 YAHOO.widget.DataTable.Formatter.entryCustom = entryType;
		  
		 tradesDataSource = new YAHOO.util.DataSource({});  //"http://"+Meteor.host+((Meteor.port==80)?"":":"+Meteor.port)+"/stream.html" 
 		 tradesDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
		 tradesDataSource.responseSchema = { fields: App.columnsIds   };  //["OASIS_TIME","PHASE_ID","SIDE","ORDER_NUMBER","VOLUME","PRICE","TRADER_ID","ORDER_STATUS","M_C_FLAG"]
		 
		 App.createTradesGrid.colIds=App.columnsIds;
		 App.createTradesGrid.colDef=App.columnsDef;
		 
		 App.createTradesGrid.colDef[3].formatter="orderCustom";
		 App.createTradesGrid.colDef[2].formatter="entryCustom";
		 
		 var myConfigs = {   
    		 //scrollable:false,
			 draggableColumns:true,
			 dynamicData : false,
			 height: "115px", 
			// scrollable:true,
			 renderLoopSize: 0, 
			 initialLoad:false
			 };  
		
			
		var refreshTradesGraph = function( oArgs ){
						orec=oArgs.record;
						sTimes=sTime=orec.getData("OASIS_TIME");
						
					//YAHOO.log("TRADE:"+sTime +"=="+ App.fdate,'error');
					//YAHOO.log("TRADE:"+sTime +"=="+ sTime.substr(3,2)+"=="+(parseInt(sTime.substr(3,2)) +4),'error');	
				 
					if (App.showGraph && showGraphApp.oButtonStart.get("value")==_TOOLBAR_START_PLAY)
					{
						if ( (parseInt(sTime.substr(3,2)) - parseInt(App.bidask_lastTime.substr(3,2))) < 7 ) {					
							//var lst= App.bidask_lastTime.substr(0,3) + (parseInt(App.bidask_lastTime.substr(3,2)) -2) + App.bidask_lastTime.substr(5,7);
							//YAHOO.log("TRADE:"+lst +"=="+ App.bidask_lastTime,'error');
							//App.bidask_lastTime = lst;
							sTime = sTime.substr(0,3) + (parseInt(sTime.substr(3,2)) +4) + sTime.substr(5,7);
							
						    }
					
						UnSetMarketToGraph();
						var lst=App.bidask_lastTime.substr(0,3)+(parseInt(App.bidask_lastTime.substr(3,2))-4) +App.bidask_lastTime.substr(5,7);

						//YAHOO.log("refreshTradesGraph:"+App.fdate+' '+lst+"=="+ App.fdate+' '+sTime,'error');
						App.isinAr[document.getElementById('isin').value][_SEC_TABLE_FLASHOBJ].setZoom(App.fdate+' '+lst, App.fdate+' '+sTime);
					}
					else
					  App.processIsFree=true;
					
					App.bidask_lastTime=sTimes;
																					//App.bid_1 App.ask_1+
						//App.flashBidAsk.appendData(App.fdate+' '+sTime+";"+orec.getData("PRICE")+";"+App.bid_1+";"+App.ask_1+";"+orec.getData("VOLUME")+";"+orec.getData("PRICE"));
						//setTimeout(function() {
					
					//	App.flashBidAsk.setEvents("<events><event><date>"+App.fdate+' '+sTime+"</date><url></url><color>F7F37E</color><letter>A</letter><bullet>flag</bullet><description><![CDATA["+orec.getData("PRICE")+"]]></description><size>12</size></event></events>",false);
						//}, 3000);
						//App.bidask_so.addVariable("additional_chart_settings", "<settings><data_sets><data_set did='0'><events><event><date>"+App.fdate+' '+sTime+"</date><url></url><color>F7F37E</color><letter>A</letter><bullet>flag</bullet><description><![CDATA["+orec.getData("PRICE")+"]]></description><size>12</size></event></events></data_set></data_sets></settings>");
						
						};

		App.mygrid_trades = new YAHOO.widget.DataTable("div_trades", App.columnsDef, tradesDataSource, myConfigs); //scrollable:true
 		App.mygrid_trades.subscribe("rowClickEvent", App.mygrid_trades.onEventSelectRow);
 		App.mygrid_trades.subscribe("rowMouseoverEvent", App.mygrid_trades.onEventHighlightRow);
 		App.mygrid_trades.subscribe("rowMouseoutEvent", App.mygrid_trades.onEventUnhighlightRow);
		App.mygrid_trades.subscribe("rowAddEvent", refreshTradesGraph);
		App.mygrid_trades.subscribe("postRenderEvent", function(){
																App.processIsFree=true;
																});
		App.mygrid_trades.subscribe("initEvent", function() {
															  	
															  var el_buy=Dom.get(this.getColumn('BUY_TRADER_ID').getThEl());
															  var el_sell=Dom.get(this.getColumn('SELL_TRADER_ID').getThEl());
															  //alert(el.innerHTML+"=="+el.id);
															  var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el_buy,"id"),"buyers");
															  var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el_sell,"id"),"buyers");
															  if (trg)
															      YAHOO.log('Trades trg: '+Dom.getAttribute(el_buy,"id"), 'warn', 'mktGrids.js');
															   });
		
		//for(i=0;i<41;i++)
		//	App.mygrid_trades.addRow({});
			
		// ===== Attach		TRADES MENU ===========
				
		App.split_time = function(str){
			 if (!str)
			     return str;
			 var newstr='';
			 for(var i=0; i<str.length; i++)
			     newstr+=(str[i]==':' || str[i]=='.') ?'' :str[i];
			return newstr;	
		 	}
		
		ontradesContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];   
             if(task) {   
                 // Extract which TR element triggered the context menu  
				 
                 var elRow = this.contextEventTarget; 
				
				 var elCell = p_myDataTable.getTdEl(elRow);
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {   
                         case 0:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow); 
							
							var trade_time=App.split_time(oRecord.getData("OASIS_TIME"));
							
							App.createTradeOrdersWindow_bid(trade_time, oRecord.getData("ID"),oRecord.getData("BUY_ORDER_NUMBER"),oRecord.getData("SELL_ORDER_NUMBER"),																	   'Orders Details of trade: '+oRecord.getData("TRADE_NUMBER"),'order_ftr');
							break;
						case 1:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow); 
							var te = oRecord.getData("OASIS_TIME").split(':');
							var trade_time='';
							for(var i=0; i<te.length; i++)
			    				trade_time+=te[i];
							App.createTradeWindow(trade_time, oRecord.getData("ID"), 'Trade Details of: '+oRecord.getData("BUY_TRADER_ID")+"/"+oRecord.getData("SELL_TRADER_ID"),'trade');
							break;
						case 2:
							var oRecord = p_myDataTable.getRecord(elRow);
							
							var trade_time = App.split_time(oRecord.getData("OASIS_TIME"));
							var sb=(oRecord.getData("SIDE")=='B' ?'BUY' :'SELL');
							App.createTradeOrdersWindow_bid(trade_time, 0,oRecord.getData("ID"),0,'Order Details of: '+oRecord.getData(sb+"_TRADER_ID"),'order');
							break;
                     	case 3:
							//Dom.get('filt_field').value = App.isin;
							var selectbox = document.forms.OrderSurvForm.elements.filt_field;
							App.mygrid_trades.orecord = p_myDataTable.getRecord(elRow); 
							
							selectbox.onchange=function(){ //run some code when "onchange" event fires
 										var chosenoption=this.options[this.selectedIndex];
										Dom.get('sordnum').value = App.mygrid_trades.orecord.getData(chosenoption.value);
										Dom.get('sordfield').value = chosenoption.value;
										//alert(chosenoption.value+"=="+App.mygrid_orders.orecord.getData(chosenoption.value));
							};
							var selectCond = document.forms.OrderSurvForm.elements.filtcond;
							selectCond.onchange=function(){ //run some code when "onchange" event fires
 										var chosenoption=this.options[this.selectedIndex];
										Dom.get('condhidden').value = chosenoption.value;
										//alert(chosenoption.value+"=="+App.mygrid_orders.orecord.getData(chosenoption.value));
							};
							if (selectbox.options.length==0)
								{
								 for(i=0; i<App.createTradesGrid.colDef.length;i++) 
								 	 if (typeof(App.createTradesGrid.colDef[i].hidden)== 'undefined') //  && App.createOrdersGrid.colDef[i].hidden=='true'
		    						     selectbox.options[selectbox.options.length] = new Option(App.createTradesGrid.colDef[i].label, App.createTradesGrid.colDef[i].key); 
								}
							App.orderScenario_dialog.show();
							
							break;
						case 4:
							var oRecord = p_myDataTable.getRecord(elRow); 
							var trader = oRecord.getData("BUY_TRADER_ID")
							var investor = oRecord.getData("BUY_ACCOUNT")
							Dom.get('hourtrader').value="B@"+trader;
							Dom.get('hourinvestor').value=investor;
							break;
						case 5:
							var oRecord = p_myDataTable.getRecord(elRow); 
							var trader = oRecord.getData("SELL_TRADER_ID")
							var investor = oRecord.getData("SELL_ACCOUNT")
							Dom.get('hourtrader').value="S@"+trader;
							Dom.get('hourinvestor').value=investor;
							break;
						case 6:
							var tabletoexport = p_myDataTable.getTableEl(); 
							ExportToExcel(p_myDataTable);
							break;
						case 7:
							if (App.showGraph) {
								App.setNoBidAskGraph();
								App.showGraph=0;
								otradesContextMenu.getItem(7).cfg.setProperty("checked", false);
								}
								else {
										App.setWithBidAskGraph();
										App.showGraph=1;
										otradesContextMenu.getItem(7).cfg.setProperty("checked", true);
										}
							break;
							
					 }   
                 }   
             }   
         };  

		 var otradesContextMenu = new YAHOO.widget.ContextMenu("tradescontextmenu",   
                {trigger:App.mygrid_trades.getTbodyEl()});   

		
		 otradesContextMenu.addItems([
			"Buy/Sell Details", 
            "Trade Details",
			"Order Details",
			"Set Filter",
			"Copy Buy Trader/Investor",
			"Copy Sell Trader/Investor",
			"Export to Excel",
			"Show/Hide BidAsk Graph"
        	]);

         otradesContextMenu.render(document.body);
		 otradesContextMenu.clickEvent.subscribe(ontradesContextMenuClick, App.mygrid_trades);  
// ===== END		TRADES MENU       ===========
	
	App.mygrid_trades.newCols=true;
		 
	//					 ===== Column Picker Dialog  =====	 
		 App.mygrid_trades.showDlg = function(){
			 if(App.mygrid_trades.newCols) {
				 Dom.get("dt-dlg-trades-hd").innerHTML="Choose which columns of Trades you would like to see:";
		  		 App.mygrid_trades.allColumns = App.mygrid_trades.getColumnSet().keys;   
                 App.mygrid_trades.elPicker = YAHOO.util.Dom.get("dt-dlg-picker-trades");   
                 App.mygrid_trades.elTemplateCol = document.createElement("div");   
                 Dom.addClass(App.mygrid_trades.elTemplateCol, "dt-dlg-pickercol-trades");   
                 App.mygrid_trades.elTemplateKey = App.mygrid_trades.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_trades.elTemplateKey, "dt-dlg-pickerkey-trades");   
                 App.mygrid_trades.elTemplateBtns = App.mygrid_trades.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_trades.elTemplateBtns, "dt-dlg-pickerbtns-trades");   
                 App.mygrid_trades.onclickObj = {fn:App.mygrid_trades.handleButtonClick, obj:this, scope:false };   
                    
                 // Create one section in the SimpleDialog for each Column   
                 App.mygrid_trades.elColumn=null;
				 App.mygrid_trades.elKey=null;
				 App.mygrid_trades.elButton=null;
				 App.mygrid_trades.oButtonGrp=null; 
				 App.mygrid_trades.oColumn=null;
				 
                 for(var i=0,l=App.mygrid_trades.allColumns.length;i<l;i++) {   
                      App.mygrid_trades.oColumn = App.mygrid_trades.allColumns[i];   
                        
                     // Use the template   
                     App.mygrid_trades.elColumn = App.mygrid_trades.elTemplateCol.cloneNode(true);   
                        
                     // Write the Column key   
                     App.mygrid_trades.elKey = App.mygrid_trades.elColumn.firstChild;   
                     App.mygrid_trades.elKey.innerHTML = App.mygrid_trades.oColumn.getKey();   
                        
                     // Create a ButtonGroup   
                     App.mygrid_trades.oButtonGrp = new YAHOO.widget.ButtonGroup({    
                                     id: "buttongrp"+i,    
                                     name: App.mygrid_trades.oColumn.getKey(),    
                                     container: App.mygrid_trades.elKey.nextSibling   
                     });   
                     App.mygrid_trades.oButtonGrp.addButtons([   
                         { label: "Show", value: "Show", checked: ((!App.mygrid_trades.oColumn.hidden)), onclick: App.mygrid_trades.onclickObj},   
                         { label: "Hide", value: "Hide", checked: ((App.mygrid_trades.oColumn.hidden)), onclick: App.mygrid_trades.onclickObj}   
                     ]);   
                                        
                     App.mygrid_trades.elPicker.appendChild(App.mygrid_trades.elColumn);   
                 }   
                 App.mygrid_trades.newCols = false;   
             }   
             App.mygrid_trades.tradesDlg.show();   
         }; 
		 
		 App.mygrid_trades.hideDlg = function(e) {
                this.hide();
            };
			
		
			
         App.mygrid_trades.handleButtonClick = function(e, oSelf) {
                var sKey = this.get("name");
                if(this.get("value") === "Hide") {
                    // Hides a Column
                     App.mygrid_trades.hideColumn(sKey);
                }
                else {
                    // Shows a Column
                     App.mygrid_trades.showColumn(sKey);
                }
            };
            
            // Create the SimpleDialog
            Dom.removeClass("dt-dlg-trades", "inprogress");
            App.mygrid_trades.tradesDlg = new YAHOO.widget.SimpleDialog("dt-dlg-trades", {
                    width: "30em",
    			    visible: false,
    			    modal: true,
    			    buttons: [ 
    					{ text:"Close",  handler:App.mygrid_trades.hideDlg }
                    ],
                    fixedcenter: true,
                    constrainToViewport: true
    		});
    		App.mygrid_trades.tradesDlg.render();
				
		
		 App.mygrid_trades.projected_so='';
		 App.mygrid_trades.flashProjected='';
		 App.mygrid_trades.createProjectedGraph = function()
				{
					var isin=document.getElementById('isin').value;  // get active isin
					
			 		var gid=App.isinAr[isin][_SEC_TABLE_ISIN];
			 		
					App.mygrid_trades.projected_so = new SWFObject(App.graphs+"amstock.swf", "projected", "99%", "480", "8", "#FFFFFF");
					App.mygrid_trades.projected_so.addVariable("path", App.graphs);
					App.mygrid_trades.projected_so.addVariable("chart_id", "projected");
					App.mygrid_trades.projected_so.addParam("wmode", "opaque");
					App.mygrid_trades.projected_so.addVariable("settings_file", encodeURIComponent(App.graphs+"projected_settings.php?ses="+App.ses+"&sec="+gid));
					//alert(encodeURIComponent(App.graphs+"projected_settings.php?ses="+App.ses+"&sec="+gid));
					/*App.mygrid_orders.projected_so.addVariable("settings_file", escape(App.graphs+"projected_settings.xml"));
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='1'><events_file_name>"+escape("Projected_events_"+App.secs[0]+'_' +App.ses + ".xml")+"</events_file_name></data_set></data_sets></settings>");			
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+escape("Projected_data1_" +App.secs[0]+'_'+ App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='1'><file_name>"+escape("Projected_data2_" +App.secs[0]+'_'+ App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");*/
					App.mygrid_trades.projected_so.write("projected_flashcontent");
					}
			
		App.bidask_so="";
		
		App.createBidAskGraph = function () {
			YAHOO.log("Ready to call SWFObject","info"); 
			
			var isin=document.getElementById('isin').value;  // get active isin
			
			var l3=App.layout3.getUnitByPosition('center'); 	// get layout cell where flash displayed
			
			/*for (var i=0; i < l3.body.childNodes.length; i++)	// Delete temporary div with graph
			{		
				//var ch=document.getElementById('div_'+App.isinAr[i][_SEC_TABLE_ISIN]);
				l3.body.removeChild(l3.body.childNodes[i]); //l3.body.childNodes[i]
			}*/
			
			
			for(i in App.isinAr)
			{
			 var gid=App.isinAr[i][_SEC_TABLE_ISIN];
			 var sym=App.isinAr[i][_SEC_TABLE_SYMBOL];
			 
			 var ch=document.getElementById('div_'+App.isinAr[i][_SEC_TABLE_ISIN]);
			 if (ch)
				 l3.body.removeChild(ch); 
			 //alert("div "+gid+"=="+App.isinAr[i][_SEC_TABLE_ISIN]);	
			 var el = document.createElement('div'); 					//  Create a new DIV
			 el.setAttribute("id","div_"+gid);		// Give to new div an id  as the ISIN code

			 l3.body.appendChild(el);
			 
			 App.bidask_so = new SWFObject(App.graphs+"amstock.swf", gid, "100%", "575", "8", "#303E4E");
			 App.bidask_so.addVariable("path", App.graphs);
			 App.bidask_so.addVariable("chart_id", gid);
			 App.bidask_so.addParam("wmode", "opaque");
			//App.bidask_so.addVariable("chart_settings", "");
		
			 App.bidask_so.addVariable("settings_file", encodeURIComponent(App.graphs+"bidask_settings.php?ses="+App.ses+"&sec="+gid+"&sym="+sym)); //encodeURIComponent
			 App.bidask_so.write(el); //"bidask_flashcontent");
			 //App.bidask_so.write("bidask_flashcontent");
			 }
			
			 //l3.body.appendChild(Dom.get(isin));	
		//	App.bidask_so.addVariable("settings_file", escape(App.graphs+"bidask_settingsOLD.xml"));
		//	bidask_so.addVariable("chart_settings",encodeURIComponent(App.XML_var));
			//App.bidask_so.addVariable("data_file", escape(App.graphs+"bidask_data_" +App.ses + ".txt"));
			
		//bidask_so.addVariable ("additional_chart_settings",encodeURIComponent("<settings><data_sets><data_set did='0'><events_file_name>...	
		
		
			//App.bidask_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><events_file_name>"+"bidask_events_"+App.secs[0]+'_' +App.ses + ".xml"+"</events_file_name></data_set></data_sets></settings>");
			
			//App.bidask_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+encodeURIComponent("bidask_data_" +App.secs[0]+'_'+ App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");
		//	YAHOO.log(encodeURIComponent("bidask_data_" +App.secs[0]+'_'+ App.ses + ".txt"),"error");
					
			

			
			
			
			//var flashm=document.getElementById("bidaskg");
		
			}	
		
	//	YAHOO.util.Event.onAvailable("bidask_flashcontent", function(){
							//if (App.XML_var=='')
							  //  setTimeout(function(){createBidAskGraph();}, 1000);
	//						createBidAskGraph();
	//						});
					
	 };