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
		
		this.options=options;
		
		this.buttonObj=$(this.options.buttonSelector).click(this.callback('expandHandler'));

		this.expandToggle='shrunk';

// 		this.element.html("//widgets/controller/features/expando_button/views/init.ejs",{
// 			message: "Hello World"
// 		});
	},
	
	expandHandler:function(eventObj){
		var target=$(eventObj.target)
			parent=target.parent();
AAA=parent;
BBB=this;
		if (this.expandToggle=='shrunk'){
			target.removeClass(this.options.expandedButtonClassName).addClass(this.options.shrunkButtonClassName);
			this.origHeight=target.parent().css('height');
			parent.css({height:'100%'}, 5000);
			this.expandToggle='expanded';
		}
		else{
			target.removeClass(this.options.shrunkButtonClassName).addClass(this.options.expandedButtonClassName);
			parent.css({height:this.origHeight}, 5000);
			this.expandToggle='shrunk';
		}
	}
})

});