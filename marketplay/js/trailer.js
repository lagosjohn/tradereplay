// JavaScript Document
(function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;
    	App = YAHOO.mainTradeReplay.app;
		
    YAHOO.log('trailer.js file loaded..', 'info', 'trailer.js');
    //Create this loader instance and ask for the Calendar module
    var loader = new YAHOO.util.YUILoader({
        base: 'yui/build/',
        require: ['animation'],
        onSuccess: function() {
            //Set a flag to show if the calendar is open or not
            YAHOO.mainTradeReplay.app.trailerLeft = false;

            YAHOO.log('Create the trailer', 'info', 'trailer.js');
                //setup the animation instance
           				
			HorizontalScroller = function()
			{
	// Variables
			var width			= 400;
			var contentWidth 	= 800;
			var height			= 40;
			var target			= '';
			var buttonWidth		= 40;
			var animLeft		= null;
			var animRight		= null;
 
	// Functions
			function addHandlers()
			{
			YAHOO.util.Event.addListener('move-left', 'mouseover', scrollLeft, this);
			YAHOO.util.Event.addListener('move-right', 'mouseover', scrollRight, this);
			//YAHOO.util.Event.addListener('move-left', 'mouseout', scrollStop, this);
			//YAHOO.util.Event.addListener('move-right', 'mouseout', scrollStop, this);
			
			}
			
			function getScrollContentWidth(){
				str = YAHOO.util.Dom.getStyle('scroll-content', 'width');
				contentWidth = 800; //str.substring(0, (str.length-2));
				}
 			function setupAnimations()
			{
			animRight = new YAHOO.util.Anim('scroll-content', {left : {to : -(contentWidth-width)}},4,YAHOO.util.Easing.easeOut);
			animLeft = new YAHOO.util.Scroll('scroll-content', {left : {to : 700}},9,YAHOO.util.Easing.easeOut); //easeOut
			}
			
			function scrollLeft()
			{
			animLeft.animate();
			}
 
			function scrollRight()
			{
			animRight.animate();
			}
 
			function scrollStop()
			{
			animLeft.stop();
			animRight.stop();
			}
			function setContainers()
			{
			
			YAHOO.util.Dom.setStyle('scroller-container', 'width', (width+(2*buttonWidth)) + 'px');
			YAHOO.util.Dom.setStyle('scroll-content', 'position', 'absolute');
			YAHOO.util.Dom.setStyle('scroll-content', 'left', '0px');
			YAHOO.util.Dom.setStyle('scroll-content', 'top', '3px');
 
			YAHOO.util.Dom.setStyle('scroller', 'position', 'relative');
			YAHOO.util.Dom.setStyle('scroller', 'width', width + 'px');
			YAHOO.util.Dom.setStyle('scroller', 'height', height + 'px');
			YAHOO.util.Dom.setStyle('scroller', 'overflow', 'hidden');
			YAHOO.util.Dom.setStyle('scroller', 'float', 'left');
 
			YAHOO.util.Dom.setStyle('move-left', 'float', 'left');
			YAHOO.util.Dom.setStyle('move-left', 'width', buttonWidth + 'px');
			YAHOO.util.Dom.setStyle('move-left', 'height', height + 'px');
 
			YAHOO.util.Dom.setStyle('move-right', 'float', 'left');
			YAHOO.util.Dom.setStyle('move-right', 'width', buttonWidth + 'px');
			YAHOO.util.Dom.setStyle('move-right', 'height', height + 'px');
			}
			
			function setup()
			{
				getScrollContentWidth();
				setContainers();
				addHandlers();
				setupAnimations();
				animLeft.onComplete.subscribe(function(s, o) { 
	       			animRight.animate();
	    		}); 
				animRight.onComplete.subscribe(function(s, o) { 
	       			animLeft.animate();
	    		}); 
				//animLeft.animate();
			 }
	// Public return
			var pub = {
			initScroll : function(args){
				target = args.target;
				contentWidth = args.contentWidth;
				width = args.width;
				height = args.height;
				buttonWidth = args.buttonWidth;
				YAHOO.util.Event.onContentReady('scroll-content', setup);
				}
			}
 
			return pub;
			}();
			
			
				
			var args = {
 				width : '900',
 				contentWidth : '1300',
 				height : '22',
 				target : 'scroller',
				step : '100',
 				buttonWidth : '36'
				}
 
			var myScroller = HorizontalScroller.initScroll(args);	
			
			
			
            //Handle the click event on the cal box at the bottom
           
			
        }
    });
    //Call insert, only choosing the JS files, so the skin doesn't over write my custom css
    loader.insert({}, 'js');
})();
/*
<div id="scroller-container">
	<div id="move-left" ><img src="yui/images/lion_left.png" /></div>
	<div id="scroller">
		<div id="scroll-content">
			<p><span id="scroller_tm">NAViTRAD 1.0 &copy;</span>&nbsp;|&nbsp;Ses:<span id="scroller_session"></span>&nbsp;|&nbsp;Date:<span id="scroller_date"></span>&nbsp;|&nbsp;Sec:<span id="scroller_isin"></span>&nbsp;|&nbsp;Phase:<span id="scroller_phase"></span>&nbsp;|&nbsp;Price:<span id="scroller_price"></span></p> 
		</div>
	</div>
	<div id="move-right"></div>
	</div>*/