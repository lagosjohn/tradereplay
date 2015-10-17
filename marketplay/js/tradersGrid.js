// JavaScript Document
(function() {
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
    
	
    YAHOO.log('tradersGrid.js file loaded...', 'info', 'mktGrids.js');
	
	App.createSmartTradersGrid = function()
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
 	
		Paginator = YAHOO.widget.Paginator;
		var myColumnDefs = [ // sortable:true enables sorting
        //{key:"surv", editor: new YAHOO.widget.RadioCellEditor({radioOptions:["yes","no"],disableBtns:true})},  
		{key:'surv', label:'Survay<br/>Trader', formatter:'checkbox', className:'center'},
		{key:"Trader", label:"Trader", resizeable:true, sortable:true},
        {key:"bvol", label:"Bid", className:'align-right', formatter:"number",sortable:true,sortOptions: { defaultDir: YAHOO.widget.DataTable.CLASS_DESC }},
        {key:"svol", label:"Ask", className:'align-right', formatter:"number",sortable:true,sortOptions: { defaultDir: YAHOO.widget.DataTable.CLASS_DESC }},
		{key:"mktvol", label:"Mkt volume", className:'align-right', formatter:"number",sortable:true},
		{key:"mktpct", label:"Market pct", className:'align-right', formatter:myFormatDecimal,sortable:true}
    	];

    smartTraders = App.createSmartTradersGrid;
	smartTraders.newValue='';
	smartTraders.trader='';
	smartTraders.recordIndex=0;
	smartTraders.record=null;
    // DataSource instance
    App.myDataSource = new YAHOO.util.DataSource(App.smartTraders+"?format=JSON&session="+App.ses+"&");
    App.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
    App.myDataSource.responseSchema = {
        resultsList: "records",
        fields: [
            {key:"surv"},
			{key:"Trader"},
            {key:"bvol", parser:"number"},
            {key:"svol",parser:"number"},
			{key:"mktvol",parser:"number"},
			{key:"mktpct",parser:"text"}
        ],
        metaFields: {
            totalRecords: "totalRecords" // Access to value in the server response
        }
    };
    
    // DataTable configuration
    var myConfigs = {
        initialRequest: "sort=trader&dir=asc&startIndex=0&results=25", // Initial request for first page of data
        dynamicData: true, // Enables dynamic server-driven data
		height: "230px", 
		width: "300px", 
        sortedBy : {key:"Trader", dir:YAHOO.widget.DataTable.CLASS_ASC}, // Sets UI initial sort arrow
        paginator: new YAHOO.widget.Paginator({ rowsPerPage:20 }), // Enables pagination 
		selectionMode : 'single'
    };
    

	var handleSuccess = function(o){
			//YAHOO.log("Ajax smartTraders Success:"+o.status+" - Response: " + o.responseText+"=="+this.trader, "info","tradersGrid.js"); 
			var data = this.record.getData();
							data[this.column.key] = this.newValue;
							App.tradersDataTable.updateRow(this.recordIndex,data);
			};
	var handleFailure = function(o){
			YAHOO.log("Ajax smartTraders failed:"+o.status+" - Status: " + o.statusText, "error","tradersGrid.js");
			};
	
	
	smartTraders.tradersCallback= {
					success:handleSuccess,
					failure:smartTraders.handleFailure,
					scope: smartTraders,
					argument: []
					};
    // DataTable instance
   
   App.tradersDataTable = new YAHOO.widget.DataTable("div_smartTraders", myColumnDefs, App.myDataSource, myConfigs);
	App.tradersDataTable.subscribe("rowClickEvent", App.tradersDataTable.onEventSelectRow);
	App.tradersDataTable.subscribe('checkboxClickEvent', function(oArgs){
					Event.preventDefault(oArgs.event);
				    var elCheckbox = oArgs.target;
					smartTraders.newValue = elCheckbox.checked;
				    smartTraders.record = this.getRecord(elCheckbox);
					smartTraders.column = this.getColumn(elCheckbox);
					var oldValue = smartTraders.record.getData(smartTraders.column.key);
					smartTraders.recordIndex = this.getRecordIndex(smartTraders.record);
					smartTraders.trader = smartTraders.record.getData('Trader');

				 	msg='task=setSurvTrader'+'&trader='+smartTraders.trader+'&value='+smartTraders.newValue+"&format=JSON&session="+App.ses;
				    App.AjaxObject.syncRequest(App.smartTraders,msg,smartTraders.tradersCallback);
				});  
    // Update totalRecords on the fly with value from server
    App.tradersDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
        oPayload.totalRecords = oResponse.meta.totalRecords;
        return oPayload;
    	}
	
// ===== 	TRADERS MENU       ===========
	onsmartTradersContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];  
			
             if(task) {   
                 // Extract which TR element triggered the context menu   
                 var elRow = this.contextEventTarget;   
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {                           
							case 0:
							var oRecord = p_myDataTable.getRecord(elRow);
							var tiv=oRecord.getData("Trader");
							var trader = tiv.substring(0,tiv.indexOf('-'));
							var investor = tiv.substring(tiv.indexOf('-')+1,tiv.length);
							Dom.get('hourtrader').value=trader;
							Dom.get('hourinvestor').value=investor;
							break;
                     }   
                 }   
             }   
         };  


		 var smartTradersContextMenu = new YAHOO.widget.ContextMenu("smart_traderscontextmenu",   
                {trigger:App.tradersDataTable.getTbodyEl()});   

		
		 smartTradersContextMenu.addItems([
			"Copy Trader/Investor",
        	]);

         smartTradersContextMenu.render('div_smartTraders'); //"div_orders"
		 smartTradersContextMenu.clickEvent.subscribe(onsmartTradersContextMenuClick, App.tradersDataTable);  
// ===== END		TRADERS MENU       ===========
	};

 var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        require: ['datasource', 'paginator', 'button','datatable', "dragdrop"], //,'charts','swf', "container"
        onSuccess: function() {	
            //Set a flag to show if the calendar is open or not
			}/*,  // YUILoader onSuccess
		
		onFailure: function(msg, xhrobj) {   
         	var m = "LOAD FAILED: " + msg;   
         // if the failure was from the Connection Manager, the object   
         // returned by that utility will be provided.   
         	if (xhrobj) {   
             m += ", " + YAHOO.lang.dump(xhrobj);   
         	}   
         YAHOO.log(m);   
     	}*/
		});
  loader.insert({}, 'js');
 })();
 