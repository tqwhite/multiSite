steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Features.ContactForm
 */
Widgets.Controller.Base.extend('Widgets.Controller.Features.ContactForm',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(el, options){
		this.baseInits();

		qtools.validateProperties({
			targetObject:options,
			targetScope: this, //will add listed items to targetScope
			propList:[
				{name:'event'},
				{name:'parameterFileName'},
				{name:'calculatedFormData', optional:true}
			],
			source:this.constructor._fullName
		});
	
		this.initControlProperties();
		this.initDisplayProperties();
		this.initDisplay({});			
	
	},

	update:function(options){
		this.init(this.element, options);
	},

	initDisplayProperties:function(){

		nameArray=[];

		name='status'; nameArray.push({name:name});
		name='saveButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
		name='closeButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

		this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

	},

	initControlProperties:function(){
		this.viewHelper=new viewHelper2();
		this.enterKeyEnabled=false;
		
		this.serverData=Widgets.Models.Session.get('serverData');		

		qtools.validateProperties({
			targetObject:this.serverData, targetScope: this, //will add listed items to targetScope
			propList:[{name:'parameters'}], source:this.constructor._fullName, showAlertFlag:true
		});
		qtools.validateProperties({
			targetObject:this.serverData['parameters'], targetScope: this, //will add listed items to targetScope
			propList:[{name:this.parameterFileName}, {name:'processContentSourceRouteName'}], source:this.constructor._fullName, showAlertFlag:true
		});
	
		this.clickedButtonObj=$(this.event.target);
		var	formName=qtools.findAttributeInParents(this.clickedButtonObj, 'formName');
		
		if (this.serverData.parameters[this.parameterFileName][formName]){
			this.formParameters=this.serverData.parameters[this.parameterFileName][formName];
		
		}
		else{
			alert('_PARAMETERS/"+this.parameterFileName+" contains no spec for :'+formName+" in either the site or page directory");
		}
		
		if (typeof(this.calculatedFormData)=='undefined'){ this.calculatedFormData={};}
	},

initDisplay:function(inData){
	
	var formData=qtools.mergeRecursive(this.calculatedFormData, this.formParameters);

	var html=$.View('//widgets/controller/features/contact_form/views/init.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:formData,
			message:'hip hooray'
		})
		);
	this.displayPanel=$(html);
 	this.element.append(this.displayPanel);
 	this.initDomElements();
},

initDomElements:function(){

	var posX=this.event.pageX,
		posY=this.event.pageY,
		height=this.displayPanel.height(),
		width=this.displayPanel.width(),
		topp=posY-height,
		leftt=posX-width;
		
		topp=(topp>0)?topp:100;
		leftt=(leftt>0)?leftt:100;

	this.displayPanel.css({
		position:'absolute',
		top:topp, //for some reason, 'top' and 'left' do not work with natural spelling
		left:leftt,
		'z-index':1000
	});

	this.displayParameters.saveButton.domObj=$("<div class='basicButton' style='position:absolute;right:10px;bottom:10px;'></div>");

			this.displayParameters.saveButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'basicReady'},
				hover:{classs:'basicHover'},
				clicked:{classs:'basicActive'},
				unavailable:{classs:'basicUnavailable'},
				accessFunction:this.displayParameters.saveButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div style='margin-top:5px;'>Send</div>"
			});
			
	this.displayPanel.append(this.displayParameters.saveButton.domObj);

	this.displayParameters.closeButton.domObj=$("<div class='closeCircleReady' style='position:absolute;top:5px;right:5px;'></div>");

			this.displayParameters.closeButton.domObj.widgets_tools_ui_button2({
				ready:{classs:'closeCircleReady'},
				hover:{classs:'closeCircleHover'},
				clicked:{classs:'closeCircleReady'},
				unavailable:{classs:'closeCircleUnavailable'},
				accessFunction:this.displayParameters.closeButton.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:"<div style='margin-top:5px;'>&nbsp;</div>"
			});
			
	this.displayPanel.append(this.displayParameters.closeButton.domObj);

//	this.element.find('input').qprompt();

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
		
		var buttonId=this.clickedButtonObj.attr('buttonId');

		Widgets.Models.Email.send({
			formParams:$.extend(this.displayPanel.formParams(), {buttonId:buttonId}),
			mailParams:this.formParameters,
			serverManagement:{processContentSourceRouteName:this.processContentSourceRouteName}
			
			}, this.callback('resetAfterSave'));

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
		
		var html=$.View('//widgets/controller/features/contact_form/views/confirmEmail.ejs',
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper
		})
		);
		html="<div class='bad'>"+errorString+"</div>";
		this.displayPanel.prepend(html).css({overflow:'scroll'});
	
	}
	else{
		var html=$.View('//widgets/controller/features/contact_form/views/confirmEmail.ejs', {});
		this.displayPanel.html(html);
		
		var thiss=this;
		this.displayPanel.fadeOut(
		3000,
		function(){
			thiss.displayPanel.remove();
		});
	}
},

closeButtonHandler:function(control, parameter){
	var componentName='saveButton';
	//if (control.which=='13'){control='click';}; //enter key
	switch(control){
		case 'click':

		this.displayPanel.remove();
		
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
}







})

});