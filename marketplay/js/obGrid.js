// JavaScript Document
(function() {
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	XHRDataSource = YAHOO.util.XHRDataSource,
    DataTable = YAHOO.widget.DataTable,
    //Paginator = YAHOO.widget.Paginator,
	App = YAHOO.mainTradeReplay.app;
    
	
    YAHOO.log('ObGrid.js file loaded...', 'info', 'obGrids.js');
	
	App.createOBGrid = function()
	{
	    // Define a custom format function
	 var myFormatDecimal = function (elCell, oRecord, oColumn, oData) {
     var oNum = oData;
     var conf = {
    	 format: '%{number}',
    	 negativeFormat: '%-{number}',
    	 thousandsSeparator: ',',
    	 decimalPlaces: 2
		 };
		
    	 elCell.innerHTML = YAHOO.util.Number.format(oNum, conf);
	 	};
		
					
 	
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
						"&sort=" 		+ sortcol +
						"&dir=" 		+ dir;
			};	
			
		Paginator = YAHOO.widget.Paginator;
		myColumnDefs = [
    				 {key:"BTIME", label:"Time",width:30,parser:"number"},
					 {key:"BENTRIES", label:"#B",width:30,parser:"number"},
					 {key:"BVOL", label:"Qty(B)",width:40,parser:"number"},
					 {key:"BPRICE", label:"Bid",width:45,parser:"number",className:"ob-bid"},
					 {key:"SPRICE", label:"Ask",width:45,parser:"number",className:"ob-ask"},
					 {key:"SVOL", label:"Qty(S)",width:40,parser:"number"},
					 {key:"SENTRIES", label:"#S",width:30,parser:"number"},
					 {key:"STIME", label:"Time",width:30,parser:"number"}
    			];

    			// Create a new DataSource
    			App.obDataSource = new XHRDataSource(App.smartOrders+"?task=OB&format=JSON&session="+App.ses);

    			// data.php just happens to use JSON. Let the DataSource
    			// know to expect JSON data.
    			App.obDataSource.responseType = XHRDataSource.TYPE_JSON;

    			// Define the structure of the DataSource data.  								 
				App.obDataSource.responseSchema = {
    				resultsList: "records",
					fields: ["BTIME","BENTRIES","BVOL","BPRICE","SPRICE","SVOL","SENTRIES","STIME"],
    				metaFields: {
    					totalRecords: "totalRecords"
    				}
    			};

    			
				// Set the DataTable configuration
    			myConfigs = {
    				initialRequest: "&sort=trader&dir=asc&startIndex=0&results=25",
					initialLoad: true,
    				dynamicData: true,
					height: "650px", 
					width: "620px", 
					generateRequest: requestBuilder,
    				paginator: new Paginator({
    					rowsPerPage: 25,
    					containers: 'OB_Paginator'
    				}),

    				// This configuration item is what builds the query string
    				// passed to the DataSource.
     			};

				var onCellSelected = function( oArgs )
				{
					var oRec = oArgs.record; 
					var oColumn=oArgs.column;
					this.unselectCell(oArgs.el);
			 YAHOO.log("onCellSelected="+oRec.getData("BPRICE")+"=="+oColumn.getKey(),"warn");
					if (App.md_traders_win.cfg.getProperty("visible")==false)
						App.md_traders_win.show();
					App.MdBidTradersDataSource.sendRequest("?task=get_mdTraders&price="+(Math.round(oRec.getData("BPRICE")*100))+"&side=B&format=JSON&session="+App.ses,App.mygrid_MdBidTraders.requestCallback); 
					App.MdAskTradersDataSource.sendRequest("?task=get_mdTraders&price="+(Math.round(oRec.getData("SPRICE")*100))+"&side=S&format=JSON&session="+App.ses,App.mygrid_MdAskTraders.requestCallback); 
					return false;
		 			}
    			// Create the DataTable.
    			App.obDataTable = new YAHOO.widget.ScrollingDataTable("OB_Container", myColumnDefs, App.obDataSource, myConfigs);
				App.obDataTable.subscribe("cellSelectEvent", onCellSelected);
				
    			// Define an event handler that scoops up the totalRecords which we sent as
    			// part of the JSON data. This is then used to tell the paginator the total records.
    			// This happens after each time the DataTable is updated with new data.
    			App.obDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
    				oPayload.totalRecords = oResponse.meta.totalRecords;
					YAHOO.log(oRequest,"warn");
    				return oPayload;
    			}
				
				var handleFailure = function(o){
					YAHOO.log("sendRequest MdAskTraders failed:"+o.status+" - Status: " + o.statusText, "error","MdBidTraders.js");
					};	
	
				var handleSuccess = function(o){
					//YAHOO.log("sendRequest obDataTable handleSuccess:"+o.status+" - Status: " + o.statusText, "error","obGrid.js");
					App.obDataTable.onDataReturnInitializeTable.apply(App.obDataTable,arguments);  
					};			
				App.obDataTable.requestCallback = {   
             		success : handleSuccess,   
             		failure : handleFailure,   
             		scope : App.obDataTable,
					argument: []   
         			};
    //YAHOO.log('ObGrid inside...', 'warn', 'obGrids.js');
	};
 
 var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        require: ["reset", "fonts", "menu", "button", "connection", "paginator", "datatable"], //,'charts','swf', "container"
        onSuccess: function() {	
            //Set a flag to show if the calendar is open or not
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
	
  loader.insert({}, 'js');
 })();