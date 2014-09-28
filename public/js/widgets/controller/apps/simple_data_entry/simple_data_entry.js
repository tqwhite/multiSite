steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Apps.SimpleDataEntry
 */
Widgets.Controller.Base.extend('Widgets.Controller.Apps.SimpleDataEntry',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

//
//dependency phrase: widgets/controller/apps/simple_data_entry/simple_data_entry.js
//

init : function(element, options){
	this.baseInits();
	this.element=$(element);


	qtools.validateProperties({
		targetObject:options,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'saveButtonSelector'}
		],
		source:this.constructor._fullName
 	});



	this.initControlProperties();
	this.initDisplayProperties();
	this.initDisplay({});

	
	return this;
},

update:function(control, parameter){
	switch(control){
		case 'hide':
			this.element.hide();
			break;
		case 'show':
			this.element.show();
			break;
		default:
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.init(this.element, this.options);
			break;
		case 'setAccessFunction':
			if (!this.employer){this.employer={};}
			this.employer.accessFunction=parameter;
		break;
	}
	
	return this;
},

initDisplayProperties:function(){

	nameArray=[];

	name='contentContainer'; nameArray.push({name:name});
	name='saveButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();

},

initDisplay:function(inData){

//in multiSite, the html is specified by the user, leaving this code in case I want to inject something later
// 	var html=$.View("//widgets/controller/apps/tmp/views/init.ejs",
// 		$.extend(inData, {
// 			displayParameters:this.displayParameters,
// 			viewHelper:this.viewHelper,
// 			formData:{
// 				message:'Widgets.Controller.Apps.Tmp'
// 			}
// 		})
// 		);
// 		
// 	this.element.html(html);

	this.initDomElements();
},

initDomElements:function(){

	this.initRefId();

	var displayItem=this.displayParameters.saveButton;
	
	$('#'+displayItem.divId).click(displayItem.handler);
	
	var retrievedLabel=$(this.saveButtonSelector).text(); //for this use, I want the label to be specified by the html
	
	var name='saveButton', displayItem=this.displayParameters[name]; 
		displayItem['controllerName']='widgets_tools_ui_button2';
		displayItem.domObj=$(this.saveButtonSelector)
			[displayItem.controllerName]({
				ready:{classs:'basicReady'},
				hover:{classs:'basicHover'},
				clicked:{classs:'basicActive'},
				unavailable:{classs:'basicUnavailable'},
				accessFunction:displayItem.handler,
				initialControl:'setToReady', //initialControl:'setUnavailable'
				label:retrievedLabel
			})
			.addClass('basicButton');

	this.element.find('input').qprompt();

},

//BUTTON HANDLERS =========================================================================================================

saveButtonHandler:function(control, parameter){
	var componentName='saveButton',
		displayItem=this.displayParameters[componentName];
		
	if (control.which=='13'){control='click';}; //enter key
	
	switch(control.type || control){
		case 'click': if (this.isAcceptingClicks()){this.turnOffClicksForAwhile(200);} else{eventObj.preventDefault(); return;}
			
			var formParams=this.element.formParams();
			//models.simpleData(formParams, this.callback(this.saveStatusCallback));

		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

//APPLICATION SPECIFIC =========================================================================================================

initRefId:function(){
	var params=this.element.formParams();
	if (!params.refId){
		this.element.append("<input type='hidden' name='refId' value='"+qtools.newGuid()+"'>");
	}
},

saveStatusCallback:function(status){
	console.dir(status);
}

}) //end of method-containing object ===

});