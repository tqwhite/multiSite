define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/widgets/resources/services/qtools.js',
	'/js/fidgets/controls/base/control.js',
	'/js/vendor/foundation/foundation.min.js',
	'can/component', 'can/view', 'can/view/stache',

	'/js/vendor/jquery_downloads/jquery-bullseye-1-0b/jquery.bullseye-1.0.js'

], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.FeatureOverlay';

	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {
			this.directory = '/js/fidgets/controls/verticalStructure/';
			this._super(); //execute base.init(), only works in method 'init'
			//		this.loadCssFile(this.directory+'local.css', this.startThisController.bind(this, element, options));
			this.startThisController(element, options);
		},

		startThisController: function(element, options) {
			this.validateOptions(options);
			this.controllerName = controllerName;

			this.initControlProperties(options);
			this.initDisplayParameters();
			this.establishViewModel();

			this.initDisplay();
			this.updateDom();

		},
		
		validateOptions:function(options){
		

			qtools.validateProperties({
				targetObject: options,
				targetScope: this, //will add listed items to targetScope
				propList: [
					{
						name: 'delay', //wait this long before showing panel
						optional: true
					},
					{
						name: 'speed', //fade in over this period
						optional: true
					},
					{
						name: 'action', //fade in over this period
						optional: true
					}
				],
				source: this.constructor._fullName
			});
		},

		update: function(options) {
			this.validateOptions(options);
			switch(this.action){
				case 'hide':
					this.element.hide();
					this.hidden=true;
				break;
				default:
					this.startFadeDelay();
				break;
			}
		},

		initControlProperties: function(options) {
		},

		initDisplayParameters: function() {},

		establishViewModel: function() {},

		initDisplay: function() {
			this.element.hide();
			this.hidden=true;
		},

		updateDom: function() {
					
		
		},
		
		startFadeDelay:function(){
			if (!this.hidden){ return;}
			setTimeout(this.callback('executeFade'), this.delay || 1000);
		},
		
		executeFade:function(){
			this.element.fadeIn(this.speed || 1000, function(){
				this.hidden=false;
			}.bind(this));
		}
	});
});


