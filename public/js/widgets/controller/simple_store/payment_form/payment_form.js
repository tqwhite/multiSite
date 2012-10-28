steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.SimpleStore.PaymentForm
 */
Widgets.Controller.Base.extend('Widgets.Controller.SimpleStore.PaymentForm',
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
		propList:[
			{name:'paymentServerUrl'},
			{name:'purchaseData'},
			{name:'infoDispatchHandler'}
		],
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}

	this.initDisplay();

},

update:function(control, parameter){
	var componentName='infoDispatch';
	switch(control){
		case 'changePurchaseData':
			$('#'+this.displayParameters.chargePrice.divId).html('$'+this.purchaseData.subtotal);
			if(this.purchaseData.subtotal>0){
				this.submitButton.accessFunction('setToReady');
			}
			else{
				this.submitButton.accessFunction('setUnavailable');
			}
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
		default:
			this.init(this.element, options);
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

initDisplayProperties:function(){

	nameArray=[];

	name='chargePrice'; nameArray.push({name:name});
	name='status'; nameArray.push({name:name});

	name='submitButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
},

initDisplay:function(inData){

	var html=$.View("//widgets/controller/simple_store/payment_form/views/init.ejs",
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
	$('#'+displayItem.divId).widgets_tools_ui_button2({
		ready:{classs:'submitButtonReady'},
		hover:{classs:'submitButtonHover'},
		clicked:{classs:'submitButtonActive'},
		unavailable:{classs:'submitButtonUnavailable'},
		accessFunction:displayItem.handler,
		initialControl:'setUnavailable', //initialControl:'setUnavailable'
		label:"<div style='margin-top:8px;'>Purchase</div>"
	});


//	this.element.find('input').qprompt();

},


submitButtonHandler:function(control, parameter){
	var componentName='submitButton';
	switch(control){
		case 'click':

			if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			else{return;}

			var formParams=this.element.formParams();
			this.purchaseData.cardData=formParams;

			Widgets.Models.Purchase.process({
					paymentServerUrl:this.paymentServerUrl,
					cardData:formParams,
					purchaseData:this.purchaseData
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
		statusDomObj.html('');
		var list=inData.messages;
		for (var i=0, len=list.length; i<len; i++){
			var element=list[i];
			$message=element[1]?element[1]:'Unknown processor error, contact tech support'; //element[0] is fieldname or category, element[1] is message
			statusDomObj.append("<span style='margin-left:4px;'>&bull; "+$message+"</span>");
		}
		statusDomObj.addClass('badStatus');
	}
	else{
		if (true){ //this can go away as soon as debugging is well into the past. 'false' makes it so that the payment process can run repeatedly.

			var cardNo=this.purchaseData.cardData.cardNumber,
				len=cardNo.length;

			cardNo=cardNo.substring(len-4, len);
			this.purchaseData.cardData.cardNumber=cardNo;

			switch(inData.status.toString()){
				case '1':
					this.infoDispatchHandler('displayCompletion');
					break;
			}


					}
	}
}


})

});