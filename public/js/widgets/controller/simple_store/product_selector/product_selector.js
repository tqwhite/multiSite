steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs','./views/priceDisplay.ejs', function($){

/**
 * @class Widgets.Controller.SimpleStore.ProductSelector
 */
Widgets.Controller.Base.extend('Widgets.Controller.SimpleStore.ProductSelector',
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
			{name:'productInfo'},
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

update:function(options){
	this.init(this.element, options);
},

initDisplayProperties:function(){

	nameArray=[];

	name='priceDisplayContainer'; nameArray.push({name:name});

//	name='cancelButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.totalPrice=0;
},

initDisplay:function(inData){

	var html=$.View("//widgets/controller/simple_store/product_selector/views/init.ejs",
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				productInfo:this.productInfo
			}
		})
		);
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){

	$('input', this.element)
		.bind('keypress', this.callback('keypressHandler'))
		.bind('keyup', this.callback('inputHandler'));

// 	var displayItem=this.displayParameters.submitButton;
// 	$('#'+displayItem.divId).good_earth_store_tools_ui_button2({
// 		ready:{classs:'basicReady'},
// 		hover:{classs:'basicHover'},
// 		clicked:{classs:'basicActive'},
// 		unavailable:{classs:'basicUnavailable'},
// 		accessFunction:displayItem.handler,
// 		initialControl:'setToReady', //initialControl:'setUnavailable'
// 		label:"<div style='margin-top:4px;'>Submit</div>"
// 	});
//
// 	var displayItem=this.displayParameters.cancelButton;
// 	$('#'+displayItem.divId).good_earth_store_tools_ui_button2({
// 		ready:{classs:'basicReady'},
// 		hover:{classs:'basicHover'},
// 		clicked:{classs:'basicActive'},
// 		unavailable:{classs:'basicUnavailable'},
// 		accessFunction:displayItem.handler,
// 		initialControl:'setToReady', //initialControl:'setUnavailable'
// 		label:"Cancel"
// 	});


	this.element.find('input').qprompt();

},

keypressHandler:function(control, parameter){
	switch(control.type){
		case 'keypress':
			if ((control.which<48 || control.which>57) && [8, 9, 0].indexOf(control.which)<0){ //only numbers in this form
				control.preventDefault();
				return false
			}
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

inputHandler:function(control, parameter){
	switch(control.type){
		case 'keyup':
			this.updatePurchase();
			var value=$(control.target).attr('value');
			if (value>0){
				$(control.target).parent().addClass('nonZeroProdLine');
			}
			else{
				$(control.target).parent().removeClass('nonZeroProdLine');
			}
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

updatePurchase:function(){
	var list=this.element.formParams(true).product,
		totalPrice=0;

	this.purchaseData.productList=[];
	this.purchaseData.productDisplayList=[];

	for (var prodCode in list){
		var quantity=1.0*(1.0*list[prodCode]).toFixed(2);
		if (quantity>0){

			var productObj=qtools.lookupDottedPath(this.productInfo, 'details.prodCode', prodCode),
				price=1.0*(1.0*productObj.details.price).toFixed(2),
				extendedPrice=1.0*(price*quantity).toFixed(2),
				totalPrice=totalPrice+extendedPrice;

			this.purchaseData.productList.push({prodCode:prodCode, quantity:quantity});
			this.purchaseData.productDisplayList.push({
				prodCode:prodCode,
				quantity:quantity,
				name:productObj.details.name,
				price:price,
				extendedPrice:extendedPrice
			});
		}
	}
	this.totalPrice=1.0*totalPrice.toFixed(2);
	this.purchaseData.subtotal=this.totalPrice;
	this.purchaseData.tax=this.calcTax();
	this.purchaseData.grandTotal=this.purchaseData.subtotal+this.purchaseData.tax;


	this.writePriceDisplay();
	this.infoDispatchHandler('changePurchaseData');
},

calcTax:function(){
//	this.infoDispatchHandler('getStateCode');
//	if (stateCode has tax value) calc tax
//else
	return 1.25;
},

writePriceDisplay:function(){
	var html=$.View("//widgets/controller/simple_store/product_selector/views/priceDisplay.ejs",
		$.extend({}, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				purchaseData:this.purchaseData
			}
		})
		);
	$('#'+this.displayParameters.priceDisplayContainer.divId).html(html);
}

})

});