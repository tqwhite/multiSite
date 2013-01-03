steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs','./views/showScript.ejs','./views/statusMessages.ejs', function($){

/**
 * @class Pinpoint.Controller.App.Signup.ShowWorkScript
 */
Pinpoint.Controller.Base.extend('Pinpoint.Controller.App.Signup.ShowWorkScript',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
init: function(el, options) {
	this.baseInits();

	qtools.validateProperties({
		targetObject:options,
		targetScope: this, //will add listed items to targetScope
		propList:[],
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}

	this.initDisplay();

},

update:function(options){
	this.init(this.element, options);
},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.errorStatus=false;
},

initDisplayProperties:function(){

	nameArray=[];

	name='status'; nameArray.push({name:name});
	name='entryContainer'; nameArray.push({name:name});

	name='submitButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	name='emailAdrInput'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initDisplay:function(inData){

	var html=$.View("//pinpoint/controller/app/signup/show_work_script/views/init.ejs",
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{}
		})
		);
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){
	var displayItem=this.displayParameters.submitButton;
	$('#'+displayItem.divId).pinpoint_tools_ui_button2({
		ready:{classs:'basicReady'},
		hover:{classs:'basicHover'},
		clicked:{classs:'basicActive'},
		unavailable:{classs:'basicUnavailable'},
		accessFunction:displayItem.handler,
		initialControl:'setUnavailable', //initialControl:'setUnavailable'
		label:"<div style='margin-top:8px;'>Submit</div>"
	});

	var displayItem=this.displayParameters.emailAdrInput;
	$('#'+displayItem.divId)
	.bind('keyup', displayItem.handler)
	.bind('change', displayItem.handler);

	this.statusDomObj=$('#'+this.displayParameters.status.divId);

	this.element.find('input').qprompt();

},

emailAdrInputHandler:function(control, parameter){
	var componentName='emailAdrInput';
	switch(control.type){
		case 'keyup':

			var errorList=Pinpoint.Models.Account.validate(
				this.element.formParams(),
				this.callback('catchProcessResult'));



			if (qtools.isEmpty(errorList)){
				this.submitButton.accessFunction('setToReady');
				if (this.errorStatus){
					this.statusDomObj.html('');
					this.errorStatus=false;
				}
			}
			else{
				this.submitButton.accessFunction('setUnavailable');
			}
		break;

		case 'change':

			var errorList=Pinpoint.Models.Account.validate(
				this.element.formParams()
			);

			if (qtools.isNotEmpty(errorList)){
				this.showStatusMessages(errorList);
			}

		break;

		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

submitButtonHandler:function(control, parameter){
	var componentName='submitButton';
	switch(control){
		case 'click':
			if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			else{return;}

			Pinpoint.Models.Account.register({
					data:this.element.formParams()
				},
				this.callback('catchProcessResult'));

		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

catchProcessResult:function(inData){
		var statusDomObj=$('#'+this.displayParameters.status.divId);
	if (inData.status<0){
		var errorList=inData.messages;
		this.showStatusMessages(errorList);
	}
	else{
		if (true){ //this can go away as soon as debugging is well into the past. 'false' makes it so that the payment process can run repeatedly.

			switch(inData.status.toString()){
				case '1':
					this.showScript();
					break;
// 				case '2':
// 					$('#'+this.displayParameters.submitButton.divId).remove();
// 					$('#'+this.displayParameters.cancelButton.divId).remove();
// 					$('#'+this.displayParameters.entryContainer.divId).html($.View('//good_earth_store/controller/customer/checkout/views/deferred.ejs'));
// 					break;
// 				case '3':
// 					statusDomObj.append("<div style=color:red;margin-left:4px;'>Repeat</div>");
// 					break;
// 				case '4':
// 					$('#'+this.displayParameters.submitButton.divId).remove();
// 					$('#'+this.displayParameters.cancelButton.divId).remove();
// 					$('#'+this.displayParameters.entryContainer.divId).html($.View('//good_earth_store/controller/customer/checkout/views/fr.ejs'));
// 					break;

					break;
			}


					}
	}
},

showScript:function(){

	var html=$.View("//pinpoint/controller/app/signup/show_work_script/views/showScript.ejs",
		$.extend({}, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{}
		})
		);
	this.element.html(html)
		.find('textarea').css({
			height:'100px',
			width:'400px',
			padding:'30px'
		});
	//this.initDomElements();
},

showStatusMessages:function(errorList){

	var html=$.View("//pinpoint/controller/app/signup/show_work_script/views/statusMessages.ejs",
		$.extend({}, {
			messageList:errorList,
			className:'bad'
		})
		);
	this.statusDomObj.html(html);
	this.errorStatus=true;
	//this.initDomElements();
}

})

});