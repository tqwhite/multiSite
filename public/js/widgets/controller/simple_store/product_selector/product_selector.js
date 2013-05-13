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
			{name:'purchaseData'}, //communication object, owned by employer (main.js)
			{name:'infoDispatchHandler'},
			{name:'specialDisplayOption', importance:'optional'},
			{name:'pageFormTemplates'}
		],
		showAlertFlag:true,
		source:this.constructor._fullName
 	});

	qtools.validateProperties({
		targetObject:options,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'cartPopup.ini', importance:'optional'}
		],
		showAlertFlag:true,
		source:this.constructor._fullName
 	});
 	
 	this.options=options;

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}

	switch(this.options.specialDisplayOption){
		default:
			this.initDisplay();
			break;
		case 'showCartPopup':
			this.displayCartPopup();
			break;
	}

},

update:function(control, parameter){
	switch(control){
		case 'updatePriceDisplays':
			this.updatePurchase();
			break;
		default:
			if (typeof(control)=='object'){ this.options=$.extend(this.options, control);}
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.init(this.element, this.options);
			break;
	}
},

initDisplayProperties:function(){

	var nameArray=[], name;

	name='priceDisplayContainer'; nameArray.push({name:name});
	name='priceHoverIdClass'; nameArray.push({name:name});
	name='cartPopupContainer'; nameArray.push({name:name});
	name='cartPopupBackground'; nameArray.push({name:name});

//	name='cancelButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.totalPrice=0;
	
	this.productInfo=qtools.intoSortedArray(this.productInfo, 'fileName');
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
	$('[title]', this.element).qtip();
	
	

},

keypressHandler:function(control, parameter){
	switch(control.type){
		case 'keypress':
			if ((control.which<48 || control.which>57) && qtools.indexOf([8, 9, 0], control.which)<0){ //only numbers in this form
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
			if ((control.which<48 || control.which>57) || (qtools.indexOf([16, 9], control.which)>-1)){return;}
			var targetObj=$(control.target),
				value=targetObj.attr('value'),
				parentObj=targetObj.parent();
				
//Wilbert used to not be able to receive an order for two things at once. That is no longer true.
// 				$('input', this.element).attr('value', '0').each(function(){$(this).parent().removeClass('nonZeroProdLine')});
// 				targetObj.attr('value', value);


			this.updatePurchase(parentObj);
			if (value>0){
				parentObj.addClass('nonZeroProdLine');
			}
			else{
				parentObj.removeClass('nonZeroProdLine');
				this.updateLinePrice(parentObj);
			}
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

updatePurchase:function(parentObj){
	var list=this.element.formParams(true).product,
		totalPrice=0;

	this.purchaseData.shoppingCart=[]; //has complete product object plus price and quantity info
	this.purchaseData.totalsDisplayObject={};

	for (var prodCode in list){
		var quantity=1.0*(1.0*list[prodCode]).toFixed(2);
		if (quantity>0){

			var productObj=qtools.lookupDottedPath(this.productInfo, 'prodCode', prodCode),
				price=this.calcDiscountPrice(productObj, quantity),

				extendedPrice=1.0*(price*quantity),
				totalPrice=totalPrice+extendedPrice;
			productObj=$.extend(productObj, {
					quantity:quantity,
					price:price,
					extendedPrice:extendedPrice.toFixed(2).toString()
				});
			
			this.purchaseData.shoppingCart.push(productObj);
		}
	}

	this.totalPrice=1.0*totalPrice;
	this.purchaseData.subtotal=this.totalPrice;
	this.purchaseData.tax=this.calcTax();
	this.purchaseData.grandTotal=this.purchaseData.subtotal+this.purchaseData.tax;

	this.purchaseData.totalsDisplayObject.subtotal=this.purchaseData.subtotal.toFixed(2).toString();
	this.purchaseData.totalsDisplayObject.tax=this.purchaseData.tax.toFixed(2).toString();
	this.purchaseData.totalsDisplayObject.grandTotal=this.purchaseData.grandTotal.toFixed(2).toString();


	if (typeof(parentObj)!='undefined'){
		this.updateLinePrice(parentObj, price);
	}
	this.writePriceDisplay();
	this.infoDispatchHandler('changePurchaseData');
},

updateLinePrice:function(parentObj, price){
	if (typeof(price)=='undefined' ||price===''){price='pricing';}
	else{ price='$'+price;}
	parentObj.find('.'+this.displayParameters.priceHoverIdClass.divId).text(price);
},

calcDiscountPrice:function(productObj, quantity){

	if (typeof(productObj.price)!='undefined'){
		return productObj.price;
	}
	else if (typeof(productObj.discountSchedule)!='undefined'){

	var list=productObj.discountSchedule,
		previousElementPrice;

	for (var i=0, len=list.length; i<len; i++){
		var element=list[i];

		if (quantity<element.minCount){
			return previousElementPrice;
		}
		//else
		previousElementPrice=element.price;

	}

	return previousElementPrice;
}

},

calcTax:function(){
//	this.infoDispatchHandler('getStateCode');
//	if (stateCode has tax value) calc tax
//else
	return 0; //pending someone deciding they want to collect tax after all
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
},

displayCartPopup:function(){

	var list=this.productInfo,
		outString='';
	for (var i in list){
		var element=list[i];
		outString+="<tr><td style='text-align:left;'>"+element.quantity+"</td><td>"+element.name+"</td><td style='text-align:right;'>$"+(1.0*element.price).toFixed(2)+"</td></tr>";
	}
	outString="<table style='border-collapse:separate;border-spacing: 10px 15px;font-weight:normal;font-size:10pt;padding:30px 15px;width:500px;text-align:left;background:rgba(200,255,255,1);color:black;margin:0px auto;'>"+outString+"</table>";

			this.assertModalScreen($('body'), {
				message:outString,
				version:2,
				backgroundClickFunction:function(){ $(this).hide(); },
				fadeParms:{duration:3000},
				messagePosition:'window'
				
			
			});

}

})

});