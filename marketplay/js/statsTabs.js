// JavaScript Document
// JavaScript Document
// JavaScript Document
(function() {
    var Dom = YAHOO.util.Dom,
    Event = YAHOO.util.Event,
	App = YAHOO.mainTradeReplay.app;
    
	
    YAHOO.log('statsTabs.js file loaded...', 'info', 'statsTabs.js');
	
	App.createStatisticsTabView = function()
	{
	 App.tabindex=0;     	
    // DataSource instance
       App.tabView = new YAHOO.widget.TabView('div_MktInfo');

	    App.addNewTab = function (content, label, source) {
			App.tabindex++;
			YAHOO.log('statsTabs.jsindex...'+content, 'warn', 'statsTabs.js');
			App.tabView.addTab( new YAHOO.widget.Tab({
        		label: App.isinAr[label][1],
				title: label,
        		content:'<div id="' + content + '"></div>', //Dom.get(content), //
        		dataSrc: App.tradeOrders+'?task=mkt_statistics'+'&format=JSON'+'&id='+App.tabindex+'&isin='+label+'&session='+App.ses,
				cacheData: false,
				contentVisible:false
    			}), App.tabindex);
		
		
		var frm_child;
		var myTab = App.tabView.getTab(App.tabindex-1);
	// Event trap to change Active Tab Tooltip from 'active' to label
		App.tabView.addListener('activeTabChange', function (e){myTab.set('title',label);} );
		App.tabView.addListener('beforeContentChange', function (e){ if (App.marketStatus=='s') return(false); else return(true);} );
	//	App.callback_mkt.argument = ['JSON',App.fillMktInfoForm];
		myTab.loadHandler=App.callback_mkt;
		//myTab.get('contentEl')=Dom.get('div_MktInfo_main');
		el = new YAHOO.util.Element(content);
		
		var div=Dom.get('div_MktInfo_main');
		clone_div = div.cloneNode(true);
		clone_div.setAttribute('id', 'div_MktInfo_main_'+App.tabindex);
		var ar=clone_div.getElementsByClassName('mkt_val');
//		YAHOO.log('Tabs...'+ar.length, 'warn');
		for (var i=0; i<ar.length; i++) {
			if (ar[i].id=='MktInfoForm')
			   {
				   frm_ar=ar[i].getElementsByClassName('mkt_val');
				   //for (var j=0; j<frm_ar.length; j++)  
				   //     frm_ar[j].setAttribute('id', frm_ar[j].id+'_'+App.tabindex); 
					
			   }
			ar[i].setAttribute('id', ar[i].id+'_'+App.tabindex);
			}

		el.appendChild(clone_div);
	//YAHOO.log('Tabs...div_hourTradesGraph_'+App.tabindex, 'warn');
		var graph_div = document.createElement('div');
		graph_div.setAttribute('id','div_hourTradesGraph_'+App.tabindex);
		el.appendChild(graph_div);
		
/*		
		if (myTab) {
			myTab.addListener('click', function(){
												//YAHOO.log('statsTabs.jsindex...'+this.get('label'), 'warn', 'statsTabs.js');
   				
				App.callback2.argument = ['JSON',App.fillMktInfoForm];
				msg='task=mkt_statistics'+'&format=JSON'+'&id='+App.tabindex+'&isin='+this.get('label')+'&session='+App.ses;	
				App.AjaxObject.syncRequest(App.tradeOrders,msg,App.callback2);
				});
			}*/
		}
	};

 var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
		filter: 'debug',
        require: ['tabview', 'connection', 'event','utilities'], //,'charts','swf', "container"
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