steal( 'jquery/controller','jquery/view/ejs' )
.then(
	'widgets/stylesheets/simpleStore.css'
)
	.then( './views/init.ejs', './views/displayCompletion.ejs', function($){

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
			{name:'paymentServerUrl'},
			{name:'serverData'},
			{name:'deferAppearance', importance:'optional'}, //this is sent by the catalog in fancyCatalog1
			{name:'catalogData', importance:'optional'} //this comes from fancyCatalog1
		],
		source:this.constructor._fullName
 	});
 	
 	this.options=options;
 	
 	if (options.deferAppearance){
 		return;
 	}
 	
	qtools.validateProperties({
		targetObject:this.serverData,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'productInfo', importance:'optional'},
			{name:'confirmationPageTemplate'}
		],
		showAlertFlag:true,
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}

	this.initDisplay();

	testFinal=this.callback('infoDispatchHandler', 'testFinal');

},

update:function(control, parameter){
	switch(control){
		case 'display':
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.options.deferAppearance=false;
			this.init(this.element, this.options);
			break;
		case 'addEventObjToCart':
			this.addEventObjToCart(parameter);
			break;
		case 'showViewCartButtonIfNeeded':
				var incumbentCart=Widgets.Models.LocalStorage.getCookieData('cart').data;
				if (typeof(incumbentCart)!='undefined'){
					$(parameter).show();
				}
			break;
		default:
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.init(this.element, this.options);
			break;
	}
},

initDisplayProperties:function(){

	nameArray=[];

	name='productListContainer'; nameArray.push({name:name});
	name='paymentFormContainer'; nameArray.push({name:name});

	name='infoDispatch'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});
	name='printButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();
	this.purchaseData={};

	if (typeof(this.productInfo)=='undefined'){
		this.productInfo=Widgets.Models.LocalStorage.getCookieData('cart').data;
	}

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
			paymentServerUrl:this.paymentServerUrl,
			purchaseData:this.purchaseData,
			infoDispatchHandler:this.displayParameters.infoDispatch.handler,
			catalogUrl:this.options.catalogUrl
	});

	$('#'+this.displayParameters.productListContainer.divId).widgets_simple_store_product_selector('updatePriceDisplays');
},

infoDispatchHandler:function(control, parameter){
	var componentName='infoDispatch';
	switch(control){
		case 'clearCart':
			Widgets.Models.LocalStorage.deleteCookie('cart')
			break;
		case 'displayCompletion':
			this.displayCompletion();
		break;
		case 'changePurchaseData':
			$('#'+this.displayParameters.paymentFormContainer.divId).widgets_simple_store_payment_form(control, parameter);
		break;
		case 'testFinal':
			var testData=eval(({"productList":[{"prodCode":"prodA","quantity":11},{"prodCode":"prodB","quantity":22}],"productDisplayList":[{"prodCode":"prodA","quantity":11,"name":"submitButton"},{"prodCode":"prodB","quantity":22,"name":"submitButton","price":11.33}],"subtotal":615.89,"tax":2.1,"grandTotal":617.99,"cardData":{"name":"TQ White II","emailAdr":"tq@justkidding.com","confirmEmail":"tq@justkidding.com","cardName":"TQ School District","street":"5004 Three Points Blvd","city":"Mound","state":"MN","zip":"55364","phoneNumber":"708-763-0100","cardNumber":"3124","expMonth":"12","expYear":"13","chargeTotal":"111","poNumber":"A0-995-628-8295"}}));
			this.purchaseData=testData;
			this.infoDispatchHandler('displayCompletion');
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

displayCompletion:function(){

	var html=$.View("//widgets/controller/simple_store/main/views/displayCompletion.ejs",
		$.extend({}, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				displayParameters:this.displayParameters,
				serverData:this.serverData,
				purchaseData:this.purchaseData,

				confirmationPageTemplate:this.confirmationPageTemplate
			}
		})
		);
	this.element.html(html);
	$('#'+this.displayParameters.printButton.divId).click(this.displayParameters.printButton.handler);
},

printButtonHandler:function(control, parameter){
	window.print();
},

addEventObjToCart:function(eventObj){
	var targetDomObj=$(eventObj.target).parent(),
		newProductChoice=targetDomObj.formParams(true);
	
	if (this.isValidProductChoice(newProductChoice)){
		this.addToCart(newProductChoice);
	}
},

isValidProductChoice:function(productChoice){
	if (!productChoice.price ||
		!productChoice.quantity ||
		!productChoice.prodCode){
			alert(this._shortName+".addToCart() says, this product button is producing bad cart input (missing product, quantity or prodCode)"); 
			return false;
		}
	//else
		
		return true;
	
},

addToCart:function(newProductChoice){
	var incumbentCart=Widgets.Models.LocalStorage.getCookieData('cart').data;

	if (typeof(incumbentCart)=='undefined'){incumbentCart=[];}
	
	
	var existingObject=qtools.lookupDottedPath(incumbentCart, 'prodCode', newProductChoice.prodCode);

	if (typeof(existingObject)=='undefined'){
		incumbentCart.push(newProductChoice);
	}
	else{
		existingObject.quantity=(1.0*existingObject.quantity)+newProductChoice.quantity;
	}

	Widgets.Models.LocalStorage.setCookie('cart', incumbentCart);
}

})

});