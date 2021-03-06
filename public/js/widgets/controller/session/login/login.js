steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Session.Login
 */
Widgets.Controller.Base.extend('Widgets.Controller.Session.Login',
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

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}
	if (options.newUsername){this.newUsername=options.newUsername;}

	this.initDisplay();

},

update:function(){
	this.init();
},

initDisplayProperties:function(){

	nameArray=[];

	name='status'; nameArray.push({name:name});
	name='saveButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	name='registerButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	name='forgotButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.enterKeyEnabled=false;
},

initDisplay:function(inData){

	var html=$.View('//widgets/controller/session/login/views/init.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				newUsername:this.newUsername,
				userName:Widgets.Models.LocalStorage.getCookieData(Widgets.Models.LocalStorage.getConstant('loginCookieName')).data
			}
		})
		);
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){
	this.displayParameters.saveButton.domObj=$('#'+this.displayParameters.saveButton.divId);
	this.displayParameters.registerButton.domObj=$('#'+this.displayParameters.registerButton.divId);
	this.displayParameters.forgotButton.domObj=$('#'+this.displayParameters.forgotButton.divId);

			this.displayParameters.saveButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'basicReady'},
				hover:{classs:'basicHover'},
				clicked:{classs:'basicActive'},
				unavailable:{classs:'basicUnavailable'},
				accessFunction:this.displayParameters.saveButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div style='margin-top:5px;'>Login</div>"
			});

			this.displayParameters.registerButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'smallReady'},
				hover:{classs:'smallHover'},
				clicked:{classs:'smallActive'},
				unavailable:{classs:'smallUnavailable'},
				accessFunction:this.displayParameters.registerButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div >New Customer</div>"
			});
/*
			this.displayParameters.forgotButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'smallReady'},
				hover:{classs:'smallHover'},
				clicked:{classs:'smallActive'},
				unavailable:{classs:'smallUnavailable'},
				accessFunction:this.displayParameters.forgotButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div style='text-decoration:line-through;'>Forgot Password</div>"
			});
*/
			if (this.initialStatusMessage){
				$('#'+this.displayParameters.status.divId).html(this.initialStatusMessage).removeClass('bad').addClass('good');
			}


			this.setupEnterKey(this.displayParameters.saveButton.handler);
},

//BUTTON HANDLERS =========================================================================================================


saveButtonHandler:function(control, parameter){
	var componentName='saveButton';
	if (control.which=='13'){control='click';}; //enter key
	switch(control){
		case 'click':
			Widgets.Models.Session.login(this.element.formParams(), this.callback('resetAfterSave'));
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

	if (inData.status>0){
		Widgets.Models.Session.keep('user', inData.data);
		$('#'+this.displayParameters.status.divId).html("Welcome back, "+inData.data.firstName+" <span style=color:gray;font-size:6pt'>("+inData.data.school+")</span>").removeClass('bad').addClass('good');
		Widgets.Models.LocalStorage.setCookie(Widgets.Models.LocalStorage.getConstant('loginCookieName'), inData.data.userName);
		this.element.html("<div style='margin-left:450px;margin-top:200px;' id='spinner'></div>").widgets_customer_dashboard();


	var opts={
	  lines: 7, // The number of lines to draw
	  length: 20, // The length of each line
	  width: 4, // The line thickness
	  radius: 10, // The radius of the inner circle
	  color: '#436235', // #rbg or #rrggbb
	  speed: 1, // Rounds per second
	  trail: 60, // Afterglow percentage
	  shadow: true // Whether to render a shadow
	};

	var spinner = new Spinner(opts).spin();
	$('#spinner').append(spinner.el);

	}
	else{
		switch (inData.status.toString()){
			case '-2':

					var outMessage='';
					for (var i=0, len=inData.messages.length; i<len; i++){
						outMessage+=inData.messages[i]+'<br/>';
					}
				$('#'+this.displayParameters.status.divId).html(outMessage).removeClass('good').addClass('bad');

				break;
			default:
				$('#'+this.displayParameters.status.divId).html("Invalid User Name/Password").removeClass('good').addClass('bad');

				break;


		}
	}
},



registerButtonHandler:function(control, parameter){
	var componentName='registerButton';
	switch(control){
		case 'click':

		this.element.widgets_session_register();

		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

forgotButtonHandler:function(control, parameter){
	var componentName='forgotButton';
	switch(control){
		case 'click':

		alert("Sorry, we've not had time to get this working. Please call us and we will help you out.");

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
}



})

});