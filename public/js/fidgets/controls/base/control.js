define([
	'/js/fidgets/core/start/setNameSpace.js',
	'can/control',
	'can/construct/super',
	'can/control/plugin'
], function(nameSpaceName) {

	can.Control.extend('Fidgets.Control.Base', {}, {
		init: function() {
		this.eventNameSpace=Math.random().toString().replace(/0\./,'');
		},

		update: function(options) {},

		sayMyName: function() {

			console.log("this.controllerName=" + this.controllerName);
			console.log("_fullName=" + this._fullName);
		},
		
		// CLICK MANAGEMENT ========================================================================

		turnOffClicksForAwhile: function(milliseconds, callbacks) {

			// 		if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
			// 		else{return;}

			milliseconds = milliseconds ? milliseconds : 2000;
			callbacks = callbacks ? callbacks : {};

			this.setNotAcceptClicks();

			var setAcceptClicks = function(scope, args) {
				scope.setAcceptClicks();
			}
			if (typeof (callbacks.beforeFunction) == 'function') {
				callbacks.beforeFunction(callbacks.beforeArgs);
			}

			var setAcceptClicks = this.callback('setAcceptClicks');
			var postFunc = (typeof (callbacks.afterFunction) == 'function' ? callbacks.afterFunction : function() {
					return;
				});
			var afterArgs = callbacks.postArgs;
			var timeFunction = function() {
				setAcceptClicks();
				postFunc();
			};

			qtools.timeoutProxy(timeFunction, milliseconds);

		},

		setNotAcceptClicks: function() {
			this.acceptClicks = false;
		},

		setAcceptClicks: function() {
			this.acceptClicks = true;
		},

		isAcceptingClicks: function() {
			return this.acceptClicks;
		}
	});

});





