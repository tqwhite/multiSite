define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/fidgets/core/state/state.js',
	'/js/fidgets/contactForm/contactForm.js',
], function(nameSpaceName, stateInit) {
	//note: the directory for the canjs stuff is set by the call in the layout, foundation-default.phtml

	var stateContainerName = 'State';

	var stateContainer = stateInit(nameSpaceName, stateContainerName);

	var serverControl = Fidgets.serverInterface.serverControl();


	for (var i = 0, len = serverControl.length; i < len; i++) {
		var element = serverControl[i];
		console.dir({
			message: 'initializing this list of js controls is not yet implemented in fidgets.core.start.fidgets.js',
			serverControl: element
		});
	}




	return Fidgets.State;

});




