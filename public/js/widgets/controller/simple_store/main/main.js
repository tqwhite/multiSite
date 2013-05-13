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
			{name:'paymentServerUrl'},
			{name:'serverData'},
			{name:'processContentSourceRouteName'},
			{name:'deferAppearance', importance:'optional'}, //this is sent by the catalog in fancyCatalog1
			{name:'catalogData', importance:'optional'} //this comes from fancyCatalog1 UNUSED REMOVE FROM BOTH
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
			{name:'productInfo', importance:'optional'}, //if not here, initControlProperties fills in from cookie
			{name:'pageFormTemplates'},
			{name:'simpleStore'}
		],
		showAlertFlag:true,
		source:this.constructor._fullName
 	});

	this.initControlProperties();
	this.initDisplayProperties();

	options=options?options:{};
	if (options.initialStatusMessage){this.initialStatusMessage=options.initialStatusMessage;}
	
	if (this.options.showCartPopup){
		this.cartPopupDisplay();
	}
	else{
		this.initDisplay();
	}

},

update:function(control, parameter){
	switch(control){
		case 'display':
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.options.deferAppearance=false;
			this.options.showCartPopup=false;
			this.init(this.element, this.options);
			break;
		case 'showCartPopup':
			if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
			this.options.deferAppearance=false;
			this.options.showCartPopup=true;
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
			if (typeof(control)=='object'){ this.options=$.extend(this.options, control);}
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
	this.purchaseData={}; //communication object, subordinate controllers write to this

	this.updateProductInfo();
	
	this.simpleStore.showShippingOptions=this.needShipping(this.productInfo);

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

	var displayItem=this.displayParameters.productListContainer;
	$('#'+displayItem.divId).widgets_simple_store_product_selector({
			productInfo:this.productInfo,
			purchaseData:this.purchaseData,
			infoDispatchHandler:this.displayParameters.infoDispatch.handler,
			pageFormTemplates:this.pageFormTemplates
	});

	var displayItem=this.displayParameters.paymentFormContainer;
	$('#'+displayItem.divId).widgets_simple_store_payment_form({
			paymentServerUrl:this.paymentServerUrl,
			purchaseData:this.purchaseData,
			processContentSourceRouteName:this.processContentSourceRouteName,
			infoDispatchHandler:this.displayParameters.infoDispatch.handler,
			catalogUrl:this.options.catalogUrl,
			simpleStore:this.simpleStore
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
			this.displayCompletion(parameter);
		break;
		case 'changePurchaseData':
			$('#'+this.displayParameters.paymentFormContainer.divId).widgets_simple_store_payment_form(control, parameter);
			if (this.cartExists){this.maintainCart();}
		break;
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter;
		break;
	}
	//change dblclick mousedown mouseover mouseout dblclick
	//focusin focusout keydown keyup keypress select
},

displayCompletion:function(confirmationHtml){

	this.element.html(confirmationHtml);
	$('#'+this.displayParameters.printButton.divId).click(this.displayParameters.printButton.handler);
},

printButtonHandler:function(control, parameter){
	window.print();
},

addEventObjToCart:function(eventObj){
	var targetDomObj=$(eventObj.target).parent(),
		newProductChoice=targetDomObj.formParams(true);
	
	var selectedProduct=this.getCompleteProduct(newProductChoice);
	if (selectedProduct){
		this.addToCart(selectedProduct);
	}
},

getCompleteProduct:function(productChoice){

	var selectedProduct=qtools.lookupDottedPath(this.catalogData, 'prodCode', productChoice.prodCode);
	
	if (!selectedProduct){
			alert(this._shortName+".addToCart() says, this product button is producing bad cart input (missing product, quantity or prodCode)"); 
			return false;
		}
	
	selectedProduct=qtools.passByValue(selectedProduct);
	if (typeof(productChoice.quantity)=='undefined'){ //which is to say, selected quantity could be zero
		selectedProduct.quantity=1;
	}
	else{
		selectedProduct.quantity=productChoice.quantity;
	}
		
		return selectedProduct;
	
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
	
	this.productInfo=incumbentCart;
	
	Widgets.Models.LocalStorage.setCookie('cart', incumbentCart);
	this.cartExists=true;
},

maintainCart:function(){
	Widgets.Models.LocalStorage.setCookie('cart', this.purchaseData.shoppingCart);
},

needShipping:function(productInfo){
	
	var list=[];
	for (var i in productInfo){
		var element=productInfo[i];
		if (element.requiresShipping==1){
			return true;
		}
	}
	return false;
},

updateProductInfo:function(forceCookieRefresh){
		if (typeof(this.productInfo)=='undefined' || forceCookieRefresh){
		this.productInfo=Widgets.Models.LocalStorage.getCookieData('cart').data; //shopping cart is same format as file-based productInfo
		this.cartExists='true';
	}
	else{ this.cartExists=false; }
},

cartPopupDisplay:function(){

		
		this.element.widgets_simple_store_product_selector({
				specialDisplayOption:'showCartPopup',
				productInfo:this.productInfo,
				purchaseData:this.purchaseData,
				infoDispatchHandler:this.displayParameters.infoDispatch.handler,
				pageFormTemplates:this.pageFormTemplates
		});


}

})

});