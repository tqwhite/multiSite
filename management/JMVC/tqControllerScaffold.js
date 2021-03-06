steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class GoodEarthStore.Controller.Admin.User
 */
GoodEarthStore.Controller.Base.extend('<%= name %>',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

/*

This works with tqViewScaffold, 5.16.15
It has all of the JMVC organization TQ likes and create one working button.
*/

init: function(el, options) {
	this.baseInits();
	this.thisObj=el;
	this.directory="//<%= path %>/<%= underscore %>/views/init.ejs";

	qtools.validateProperties({
		targetObject:options || {},
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'loginUser'}
		],
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}

	this.initDisplay();

},

update:function(){
	this.init();
},

initDisplayProperties:function(){

	nameArray=[];

	name='status'; nameArray.push({name:name});
	name='saveButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	
	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
},

initDisplay:function(inData){

	var html=$.View(this.directory+'views/init.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				message:"Time for the good stuff",
				source:this.constructor._fullName
			}
		})
		);
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){

	this.displayParameters.saveButton.domObj=$('#'+this.displayParameters.saveButton.divId);

	this.displayParameters.saveButton.domObj.good_earth_store_tools_ui_button2({
		ready:{classs:'basicReady'},
		hover:{classs:'basicHover'},
		clicked:{classs:'basicActive'},
		unavailable:{classs:'basicUnavailable'},
		accessFunction:this.displayParameters.saveButton.handler,
		initialControl:'setToReady', //initialControl:'setUnavailable'
		label:"Save"
	});

},


saveButtonHandler:function(control, parameter){
	var componentName='saveButton';
	switch(control){
		case 'click':

			if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			else{return;}
			
			//obviously, this is a dummy call to prove the button works.
			this.catchSave(); //GoodEarthStore.Models.Session.logout({}, this.callback('catchSave'));
			
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

catchSave:function(){
	//window.location.reload()
	alert('save happened');
}

})

});