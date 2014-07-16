steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Features.ContactButton
 */
Widgets.Controller.Base.extend('Widgets.Controller.Features.ContactButton',
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
		this.directToServerHandler();
		
	},

	update:function(options){
		this.init(this.element, options);
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
		
		if (this.formParameters.entryForm.mode==='useHtmlForm'){
			this.directToServer=true;
		}
		
		if (typeof(this.calculatedFormData)=='undefined'){ this.calculatedFormData={};}
	},

resetAfterSave:function(inData){ 

	var errorString=this.listMessages(inData.messages);
	if (inData.status<1){
	console.log('got error');
// 		$('#'+this.formParameters.entryForm.formDivId).html(errorString).removeClass('good').addClass('bad');
// 		
// 		var html=$.View('//widgets/controller/features/contact_form/views/confirmEmail.ejs',
// 		$.extend(inData, {
// 			displayParameters:this.displayParameters,
// 			viewHelper:this.viewHelper
// 		})
// 		);
// 		html="<div class='bad'>"+errorString+"</div>";
// 		this.displayPanel.prepend(html).css({overflow:'scroll'});
	
	}
	else{
	console.log('it worked');
// 		var html=$.View('//widgets/controller/features/contact_form/views/confirmEmail.ejs', {});
// 		this.displayPanel.html(html);
// 		
// 		var thiss=this;
// 		this.displayPanel.fadeOut(
// 		3000,
// 		function(){
// 			thiss.displayPanel.remove();
// 		});
	}
},

directToServerHandler:function(){
		this.clickedButtonObj=$(this.event.target);
		var buttonId=this.clickedButtonObj.attr('buttonId');
		
		var formObj=$('#'+this.formParameters.entryForm.formDivId);

		Widgets.Models.Email.send({
			formParams:$.extend(formObj.formParams(), {buttonId:buttonId}),
			mailParams:this.formParameters,
			serverManagement:{processContentSourceRouteName:this.processContentSourceRouteName}
			
			}, this.callback('resetAfterSave'));
}






})

});