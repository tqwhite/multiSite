steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.SimpleStore.PaymentForm.Editors.Po
 */
Widgets.Controller.Base.extend('Widgets.Controller.SimpleStore.PaymentForm.Editors.Po',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

//
//dependency phrase: widgets/controller/simple_store/payment_form/editors/po/po.js
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

	var html=$.View("//widgets/controller/simple_store/payment_form/editors/po/views/init.ejs",
		$.extend(inData, {
			displayParameters:this.displayParameters,
			viewHelper:this.viewHelper,
			formData:{
				message:'Widgets.Controller.SimpleStore.PaymentForm.Editors.Po',
				dataGroupName:this.dataGroupName
			}
		})
		);
		
	this.element.html(html);
	this.initDomElements();
},

initDomElements:function(){



}

}) //end of method-containing object ===

});