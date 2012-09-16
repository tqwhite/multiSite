steal( 'jquery/controller','jquery/view/ejs' )
.then(
'widgets/stylesheets/anythingslider/animate.css',
'widgets/stylesheets/anythingslider/anythingslider.css',
'widgets/stylesheets/anythingslider/theme-construction.css',
'widgets/stylesheets/anythingslider/theme-cs-portfolio.css',
'widgets/stylesheets/anythingslider/theme-metallic.css',
'widgets/stylesheets/anythingslider/theme-minimalist-round.css',
'widgets/stylesheets/anythingslider/theme-minimalist-square.css',

'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.fx.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.fx.min.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.min.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.video.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.anythingslider.video.min.js',
'widgets/resources/jqueryPlugins/anythingslider/jquery.easing.1.2.js',
'widgets/resources/jqueryPlugins/anythingslider/swfobject.js'
)
.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Display.Anythingslider
 */
Widgets.Controller.Base.extend('Widgets.Controller.Display.Anythingslider',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(){

		$('ul', this.element).anythingSlider({
				theme           : 'metallic',
				easing          : 'easeInOutSine',
				autoPlay:true,
				navigationFormatter : function(index, panel){
					return ['Slab', 'Parking Lot', 'Drive', 'Glorius Dawn', 'Bjork?', 'Traffic Circle'][index - 1];
				},
				onSlideComplete : function(slider){
				}
			});

		$('.panel').css({width:'800px', height:'500px'});
		qtools.timeoutProxy(function(){
			$('.anythingSlider').css({width:'800px', height:'500px'});
			$('#slider').css({width:'800px', height:'500px'});
		}, 100);

	}
})

});