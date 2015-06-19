define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/widgets/resources/services/qtools.js',
	'/js/fidgets/controls/base/control.js',
	'/js/fidgets/core/serverInterface/serverInterface.js',
	'/js/vendor/foundation/foundation.min.js',
	'/js/vendor/foundation/foundation/foundation.abide.js',
	'can/component', 'can/view', 'can/view/stache',
	'/js/fidgets/models/sendEmail.js'], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.ContactForm';

	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {
			this.controllerName = controllerName;
			this.directory = '/js/fidgets/controls/contactForm/';

			qtools.validateProperties({
				targetObject: options,
				targetScope: this, //will add listed items to targetScope
				propList: [
					{
						name: 'event',
						optional: false
					},
					{
						name: 'parameterFileName',
						optional: false
					},
					{
						name: 'clickTarget',
						optional: false
					}
				],
				source: this.constructor._fullName
			});

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

			qtools.validateProperties({
				targetObject: this.userControlObject,
				propList: [
					{
						name: 'entryForm',
						optional: false
					}
				],
				source: this.constructor._fullName
			});

			this.buttonId = options.clickTarget ? $(options.clickTarget).attr('buttonId') : '';
			this.processContentSourceRouteName = this.serverData.parameters.processContentSourceRouteName; //set by server

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

			this.disableScrolling();
			var css = can.view(this.directory + 'local.css');
			$('body').prepend(css());

			$('body').append("<script id='contact-" + this.customElementName + "' type='text/mustache'><contact-" + this.customElementName + "></contact-" + this.customElementName + "></script>");

			Fidgets.tmp = can.Component.extend({
				tag: "contact-" + this.customElementName,
				template: can.view(this.directory + 'view.stache'),
				viewModel: this.contactVm,
				helpers: {
					cancel: function() {
						this.attr('isVisible', false);
					}
				}
			});

			$('body').append(can.view('contact-' + this.customElementName, {}));
			$(document).foundation('abide', 'reflow'); //activate any foundation items, if any
		},

		//handlers ==================================================

		saveHandler: function(viewModel, element, event) {
			var formParams = this.element.formParams();
			this.contactVm.attr('isAcceptingInput', false);
			this.contactVm.attr('isSending', true);
			this.sendEmail(formParams);
		},

		cancelHandler: function() {
			this.enableScrolling();
			this.contactVm.attr('isVisible', false);
			$(document).foundation('abide', 'reflow'); //clean up any foundation items, if any
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
				this.contactVm.attr('errorList', inData.messages.map(function(item) {
					return {
						message: item
					}
				}));
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
					this.cancelHandler();
				}.bind(this));
			}.bind(this), 1000);
		},

		disableScrolling: function() {
			//http://stackoverflow.com/questions/3656592/how-to-programmatically-disable-page-scrolling-with-jquery
			$('html, body').css({
				'overflow': 'hidden',
				'height': '100%'
			});
		},

		enableScrolling: function() {
			$('html, body').css({
				'overflow': 'auto',
				'height': 'auto'
			});
		}
	});

});

