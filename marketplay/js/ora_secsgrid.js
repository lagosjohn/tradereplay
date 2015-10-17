// JavaScript Document
(function() {
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
    
 YAHOO.log('oraSecsGrid.js file loaded...', 'info', 'ora_secsgrid.js');
   
	YAHOO.widget.DataTable.prototype.requery = function(newRequest) {
		var ds = this.getDataSource(),
			req;
		if (this.get('dynamicData')) {
			// For dynamic data, newRequest is ignored since the request is built by function generateRequest.
			var paginator = this.get('paginator');
			this.onPaginatorChangeRequest(paginator.getState({'page':paginator.getCurrentPage()}));
		} else {
			// The LocalDataSource needs to be treated different
			if (ds instanceof YAHOO.util.LocalDataSource) {
				ds.liveData = newRequest;
				req = "";
			} else {
				req = (newRequest === undefined?this.get('initialRequest'):newRequest);
			}
			ds.sendRequest(req,
				{
					success: this.onDataReturnInitializeTable,
					failure: this.onDataReturnInitializeTable,
					scope: this,
					argument: this.getState() 
				}
			);
		}
	};

	App.createsmartOraSecsGrid_filter = {
    	 id: 0,
		 session: App.ses,
		 datefrom: '',
		 dateto: '',
		 datecond1: '',
		 sidetrader: '',
		 trader: '',
		 volcond:'',
		 volume: '',
 	     sideinvestor: '',
		 investor: '',
		 phase: '',
		 suspended: '',
		 pricefield: '',
		 pricefield2: '',
		 pricecond: '',
		 pricepct: '',	 
		 symbol: 'OTE'
		 };
	
	oraFilter=App.createsmartOraSecsGrid_filter;
	
	App.requerysmartOraSecsGrid = function()
	{
		//App.MdBidTradersDataSource.sendRequest("?task=get_mdTraders&price="+(Math.round(oRec.getData("PRICE")*100))+"&side=B&format=JSON&session="+App.ses,App.mygrid_MdBidTraders.requestCallback); 
		App.oraSecsDataTable.requery(); //App.aux+"?task=getSelectedSecs&REQUERY&format=JSON&newRequest="+App.newOraQueryParamsId+"&session="+App.ses+"&"

	}
	
	App.createsmartOraSecsGrid = function()
	{
	    // Define a custom format function
	if (App.oraDataSource) // instanceof YAHOO.util.DataSource
		return App.requerysmartOraSecsGrid();
		
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
		{key:'SELECTED', label:'Select<br/>Date', formatter:'checkbox', className:'center'},
		{key:"FDATE", label:"Date", resizeable:true, sortable:true},
		{key:"SYMBOL", label:"Symbol", resizeable:true, sortable:true},
		{key:"VOLUME", label:"Volume", resizeable:true, sortable:true},
    	];

    smartOraSecs = App.createsmartOraSecsGrid;
	smartOraSecs.newValue='';
	smartOraSecs.fdate='';
	smartOraSecs.symbol='';
	smartOraSecs.isin='';
	smartOraSecs.recordIndex=0;
	smartOraSecs.record=null;
    // DataSource instance
    App.oraDataSource = new YAHOO.util.DataSource(App.aux+"?task=getSelectedSecs&format=JSON&newRequest="+oraFilter.id+"&session="+App.ses+"&"); //App.newOraQueryParamsId
    App.oraDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
    App.oraDataSource.responseSchema = {
        resultsList: "records",
        fields: [
            {key:"SELECTED"},
			{key:"FDATE"},
			{key:"SYMBOL"},
			{key:"VOLUME"}
        ],
        metaFields: {
            totalRecords: "totalRecords" // Access to value in the server response
        }
    };
   
	//alert(document.getElementById('t_OraSecs').rows.length);
	var tr=document.getElementById('t_OraSecs').rows[0].cells;
	var j=0;
	for (var k in App.createsmartOraSecsGrid_filter)
	{
	 if (k=='id' || k=='session'||k=='datecond1'||k=='sidetrader'||k=='sideinvestor')
		 continue;
	//YAHOO.log('k='+k+"="+App.createsmartOraSecsGrid_filter[k], 'info', 'ora_secsgrid.js');
	tr[j++].innerHTML=App.createsmartOraSecsGrid_filter[k]; //firstChild.nodeValue
	}
  
	
// Button for updating config with new dates securities 	   
  var refreshButtonCheckedChange=function(e)
  {
	   App.smartOraSecs_win.hide();
    };
  
  var calendarButtonCheckedChange=function(e)
   {
	   msg="task=setConfigCalendar&crid="+App.createsmartOraSecsGrid_filter.id+"&session="+App.ses;
	   App.callback.argument[0] = 'JS';
	   App.AjaxObject.syncRequest(App.aux,msg);
    };

 
   var refreshButton = new YAHOO.widget.Button({
                                    id: "InitSession",
        							label: "Init Session", //
									type: "button",
									container:"smOraButtons",
									title: "Current Session contents will change according to the selected Dates",
                                    checked: false
                                });
   //refreshButton.addListener("checkedChange", refreshButtonCheckedChange);
   refreshButton.on("click", refreshButtonCheckedChange);
// Button for setting grid dates as default calendar dates for this session 
  
	
   var calendarButton = new YAHOO.widget.Button({
                                    id:"UpdateCalendar",
									label:"Update Calendar",
									type: "button",
									container:"smOraButtons",
									title: "All Calendars will show as valid dates only those from the Selected Date (only the first one!)",
                                    checked: false
                                });
   //calendarButton.addListener("checkedChange", calendarButtonCheckedChange);
   calendarButton.on("click", calendarButtonCheckedChange);

   var params = YAHOO.lang.JSON.stringify(oraFilter);
//YAHOO.log(params,"error");
    // DataTable configuration
    var myConfigs = {
        initialRequest: "sort=fdate&dir=asc&startIndex=0&results=0&filter="+encodeURI(params), // Initial request for first page of data
        dynamicData: true, // Enables dynamic server-driven data
		height: "230px", 
		width: "300px", 
        sortedBy : {key:"FDATE", dir:YAHOO.widget.DataTable.CLASS_ASC}, // Sets UI initial sort arrow
        paginator: new YAHOO.widget.Paginator({ rowsPerPage:20 }), // Enables pagination 
		selectionMode : 'single'
    };
    

	var handleSuccess = function(o){			
			var data = this.record.getData();
//YAHOO.log("Ajax smartOraSecs Success:"+o.status+" - Response: " + o.responseText+"=="+this.newValue+"=key="+this.column.key, "info","tradersGrid.js"); 			
							data[this.column.key] = this.newValue;
							App.oraSecsDataTable.updateRow(this.recordIndex,data);
			};
			
	var handleFailure = function(o){
			YAHOO.log("Ajax smartOraSecs failed:"+o.status+" - Status: " + o.statusText, "error","tradersGrid.js");
			};
	
	smartOraSecs.Callback= {
					success:handleSuccess,
					failure:smartOraSecs.handleFailure,
					scope: smartOraSecs,
					argument: []
					};
    // DataTable instance
   
    App.oraSecsDataTable = new YAHOO.widget.DataTable("div_smartOraSecs", myColumnDefs, App.oraDataSource, myConfigs);
	App.oraSecsDataTable.subscribe("rowClickEvent", App.oraSecsDataTable.onEventSelectRow);
	App.oraSecsDataTable.subscribe('checkboxClickEvent', function(oArgs){
					Event.preventDefault(oArgs.event);
				    var elCheckbox = oArgs.target;
					smartOraSecs.newValue = elCheckbox.checked;
				    smartOraSecs.record = this.getRecord(elCheckbox);
					smartOraSecs.column = this.getColumn(elCheckbox);
					var oldValue = smartOraSecs.record.getData(smartOraSecs.column.key);
					smartOraSecs.recordIndex = this.getRecordIndex(smartOraSecs.record);
					smartOraSecs.fdate = smartOraSecs.record.getData('FDATE');

				 	msg='task=checkSelectedSecs'+'&fdate='+smartOraSecs.fdate+'&value='+smartOraSecs.newValue+"&format=JSON&session="+App.ses;
				    App.AjaxObject.syncRequest(App.aux,msg,smartOraSecs.Callback);
				});  
    // Update totalRecords on the fly with value from server
	
	App.oraSecsDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
        oPayload.totalRecords = oResponse.meta.totalRecords;
        return oPayload;
    	}
	
// ===== 	TRADERS MENU       ===========
	onsmartOraSecsContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {   
             var task = p_aArgs[1];  
			
             if(task) {   
                 // Extract which TR element triggered the context menu   
                 var elRow = this.contextEventTarget;   
                 elRow = p_myDataTable.getTrEl(elRow);   
   
                 if(elRow) {   
                    switch(task.index) {                           
							case 0:
							var oRecord = p_myDataTable.getRecord(elRow);
							var tiv=oRecord.getData("FDATE");
							
							Dom.get('fdate').value=tiv;
							break;
                     }   
                 }   
             }   
         };  


		 var smartOraSecsContextMenu = new YAHOO.widget.ContextMenu("smart_traderscontextmenu",   
                {trigger:App.oraSecsDataTable.getTbodyEl()});   

		
		 smartOraSecsContextMenu.addItems([
			"Copy Trader/Investor",
        	]);

         smartOraSecsContextMenu.render('div_smartOraSecs'); //"div_orders"
		 smartOraSecsContextMenu.clickEvent.subscribe(onsmartOraSecsContextMenuClick, App.oraSecsDataTable);  
// ===== END		TRADERS MENU       ===========
	};

 var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        require: ['datasource', 'paginator', 'button','datatable','json'], //,'charts','swf', "container"
        onSuccess: function() {	
           YAHOO.log("ora_secsgrid loaded successfully...","info");
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
 