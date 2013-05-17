steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity
 */
Widgets.Controller.Base.extend('Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

//
//dependency phrase: widgets/controller/simple_store/payment_form/editors/identity/identity.js
//

init : function(element, options){
	this.baseInits();

	qtools.validateProperties({
		targetObject:options,
		targetScope: this, //will add listed items to targetScope
		propList:[
			{name:'dataGroupName'}
		],
		source:this.constructor._fullName
	});

	this.initControlProperties();
	this.initDisplayProperties();
	this.initDisplay({});	
},

update:function(control, parameter){
	switch(control){
		case 'hide':
			this.element.hide();
			break;
		case 'show':
			this.element.show();
			break;
		case 'collapse':
			this.showCollapse();
			break;
		case 'expand':
			this.initDisplay();
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
},

initDisplayProperties:function(){

	nameArray=[];

	name='contentContainer'; nameArray.push({name:name});
//	name='activationButton'; nameArray.push({name:name, handlerName:name+'Handler', targetDivId:name+'Target'});

	this.displayParameters=$.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

},

initControlProperties:function(){
	this.viewHelper=new viewHelper2();

},

initDisplay:function(inData){

	var html=$.View("//widgets/controller/simple_store/payment_form/editors/identity/views/init.ejs",
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				message:'Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity',
				dataGroupName:this.dataGroupName
			}
		})
		);
		
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){

},

showCollapse:function(inData){

	var html=$.View("//widgets/controller/simple_store/payment_form/editors/identity/views/collapsed.ejs",
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				message:'Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity'
			}
		})
		);
		
	this.element.html(html);
	this.initCollapseDomElements();
},

initCollapseDomElements:function(){
	console.log('initCollapseDomElements');
}

}) //end of method-containing object ===

});