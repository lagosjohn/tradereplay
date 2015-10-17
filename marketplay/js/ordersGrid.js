// JavaScript Document
(function() {   
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
	
	var loader = new YAHOO.util.YUILoader({   
        base: 'yui/build/',   
        require: ["datasource","datatable","json","menu", "fonts"],   
        //loadOptional: false,   
        //combine: true,   
        //filter: "MIN",   
        //allowRollup: true,   
        onSuccess: function() { 
		YAHOO.log("OrdersGrid loaded successfully...",'error');
			msg='task=getMsgColumns'+'&objId=IT'+'&execWhenReturn=createOrdersGrid();';
		    App.callback.argument[0] = 'JS';
			App.AjaxObject.syncRequest(App.test1,msg);
            //you can make use of all requested YUI modules   
            //here.   
        }  
    });   
  
// Load the files using the insert() method.   
loader.insert({}, 'js');   
})(); 

App.createOrdersGrid = function()
	{
	 	filter:null;
		filterOrder:null;
		scenarioOrder:null;
		fieldOrder:null;
		fieldCond:null;
		
		var ordersDataSource = new YAHOO.util.DataSource({});   
 		ordersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		ordersDataSource.responseSchema = {  fields: App.columnsIds   };  
		
		 App.createOrdersGrid.colIds=App.columnsIds;
		 App.createOrdersGrid.colDef=App.columnsDef;
		 
 		var myConfigs = {   
     		draggableColumns:true,
			//scrollable:false,
	 		dynamicData : false,
	 		height: "115px", 
			width: "540px",
	 		renderLoopSize: 0, 
	 		initialLoad:false  
			};  
		
		 var tid='';
		 App.mygrid_orders.orecord='';
		 
		 App.mygrid_orders = new YAHOO.widget.ScrollingDataTable( "div_orders", App.columnsDef, ordersDataSource, myConfigs ); //scrollable:true
		 App.mygrid_orders.subscribe("rowClickEvent", App.mygrid_orders.onEventSelectRow);
 		 App.mygrid_orders.subscribe("rowMouseoverEvent", App.mygrid_orders.onEventHighlightRow);
		 App.mygrid_orders.subscribe("rowMouseoutEvent", App.mygrid_orders.onEventUnhighlightRow);		 
		 App.mygrid_orders.subscribe("initEvent", function() {
															  	
															  var el=Dom.get(this.getColumn('TRADER_ID').getThEl());
															  //alert(el.innerHTML+"=="+el.id);
															  var trg=new YAHOO.util.DDTarget(Dom.getAttribute(el,"id"),"buyers");
															  if (trg)
															      YAHOO.log('Order trg: '+Dom.getAttribute(el,"id"), 'warn', 'mktGrids.js');
														   	   });
		// ===== Attach		ORDERS MENU ===========
		
		 App.split_time = function(str){
			 var newstr='';
			 for(var i=0; i<str.length; i++)
			     newstr+=(str[i]==':' || str[i]=='.') ?'' :str[i];
			return newstr;	
		 	}
			
		 onordersContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];   
             if(task) {   
                 // Extract which TR element triggered the context menu   
                 var elRow = this.contextEventTarget;   
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {   
                         case 0:     // Delete row upon confirmation   
                            var oRecord = p_myDataTable.getRecord(elRow);
							
							var trade_time = App.split_time(oRecord.getData("OASIS_TIME"));
							
							App.createTradeOrdersWindow_bid(trade_time, 0,oRecord.getData("ORDER_NUMBER"),0,'Order Details of: '+oRecord.getData("TRADER_ID"),'order');
							break;
						case 1:
							this.filter = null;
							break;
						case 2:
							//Dom.get('filt_field').value = App.isin;
							var selectbox = document.forms.OrderSurvForm.elements.filt_field;
							App.mygrid_orders.orecord = p_myDataTable.getRecord(elRow); 
							
							selectbox.onchange=function(){ //run some code when "onchange" event fires
 										var chosenoption=this.options[this.selectedIndex];
										Dom.get('sordnum').value = App.mygrid_orders.orecord.getData(chosenoption.value);
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
								 for(i=0; i<App.createOrdersGrid.colDef.length;i++) 
								 	 if (typeof(App.createOrdersGrid.colDef[i].hidden)== 'undefined') //  && App.createOrdersGrid.colDef[i].hidden=='true'
		    						     selectbox.options[selectbox.options.length] = new Option(App.createOrdersGrid.colDef[i].label, App.createOrdersGrid.colDef[i].key); 
								}
							App.orderScenario_dialog.show();
							
							break;
							case 3:
							var oRecord = p_myDataTable.getRecord(elRow); 
							var trader = oRecord.getData("TRADER_ID")
							var investor = oRecord.getData("CSD_ACCOUNT_ID")
							Dom.get('hourtrader').value=trader;
							Dom.get('hourinvestor').value=investor;
							break;
                     }   
                 }   
             }   
         };  


		 var oordersContextMenu = new YAHOO.widget.ContextMenu("orderscontextmenu",   
                {trigger:App.mygrid_orders.getTbodyEl()});   

		
		 oordersContextMenu.addItems([
			"Order Details", 
            "Clear filter",
			"Scenario",
			"Copy Trader/Investor"
        	]);

         oordersContextMenu.render(document.body); //"div_orders"
		 oordersContextMenu.clickEvent.subscribe(onordersContextMenuClick, App.mygrid_orders);  
// ===== END		ORDERS MENU       ===========
		 
		 App.mygrid_orders.newCols=true;
		 App.mygrid_orders.showDlg = function(){
			 if(App.mygrid_orders.newCols) {
				 Dom.get("dt-dlg-hd").innerHTML="Choose which columns of Orders you would like to see:";
		  		 App.mygrid_orders.allColumns = App.mygrid_orders.getColumnSet().keys;   
                 App.mygrid_orders.elPicker = YAHOO.util.Dom.get("dt-dlg-picker");   
                 App.mygrid_orders.elTemplateCol = document.createElement("div");   
                 Dom.addClass(App.mygrid_orders.elTemplateCol, "dt-dlg-pickercol");   
                 App.mygrid_orders.elTemplateKey = App.mygrid_orders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_orders.elTemplateKey, "dt-dlg-pickerkey");   
                 App.mygrid_orders.elTemplateBtns = App.mygrid_orders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_orders.elTemplateBtns, "dt-dlg-pickerbtns");   
                 App.mygrid_orders.onclickObj = {fn:App.mygrid_orders.handleButtonClick, obj:this, scope:false };   
                    
                 // Create one section in the SimpleDialog for each Column   
                 App.mygrid_orders.elColumn=null;
				 App.mygrid_orders.elKey=null;
				 App.mygrid_orders.elButton=null;
				 App.mygrid_orders.oButtonGrp=null;
				 App.mygrid_orders.oColumn-null;
				 
                 for(var i=0,l=App.mygrid_orders.allColumns.length;i<l;i++) {   
                     App.mygrid_orders.oColumn = App.mygrid_orders.allColumns[i];   
                        
                     // Use the template   
                     App.mygrid_orders.elColumn = App.mygrid_orders.elTemplateCol.cloneNode(true);   
                        
                     // Write the Column key   
                     App.mygrid_orders.elKey = App.mygrid_orders.elColumn.firstChild;   
                     App.mygrid_orders.elKey.innerHTML = App.mygrid_orders.oColumn.getKey();   
                        
                     // Create a ButtonGroup   
                     App.mygrid_orders.oButtonGrp = new YAHOO.widget.ButtonGroup({    
                                     id: "buttongrp"+i,    
                                     name: App.mygrid_orders.oColumn.getKey(),    
                                     container: App.mygrid_orders.elKey.nextSibling   
                     });   
                     App.mygrid_orders.oButtonGrp.addButtons([   
                         { label: "Show", value: "Show", checked: ((!App.mygrid_orders.oColumn.hidden)), onclick: App.mygrid_orders.onclickObj},   
                         { label: "Hide", value: "Hide", checked: ((App.mygrid_orders.oColumn.hidden)), onclick: App.mygrid_orders.onclickObj}   
                     ]);   
                                        
                     App.mygrid_orders.elPicker.appendChild(App.mygrid_orders.elColumn);   
                 }   
                 App.mygrid_orders.newCols = false;   
             }   
             ordersDlg.show();   
         }; 
		 
		 App.mygrid_orders.projected_so='';
		 App.mygrid_orders.flashProjected='';
		 App.mygrid_orders.createProjectedGraph = function()
				{
					var isin=document.getElementById('isin').value;  // get active isin
					
			 		var gid=App.isinAr[isin][_SEC_TABLE_ISIN];
			 		
					App.mygrid_orders.projected_so = new SWFObject(App.graphs+"amstock.swf", "projected", "99%", "480", "8", "#FFFFFF");
					App.mygrid_orders.projected_so.addVariable("path", App.graphs);
					App.mygrid_orders.projected_so.addVariable("chart_id", "projected");
					App.mygrid_orders.projected_so.addParam("wmode", "opaque");
					App.mygrid_orders.projected_so.addVariable("settings_file", encodeURIComponent(App.graphs+"projected_settings.php?ses="+App.ses+"&sec="+gid));
					alert(encodeURIComponent(App.graphs+"projected_settings.php?ses="+App.ses+"&sec="+gid));
					/*App.mygrid_orders.projected_so.addVariable("settings_file", escape(App.graphs+"projected_settings.xml"));
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='1'><events_file_name>"+escape("Projected_events_"+App.secs[0]+'_' +App.ses + ".xml")+"</events_file_name></data_set></data_sets></settings>");			
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='0'><file_name>"+escape("Projected_data1_" +App.secs[0]+'_'+ App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");
					App.mygrid_orders.projected_so.addVariable ("additional_chart_settings","<settings><data_sets><data_set did='1'><file_name>"+escape("Projected_data2_" +App.secs[0]+'_'+ App.ses + ".txt")+"</file_name></data_set></data_sets></settings>");*/
					App.mygrid_orders.projected_so.write("projected_flashcontent");
					}
		
		App.mygrid_orders.hideDlg = function(e) {
                this.hide();
            };
         App.mygrid_orders.handleButtonClick = function(e, oSelf) {
                var sKey = this.get("name");
                if(this.get("value") === "Hide") {
                    // Hides a Column
                     App.mygrid_orders.hideColumn(sKey);
                }
                else {
                    // Shows a Column
                     App.mygrid_orders.showColumn(sKey);
                }
            };
            
            // Create the SimpleDialog
            YAHOO.util.Dom.removeClass("dt-dlg", "inprogress");
            var ordersDlg = new YAHOO.widget.SimpleDialog("dt-dlg", {
                    width: "30em",
    			    visible: false,
    			    modal: true,
    			    buttons: [ 
    					{ text:"Close",  handler:App.mygrid_orders.hideDlg }
                    ],
                    fixedcenter: true,
                    constrainToViewport: true
    		});
    		ordersDlg.render(document.body);
	 };