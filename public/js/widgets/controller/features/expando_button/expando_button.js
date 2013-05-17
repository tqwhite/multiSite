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
				{name:'expandedButtonClassName'},
				{name:'contentSelector'}

			],
			source:this.constructor._fullName
		});

		
		this.buttonObj=$(this.buttonSelector).click(this.callback('expandHandler'));

		this.expandToggle='shrunk';

		//this.element.html("//widgets/controller/features/expando_button/views/init.ejs",{
		//message: "Hello World"
		//});
	},

update:function(control, parameter){
	switch(control){
		case 'killEmptyButtons':
			this.killEmptyButton();
			break;
		default:
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.init(this.element, this.options);
			break;
	}
},
	
	expandHandler:function(eventObj){
		var target=$(eventObj.target)
			parent=target.parent()
			buttonHeight=$(target).height(),
			content=$(this.contentSelector);
	
		this.addSpacer(parent, buttonHeight);
		
		if (this.expandToggle=='shrunk'){
			this.shrunkDisplayState=content.css('display');
			
			content.css({overflow:'visible'});
			if (this.shrunkDisplayState=='none'){
				content.show();
			}
			
			target.removeClass(this.expandedButtonClassName).addClass(this.shrunkButtonClassName);
			this.origHeight=$(target).parent().outerHeight();
			
			var expandedHeight=this.getExpandedHeight(parent),
				parentHeight=buttonHeight+expandedHeight; //I don't know why, but it needs the extra button height
			
			this.duration=((expandedHeight-this.origHeight)/150)*500;
			target.parent().animate({height:parentHeight}, this.duration);
			
			this.expandToggle='expanded';
		}
		else{

//hackery note: this.duration will refer to whatever the last expand button set, 
//could result in faster or slower than you want for this one
//no instances of multiple buttons per page, non-critical consequence when it happens, fix it then

			$(target).removeClass(this.shrunkButtonClassName).addClass(this.expandedButtonClassName);
			target.parent().animate({height:this.origHeight}, this.duration, function(){
			content.css({overflow:'hidden'}).css('display', this.shrunkDisplayState)
			}); 
			;
			this.expandToggle='shrunk';
		}
		
		this.turnOffClicksForAwhile(1000);
	},

addSpacer:function(parent, buttonHeight){
	if (!this.alreadySpaced){
		$(parent).append("<div style='height:"+buttonHeight+"px;'>&nbsp;</div>");
	}
	this.alreadySpace=true;

},

getExpandedHeight:function(domObj){

	var currHeight=$(domObj).outerHeight();
	$(domObj).css({height:'100%'}); //for some happy reason, this does not display to user
	var expandedHeight=$(domObj).height();
	$(domObj).css({height:currHeight});
	return expandedHeight;

},

killEmptyButton:function(){
		var contentSelector=this.contentSelector; //closure for .each's function
		
		this.buttonObj.each(function(inx, buttonObj){
			buttonObj=$(buttonObj);
			var parent=buttonObj.parent(),
				content=parent.find(contentSelector);
			
				if (content.text()===''){
					buttonObj.hide();
				}
		
		});

},

showButtonsWithContent:function(){
		var contentSelector=this.contentSelector; //closure for .each's function
		
		this.buttonObj.each(function(inx, buttonObj){
			buttonObj=$(buttonObj);
			var parent=buttonObj.parent(),
				content=parent.find(contentSelector);
			
				if (content.text()!==''){
					buttonObj.show();
				}
		
		});

}

})

});