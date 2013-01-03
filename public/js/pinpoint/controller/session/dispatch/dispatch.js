steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Pinpoint.Controller.Session.Dispatch
 */
Pinpoint.Controller.Base.extend('Pinpoint.Controller.Session.Dispatch',
/** @Static */
{
	defaults : {}
},



//js jquery/generate/controller pinpoint/controller/session/login


/** @Prototype */
{
	init : function(){

	//	this.session.saveReferenceLocation();  //I don't remember what this is supposed to do,candidate for delete
		this.getServerData();
		Pinpoint.Models.Session.start([], this.callback('receiveSessionStartup'));

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


		var defaultController={
				domSelector:"body",
				controllerName:'pinpoint_app_annotation_panel',
				parameters:JSON.stringify({background:'#aaaaaa', color:'blue'})
			}


			this.controllerStartupList=(typeof(formParams.controller_startup_list)!='undefined')?formParams.controller_startup_list:[defaultController];
	},

	receiveSessionStartup:function(inData){
		var userIdCookie=Pinpoint.Models.LocalStorage.getCookieData(Pinpoint.Models.LocalStorage.getConstant('loginCookieName')).data;
		if (inData){Pinpoint.Models.Session.keep('user', inData.data);}

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