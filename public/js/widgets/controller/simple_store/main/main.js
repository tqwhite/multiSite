steal( 'jquery/controller','jquery/view/ejs' )
.then(
	'widgets/stylesheets/simpleStore.css'
)
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.SimpleStore.Main
 */
Widgets.Controller.Base.extend('Widgets.Controller.SimpleStore.Main',
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
			{name:'serverData'}
		],
		source:this.constructor._fullName
 	});
	qtools.validateProperties({
		targetObject:this.serverData,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'productInfo'}
		],
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

initDisplayProperties:function(){

	nameArray=[];

	name='productListContainer'; nameArray.push({name:name});
	name='paymentFormContainer'; nameArray.push({name:name});

	name='infoDispatch'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.purchaseData={};
},

initDisplay:function(inData){

	var html=$.View("//widgets/controller/simple_store/main/views/init.ejs",
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

	name='productListContainer'; nameArray.push({name:name});
	name='paymentFormContainer'; nameArray.push({name:name});

	var displayItem=this.displayParameters.productListContainer;
	$('#'+displayItem.divId).widgets_simple_store_product_selector({
			productInfo:this.productInfo,
			purchaseData:this.purchaseData,
			infoDispatchHandler:this.displayParameters.infoDispatch.handler
	});

	var displayItem=this.displayParameters.paymentFormContainer;
	$('#'+displayItem.divId).widgets_simple_store_payment_form({
			productInfo:this.productInfopurchaseData,
			purchaseData:this.purchaseData,
			infoDispatchHandler:this.displayParameters.infoDispatch.handler
	});

},

infoDispatchHandler:function(control, parameter){
	var componentName='infoDispatch';
	switch(control){
		case 'changePurchaseData':
			console.dir(this.purchaseData);
			$('#'+this.displayParameters.paymentFormContainer.divId).widgets_simple_store_payment_form(control, parameter);
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
}

/*
submitButtonHandler:function(control, parameter){
	var componentName='submitButton';
	switch(control){
		case 'click':

			if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			else{return;}

			GoodEarthStore.Models.Purchase.process({
					cardData:this.element.formParams(),
					purchase:this.purchases,
					account:this.account
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


cancelButtonHandler:function(control, parameter){
	var componentName='cancelButton';
	switch(control){
		case 'click':

			if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			else{return;}

			this.dashboardContainer[this.returnClassName](this.returnClassOptions);
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
			statusDomObj.append("<div style=color:red;margin-left:4px;'>"+$message+"</div>");
		}
	}
	else{
		if (true){ //this can go away as soon as debugging is well into the past. 'false' makes it so that the payment process can run repeatedly.

			switch(inData.status.toString()){
				case '1':
					$('#'+this.displayParameters.submitButton.divId).remove();
					$('#'+this.displayParameters.cancelButton.divId).remove();
					$('#'+this.displayParameters.entryContainer.divId).html($.View('//good_earth_store/controller/customer/checkout/views/approved.ejs'));
					break;
				case '2':
					$('#'+this.displayParameters.submitButton.divId).remove();
					$('#'+this.displayParameters.cancelButton.divId).remove();
					$('#'+this.displayParameters.entryContainer.divId).html($.View('//good_earth_store/controller/customer/checkout/views/deferred.ejs'));
					break;
				case '3':
					statusDomObj.append("<div style=color:red;margin-left:4px;'>Repeat</div>");
					break;
				case '4':
					$('#'+this.displayParameters.submitButton.divId).remove();
					$('#'+this.displayParameters.cancelButton.divId).remove();
					$('#'+this.displayParameters.entryContainer.divId).html($.View('//good_earth_store/controller/customer/checkout/views/fr.ejs'));
					break;

					break;
			}


					}
	}
},
*/

})

});