define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/fidgets/core/serverInterface/serverInterface.js', '/js/fidgets/core/base/control.js',
	'can/construct/super', 'can/control/plugin', 'can/component', 'can/view', 'can/view/stache',
	'/js/fidgets/models/sendEmail.js'], function(nameSpace) {
	var scopeObject = window[nameSpace];

	var controllerName = nameSpace + '.Control.ContactForm';



	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {

			this.controllerName = controllerName;

			this.serverData = Fidgets.serverInterface.serverData();
			this.initControlProperties(options);
			this.initDisplayParameters();
			this.establishViewModel();

			this.initDisplay();

		},
		update: function(options) {
			this.init(this.element, options);
		},

		initControlProperties: function(options) {

			this.formName = $(options.event.target).attr('formName');
			this.customElementName = this.formName.toLowerCase();




			this.userControlObject = this.serverData.parameters[options.parameterFileName][this.formName]
			if (!this.userControlObject) {
				alert('_PARAMETERS/"+this.parameterFileName+" contains no spec for :' + formName + " in either the site or page directory");
			}


			this.buttonId = options.clickTarget ? $(options.clickTarget).attr('buttonId') : '';
			this.processContentSourceRouteName = this.serverData.parameters.processContentSourceRouteName


		},

		initDisplayParameters: function() {
			this.modalContainerId = this.customElementName + 'Container';
		},

		establishViewModel: function() {
			var entryForm = this.userControlObject.entryForm.html;

			var contactVm = can.Map.extend({
				entryForm: entryForm,
				errorList: [],
				modalContainerId: this.modalContainerId,

				isVisible: true,
				isAcceptingInput: true,
				isSending: false,
				isErrorFinish: false,
				isSuccessFinish: false,

				save: this.saveHandler.bind(this),
				cancel: this.cancelHandler.bind(this),
				controlPropagation: function(viewModel, element, event) {
					event.stopPropagation();
				}.bind(this)
			});
			AAA = this.contactVm = new contactVm(); //let me change the model from firebug, remove later
		},

		initDisplay: function() {
			var css = can.view('/js/fidgets/contactForm/local.css');
			$('body').prepend(css());

			$('body').append("<script id='contact-" + this.customElementName + "' type='text/mustache'><contact-" + this.customElementName + "></contact-" + this.customElementName + "></script>");

			Fidgets.tmp = can.Component.extend({
				tag: "contact-" + this.customElementName,
				template: can.view('/js/fidgets/contactForm/view.stache'),
				viewModel: this.contactVm,
				helpers: {
					cancel: function() {
						this.attr('isVisible', false);
					}
				}
			});

			$('body').append(can.view('contact-' + this.customElementName, {}));
		},

		//handlers ==================================================


		saveHandler: function(viewModel, element, event) {
			var formParams = this.element.formParams();
			this.contactVm.attr('isAcceptingInput', false);
			this.contactVm.attr('isSending', true);
			this.sendEmail(formParams);


			// 					setTimeout(function(){
			// 					this.contactVm.attr('isSending', false);
			// 					this.contactVm.attr('isSuccessFinish', true);
			// 					}.bind(this), 3000);
			// 					setTimeout(function(){
			// 					this.contactVm.attr('isSuccessFinish', false);
			// 					this.contactVm.attr('isErrorFinish', true);
			// 					}.bind(this), 6000);
			// 					setTimeout(function(){
			// 					this.contactVm.attr('isVisible', false);
			// 					}.bind(this), 9000);

		},

		cancelHandler: function() {
			this.contactVm.attr('isVisible', false);
		},

		sendEmail: function(formParams) {
			var success = function(inData) {

				if (inData.status < 0) {
					error.apply(this, [inData]);
					return;
				}
				this.contactVm.attr('isSending', false);
				this.contactVm.attr('isSuccessFinish', true);
				this.fadeFromView();
			}

			var error = function(inData) {

console.dir({"inData.messages.map(function(item){return {message:item}})":inData.messages.map(function(item){return {message:item}})});


				this.contactVm.attr('errorList', inData.messages.map(function(item){return {message:item}}));
				this.contactVm.attr('isSending', false);
				this.contactVm.attr('isErrorFinish', true);
				this.fadeFromView();

			}

			var parameters = {
				formParams: $.extend(formParams, {
					buttonId: this.buttonId
				}),
				mailParams: this.userControlObject,
				serverManagement: {
					processContentSourceRouteName: this.processContentSourceRouteName
				}
			};



			Fidgets.Models.sendEmail.sendPlus(parameters, this.element, success.bind(this), error.bind(this));
		},

		fadeFromView: function() {
			setTimeout(function() {
				$('#' + this.modalContainerId).fadeOut(2000, function() {
					this.contactVm.attr('isVisible', false);
				}.bind(this));
			}.bind(this), 1000);
		}
	});

});










