steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Features.ExpandoButton
 */
Widgets.Controller.Base.extend('Widgets.Controller.Features.ExpandoButton',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(el, options){
	
//this will cause the parent of any button matched to expand/contract
//works for multiple button/container sets per invocation

		qtools.validateProperties({
			targetObject:options,
			targetScope: this, //will add listed items to targetScope
			propList:[
				{name:'buttonSelector'},
				{name:'shrunkButtonClassName'},
				{name:'expandedButtonClassName'}

			],
			source:this.constructor._fullName
		});

		
		this.buttonObj=$(this.buttonSelector).click(this.callback('expandHandler'));

		this.expandToggle='shrunk';

		//this.element.html("//widgets/controller/features/expando_button/views/init.ejs",{
		//message: "Hello World"
		//});
	},
	
	expandHandler:function(eventObj){
		var target=$(eventObj.target)
			parent=target.parent();

		if (this.expandToggle=='shrunk'){
			target.removeClass(this.expandedButtonClassName).addClass(this.shrunkButtonClassName);
			this.origHeight=target.parent().outerHeight();
			
			var expandedHeight=this.getExpandedHeight(parent);
			
			parent.animate({height:expandedHeight}, 500);
			this.expandToggle='expanded';
		}
		else{
			target.removeClass(this.shrunkButtonClassName).addClass(this.expandedButtonClassName);
			parent.animate({height:this.origHeight}, 500);
			this.expandToggle='shrunk';
		}
		
		this.turnOffClicksForAwhile(1000);
	},

getExpandedHeight:function(domObj){

	var currHeight=domObj.height();
	domObj.css({height:'100%'}); //for some happy reason, this does not display to user
	var expandedHeight=domObj.height();
	domObj.css({height:currHeight});
	return expandedHeight;

}
})

});