steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class GoodEarthStore.Controller.Display.LinkSwitch
 */
$.Controller('GoodEarthStore.Controller.Display.LinkSwitch',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(){
		this.element.html("//good_earth_store/controller/display/link_switch/views/init.ejs",{
			message: "Hello World"
		});
	}
})

});