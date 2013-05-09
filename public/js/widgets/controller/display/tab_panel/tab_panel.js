steal( 'jquery/controller','jquery/view/ejs' )
.then(
	'widgets/stylesheets/anythingslider/css/animate.css',
	'widgets/stylesheets/anythingslider/css/anythingslider.css',
	'widgets/stylesheets/anythingslider/css/theme-construction.css',
	'widgets/stylesheets/anythingslider/css/theme-cs-portfolio.css',
	'widgets/stylesheets/anythingslider/css/theme-metallic.css',
	'widgets/stylesheets/anythingslider/css/theme-minimalist-round.css',
	'widgets/stylesheets/anythingslider/css/theme-minimalist-square.css',

	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.fx.js',
//	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.fx.min.js',
	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.js',
//	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.min.js',
//	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.video.js',
//	'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.video.min.js',
	'widgets/resources/jqueryPlugins/anythingslider/jquery.easing.1.2.js',
	'widgets/resources/jqueryPlugins/anythingslider/swfobject.js'
)
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Display.TabPanel
 */
Widgets.Controller.Base.extend('Widgets.Controller.Display.TabPanel',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(el, options){

	qtools.validateProperties({
		targetObject:options,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'tabDisplayContainerIdName'},
			{name:'tabListIdName'},
			{name:'switchableContentListId'}
		],
		source:this.constructor._fullName
 	});

 	this.tabListSourceDomObj=$('#'+this.tabListIdName);
	var controlsDomObj=$('#'+this.tabDisplayContainerIdName);

	$('#'+this.switchableContentListId).anythingSlider({

    // *********** Appearance ***********
    // Theme name; choose from: minimalist-round, minimalist-square,
    // metallic, construction, cs-portfolio
    theme: 'default',
    // Set mode to "horizontal", "vertical" or "fade"
    // (only first letter needed); replaces vertical option
    mode: 'horizontal',
    // If true, the entire slider will expand to fit the parent element
    expand: false,
    // If true, solitary images/objects in the panel will expand to
    // fit the viewport
    resizeContents: true,
    // Set this value to a number and it will show that many slides at once
    showMultiple: false,
    // Anything other than "linear" or "swing" requires the easing plugin
    easing: "swing",

    // If true, builds the forwards and backwards buttons
    buildArrows: true, //tqii
    // If true, builds a list of anchor links to link to each panel
    buildNavigation: true,
    // If true, builds the start/stop button
    buildStartStop: false,

    // Append forward arrow to a HTML element
    // (jQuery Object, selector or HTMLNode), if not null
    appendFowardTo: null,
    // Append back arrow to a HTML element
    // (jQuery Object, selector or HTMLNode), if not null
    appendBackTo: null,
    // Append controls (navigation + start-stop) to a HTML element
    // (jQuery Object, selector or HTMLNode), if not null
    appendControlsTo: controlsDomObj,
    // Append navigation buttons to a HTML element
    // (jQuery Object, selector or HTMLNode), if not null
    appendNavigationTo: null,
    // Append start-stop button to a HTML element
    // (jQuery Object, selector or HTMLNode), if not null
    appendStartStopTo: null,

    // If true, side navigation arrows will slide out on
    // hovering & hide @ other times
    toggleArrows: false,
    // if true, slide in controls (navigation + play/stop button)
    // on hover and slide change, hide @ other times
    toggleControls: false,

    // Start button text
    startText: "Start",
    // Stop button text
    stopText: "Stop",
    // Link text used to move the slider forward
    // (hidden by CSS, replaced with arrow image)
    forwardText: "&raquo;",
    // Link text used to move the slider back
    // (hidden by CSS, replace with arrow image)
    backText: "&laquo;",
    // Class added to navigation & start/stop button
    // (text copied to title if it is hidden by a negative text indent)
    tooltipClass: 'tooltip',

    // if false, arrows will be visible, but not clickable.
    enableArrows: true,
    // if false, navigation links will still be visible, but not clickable.
    enableNavigation: true,
    // if false, the play/stop button will still be visible, but not
    // clickable. Previously "enablePlay"
    enableStartStop: true,
    // if false, keyboard arrow keys will not work for this slider.
    enableKeyboard: true,

    // *********** Navigation ***********
    // This sets the initial panel
    startPanel: 1,
    // Amount to go forward or back when changing panels.
    changeBy: 1,
    // Should links change the hashtag in the URL?
    hashTags: true,
    // if false, the slider will not wrap
    infiniteSlides: true,
    // Details at the top of the file on this use (advanced use)
    navigationFormatter: this.callback('formatTabs'),
    // Set this to the maximum number of visible navigation tabs;
    // false to disable
    navigationSize: false,

    // *********** Slideshow options ***********
    // If true, the slideshow will start running; replaces "startStopped" option
    autoPlay: false,
    // If true, user changing slides will not stop the slideshow
    autoPlayLocked: false,
    // If true, starting a slideshow will delay advancing slides; if false, the slider will immediately advance to the next slide when slideshow starts
    autoPlayDelayed: false,
    // If true & the slideshow is active, the slideshow will pause on hover
    pauseOnHover: true,
    // If true & the slideshow is active, the  slideshow will stop on the last page. This also stops the rewind effect  when infiniteSlides is false.
    stopAtEnd: false,
    // If true, the slideshow will move right-to-left
    playRtl: false,

    // *********** Times ***********
    // How long between slideshow transitions in AutoPlay mode (in milliseconds)
    delay: 5000, //tqii, was 3000
    // Resume slideshow after user interaction, only if autoplayLocked is true (in milliseconds).
    resumeDelay: 10000, //tqii, was 15000
    // How long the slideshow transition takes (in milliseconds)
    animationTime: 600,
    // How long to pause slide animation before going to the desired slide (used if you want your "out" FX to show).
    delayBeforeAnimate  : 0,

    // *********** Callbacks ***********
    // Callback before the plugin initializes
    onBeforeInitialize: function(e, slider) {},
    // Callback when the plugin finished initializing
    onInitialized: function(e, slider) {},
    // Callback on slideshow start
    onShowStart: function(e, slider) {},
    // Callback after slideshow stops
    onShowStop: function(e, slider) {},
    // Callback when slideshow pauses
    onShowPause: function(e, slider) {},
    // Callback when slideshow unpauses - may not trigger
    // properly if user clicks on any controls
    onShowUnpause: function(e, slider) {},
    // Callback when slide initiates, before control animation
    onSlideInit: function(e, slider) {},
    // Callback before slide animates
    onSlideBegin: function(e, slider) {},
    // Callback when slide completes - no event variable!
    onSlideComplete: function(slider) {},

    // *********** Interactivity ***********
    // Event used to activate forward arrow functionality
    // (e.g. add jQuery mobile's "swiperight")
    clickForwardArrow: "click",
    // Event used to activate back arrow functionality
    // (e.g. add jQuery mobile's "swipeleft")
    clickBackArrow: "click",
    // Events used to activate navigation control functionality
    clickControls: "click focusin",
    // Event used to activate slideshow play/stop button
    clickSlideshow: "click",

    // *********** Video ***********
    // If true & the slideshow is active & a youtube video
    // is playing, it will pause the autoplay until the video
    // is complete
    resumeOnVideoEnd: true,
    // If true the video will resume playing (if previously
    // paused, except for YouTube iframe - known issue);
    // if false, the video remains paused.
    resumeOnVisible: true,
    // If your slider has an embedded object, the script will
    // automatically add a wmode parameter with this setting
    addWmodeToObject: "opaque",
    // return true if video is playing or false if not - used
    // by video extension
    isVideoPlaying: function(base) {
        return false;
    }


});


	$('.anythingSlider').css('padding', '0px');

	//thanks: http://jsfiddle.net/Mottie/VM8XG/2952/
	var thiss=this;
	$('#'+thiss.switchableContentListId+' li a').click(function(){
		var h = this.href, s
		prefix='#&panel1-',
		pattern=new RegExp(prefix+'\\d');
		// external links will fall through and go to the external url
		if (pattern.test(this.href)) {
			s = h.substring( h.lastIndexOf(prefix) + prefix.length, h.length );
			$('#'+thiss.switchableContentListId).anythingSlider(s);
			return false;
		}


		var h = this.href, s,
		prefix='#id=',
		pattern=new RegExp(prefix+'.*');
		// external links will fall through and go to the external url
		if (pattern.test(this.href)) {
			s = h.substring( h.lastIndexOf(prefix) + prefix.length, h.length );


			var slider=$('#'+thiss.switchableContentListId);

			var target = slider.find('#'+s).closest('.panel');

			var plugin = slider.data('AnythingSlider');


			var index=plugin.$items.index( target );



			$('#'+thiss.switchableContentListId).anythingSlider(index);
			return false;
		}



	});


		var h = window.location.href, s,
		prefix='#id=',
		pattern=new RegExp(prefix+'.*');
		// external links will fall through and go to the external url
		if (pattern.test(window.location.href)) {
			s = h.substring( h.lastIndexOf(prefix) + prefix.length, h.length );


			var slider=$('#'+this.switchableContentListId);

			var target = slider.find('#'+s).closest('.panel');

			var plugin = slider.data('AnythingSlider');


			var index=plugin.$items.index( target );



			$('#'+this.switchableContentListId).anythingSlider(index);
		}



		var h = window.location.href, s,
		prefix='/?id=',
		pattern=new RegExp(prefix+'.*');
		prefix2='?id=',
		pattern2=new RegExp(prefix+'.*');
		// external links will fall through and go to the external url
		if (pattern.test(window.location.href) || pattern2.test(window.location.href)) {
			if (pattern.test(window.location.href)){
			s = h.substring( h.lastIndexOf(prefix) + prefix.length, h.length );
			}
			else{
			s = h.substring( h.lastIndexOf(prefix2) + prefix2.length, h.length );
			}


			var slider=$('#'+this.switchableContentListId);

			var target = slider.find('#'+s).closest('.panel');

			var plugin = slider.data('AnythingSlider');


			var index=plugin.$items.index( target );



			$('#'+this.switchableContentListId).anythingSlider(index);
		}



	},

	formatTabs:function(index, panel) {
		var id=panel.attr('id'),
			panelItem=this.tabListSourceDomObj.find('[href="#'+id+'"]'),
			labelText=panelItem.text().replace(' ', '&nbsp;');
        // This is the default format (show just the panel index number)
        return labelText+' '; //the appended space allows the buttons to wrap into multiple lines
    }
})

});