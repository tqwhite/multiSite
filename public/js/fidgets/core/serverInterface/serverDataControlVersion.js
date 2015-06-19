define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/fidgets/controls/base/control.js'], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.ContactForm';


	scopeObject.Control.Base.extend('Fidgets.ServerData', {
		init: function() {
			this._super(); //execute base.init()
		},


		getControllerStartupList: function() {
			var serverControlDomObj = $('#serverControl'), //'#serverData' is defined in Q_Controller_Action_Helper_WriteServerCommDiv()
				formParams, controllerStartupList;

			if (serverControlDomObj.length > 0) {
				formParams = serverControlDomObj.formParams();
				this.serverControlDomObj = serverControlDomObj;
			} else {
				formParams = {};
			}

			controllerStartupList = (typeof (formParams.controller_startup_list) != 'undefined') ? formParams.controller_startup_list : [];

			for (var i in controllerStartupList) {
				if (typeof (controllerStartupList[i].parameters) != 'undefined') {
					controllerStartupList[i].parameters = $.parseJSON(controllerStartupList[i].parameters);
				} else {
					controllerStartupList[i].parameters = {};
				}
			}



			return controllerStartupList;
		},


		getServerData: function() {
			var serverDataDomObj = $('.serverData'),
				serverData = {};

			var list = serverDataDomObj;
			this.serverData = {};

			for (var i = 0, len = list.length; i < len; i++) {
				var element = $(list[i]);
				serverData[element.attr('id')] = JSON.parse(element.text());
			}

			return serverData;
		}
	})
});



