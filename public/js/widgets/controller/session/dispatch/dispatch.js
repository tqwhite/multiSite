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
		Widgets.Models.Session.start([], this.callback('receiveSessionStartup')); //receiveSessionStartup initializes the app

		if (window.location.hash){
			this.startingHash=window.location.hash.replace('#', '');
		}

		this.initTooltips();

	},

	getServerData:function(){

		this.controllerStartupList=Widgets.Models.HtmlData.getControllerStartupList();

		this.serverData=Widgets.Models.HtmlData.getServerData();
		Widgets.Models.Session.keep('serverData', this.serverData);

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
		var domSelector=startupItem.domSelector,
		controllerName=startupItem.controllerName,
		parameters=startupItem.parameters,
		domObj=$(domSelector);

		parameters.serverData=this.serverData;

		if (typeof(domObj[controllerName])=='function'){
			domObj[controllerName](parameters);
		}
		else{
			alert('${'+domSelector+').'+controllerName+'() is not a function');
		}

	},

	initTooltips:function(){

		$('.tooltipAboveCrossLeft[title]', this.element).qtip({
			position: {
				my: 'bottom left',
				at: 'top left'
			}
		});

		$('.tooltipAboveCrossRight[title]', this.element).qtip({
			position: {
				my: 'bottom right',
				at: 'top right'
			}
		});
	}

})

});