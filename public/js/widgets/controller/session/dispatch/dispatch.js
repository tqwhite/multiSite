steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Session.Dispatch
 */
Widgets.Controller.Base.extend('Widgets.Controller.Session.Dispatch',
/** @Static */
{
	defaults : {}
},

//js jquery/generate/controller widgets/controller/session/login


/** @Prototype */
{
	init : function(){
	//	this.session.saveReferenceLocation();
		this.getServerData();
		Widgets.Models.Session.start([], this.callback('receiveSessionStartup'));

		if (window.location.hash){
			this.startingHash=window.location.hash.replace('#', '');
		}

	},

	getServerData:function(){
		var serverDataDomObj=$('#serverData'), //'#serverData' is defined in Q_Controller_Action_Helper_WriteServerCommDiv()
			formParams;

			if (serverDataDomObj.length>0){
				formParams=serverDataDomObj.formParams();
				this.serverDataDomObj=serverDataDomObj;
			}
			else{
				formParams={};
			}

			this.controllerStartupList=(typeof(formParams.controller_startup_list)!='undefined')?formParams.controller_startup_list:[];
	},

	receiveSessionStartup:function(inData){
		var userIdCookie=Widgets.Models.LocalStorage.getCookieData(Widgets.Models.LocalStorage.getConstant('loginCookieName')).data;
		if (inData){Widgets.Models.Session.keep('user', inData.data);}

		var list=this.controllerStartupList;
		for (var i in list){
			this.startController(list[i]);
		}

	},

	startController:function(startupItem){
		domSelector=startupItem.domSelector,
		controllerName=startupItem.controllerName,
		parameters=startupItem.parameters,
		domObj=$(domSelector);

		if (typeof(domObj[controllerName])=='function'){
			domObj[controllerName]($.parseJSON(parameters));
		}
		else{
			alert('${'+domSelector+').'+controllerName+'() is not a function');
		}

	}

})

});