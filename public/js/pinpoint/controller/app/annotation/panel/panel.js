steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Pinpoint.Controller.App.Annotation.Panel
 */
Pinpoint.Controller.Base.extend('Pinpoint.Controller.App.Annotation.Panel',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(){
		this.element.html("//pinpoint/controller/app/annotation/panel/views/init.ejs",{
			message: "Hello World!!"
		});
	}
})

});