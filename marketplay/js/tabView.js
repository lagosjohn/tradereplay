// JavaScript Document
App = YAHOO.mainTradeReplay.app;
(function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
        Sel = YAHOO.util.Selector;
		
        YAHOO.log('tabview.js loaded', 'info', 'tabview.js');
        //Set the time on the home screen
                
        //Method to Resize the tabview
        App.resizeTabView = function() {
            var ul = App.tabView._tabParent.offsetHeight;
			YAHOO.log('resizeTabViewe..'+ul+"=="+App.layout3.getSizes().top.h, 'info', 'tabview.js');
            Dom.setStyle(App.tabView._contentParent, 'height', ((App.layout3.getSizes().top.h - ul) - 2) + 'px');
        };
        
        //Listen for the layout resize and call the method
      //  App.layout3.on('resize', App.resizeTabView);
        //Create the tabView
        YAHOO.log('Creating the main TabView instance', 'info', 'tabview.js');
        App.tabView = new YAHOO.widget.TabView();
        //Create the Home tab       
        App.tabView.addTab( new YAHOO.widget.Tab({
            //Inject a span for the icon
            label: '<span>Markets</span>',
            id: 'tradingView',
            content: '<div id="' + 'div_trades' + '"></div>',
            active: true
        }));
        //Create the Inbox tab
        App.tabView.addTab( new YAHOO.widget.Tab({
            //Inject a span for the icon
            label: '<span>Traders</span>',
            id: 'tradingView',
            content: ''//<div id="' + 'div_traders_layout' + '"></div>'
        }));
	   App.tabView.addTab( new YAHOO.widget.Tab({
            //Inject a span for the icon
            label: '<span>Alerts</span>',
            id: 'reportsView',
            content: ''

        }));
        App.tabView.on('activeTabChange', function(ev) {
            //Tabs have changed
            if (ev.newValue.get('id') == 'reportView') {
                //inbox tab was selected
                if (!App.reportLoaded && !App.reportLoading) {
                    YAHOO.log('Fetching the inbox.js file..', 'info', 'tabview.js');
                    YAHOO.log('Inbox is not loaded yet, use Get to fetch it', 'info', 'tabview.js');
                    YAHOO.log('Adding loading class to tabview', 'info', 'tabview.js');
                    //App.getFeed();
                }
            }
            
            //Resize to fit the new content
            App.layout3.resize();
        });
        //Add the tabview to the center unit of the main layout
        var el = App.layout3.getUnitByPosition('top').body; // App.layout3.getUnitByPosition('top').get('wrap'); // //
		//YAHOO.log('appendTo..'+el.id, 'info', 'tabview.js');
        App.tabView.appendTo(el);
		//App.layout3.getUnitByPosition('top').body.appendChild();
	    //Dom.get('div_trades').appendTo( App.tabView.get("contentEl"));
        //resize the TabView
        App.resizeTabView();
        //Set the time on the home screen
               
        YAHOO.log('Fetch the news feed', 'info', 'tabview.js');
        //YAHOO.util.Get.script('assets/js/news.js'); 


        //When inboxView is available, update the height..
        Event.onAvailable('reportView', function() {
            var t = App.tabView.get('tabs');
            for (var i = 0; i < t.length; i++) {
                if (t[i].get('id') == 'reportView') {
                    var el = t[i].get('contentEl');
                    el.id = 'inboxHolder';
                    YAHOO.log('Setting the height of the TabViews content parent', 'info', 'tabview.js');
                    Dom.setStyle(el, 'height', Dom.getStyle(App.tabView._contentParent, 'height'));
                    
                }
            }

        });

})();