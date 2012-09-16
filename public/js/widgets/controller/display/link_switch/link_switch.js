steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Display.LinkSwitch
 */
Widgets.Controller.Base.extend('Widgets.Controller.Display.LinkSwitch',
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
			{name:'controlButtonIdClassName'},
			{name:'switchablePanelClassName'}
		],
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initCurrentPanel();
	this.hideUnused();
	this.showOne(this.currentPanel);
	this.cycleButtonAppearance(this.currentPanel);
	this.initButtons();

},

initControlProperties:function(){
	this.panelList=$('.'+this.switchablePanelClassName, this.element);
	this.buttonDomList=$('.'+this.controlButtonIdClassName);
},

initCurrentPanel:function(){

	this.currentPanel=window.location.hash;
	if (this.currentPanel){
		this.currentPanel=this.currentPanel.replace(/^#/, '', this.currentPanel);
	}
	else{
		this.currentPanel=$(this.panelList[0]).attr('id');
	}
	return;
},

hideUnused: function(){
	this.panelList.hide();
},

showOne:function(panelId){
	this.hideUnused();
	$('#'+panelId, this.element).show();

},

initButtons:function(){

	this.buttonDomList.click(this.callback('buttonHandler'));
	this.initialButtonColor=$(this.buttonDomList[0]).css('color');

},

buttonHandler:function(eventObj, domObj){
	var currDomObj=$(eventObj.target),
		newId=currDomObj.attr('href');

	this.buttonDomList.css('color', this.initialButtonColor);
	currDomObj.css('color', 'gray');

	this.currentPanel=newId.replace(/$#/, '', newId);

	this.showOne(this.currentPanel);

	eventObj.stopPropagation();
	eventObj.preventDefault();
	return false;
},

cycleButtonAppearance:function(panelId){
	this.buttonDomList.css('color', this.initialButtonColor);
	$('[href=["'+panelId+'"]').css('color', 'gray');
}

})

});