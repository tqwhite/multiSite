steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Apps.Bookmark.Create
 */
Widgets.Controller.Base.extend('Widgets.Controller.Apps.Bookmark.Create',
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
			propList:[],
			source:this.constructor._fullName
		});
		
		
		this.element.html("//widgets/controller/apps/bookmark/create/views/init.ejs",{
			message: "Hello World"
		});
	}
})

});