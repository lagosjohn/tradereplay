// JavaScript Document
App = YAHOO.mainTradeReplay.app;
oCurrentTextNode = null;
oLastHiLiNode=null;
oLastNodeDate='';
oCurrentDatesTextNode=null;
currentIconMode=0;
oContextDatesMenu='';
oContextMenu='';
nodeInfoDataSource='';
nodeInfoDataTable='';
oSavedNode='';
oSecNode='';
App.eff='';
App.traders_so = null;
App.traders2_so = null;
App.secTraders_so=null;

var docObj = {
        		label: '',
				className:'',
        		NodeId: 0,
				NodeType: 0,
				bvol: 0,
				svol: 0,
				isLeaf: 0,
				title:'',
				target:'_new',
			    draggable:true,
				editable: true
    			};
				
(function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;
    	
		
    YAHOO.log('traderstv.js file loaded..', 'info', 'traderstv.js');
    //Create this loader instance and ask for the Calendar module
    var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
		//base:'http://yui.yahooapis.com/2.8.2r1/build/',
        require: ['treeview','menu','event','container','editor','button','layout','element'],
        onSuccess: function() {
			document.getElementById("submitButton").disabled = true;
			document.getElementById("submitButton").style.display = "none";
			
			App.traders_layout = new YAHOO.widget.Layout('div_traders_layout',{
            minWidth: 825,
            //minHeight: 500,
            width:735,
			units: [
//					{ position: 'top', height: '30px', maxHeight: 30, resize: false, id: 'top2' }, 
					{ position: 'left', height: 530, width: 150, resize: false, body: 'div_tv_tv', gutter: '2px', scroll: true },
                    { position: 'center', body:'div_traders_info',  scroll: true } //id: 'div_traders_info',
/*					{ position: 'top', height: 30, resize: false, header: 'Traders'},
                    //{ position: 'right', width: 305, resize: true},
                    { position: 'left', height: 530, width: 170, resize: false, body: 'div_traders_tv', gutter: '2px'},
                    { position: 'center',width: 470, resize: false, body: '', }*/
            ]
        });
			App.traders_layout.on('beforeResize', function() { 
	                    Dom.setStyle('div_traders_layout', 'height', Dom.getStyle(App.tabView._contentParent, 'height')); 
                }); 
			
			App.traders_layout.on('resize', function() {
               // var t = App.layout3.getUnitByPosition('top');
               // var tw = t.getSizes().body.w-100; // - App.traders_layout.getSizes().left.w;
              
                //Dom.setStyle('div_traders_info', 'width', tw + 'px');
				//alert(t.getSizes().body.w+"=="+Dom.getStyle('div_traders_info', 'width')); 
				//YAHOO.util.Dom.setStyle(App.traders_layout.getUnitByPosition('center').body,'width', tw + 'px');
				//var r = App.traders_layout.getUnitByPosition('center');
				//Dom.setStyle(YAHOO.util.Selector.query('div.yui-layout-unit-center', r.body), 'width', tw + 'px');
            }, App.traders_layout, true);
			
			App.traders_layout.render();
			
			App.tabView.get('tabs')[1].set('content',Dom.get("div_traders_layout").innerHTML);
						
			App.createHelpTree();
			App.createDatesTree();
			
			var   itemdata= [
            [{ text: "Info Node", onclick: { fn: infoNode } },
            { text: "Chart A area", onclick: { fn: App.showNodeChart, obj:[1,'A'] } },
			{ text: "Chart B area", onclick: { fn: App.showNodeChart, obj:[1,'B'] } }],
			[{ text: "Past Session dates (A area)" ,
				submenu:{
						id:"pastA",
						itemdata:[  { text: "One week", onclick: { fn: App.showNodeChart, obj:[1,'7-A'] } },
								    { text: "One month", onclick: { fn: App.showNodeChart, obj:[1,'30-A'] } },
									{ text: "Six months", onclick: { fn: App.showNodeChart, obj:[1,'180-A'] } }
									]
				}
			},
			{ text: "Past Session dates (B area)" ,
				submenu:{
						id:"pastB",
						itemdata:[  { text: "One week", onclick: { fn: App.showNodeChart, obj:[1,'7-B'] } },
								    { text: "One month", onclick: { fn: App.showNodeChart, obj:[1,'30-B'] } },
									{ text: "Six months", onclick: { fn: App.showNodeChart, obj:[1,'180-B'] } }
									]
				}
			}]
			];
			
			
						
			var   itemdata_for_dates_tv = [
            { text: "Chart A area", value:'A', onclick: { fn: App.showNodeChart, obj:[2,'A'] } },
			{ text: "Chart B area", value:'B', onclick: { fn: App.showNodeChart, obj:[2,'B'] } }
       		];	
	
			function onShowMenu(p_sType, p_aArgs, p_oValue)
			{
				oContextMenu.getItem(0).cfg.setProperty("classname", (oCurrentTextNode.data.NodeType==0 ?"menu-disabled" :"menu-enabled"));
				
			}
			function onShowDatesMenu(p_sType, p_aArgs, p_oValue)
			{
				oContextMenu.getItem(0).cfg.setProperty("classname", (oCurrentTextNode.data.NodeType==0 ?"menu-disabled" :"menu-enabled"));
				
			}		
			
	
			YAHOO.util.Event.onAvailable("div_traders_tv", function () {
				oContextMenu = new YAHOO.widget.ContextMenu(
	    		"tradersTreeMenu",
		    	{
        		trigger: "div_traders_tv",
		        lazyload: true,
				itemdata: itemdata
				}
				);
			
		   oContextMenu.setItemGroupTitle("Dates past Session date!", 1);
		   oContextMenu.subscribe("triggerContextMenu", onTriggerContextMenu);
		   oContextMenu.subscribe("show", onShowMenu);
		  // oContextMenu.render();
		   });
		   YAHOO.util.Event.onAvailable("div_dates_tv", function () {
				oContextDatesMenu = new YAHOO.widget.ContextMenu(
	    		"datesTreeMenu",
		    	{
        		trigger: "div_dates_tv",
		        lazyload: true,
				itemdata: itemdata_for_dates_tv
				}
				);
		   
		   oContextDatesMenu.subscribe("triggerContextMenu", onTriggerDatesContextMenu);
		   oContextDatesMenu.subscribe("show", onShowDatesMenu);

		   });
	
      	  App.createDataTable();
		  }
    });
	
	
	
	var editorConfig = {
        height: '300px',
        width: '630px',
        dompath: true,
		handleSubmit:true,
        focusAtStart: true,
		allowNoEdit:true
    };
	
		
	App.Callback_insertTradersDocNode = function(html)
	{
		oCurrentTextNode.expand();
		var oResults = eval("(" + App.helpNode + ")");
        if ((oResults.ResultSet.Result) && (oResults.ResultSet.Result.length)) {
             if (YAHOO.lang.isArray(oResults.ResultSet.Result)) 
			     docObj.NodeId=oResults.ResultSet.Result[0];
				  else
				 	docObj.NodeId=oResults.ResultSet.Result;
			}
		
		oChildNode = new YAHOO.widget.TextNode(docObj, oCurrentTextNode, false);
		if (html)
			oChildNode.href='#';
	    oCurrentTextNode.refresh();
    	oCurrentTextNode.expand();
	}
	
	var infoNode = function() {
				
		var myCallback = function() { 
	            //this.set("sortedBy", null); 
	            oSavedNode='';
				nodeInfoDataTable.onDataReturnReplaceRows.apply(nodeInfoDataTable,arguments); 
	        }; 
	        var callback1 = { 
	            success : myCallback, 
	            failure : myCallback, 
	            scope : nodeInfoDataTable 
	        }; 
	     
		 if (oCurrentTextNode.data.NodeType==1)
		 {
		  var chart1=Dom.get('traders_chart1');
		  if (chart1){
			  Dom.get('div_nodeChart1').removeChild(chart1); 
		  }
		 }
		   nodeInfoDataSource.sendRequest('task=getNodeInfo'+'&format=JSON'+'&objId='+oCurrentTextNode.data.NodeId+'&objType='+oCurrentTextNode.data.NodeType+'&ses='+App.ses, 
	                callback1); 
 	}
	
	
	var editTradersNodeContent=function()
	{
	 var sUrl = App.aux+'?task=getDocContent'+'&objId='+oCurrentTextNode.data.NodeId+'&type='+oCurrentTextNode.data.NodeType;
    
    //prepare our callback object
     var callback = {
        success: function(oResponse) {
 				if (!oResponse.responseText)
					 oResponse.responseText="Empty";
				if (oResponse.responseText) {
                    Dom.get("doctitle").value=oCurrentTextNode.label;
					Dom.get("doctypename").innerHTML=(oCurrentTextNode.data.NodeType==1 ?"Document" :(oCurrentTextNode.data.NodeType==2 ?"Chapter" :"Paragraph"));
					Dom.get("objId").value=oCurrentTextNode.data.NodeId;
					Dom.get("docType").value=oCurrentTextNode.data.NodeType;
					Dom.get("task").value="saveDocContent";
					if (!App.docEditor){
						App.docEditor = new YAHOO.widget.Editor('docEditor', editorConfig);
			    		App.docEditor._defaultToolbar.buttonType = 'basic';
																		
						Dom.get("docEditor").innerHTML=oResponse.responseText;//oResults.ResultSet.Result[0];
						App.docEditor.render();
						
						//App.docEditor.setEditorHTML(oResults.ResultSet.Result[0]);
						}
					else{
						App.docEditor.clearEditorDoc();
						//alert(oResults.ResultSet.Result[0]);
						App.docEditor.setEditorHTML(oResponse.responseText); //oResults.ResultSet.Result[0]);
			 			App.docEditor.show();
						}
			 App.editor_win.show();
            }
	//		}
                                
            //oResponse.argument.fnLoadComplete();
        },
        
        failure: function(oResponse) {
            YAHOO.log("Failed to process XHR transaction.", "info", "example");
            oResponse.argument.fnLoadComplete();
        },
 
        argument: {
            "node": oCurrentTextNode
        },
        timeout: 7000
    };
    
     YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
	}

	var onTriggerContextMenu = function(p_oEvent) {
		
	    var oTarget = this.contextEventTarget;
	    oCurrentTextNode = App.tradersRoot.getNodeByElement(oTarget);
//YAHOO.log('onTriggerContextMenu..'+oCurrentTextNode.data.NodeId+" t:"+oCurrentTextNode.data.NodeType, 'info', 'helptree.js');
    	
		
		if (!oCurrentTextNode) {
        	this.cancel();
    		}

		if (oCurrentTextNode.data.NodeType==1){
			oSecNode=oCurrentTextNode;
		}
	}
		
		
	var onTriggerDatesContextMenu = function(p_oEvent) {
	    var oTarget = this.contextEventTarget;
	    oCurrentDatesTextNode = App.datesRoot.getNodeByElement(oTarget);
		
		if (!oCurrentDatesTextNode) {
        	this.cancel();
    		}
		if (oCurrentDatesTextNode.data.NodeType==1) {
			oDateNode=oCurrentDatesTextNode;
			Dom.get("chart_date").innerHTML=oCurrentTextNode.data.NodeId;
			}
		  else
			if (oCurrentTextNode.data.NodeType==2)
				Dom.get("chart_isin").innerHTML=oCurrentTextNode.data.NodeId;
		}
	
   App.updateScrollerInfo = function(chart)
   {
	   var parent_node=oCurrentTextNode.parent;
	   
	   if (oCurrentTextNode.data.NodeType==1){

			Dom.get("chart"+chart+"_isin").innerHTML=oCurrentTextNode.label;
		}
		  else
			if (oCurrentTextNode.data.NodeType==2) {
				Dom.get("chart"+chart+"_mem").innerHTML=oCurrentTextNode.data.NodeId;
				Dom.get("chart"+chart+"_isin").innerHTML=parent_node.label;
			}
			  else
				if (oCurrentTextNode.data.NodeType==3) {
					Dom.get("chart"+chart+"_trader").innerHTML=oCurrentTextNode.data.NodeId;
					Dom.get("chart"+chart+"_mem").innerHTML=parent_node.data.NodeId;
					Dom.get("chart"+chart+"_isin").innerHTML=parent_node.parent.label;
					}
				 else
					if (oCurrentTextNode.data.NodeType==4 ) {
						Dom.get("chart"+chart+"_client").innerHTML=oCurrentTextNode.data.NodeId;
						Dom.get("chart"+chart+"_mem").innerHTML=parent_node.parent.data.NodeId;
						Dom.get("chart"+chart+"_isin").innerHTML=parent_node.parent.parent.label;
						Dom.get("chart"+chart+"_trader").innerHTML=parent_node.data.NodeId;
						}
    }
	
/**
*
*/  
   App.showNodeChart = function(p_sType, p_aArgs,p_oValue)
   {	 
	 var node=oCurrentTextNode;
	 var menu=p_oValue[0];
     var oValue=p_oValue[1];
	 var dateRef='';
		  
	  if(node.data.NodeType>0){
		 var parent_node=node.parent;
		 if (node.data.NodeType==1)
			{
			 App.showBubbleChart(p_oValue);
			 return;
		    }
		
		else
		if (parent_node.data.NodeType==0)
			var parent='&sec='+node.data.NodeId;
		else
		if (parent_node.data.NodeType==_TRADER_TYPE_SEC)
			var parent='&sec='+parent_node.data.NodeId;
		else
		if (parent_node.data.NodeType==_TRADER_TYPE_MEMBER) {
			parent_node=parent_node.parent;
			var parent='&sec='+parent_node.data.NodeId;
		}
		else
			if (parent_node.data.NodeType==_TRADER_TYPE_TRADER) {
				parent_node=parent_node.parent.parent;
				var parent='&sec='+parent_node.data.NodeId;
			}
	}
	
	var pastTime='';
	var ar = oValue.split("-");
	
	if (ar.length==2) {
	 	pastTime = ar[0];
		var area=ar[1];
		}
		else
			var area=ar[0];
			
	App.updateScrollerInfo(area);
	
 	if (area=="B"){
		var chart2=Dom.get('traders_chart2');
		if (chart2) {
			  Dom.get('div_nodeChart2').removeChild(chart2); 
			  App.traders2_so = null;
				}
	   var chart_so = App.traders2_so;
	   var divId="div_nodeChart2";
	   var chartId="traders_chart2";				
	}
	else {		
		var chart1=Dom.get('traders_chart1');
		if (chart1) {
			  Dom.get('div_nodeChart1').removeChild(chart1); 
			  App.traders_so = null;
			 }
	  var chart_so= App.traders_so;
	  var divId="div_nodeChart1";
	   var chartId="traders_chart1";			 
	}
	/*
	flashMovie = document.getElementById(chart_id);    
           flashMovie.getParam("values.y_right.min");        
           flashMovie.setParam("values.y_left.min",400);
           flashMovie.setParam("values.y_left.strict_min_max",1);
                   flashMovie.rebuild();

*/

	if (oLastNodeDate!='')  // tv dates is checked !!!
		dateRef="&refDate="+oLastNodeDate;
			 
	App.callback.argument[0] = 'JS';
	msg="task=getChartSettings"+
								"&objId="+chartId+
								"&objType="+oCurrentTextNode.data.NodeType+(parent!='undefined'?parent:'')+
								"&pastTime="+pastTime+
								dateRef +
								"&ses="+App.ses;
	App.AjaxObject.syncRequest(App.aux,msg);
  
	if (chart_so === null || chart_so === undefined)
   	{
    	chart_so = new SWFObject(App.graphs+"amline.swf?cache=0", chartId, "100%", "330", "8", "#303E4E"); //#303E4E,
		if (area=="B")	
			App.traders2_so = chart_so;
			else
				App.traders_so = chart_so;
				
			 chart_so.addVariable("path", App.graphs);
			 chart_so.addVariable("chart_id", chartId);
			 chart_so.addParam("wmode", "opaque");
			 chart_so.addVariable("preloader_color", "#999999");
			//App.bidask_so.addVariable("chart_settings", "");
			 chart_so.addVariable("settings_file",encodeURIComponent(App.graphs+"traders_settings_line.xml"));
			/* App.traders_so.addVariable("additional_chart_settings", encodeURIComponent(App.aux+"?task=getChartSettings"+
																						"&objId="+encodeURIComponent(oCurrentTextNode.data.NodeId)+
																						"&objType="+oCurrentTextNode.data.NodeType+(parent!='undefined'?parent:'')+
																						"&pastTime="+pastTime+
																						"&ses="+App.ses));*/
			 
			 chart_so.addVariable("data_file", encodeURIComponent(App.aux+"?task=getChartData&objId="+
																  encodeURIComponent(oCurrentTextNode.data.NodeId)+
																  "&objType="+oCurrentTextNode.data.NodeType+(parent!='undefined'?parent:'')+
																  "&interval="+Dom.get('binterval').value+
																  "&period="+Dom.get('bperiod').value+
																  dateRef +
																  "&ref="+Dom.get('breference').value+
															      "&top="+Dom.get('btop').value+
																  "&pastTime="+pastTime+
																  "&ses="+App.ses));
			 //encodeURIComponent
			 chart_so.write(divId);
			// var flashMovie = document.getElementById(chartId);
			// flashMovie.setParam("graphs.graph[0].title", "some title");
   }
   else {
	   /*App.traders_so.addVariable("additional_chart_settings", encodeURIComponent(App.aux+"?task=getChartSettings"+
																						"&objId="+encodeURIComponent(oCurrentTextNode.data.NodeId)+
																						"&objType="+oCurrentTextNode.data.NodeType+(parent!='undefined'?parent:'')+
																						"&ses="+App.ses));*/
	   chart_so.addVariable("data_file", encodeURIComponent(App.aux+"?task=getChartData&objId="+
															encodeURIComponent(oCurrentTextNode.data.NodeId)+
															"&objType="+oCurrentTextNode.data.NodeType+(parent!='undefined'?parent:'')+
															"&interval="+Dom.get('binterval').value+
															"&period="+Dom.get('bperiod').value+
															dateRef +
															"&ref="+Dom.get('breference').value+
															"&top="+Dom.get('btop').value+
														    "&pastTime="+pastTime+
															"&ses="+App.ses));
	   chart_so.write(divId); 
   	   }
   }
			 
    	
   App.showBubbleChart = function(p_oValue)
   {
	var node=(oSecNode ?oSecNode :oCurrentTextNode);	
	var pastTime='';
	var dateRef='';
	
	var menu = p_oValue[0];
    var p_area = p_oValue[1];
	
	var ar = p_area.split("-");
	
	if (ar.length==2) {
	 	pastTime = ar[0];
		var area=ar[1];
		}
		else
			var area=ar[0];

	App.updateScrollerInfo(area);
	
	if (area=="B"){
		var chart4=Dom.get('traders_chart4');
		if (chart4) {
			  Dom.get('div_nodeChart4').removeChild(chart4); 
			  App.secTraders_so = null;
			  }
		var divId="div_nodeChart4";
	    var chartId="traders_chart4";	  
	}
	else {
		var chart3=Dom.get('traders_chart3');
		if (chart3) {
			  Dom.get('div_nodeChart3').removeChild(chart3); 
			  App.secTraders_so = null;
			}
	   var divId="div_nodeChart3";
	   var chartId="traders_chart3";
	}
	
	if (menu==2) { //tv dates
		if (oLastHiLiNode.data.NodeType==_DATES_TYPE_DATE) {
			 dateRef="&refDate="+oLastHiLiNode.data.NodeId;
			 Dom.get("chart"+area+"_date").innerHTML=oLastHiLiNode.data.NodeId;
			}
			else
			  if (oLastHiLiNode.data.NodeType==_DATES_TYPE_SEC) {
				  dateRef="&refDate="+oLastHiLiNode.parent.data.NodeId;
				  node=oLastHiLiNode;
				  Dom.get("chart"+area+"_date").innerHTML=oLastHiLiNode.parent.data.NodeId;
				  Dom.get("chart"+area+"_isin").innerHTML=oLastHiLiNode.label;
			  }
			  
		}
		else
			if (oLastNodeDate!='')  // tv dates is checked !!!
				dateRef="&refDate="+oLastNodeDate;
			
		//children
			 /*
			 var el = document.createElement('div'); 					//  Create a new DIV
			 el.setAttribute("id","div_"+gid);		// Give to new div an id  as the ISIN code

			 lc.body.appendChild(el);
	  }*/

	if (App.secTraders_so === null || App.secTraders_so === undefined)
   	{
		App.secTraders_so = new SWFObject(App.graphs+"amtimeline.swf", chartId, "100%", "340", "8", "#FFFFFF");
        App.secTraders_so.addVariable("path", App.graphs) ;
		App.secTraders_so.addVariable("chart_id", chartId);
		App.secTraders_so.addVariable("additional_chart_settings", encodeURIComponent(App.aux+"?task=getBubbleSettings"+"&top="+Dom.get('btop').value+"&pastTime="+pastTime+"&ref="+Dom.get('breference').value));
		//App.secTraders_so.addVariable("settings_file", encodeURIComponent(App.aux+"?task=getBubbleSettings"));
		App.secTraders_so.addVariable("data_file", encodeURIComponent(App.aux+"?task=getChartData&objId="+encodeURIComponent(node.data.NodeId)+
																										"&objType="+node.data.NodeType+
																										dateRef +
																										"&interval="+Dom.get('binterval').value+
																										"&period="+Dom.get('bperiod').value+
																										"&ref="+Dom.get('breference').value+
																										"&top="+Dom.get('btop').value+
																										"&pastTime="+pastTime+
																										"&ses="+App.ses));
		App.secTraders_so.addVariable("preloader_color", "#999999");
        App.secTraders_so.addParam("wmode", "opaque");
    	App.secTraders_so.write(divId);
	}
	else {
		  App.secTraders_so.addVariable("data_file", encodeURIComponent(App.aux+"?task=getChartData&objId="+encodeURIComponent(node.data.NodeId)+
																															   "&objType="+node.data.NodeType+
																															   "&interval="+Dom.get('binterval').value+
																															   "&period="+Dom.get('bperiod').value+
																															   dateRef +
																															   "&ref="+Dom.get('breference').value+
																															   "&top="+Dom.get('btop').value+
																															   "&pastTime="+pastTime+
																															   "&ses="+App.ses));
	      App.secTraders_so.write(divId);
		  }
	}
	

    //Call insert, only choosing the JS files, so the skin doesn't over write my custom css
    loader.insert({}, 'js');
 })();


function loadDatesNodeData(node, fnLoadComplete)  {

    var nodeLabel = encodeURI(node.label);
	var parent='';
	if(node.data.NodeType!=0){
		var parent_node=node.parent;
		if (parent_node.data.NodeType==0)
			var parent='&date='+node.data.NodeId;
		else
		if (parent_node.data.NodeType==_TRADER_TYPE_DATE)
			var parent='&date='+parent_node.data.NodeId;
		else
		if (parent_node.data.NodeType==_DATES_TYPE_SEC) {
			parent_node=parent_node.parent;
			var parent='&date='+parent_node.data.NodeId;
			}
	}
	
    var sUrl = App.aux+'?task=getDatesNode'+'&format=JSON'+'&objId='+node.data.NodeId+parent+'&objType='+node.data.NodeType+"&ses="+App.ses;
    
    //prepare our callback object
    var callback = {
        success: function(oResponse) {
//            YAHOO.log("XHR transaction was successful.", "info", "example");
//            console.log(oResponse.responseText);
            var oResults = eval("(" + oResponse.responseText + ")");
            if ((oResults.ResultSet.Result) && (oResults.ResultSet.Result.length)) {
                //Result is an array if more than one result, string otherwise
                if(YAHOO.lang.isArray(oResults.ResultSet.Result)) {
                    for (var i=0, j=oResults.ResultSet.Result.length; i<j; i++) {
                        docObj.label=oResults.ResultSet.Result[i].title;
						docObj.NodeId=oResults.ResultSet.Result[i].id;
						docObj.NodeType=oResults.ResultSet.Result[i].type;
																	   
					    var tempNode = new YAHOO.widget.TextNode(docObj, node, false);
						tempNode.bvol=oResults.ResultSet.Result[i].vol;
						tempNode.title="Volume:"+tempNode.bvol;

						if (oResults.ResultSet.Result[i].type==_DATES_TYPE_SEC) {
							tempNode.isLeaf = true;
							//tempNode.labelStyle = "icon-sec";
							}
						  else 
							if (oResults.ResultSet.Result[i].type==_DATES_TYPE_DATE) {
								
								//tempNode.labelStyle = "icon-trader";
								//tempNode.setNodesProperty('enableHighlight',true);
								}
				      }
                }
            }
                                
            oResponse.argument.fnLoadComplete();
        },
        
        failure: function(oResponse) {
            YAHOO.log("Failed to process XHR transaction.", "info", "example");
            oResponse.argument.fnLoadComplete();
        },
 
        argument: {
            "node": node,
            "fnLoadComplete": fnLoadComplete
        },
        timeout: 7000
    };
    
     YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
 }


function loadTradersNodeData(node, fnLoadComplete)  {

    var nodeLabel = encodeURI(node.label);
    
    //prepare URL for XHR request:
	if(node.data.NodeType>0){
		var parent_node=node.parent;
		if (parent_node.data.NodeType==0)
			var parent='&sec='+node.data.NodeId;
		else
		if (parent_node.data.NodeType==_TRADER_TYPE_SEC)
			var parent='&sec='+parent_node.data.NodeId;
		else
		if (parent_node.data.NodeType==_TRADER_TYPE_MEMBER) {
			parent_node=parent_node.parent;
			var parent='&sec='+parent_node.data.NodeId;
		}
		else
			if (parent_node.data.NodeType==_TRADER_TYPE_TRADER) {
				parent_node=parent_node.parent.parent;
				var parent='&sec='+parent_node.data.NodeId;
			}
	}
	
    var sUrl = App.aux+'?task=getTradersNode'+'&format=JSON'+'&objId='+node.data.NodeId+(parent!='undefined'?parent:'')+'&objType='+node.data.NodeType+"&ses="+App.ses;
    
    //prepare our callback object
    var callback = {
        success: function(oResponse) {
//            YAHOO.log("XHR transaction was successful.", "info", "example");
//            console.log(oResponse.responseText);
            var oResults = eval("(" + oResponse.responseText + ")");
            if ((oResults.ResultSet.Result) && (oResults.ResultSet.Result.length)) {
                //Result is an array if more than one result, string otherwise
                if(YAHOO.lang.isArray(oResults.ResultSet.Result)) {
                    for (var i=0, j=oResults.ResultSet.Result.length; i<j; i++) {
                        docObj.label=oResults.ResultSet.Result[i].title;
						docObj.NodeId=oResults.ResultSet.Result[i].id;
						docObj.NodeType=oResults.ResultSet.Result[i].type;
																	   
					    var tempNode = new YAHOO.widget.TextNode(docObj, node, false);
						if (oResults.ResultSet.Result[i].type!=_TRADER_TYPE_SEC){
							tempNode.bvol=oResults.ResultSet.Result[i].bvol;
							tempNode.svol=oResults.ResultSet.Result[i].svol;
							tempNode.title="Buy:"+tempNode.bvol+"/n"+"Sell:"+tempNode.svol;
							//var tempNode = new YAHOO.widget.HTMLNode(docObj, node, false);
							}
						if (oResults.ResultSet.Result[i].type==_TRADER_TYPE_INVESTOR) {
							tempNode.isLeaf = true;
							tempNode.labelStyle = "icon-investor";
							}
						  else 
							if (oResults.ResultSet.Result[i].type==_TRADER_TYPE_TRADER) {
								tempNode.labelStyle = "icon-trader";
								}
							 else 
								if (oResults.ResultSet.Result[i].type==_TRADER_TYPE_MEMBER) {
									tempNode.labelStyle = "icon-member";
									}
								 else 
									if (oResults.ResultSet.Result[i].type==_TRADER_TYPE_SEC) {
										tempNode.labelStyle = "icon-sec";
										}
                    }
                }
            }
                                
            oResponse.argument.fnLoadComplete();
        },
        
        failure: function(oResponse) {
            YAHOO.log("Failed to process XHR transaction.", "info", "example");
            oResponse.argument.fnLoadComplete();
        },
 
        argument: {
            "node": node,
            "fnLoadComplete": fnLoadComplete
        },
        timeout: 7000
    };
    
     YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
}

App.loadTradersNodeContent=function(node)  
{
//   alert(node.label);
   if (node.href)
   	return false;
	
   var sUrl = App.aux+'?task=getDocContent'+'&objId='+node.data.NodeId+'&type='+node.data.NodeType;
   var htmlEditorConfig = {
		height: '500px',
        width: '630px',
        dompath: false,
		allowNoEdit: true,
        focusAtStart: true
    }; 
    //prepare our callback object
     var callback = {
         success: function(oResponse) {
 				if (!oResponse.responseText)
					 oResponse.responseText="Empty";
				if (oResponse.responseText) {
                    Dom.get("docHTMLtitle").innerHTML=node.label;
					if (!App.htmlEditor){
						App.htmlEditor = new YAHOO.widget.SimpleEditor('docHtml', htmlEditorConfig);
			    		//App.htmlEditor._defaultToolbar.buttonType = 'basic';
						iframe = App.htmlEditor.get('iframe');
						Dom.addClass(iframe, 'yui-noedit');
						
						Dom.get("docHtml").innerHTML=oResponse.responseText;//oResults.ResultSet.Result[0];
						App.htmlEditor.render();
						//App.htmlEditor.toolbar.collapse(true); 
						//App.docEditor.setEditorHTML(oResults.ResultSet.Result[0]);
						}
					else{
						App.htmlEditor.clearEditorDoc();
						//alert(oResults.ResultSet.Result[0]);
						App.htmlEditor.setEditorHTML(oResponse.responseText); //oResults.ResultSet.Result[0]);
			 			App.htmlEditor.show();
						}
			 App.docHTML_win.show();
            }
	//		}
                                
            //oResponse.argument.fnLoadComplete();
        },
		
        
        failure: function(oResponse) {
            YAHOO.log("loadTradersNodeContent -Failed to process XHR transaction.", "info", "traders-tv");
        },
 
        argument: {
            "node": node
        },
        timeout: 7000
	 };
	YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
}
   
App.createHelpTree = function()
{
	App.tradersRoot = new YAHOO.widget.TreeView("div_traders_tv");
	
	App.tradersRoot.setDynamicLoad(loadTradersNodeData, currentIconMode);
	var root = App.tradersRoot.getRoot();

		docObj.label="Session Securities"; //App.helpDocs[i][_DOC_TITLE];
		docObj.NodeId=0; //App.helpDocs[i][_DOC_ID];
		docObj.NodeType=0; //App.helpDocs[i][_DOC_TYPE];

	var temp = new YAHOO.widget.TextNode(docObj, root, false); 
	
	App.tradersRoot.subscribe('dblClickEvent',App.tradersRoot.onEventEditNode); 
	
	//App.tradersRoot.subscribe('editorSaveEvent',App.editNodeLabel);
	/*App.tradersRoot.subscribe("expand", function(node) {
        if (node.expanded)
			return false;
    });
	App.tradersRoot.subscribe("collapse", function(node) {
        if (node.expanded)
			return false;
    });*/


	//App.tradersRoot.subscribe("clickEvent", function(oArgs){ App.loadTradersNodeContent(oArgs.node);}); //App.loadTradersNodeContent(node);
    App.tradersRoot.render();
 }

App.createDatesTree = function()
{
	App.datesRoot = new YAHOO.widget.TreeView("div_dates_tv");
	
	App.datesRoot.setDynamicLoad(loadDatesNodeData, currentIconMode);
	var root = App.datesRoot.getRoot();
	docObj.label="Selected Dates"; 
	docObj.NodeId=0;
	docObj.NodeType=0;
	App.datesRoot.subscribe('clickEvent',App.datesRoot.onEventToggleHighlight);
	App.datesRoot.subscribe('highlightEvent',function(oArgs){
														  if (oArgs.data.NodeType==_DATES_TYPE_SEC)
														  	   oArgs.unhighlight(true);
															else   
														 	if (oArgs.highlightState==1)
														 	{														
														  	oLastHiLiNode=oArgs;
															oLastNodeDate=oArgs.data.NodeId;  // Save it to be used by chart1,2
															Dom.get("chartA_date").innerHTML=oLastNodeDate;
															Dom.get("chartB_date").innerHTML=oLastNodeDate;
															var hiLit = App.datesRoot.getNodesByProperty('highlightState',1);
	 													  	for(var i in hiLit)
														  	{
		 														if (hiLit[i].data.NodeId!=oLastHiLiNode.data.NodeId)
		 		 													hiLit[i].unhighlight(true);
	 															}
														  	
														  	}
														  	else
														    	if (oArgs.highlightState==0) {  // unCheck
																	oLastNodeDate='';
																	oLastHiLiNode=null;
																	}
																else
																	if (oArgs.highlightState==2)  // children highlited
																	;
													 });
	var temp = new YAHOO.widget.TextNode(docObj, root, false);
	 
	//App.datesRoot.setNodesProperty('propagateHighlightUp',true); 
	//App.datesRoot.setNodesProperty('propagateHighlightDown',true); 
	/*App.tradersRoot.subscribe("highlightEvent", function(oNode){
														 oLastHiLiNode=oNode;
														  alert(oNode.label);  
													 });*/
	App.datesRoot.render();
	
 }
	
var buttonShowChart = function()
  {
   oCurrentTextNode=(oSavedNode ?oSavedNode :oCurrentTextNode);
   oSavedNode=oCurrentTextNode;
   App.showNodeChart('func','',[1,'A']);
  }
  
App.createDataTable = function()
{
   var textFormat = function (elCell, oRecord, oColumn, oData) {     	
		
		var t=oRecord.getData('title');
		t=t.toLocaleUpperCase();
		
		var str=new String(escape('Αγορές'));
		//str.toLocaleUpperCase();
		t=escape(t);
		if (t && t.localeCompare("%u0391%u0393%u039F%u03A1%u0388%u03A3")==0)  // Αγορές  //
		   {
  		    //elCell.innerHTML = '<TEXTAREA READONLY ROWS=1 COLS=40>'+oData.replace( /\,/g,"\n" )+'</TEXTAREA>';
			elCell.innerHTML = '<span class="traders_multiline">'+oData+'</span>';
			}
		else
			elCell.innerHTML = oData;
			
		//oColumn.formatter=YAHOO.widget.DataTable.formatTextarea;
		
		/*var mkts=oData.split(',');
		var mkt_options=[];
		for(i in mkts)
				mkt_options[i] = {label:mkts[i], value:i};
			alert(mkt_options.toString());
		oColumn.dropdownOptions = mkt_options;
		elCell.innerHTML = mkts[0];
			}
			else*/
	//		elCell.innerHTML = oData;
	 	};
		//YAHOO.widget.DataTable.Formatter.contentFormat = textFormat;
  
  var myColumnDefs = [ 
	            {key:"title", label:"Title",className: ['traders_title_bold','traders_title_font']}, 
	            {key:"value", label:"Content", formatter:textFormat, className: 'traders_title_font'} 
	        ]; 
	
	        nodeInfoDataSource = new YAHOO.util.DataSource(App.aux+"?"); 
	        nodeInfoDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
	   //     nodeInfoDataSource.connXhrMode = "queueRequests"; 
	        nodeInfoDataSource.responseSchema = { 
	            resultsList: "ResultSet.Result", 
	            fields: ["title","value"] 
	        }; 
	 
	        nodeInfoDataTable = new YAHOO.widget.DataTable("div_nodeInfo", myColumnDefs, 
	                nodeInfoDataSource, {initialRequest:'task=getNodeInfo'+'&format=JSON'+'&ses='+App.ses});
			
			var tfoot = nodeInfoDataTable.getTbodyEl().parentNode.createTFoot();
			var tr = tfoot.insertRow(0);
			tfoot.className='traders_footer';
			//var th = tr.appendChild(document.createElement('th'));
			//th.colSpan = 2;
			var td = tr.insertCell(0);
			td.innerHTML = ' Show Chart:';
			td.className='traders_title_bold';
			td = tr.insertCell(1);
			td.className='traders_footer_button';
			td.innerHTML = '<center><input type="button" onClick="buttonShowChart();" value="Chart" /></center>';
						
}