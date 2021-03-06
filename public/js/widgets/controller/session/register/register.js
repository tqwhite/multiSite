steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/form.ejs', function($){

/**
 * @class Widgets.Controller.Session.Register
 */
Widgets.Controller.Base.extend('Widgets.Controller.Session.Register',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

init: function(el, options) {
	this.baseInits();
	this.initControlProperties();
	this.initDisplayProperties();
	if (options && options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}


	this.getReferenceData(this.callback('initDisplay'));

},

update:function(){
	this.init();
},

initDisplayProperties:function(){

	nameArray=[];

	name='status'; nameArray.push({name:name});
	name='saveButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	name='loginButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.enterKeyEnabled=false;
},

initDisplay:function(inData){

	var html=$.View('//widgets/controller/session/register/views/form.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper
		})
		);
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){
	this.displayParameters.saveButton.domObj=$('#'+this.displayParameters.saveButton.divId);
	this.displayParameters.loginButton.domObj=$('#'+this.displayParameters.loginButton.divId);

			this.displayParameters.saveButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'basicReady'},
				hover:{classs:'basicHover'},
				clicked:{classs:'basicActive'},
				unavailable:{classs:'basicUnavailable'},
				accessFunction:this.displayParameters.saveButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div style='margin-top:5px;'>Save</div>"
			});

			this.displayParameters.loginButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'smallReady'},
				hover:{classs:'smallHover'},
				clicked:{classs:'smallActive'},
				unavailable:{classs:'smallUnavailable'},
				accessFunction:this.displayParameters.loginButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div>Login Instead</div>"
			});

	$($('.schoolIdClassString').find('option')[1]).attr('selected', 'selected'); //for debugg only, see form.ejs
	this.element.find('input').qprompt();

	if (this.initialStatusMessage){
		$('#'+this.displayParameters.status.divId).html(this.initialStatusMessage).removeClass('bad').addClass('good');
	}

	this.setupEnterKey(this.displayParameters.saveButton.handler);

	this.element.find('input').qprompt();

},

//BUTTON HANDLERS =========================================================================================================


saveButtonHandler:function(control, parameter){
	var componentName='saveButton';
	if (control.which=='13'){control='click';}; //enter key
	switch(control){
		case 'click':

		Widgets.Models.User.register(this.element.formParams(), this.callback('resetAfterSave'));

		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

resetAfterSave:function(inData){
	var errorString=this.listMessages(inData.messages);
	if (inData.status<1){
		$('#'+this.displayParameters.status.divId).html(errorString).removeClass('good').addClass('bad');
	}
	else{
			var html=$.View('//widgets/controller/session/register/views/confirmEmail.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper
		})
		);
	this.element.html(html);
	}
},

loginButtonHandler:function(control, parameter){
	var componentName='loginButton';
	switch(control){
		case 'click':
			this.element.widgets_session_login();
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

//ENTER KEY =========================================================================================================

enableKeyManager:function(eventObj){
	if (eventObj.type=='focus'){
		this.disableEnterKey();
	}
	else{
		this.enableEnterKey();
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

enterKeyHandler:function(eventObj){
	if (this.fieldsAreValid() && eventObj.which==13){
		this.saveButtonHandler('click');
	}
},

disableEnterKey:function(){
	if (this.enterKeyEnabled){
		this.element.unbind('keydown');
		this.enterKeyEnabled=false;
	}
},

enableEnterKey:function(){
	if (!this.enterKeyEnabled && this.fieldsAreValid()){
		this.element.bind('keydown',this.callback('enterKeyHandler'));
		this.enterKeyEnabled=true;
	}
},

lastFieldHandler:function(eventObj){
	var firstFieldObj=$('#'+this.displayParameters.firstField.divId);
	if (eventObj.type=='blur'){
		qtools.timeoutProxy(function(){
			firstFieldObj.focus();
		}, 100);
	}
},

getReferenceData:function(callback){

callback();
return;
		var controlObj={
			calls:{
				schools:{
					ajaxFunction:Widgets.Models.School.getList,
					argData:{}
				}
			},
			success:this.callback('referenceCallback', callback),
			error:function(){alert('the server broke down');},
			stripWrappers:true

		};
		qtools.multiAjax(controlObj);

},

referenceCallback:function(callback, inData){

		this.schoolList=inData.schools
		callback();
}


})

});