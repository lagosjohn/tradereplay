// JavaScript Document
App.createMdAskTradersGrid = function()
	{
		App.MdAskTradersDataSource = new YAHOO.util.DataSource(App.tradeOrders);   
 		App.MdAskTradersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		App.MdAskTradersDataSource.responseSchema = {  
						resultsList: "records",
						fields: ["OASIS_TIME","TRADER","ORDER_ID","ORDER_NUMBER","PRICE","VOLUME","UNMATCHED"]   
						};  
		
		var oasisTimeFormat = function (elCell, oRecord, oColumn, oData) {
     	var oTime = oData.substr(0,2)+':'+oData.substr(2,2)+':'+oData.substr(4,2)+':'+oData.substr(6,2);
		elCell.innerHTML = '<span style="background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0;color:#F0F0F0">'+oTime+'</span>';
	 	};
		
		var priceFormat = function (elCell, oRecord, oColumn, oData) {     	
		elCell.innerHTML = '<span style="font:Georgia;font-weight:bold; font-size:11px;color:#d00000">'+oData+'</span>';
	 	};
	 
		 var mdbDef=[//{key:"A", label:"", formatter:expansionFormatter, width:20},
					 {key:"OASIS_TIME", label:"Time",width:50,formatter:oasisTimeFormat},
					 {key:"TRADER", label:"Trader",width:60},
					 {key:"ORDER_ID", label:"oId",width:30,className:'align-right',parser:"number"}, //parser:"number"
					 {key:"ORDER_NUMBER", label:"oNumber",width:50},
					 {key:"PRICE", label:"Price",width:40,className:'align-right',formatter:priceFormat},
					 {key:"VOLUME", label:"Volume",width:40,className:'align-right',parser:"number"},
					 {key:"UNMATCHED", label:"Unmatched",width:70,className:'align-right',parser:"number"}
					 ];
		 
				 
 		var myConfigs = {   
     		//initialRequest: "?task=get_mdTraders&price=00&side=S&format=JSON&session="+App.ses,
			draggableColumns:true,
	 		//sortedBy : {key:"OASIS_TIME", dir:YAHOO.widget.DataTable.CLASS_ASC},
			scrollable:false,
	 		dynamicData : true,
	 		height: "95px", 
			MSG_EMPTY: 'No entries',
			MSG_ERROR: 'Ask Content Depth level selected...',
	 		//renderLoopSize: 0, 
	 		selectionMode : 'single' 
			};  
		 		 
		
		var onAskCellSelect = function(oArgs )
		{
			var elCell = oArgs.target;
			this.selectCell(elCell); 
			//this.highlightCell(elCell); 
//			YAHOO.log("onCellSelect="+Dom.get(elCell).innerHTML,"warn");
			return false;
		 }
		 
				 
		var onAskCellSelected = function( oArgs )
		{
			var oRec = oArgs.record; 
			var oColumn=oArgs.column;
			var oEl=oArgs.el;
			App.mygrid_MdAskTraders.unselectCell(oEl);
//			YAHOO.log("onAskCellSelected="+oRec.getData("PRICE")+"=="+oColumn.getKey(),"warn");
			
			if (oColumn.getKey()!="ORDER_NUMBER") {
				return false;
				}
			
			YAHOO.log("onAskCellSelected="+oRec.getData("PRICE")+"=="+oColumn.getKey(),"warn");
			var trade_time = App.split_time(oRec.getData("OASIS_TIME"));				
			App.createTradeOrdersWindow_bid(trade_time, 0,oRec.getData("ORDER_NUMBER"),0,'Order Details of: '+oRec.getData("TRADER"),'order');
			
			return false;
		 }
		 
		 App.mygrid_MdAskTraders = new YAHOO.widget.ScrollingDataTable( "div_MdAskTraders", mdbDef, App.MdAskTradersDataSource, myConfigs ); //scrollable:true
	  	 App.mygrid_MdAskTraders.subscribe("cellSelectEvent", onAskCellSelected);
		 App.mygrid_MdAskTraders.subscribe("cellClickEvent", onAskCellSelect);
		 App.mygrid_MdAskTraders.subscribe("rowClickEvent", App.mygrid_MdAskTraders.onEventSelectRow);
 		 App.mygrid_MdAskTraders.subscribe("rowMouseoverEvent", App.mygrid_MdAskTraders.onEventHighlightRow);
		 App.mygrid_MdAskTraders.subscribe("rowMouseoutEvent", App.mygrid_MdAskTraders.onEventUnhighlightRow);		 
		 App.mygrid_MdAskTraders.subscribe("initEvent", function() {
															 
														   	   });
	
		 var handleFailure = function(o){
			YAHOO.log("sendRequest MdAskTraders failed:"+o.status+" - Status: " + o.statusText, "error","MdBidTraders.js");
			};	
	
		var handleSuccess = function(o){
			App.mygrid_MdAskTraders.onDataReturnReplaceRows.apply(App.mygrid_MdAskTraders,arguments);  
			};
			
		App.mygrid_MdAskTraders.requestCallback = {   
             success : handleSuccess,   
             failure : handleFailure,   
             scope : App.bmarkDataTable   
         };
		 
		 App.mygrid_MdAskTraders.newCols=true;
		 App.mygrid_MdAskTraders.showDlg = function(){
			 if(App.mygrid_MdAskTraders.newCols) {
				 Dom.get("dt-dlg-hd").innerHTML="Choose which columns of MdAskTraders you would like to see:";
		  		 App.mygrid_MdAskTraders.allColumns = App.mygrid_MdAskTraders.getColumnSet().keys;   
                 App.mygrid_MdAskTraders.elPicker = YAHOO.util.Dom.get("dt-dlg-picker");   
                 App.mygrid_MdAskTraders.elTemplateCol = document.createElement("div");   
                 Dom.addClass(App.mygrid_MdAskTraders.elTemplateCol, "dt-dlg-pickercol");   
                 App.mygrid_MdAskTraders.elTemplateKey = App.mygrid_MdAskTraders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_MdAskTraders.elTemplateKey, "dt-dlg-pickerkey");   
                 App.mygrid_MdAskTraders.elTemplateBtns = App.mygrid_MdAskTraders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_MdAskTraders.elTemplateBtns, "dt-dlg-pickerbtns");   
                 App.mygrid_MdAskTraders.onclickObj = {fn:App.mygrid_MdAskTraders.handleButtonClick, obj:this, scope:false };   
                    
                 // Create one section in the SimpleDialog for each Column   
                 App.mygrid_MdAskTraders.elColumn=null;
				 App.mygrid_MdAskTraders.elKey=null;
				 App.mygrid_MdAskTraders.elButton=null;
				 App.mygrid_MdAskTraders.oButtonGrp=null;
				 App.mygrid_MdAskTraders.oColumn-null;
				 
                 for(var i=0,l=App.mygrid_MdAskTraders.allColumns.length;i<l;i++) {   
                     App.mygrid_MdAskTraders.oColumn = App.mygrid_MdAskTraders.allColumns[i];   
                        
                     // Use the template   
                     App.mygrid_MdAskTraders.elColumn = App.mygrid_MdAskTraders.elTemplateCol.cloneNode(true);   
                        
                     // Write the Column key   
                     App.mygrid_MdAskTraders.elKey = App.mygrid_MdAskTraders.elColumn.firstChild;   
                     App.mygrid_MdAskTraders.elKey.innerHTML = App.mygrid_MdAskTraders.oColumn.getKey();   
                        
                     // Create a ButtonGroup   
                     App.mygrid_MdAskTraders.oButtonGrp = new YAHOO.widget.ButtonGroup({    
                                     id: "buttongrp"+i,    
                                     name: App.mygrid_MdAskTraders.oColumn.getKey(),    
                                     container: App.mygrid_MdAskTraders.elKey.nextSibling   
                     });   
                     App.mygrid_MdAskTraders.oButtonGrp.addButtons([   
                         { label: "Show", value: "Show", checked: ((!App.mygrid_MdAskTraders.oColumn.hidden)), onclick: App.mygrid_MdAskTraders.onclickObj},   
                         { label: "Hide", value: "Hide", checked: ((App.mygrid_MdAskTraders.oColumn.hidden)), onclick: App.mygrid_MdAskTraders.onclickObj}   
                     ]);   
                                        
                     App.mygrid_MdAskTraders.elPicker.appendChild(App.mygrid_MdAskTraders.elColumn);   
                 }   
                 App.mygrid_MdAskTraders.newCols = false;   
             }   
             MdAskTradersDlg.show();   
         }; 
		 
				
		App.mygrid_MdAskTraders.hideDlg = function(e) {
                this.hide();
            };
         App.mygrid_MdAskTraders.handleButtonClick = function(e, oSelf) {
                var sKey = this.get("name");
                if(this.get("value") === "Hide") {
                    // Hides a Column
                     App.mygrid_MdAskTraders.hideColumn(sKey);
                }
                else {
                    // Shows a Column
                     App.mygrid_MdAskTraders.showColumn(sKey);
                }
            };
            
            // Create the SimpleDialog
            YAHOO.util.Dom.removeClass("dt-dlg", "inprogress");
            var MdAskTradersDlg = new YAHOO.widget.SimpleDialog("dt-dlg", {
                    width: "30em",
    			    visible: false,
    			    modal: true,
    			    buttons: [ 
    					{ text:"Close",  handler:App.mygrid_MdAskTraders.hideDlg }
                    ],
                    fixedcenter: true,
                    constrainToViewport: true
    		});
    		MdAskTradersDlg.render(document.body);
	 };
	 
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
		YAHOO.log("MdAskTraders loaded successfully...",'info');
		App.createMdAskTradersGrid();	
            //you can make use of all requested YUI modules   
            //here.   
        }  
    });   
  
// Load the files using the insert() method.   
loader.insert({}, 'js');   
})(); 	 