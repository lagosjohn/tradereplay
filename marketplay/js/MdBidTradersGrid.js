// JavaScript Document
App.createMdBidTradersGrid = function()
	{
		App.MdBidTradersDataSource = new YAHOO.util.DataSource(App.tradeOrders);   
 		App.MdBidTradersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
 		App.MdBidTradersDataSource.responseSchema = {  
						resultsList: "records",
						fields: ["OASIS_TIME","TRADER","ORDER_ID","ORDER_NUMBER","PRICE","VOLUME","UNMATCHED"]   
						};  
		
	 var oasisTimeFormat = function (elCell, oRecord, oColumn, oData) {
     	var oTime = oData.substr(0,2)+':'+oData.substr(2,2)+':'+oData.substr(4,2)+':'+oData.substr(6,2);
		elCell.innerHTML = '<span style="background:#3D4855 url(yui/examples/datatable/assets/img/odd.gif) repeat-x 0 0;color:#F0F0F0">'+oTime+'</span>';
	 	};
		
	  var priceFormat = function (elCell, oRecord, oColumn, oData) {     	
		elCell.innerHTML = '<span style="font:Georgia;font-weight:bold;font-size:11px;text-align:right;color:#00d000">'+oData+'</span>';
	 	};
		
		 var mdbDef=[//{key:"A", label:"", formatter:expansionFormatter, width:20},
					 {key:"OASIS_TIME", label:"&nbsp;Time&nbsp;",width:50,formatter:oasisTimeFormat},
					 {key:"TRADER", label:"&nbsp;Trader&nbsp;",width:60},
					 {key:"ORDER_ID", label:"&nbsp;oId&nbsp;",width:30,className:'align-right'}, //parser:"number"
					 {key:"ORDER_NUMBER", label:"&nbsp;oNumber&nbsp;",width:50},
					 {key:"PRICE", label:" Price&nbsp;",width:40,className:'align-right',formatter:priceFormat},
					 {key:"VOLUME", label:"&nbsp;Volume&nbsp;",width:40,className:'align-right',parser:"number"},
					 {key:"UNMATCHED", label:"Unmatched",width:70,parser:"number"}
					 ];
		 
				 
 		var myConfigs = {   
     		//initialRequest: "?task=get_mdTraders&price=00&side=B&format=JSON&session="+App.ses,
			//draggableColumns:true,
			//sortedBy : {key:"OASIS_TIME", dir:YAHOO.widget.DataTable.CLASS_DESC},
			//scrollable:true,
	 		dynamicData : true,
			MSG_EMPTY: 'No entries',
			MSG_ERROR: 'Bid Content Depth level selected...',
	 		height: "95px", 
	 		//renderLoopSize: 0, 
	 		selectionMode : 'single' 
			};  
		
		 		 
		var onBidCellSelect = function(oArgs )
		{
			var elCell = oArgs.target;
			this.selectCell(elCell); 
			//this.highlightCell(elCell); 
			//YAHOO.log("onCellSelect="+Dom.get(elCell).innerHTML,"warn");
			return false;
		 }
		 
				 
		var onBidCellSelected = function( oArgs )
		{
			var oRec = oArgs.record; 
			var oColumn=oArgs.column;
			if (oColumn.getKey()!="ORDER_NUMBER") {
				this.unselectCell(oArgs.el);
				return false;
				}
			
			YAHOO.log("onBidCellSelected="+oRec.getData("PRICE")+"=="+oColumn.getKey(),"warn");
			var trade_time = App.split_time(oRec.getData("OASIS_TIME"));				
			App.createTradeOrdersWindow_bid(trade_time, 0,oRec.getData("ORDER_NUMBER"),0,'Order Details of: '+oRec.getData("TRADER"),'order');
			
			return false;
		 }

		 App.mygrid_MdBidTraders = new YAHOO.widget.ScrollingDataTable( "div_MdBidTraders", mdbDef, App.MdBidTradersDataSource, myConfigs ); //scrollable:true
		 App.mygrid_MdBidTraders.subscribe("cellSelectEvent", onBidCellSelected);
		 App.mygrid_MdBidTraders.subscribe("cellClickEvent", onBidCellSelect);
		 App.mygrid_MdBidTraders.subscribe("rowClickEvent", App.mygrid_MdBidTraders.onEventSelectRow);
 		 App.mygrid_MdBidTraders.subscribe("rowMouseoverEvent", App.mygrid_MdBidTraders.onEventHighlightRow);
		 App.mygrid_MdBidTraders.subscribe("rowMouseoutEvent", App.mygrid_MdBidTraders.onEventUnhighlightRow);		 
		 App.mygrid_MdBidTraders.subscribe("initEvent", function() {
															 
														   	   });
	
		var handleFailure = function(o){
			YAHOO.log("sendRequest MdBidTraders failed:"+o.status+" - Status: " + o.statusText, "error","MdBidTraders.js");
			};	
	
		var handleSuccess = function(o){
			App.mygrid_MdBidTraders.onDataReturnReplaceRows.apply(App.mygrid_MdBidTraders,arguments); 
//YAHOO.log("handleSuccess MdBidTraders "+o.status+" - Status: " + o.statusText, "info","MdBidTraders.js");
			//App.mygrid_MdBidTraders.onDataReturnInsertRows.apply(App.mygrid_MdBidTraders,arguments);  

			};
			
		App.mygrid_MdBidTraders.requestCallback = {   
             success : handleSuccess,   
             failure : handleFailure,   
             scope : App.bmarkDataTable   
         };
		 
		App.mygrid_MdBidTraders.newCols=true;
		 App.mygrid_MdBidTraders.showDlg = function(){
			 if(App.mygrid_MdBidTraders.newCols) {
				 Dom.get("dt-dlg-hd").innerHTML="Choose which columns of MdBidTraders you would like to see:";
		  		 App.mygrid_MdBidTraders.allColumns = App.mygrid_MdBidTraders.getColumnSet().keys;   
                 App.mygrid_MdBidTraders.elPicker = YAHOO.util.Dom.get("dt-dlg-picker");   
                 App.mygrid_MdBidTraders.elTemplateCol = document.createElement("div");   
                 Dom.addClass(App.mygrid_MdBidTraders.elTemplateCol, "dt-dlg-pickercol");   
                 App.mygrid_MdBidTraders.elTemplateKey = App.mygrid_MdBidTraders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_MdBidTraders.elTemplateKey, "dt-dlg-pickerkey");   
                 App.mygrid_MdBidTraders.elTemplateBtns = App.mygrid_MdBidTraders.elTemplateCol.appendChild(document.createElement("span"));   
                 Dom.addClass(App.mygrid_MdBidTraders.elTemplateBtns, "dt-dlg-pickerbtns");   
                 App.mygrid_MdBidTraders.onclickObj = {fn:App.mygrid_MdBidTraders.handleButtonClick, obj:this, scope:false };   
                    
                 // Create one section in the SimpleDialog for each Column   
                 App.mygrid_MdBidTraders.elColumn=null;
				 App.mygrid_MdBidTraders.elKey=null;
				 App.mygrid_MdBidTraders.elButton=null;
				 App.mygrid_MdBidTraders.oButtonGrp=null;
				 App.mygrid_MdBidTraders.oColumn-null;
				 
                 for(var i=0,l=App.mygrid_MdBidTraders.allColumns.length;i<l;i++) {   
                     App.mygrid_MdBidTraders.oColumn = App.mygrid_MdBidTraders.allColumns[i];   
                        
                     // Use the template   
                     App.mygrid_MdBidTraders.elColumn = App.mygrid_MdBidTraders.elTemplateCol.cloneNode(true);   
                        
                     // Write the Column key   
                     App.mygrid_MdBidTraders.elKey = App.mygrid_MdBidTraders.elColumn.firstChild;   
                     App.mygrid_MdBidTraders.elKey.innerHTML = App.mygrid_MdBidTraders.oColumn.getKey();   
                        
                     // Create a ButtonGroup   
                     App.mygrid_MdBidTraders.oButtonGrp = new YAHOO.widget.ButtonGroup({    
                                     id: "buttongrp"+i,    
                                     name: App.mygrid_MdBidTraders.oColumn.getKey(),    
                                     container: App.mygrid_MdBidTraders.elKey.nextSibling   
                     });   
                     App.mygrid_MdBidTraders.oButtonGrp.addButtons([   
                         { label: "Show", value: "Show", checked: ((!App.mygrid_MdBidTraders.oColumn.hidden)), onclick: App.mygrid_MdBidTraders.onclickObj},   
                         { label: "Hide", value: "Hide", checked: ((App.mygrid_MdBidTraders.oColumn.hidden)), onclick: App.mygrid_MdBidTraders.onclickObj}   
                     ]);   
                                        
                     App.mygrid_MdBidTraders.elPicker.appendChild(App.mygrid_MdBidTraders.elColumn);   
                 }   
                 App.mygrid_MdBidTraders.newCols = false;   
             }   
             MdBidTradersDlg.show();   
         }; 
		 
				
		App.mygrid_MdBidTraders.hideDlg = function(e) {
                this.hide();
            };
         App.mygrid_MdBidTraders.handleButtonClick = function(e, oSelf) {
                var sKey = this.get("name");
                if(this.get("value") === "Hide") {
                    // Hides a Column
                     App.mygrid_MdBidTraders.hideColumn(sKey);
                }
                else {
                    // Shows a Column
                     App.mygrid_MdBidTraders.showColumn(sKey);
                }
            };
            
            // Create the SimpleDialog
            YAHOO.util.Dom.removeClass("dt-dlg", "inprogress");
            var MdBidTradersDlg = new YAHOO.widget.SimpleDialog("dt-dlg", {
                    width: "30em",
    			    visible: false,
    			    modal: true,
    			    buttons: [ 
    					{ text:"Close",  handler:App.mygrid_MdBidTraders.hideDlg }
                    ],
                    fixedcenter: true,
                    constrainToViewport: true
    		});
    		MdBidTradersDlg.render(document.body);
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
		YAHOO.log("MdBidTraders loaded successfully...",'info');
		App.createMdBidTradersGrid();	
            //you can make use of all requested YUI modules   
            //here.   
        }  
    });   
  
// Load the files using the insert() method.   
loader.insert({}, 'js');   
})(); 	 