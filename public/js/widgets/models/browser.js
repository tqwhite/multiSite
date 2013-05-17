steal('jquery/model', function(){

/**
 * @class Widgets.Models.Browser
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend Browser services.
 */
Widgets.Models.Base.extend('Widgets.Models.Browser',
/* @Static */
{

	windowSize:function(){
	
		return {height:$(window).height(), width:$(window).width()};
	
	}

},
/* @Prototype */
{});

})