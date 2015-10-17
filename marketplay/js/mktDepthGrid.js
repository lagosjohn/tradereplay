// JavaScript Document

App.createGridMarketDepth = function()
	{  		 
				
		var mdb_data =[
					{ ENTRIES: 999.99, BID: 0, PRICE: 0, SPRICE: 999.90, ASK:1, SENTRIES:0 },
					{ ENTRIES: 999, BID: 1, PRICE: 0, SPRICE: 888.90, ASK:1, SENTRIES:0   },
					{ ENTRIES: 999, BID: 1, PRICE: 0, SPRICE: 777.90, ASK:1, SENTRIES:0   },
					{ ENTRIES: 999, BID: 1, PRICE: 0, SPRICE: 666.90, ASK:1, SENTRIES:0   },
					{ ENTRIES: 999, BID: 1, PRICE: 0, SPRICE: 555.90, ASK:1, SENTRIES:0   },
				   ];


		 var levelCustom = function(elLiner, oRecord, oColumn, oData) {
               
                //YAHOO.util.Dom.addClass(elLiner.parentNode, "colorlevel"+oRecord.getData("t"));
                elLiner.innerHTML = '<span width="5" class="colorlevel'+oRecord.getData("t")+'">&nbsp;</span>&nbsp;'+oData;
                }
				
		 YAHOO.widget.DataTable.Formatter.myCustom = levelCustom;
		 mdbDataSource = new YAHOO.util.DataSource(mdb_data);  //"http://"+Meteor.host+((Meteor.port==80)?"":":"+Meteor.port)+"/stream.html" 
 		 mdbDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY ; 
		 mdbDataSource.responseSchema = { fields: ["ENTRIES","BID","PRICE","SPRICE","ASK","SENTRIES"]   };  // ["OASIS_TIME","PHASE_ID","SIDE","ORDER_NUMBER","VOLUME","PRICE","TRADER_ID","ORDER_STATUS","M_C_FLAG"]

		 var mdbDef=[//{key:"A", label:"", formatter:expansionFormatter, width:20},
					 {key:"ENTRIES", label:"#B",width:30,formatter:"myCustom"}, //parser:"number"
					 {key:"BID", label:"Qty(B)",width:40,parser:"number"},
					 {key:"PRICE", label:"Bid",width:45,parser:"number"},
					 {key:"SPRICE", label:"Ask",width:45,parser:"number"},
					 {key:"ASK", label:"Qty(S)",width:40,parser:"number"},
					 {key:"SENTRIES", label:"#S",width:35,parser:"number"},
 					 {key:"t", hidden:true}
					 ];
		 
		 var myConfigs = {
    		 //scrollable:true,
			 dynamicData : false,
			 height: "100px", 
			 //renderLoopSize: 30, 
			 initialLoad:false
			 };  

 		var rowUpdateMDGraph = function( oArgs )
		{
			 var orec = oArgs.record;
			 var oldrec = oArgs.oldDat;
			 var idx=App.mygrid_mdb.getRecordIndex(orec);
			 var recs='';
			 
			 if (idx==0) //&& (oldrec.PRICE!=orec.getData("PRICE") || oldrec.SPRICE!=orec.getData("SPRICE"))) 
			 {
				 
				 var recset=App.mygrid_trades.getRecordSet();
				 if (recset)
					 recs=recset.getRecords();
				 if (recs!='' && App.bidask_lastTime)
				 {
				    	
													sPrice='';
													sVol='';
													if (recs.length){
														sTime=recs[recs.length-1].getData("OASIS_TIME");
														//var mils=parseInt(sTime.substr(9,3))+1;
														//sTime = sTime.substr(0,9) + mils;
														}
													else {
														   sTime=Dom.get('otime').value+'0';
															}
						if (App.bidask_lastTime.indexOf(':'))
							var lst=App.bidask_lastTime.substr(0,3)+(parseInt(App.bidask_lastTime.substr(3,2))-3)+App.bidask_lastTime.substr(5,7);
							else
								var lst=App.bidask_lastTime.substr(0,2)+':'+parseInt(App.bidask_lastTime.substr(2,2))-3+':'+App.bidask_lastTime.substr(4,2)+'.'+App.bidask_lastTime.substr(6,3);
						UnSetMarketToGraph();
						// YAHOO.log("flashMdb:"+App.bidask_lastTime+' '+lst+"=="+sTime+"=="+(parseInt(App.bidask_lastTime.substr(3,2))-2),'error');
						if (App.showGraph) // BidAsk graph
							App.isinAr[document.getElementById('isin').value][_SEC_TABLE_FLASHOBJ].setZoom(App.fdate+' '+lst, App.fdate+' '+sTime);
						if (App.orderBook_win && App.orderBook_win.cfg.getProperty("visible")){
							YAHOO.log("MD:sendRequest",'error');
						    App.obDataSource.sendRequest("?task=OB&format=JSON&sort=trader&dir=asc&startIndex=0&results=25&session="+App.ses,App.obDataTable.requestCallback); }
				 }
			 }
			 mdb_data[idx].BID=orec.getData("BID");
			 mdb_data[idx].PRICE=orec.getData("PRICE");
			 mdb_data[idx].ENTRIES=orec.getData("ENTRIES");
			 mdb_data[idx].ASK=orec.getData("ASK");
			 mdb_data[idx].SPRICE=orec.getData("SPRICE");
			 mdb_data[idx].SENTRIES=orec.getData("SENTRIES");
			 
			 if ( idx==4 ){
				  App.flashMdb.setData(mdb_data[4].PRICE+";"+mdb_data[4].BID+"\n"+
																	mdb_data[3].PRICE+";"+mdb_data[3].BID+"\n"+
																	mdb_data[2].PRICE+";"+mdb_data[2].BID+"\n"+
																	mdb_data[1].PRICE+";"+mdb_data[1].BID+"\n"+
																	mdb_data[0].PRICE+";"+mdb_data[0].BID
																	 );
				  
				}
				  App.flashMda.setData(mdb_data[4].SPRICE+";"+mdb_data[4].ASK+"\n"+
																	mdb_data[3].SPRICE+";"+mdb_data[3].ASK+"\n"+
																	mdb_data[2].SPRICE+";"+mdb_data[2].ASK+"\n"+
																	mdb_data[1].SPRICE+";"+mdb_data[1].ASK+"\n"+
																	mdb_data[0].SPRICE+";"+mdb_data[0].ASK
																	);
		}
		
		var refreshEventsGraph = function( oArgs )
									{ 
									 var orec = oArgs.record;   
     								 var oColumn = oArgs.column; 
									 var idx=App.mygrid_mdb.getRecordIndex(orec);
								     //var oldData = oArgs.oldData;   
									 if (oColumn.getField()=="ASK"){ // the last updated column
									 YAHOO.log("UPDATE ROW: "+idx,'error');
									      if (idx==0 && (mdb_data[idx].PRICE!=orec.getData("PRICE") || mdb_data[idx].SPRICE!=orec.getData("SPRICE"))) { // && oColumn.getField()=="t"
													//oTime=Dom.get('otime').value+'0';
													//sTime=Dom.get('ttime').value;
													
													var recs=App.mygrid_trades.getRecordSet().getRecords();
													sPrice='';
													sVol='';
													if (recs.length){
														sTime=recs[recs.length-1].getData("OASIS_TIME");
														var mils=parseInt(sTime.substr(9,3))+1;
														sTime = sTime.substr(0,9) + mils;
														}
													else {
														   sTime=Dom.get('otime').value+'0';
															}
												//	if (sTime != App.sTime && hastrade!='t') {
													//	App.sTime=sTime;
													   
														//recs[0].getData("OASIS_TIME");
														//var hastrade = App.mygrid_mdb.getRecordSet().getRecords()[0].getData("t");
														//if(hastrade=='t')
												//	YAHOO.log("MD:"+App.fdate+"=="+sTime +"=="+ App.bid_1+"=="+App.ask_1,'error');
														//var oPrice=(hastrade==1) ?Dom.get('trade').value :'0';
												
												var sTimes=sTime;
												if (App.oButtonStart.get("value")==_TOOLBAR_START_PLAY)
													{
													if ( (parseInt(sTime.substr(3,2)) - parseInt(App.bidask_lastTime.substr(3,2))) < 2 )					
														sTime = sTime.substr(0,3) + (parseInt(sTime.substr(3,2)) +4) + sTime.substr(5,7);
													
													UnSetMarketToGraph();
													App.flashBidAsk.setZoom(App.fdate+' '+App.bidask_lastTime, App.fdate+' '+sTime);
												YAHOO.log("MD:"+App.fdate+"=="+App.bidask_lastTime +"=="+ sTime,'error');
													}
												App.bidask_lastTime=sTimes;
												
												
										// App.flashBidAsk.appendData(App.fdate+' '+sTime+";"+sPrice+";"+orec.getData("PRICE")+";"+orec.getData("SPRICE")+";"+sVol+";");
														//App.flashBidAsk.setEvents("<events><event><date>"+App.fdate+' '+sTime+"</date><url></url><color>F7F37E</color><letter>A</letter><bullet>flag</bullet><description><![CDATA["+orec.getData("PRICE")+"]]></description><size>12</size></event></events>",false);
													//	}
											 		}
										 mdb_data[idx].BID=orec.getData("BID");
										 mdb_data[idx].PRICE=orec.getData("PRICE");
										 mdb_data[idx].ENTRIES=orec.getData("ENTRIES");
									YAHOO.log("flashMdb:"+idx+"=="+ mdb_data[idx].PRICE,'error');	 
								//		 App.mygrid_mdb.mychart.refreshData();
										if ( idx==4 )
										 App.flashMdb.setData(mdb_data[4].PRICE+";"+mdb_data[4].BID+"\n"+
																	mdb_data[3].PRICE+";"+mdb_data[3].BID+"\n"+
																	mdb_data[2].PRICE+";"+mdb_data[2].BID+"\n"+
																	mdb_data[1].PRICE+";"+mdb_data[1].BID+"\n"+
																	mdb_data[0].PRICE+";"+mdb_data[0].BID
																	 );
										 
										 mdb_data[idx].ASK=orec.getData("ASK");
										 mdb_data[idx].SPRICE=orec.getData("SPRICE");
										 mdb_data[idx].SENTRIES=orec.getData("SENTRIES");
									     
								//		 App.mygrid_mdb.schart.refreshData();
										if ( idx==4 )
										 App.flashMda.setData(mdb_data[4].SPRICE+";"+mdb_data[4].ASK+"\n"+
																	mdb_data[3].SPRICE+";"+mdb_data[3].ASK+"\n"+
																	mdb_data[2].SPRICE+";"+mdb_data[2].ASK+"\n"+
																	mdb_data[1].SPRICE+";"+mdb_data[1].ASK+"\n"+
																	mdb_data[0].SPRICE+";"+mdb_data[0].ASK
																	);
									 	}
									//	YAHOO.log("Graph="+mdb_data[0].SPRICE+"=="+mdb_data[1].SPRICE+"=="+idx,"warn");
										 //YAHOO.log("refreshEventsGraph="+oColumn.getField()+": "+orec.getData("PRICE")+"=="+App.mygrid_mdb.getRecordIndex(orec));
									  
									};
			 					  
		onCellSelect = function(oArgs )
		{
			var elCell = oArgs.target;
			this.selectCell(elCell); 
			//this.highlightCell(elCell); 
			//YAHOO.log("onCellSelect="+Dom.get(elCell).innerHTML,"warn");
			return false;
		 }
		 
				 
		onCellSelected = function( oArgs )
		{
			var oRec = oArgs.record; 
			var oColumn=oArgs.column;
			this.unselectCell(oArgs.el);
			// YAHOO.log("onCellSelected="+oRec.getData("PRICE")+"=="+oColumn.getKey(),"warn");
			if (App.md_traders_win.cfg.getProperty("visible")==false)
				App.md_traders_win.show();
			App.MdBidTradersDataSource.sendRequest("?task=get_mdTraders&price="+(Math.round(oRec.getData("PRICE")*100))+"&side=B&format=JSON&session="+App.ses,App.mygrid_MdBidTraders.requestCallback); 
			App.MdAskTradersDataSource.sendRequest("?task=get_mdTraders&price="+(Math.round(oRec.getData("SPRICE")*100))+"&side=S&format=JSON&session="+App.ses,App.mygrid_MdAskTraders.requestCallback); 
			return false;
		 }

		App.mygrid_mdb = new YAHOO.widget.DataTable("div_mktdepth_bidask", mdbDef, mdbDataSource, myConfigs); //scrollable:true
 		//App.mygrid_mdb.subscribe("rowClickEvent", onRowClick);
		App.mygrid_mdb.subscribe("cellSelectEvent", onCellSelected);
		App.mygrid_mdb.subscribe("cellClickEvent", onCellSelect);
 		App.mygrid_mdb.subscribe("rowMouseoverEvent", App.mygrid_mdb.onEventHighlightRow);
 		App.mygrid_mdb.subscribe("rowMouseoutEvent", App.mygrid_mdb.onEventUnhighlightRow);
//		App.mygrid_mdb.subscribe("cellUpdateEvent", refreshEventsGraph );
		App.mygrid_mdb.subscribe("rowUpdateEvent", rowUpdateMDGraph );
//		App.mygrid_mdb.subscribe("cellMouseupEvent", refreshEventsGraph );
		
		//App.mygrid_mdb.subscribe( 'cellClickEvent', App.mygrid_mdb.onEventToggleRowExpansion ); 

		App.mygrid_mdb.doesRowExist = function(id) {
			 										var nRecs = App.mygrid_mdb.getRecordSet().getLength();
												//	YAHOO.log("doesRowExist:"+nRecs,"warn");
												  	for(var i = 0; i < nRecs; i++)
					  									{
														//YAHOO.log("doesRowExist:"+nRecs+"=="+id+"=="+App.mygrid_mdb.getRecord(i).getData('PRICE'),"warn");
									 				      if ( id == App.mygrid_mdb.getRecord(i).getData('PRICE') )
														  	  return i;
														  }
													 return -1; 	  
													 }
		var mdb_so='';
		App.mygrid_mdb.createMDbGraph= function()
		{
			mdb_so = new SWFObject(App.graphs+"amcolumn.swf", "mdb", "100%", "140", "8", "#FFFFFF");
			mdb_so.addVariable("path", App.graphs);
			mdb_so.addVariable("chart_id", "mdb");
			mdb_so.addParam("wmode", "opaque");
			mdb_so.addVariable("settings_file", escape(App.graphs+"mdb_settings.xml")); //escape(encodeURIComponent(App.XML_var_mdb)));
			//mdb_so.addVariable("data_file", escape(App.graphs+"mdb_data.txt"));
			mdb_so.addVariable("chart_data","0;0")
			mdb_so.write("div_mktdepth2");
		}
		
		App.mds_so='';
		App.mygrid_mdb.createMDaGraph = function()		
		{
 			App.mds_so = new SWFObject(App.graphs+"amcolumn.swf", "mda", "100%", "140", "8", "#FFFFFF");
 			App.mds_so.addVariable("path", App.graphs);
 			App.mds_so.addVariable("chart_id", "mda");
			App.mds_so.addParam("wmode", "opaque");
			App.mds_so.addVariable("preloader_color", "#000000");
 			//App.mds_so.addVariable("chart_settings", encodeURIComponent(App.XML_var_mda));
			App.mds_so.addVariable("settings_file", escape(App.graphs+"mda_settings.xml"));
			//alert(App.XML_var_mda);
 			//App.mds_so.addVariable("data_file", escape(App.graphs+"mda_data.txt")); // escape(App.graphs+"mda_data.txt"));
			App.mds_so.addVariable("chart_data","0;0")
 			App.mds_so.write("div_mktdepth3");
		 }
		
		YAHOO.util.Event.onAvailable("div_mktdepth2", function(){
														   App.mygrid_mdb.createMDbGraph();
														   App.mygrid_mdb.createMDaGraph()
														   });
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
		YAHOO.log("MktDepthGrid loaded successfully...",'info',"MktDepthGrid.js");
			
		    App.createGridMarketDepth();
            //you can make use of all requested YUI modules   
            //here.   
        }   
    });   
  
// Load the files using the insert() method.   
loader.insert({}, 'js');   
})(); 