steal('jquery/controller', 'jquery/view/ejs')
	.then('./views/init.ejs', function($) {

	/**
	 * @class Widgets.Controller.Apps.SimpleDataEntry
	 */
	Widgets.Controller.Base.extend('Widgets.Controller.Apps.SimpleDataEntry',
	/** @Static */
	{
		defaults: {}
	},
	/** @Prototype */
	{

		//
		//dependency phrase: widgets/controller/apps/simple_data_entry/simple_data_entry.js
		//

		init: function(element, options) {
			this.baseInits();
			this.element = $(element);
			this.options=options;


			qtools.validateProperties({
				targetObject: options,
				targetScope: this, //will add listed items to targetScope
				propList: [
					{
						name: 'saveButtonSelector'
					},
					{
						name: 'parameterFileName'
					}
				],
				source: this.constructor._fullName
			});


			this.initControlProperties(options);
			this.initDisplayProperties();
			this.initDisplay({});


			return this;
		},

		update: function(control, parameter) {
			switch (control) {
				case 'hide':
					this.element.hide();
					break;
				case 'show':
					this.element.show();
					break;
				default:
					if (typeof (parameter) == 'object') {
						this.options = $.extend(this.options, parameter);
					}
					this.init(this.element, this.options);
					break;
				case 'setAccessFunction':
					if (!this.employer) {
						this.employer = {};
					}
					this.employer.accessFunction = parameter;
					break;
			}

			return this;
		},

		initDisplayProperties: function() {

			this.serverData = Widgets.Models.Session.get('serverData');

			qtools.consoleMessage(this.serverData);

			qtools.validateProperties({
				targetObject: this.serverData,
				targetScope: this, //will add listed items to targetScope
				propList: [{
						name: 'parameters'
					}],
				source: this.constructor._fullName,
				showAlertFlag: true
			});
			qtools.validateProperties({
				targetObject: this.serverData['parameters'],
				targetScope: this, //will add listed items to targetScope
				propList: [{
						name: this.parameterFileName
					}, {
						name: 'processContentSourceRouteName'
					}],
				source: this.constructor._fullName,
				showAlertFlag: true
			});

			nameArray = [];

			name = 'contentContainer'; nameArray.push({
				name: name
			});
			name = 'saveButton'; nameArray.push({
				name: name,
				handlerName: name + 'Handler',
				targetDivId: name + 'Target'
			});
			
			
			name = 'statusDiv'; nameArray.push({
				name: name
			});

			this.displayParameters = $.extend(this.componentDivIds, this.assembleComponentDivIdObject(nameArray));

		},

		initControlProperties: function(options) {
			this.viewHelper = new viewHelper2();
			
			
			this.controlParameters=options;

		},

		initDisplay: function(inData) {

			//in multiSite, the html is specified by the user, leaving this code in case I want to inject something later
			// 	var html=$.View("//widgets/controller/apps/tmp/views/init.ejs",
			// 		$.extend(inData, {
			// 			displayParameters:this.displayParameters,
			// 			viewHelper:this.viewHelper,
			// 			formData:{
			// 				message:'Widgets.Controller.Apps.Tmp'
			// 			}
			// 		})
			// 		);
			// 		
			// 	this.element.html(html);

			this.initDomElements();
		},

		initDomElements: function() {

			this.initRefId();

			var displayItem = this.displayParameters.saveButton;

			$('#' + displayItem.divId).click(displayItem.handler);

			var retrievedLabel = $(this.saveButtonSelector).text(); //for this use, I want the label to be specified by the html

			var name = 'saveButton',
				displayItem = this.displayParameters[name];
			displayItem['controllerName'] = 'widgets_tools_ui_button2';
			displayItem.domObj = $(this.saveButtonSelector)
			[displayItem.controllerName]({
				ready: {
					classs: 'basicReady'
				},
				hover: {
					classs: 'basicHover'
				},
				clicked: {
					classs: 'basicActive'
				},
				unavailable: {
					classs: 'basicUnavailable'
				},
				accessFunction: displayItem.handler,
				initialControl: 'setToReady', //initialControl:'setUnavailable'
				label: retrievedLabel
			})
			.addClass('basicButton');

			this.element.find('input').qprompt();
			
			var name='statusDiv',
			displayItem = this.displayParameters[name];
			displayItem.domObj=$('#'+name);
			
			if (!displayItem.domObj.length){
				this.element.prepend("<div id='statusDiv' class='statusDiv'></div>");
				displayItem.domObj=$('#'+name);
			}
			
			this.statusDiv=displayItem.domObj;
			
		},

		//BUTTON HANDLERS =========================================================================================================

		saveButtonHandler: function(control, parameter) {
			var componentName = 'saveButton',
				displayItem = this.displayParameters[componentName];

			if (control.which == '13') {
				control = 'click';
			}
			; //enter key

			switch (control.type || control) {
				case 'click':
					if (this.isAcceptingClicks()) {
						this.turnOffClicksForAwhile(200);
					} else {
						eventObj.preventDefault(); return;
					}

					var rawParams = this.element.formParams(),
						formParams={};
					

					for (var i in rawParams){
						var item=rawParams[i],
							type=$(this.element).find('[name="'+i+'"]').attr('type');

						if (type=='radio'){
							formParams[i]=(item && item[0])?item[0]:item;
						}
						else{
							formParams[i]=item;
						}
					}
					
					
					Widgets.Models.SimpleData.save({
						formParams: formParams,
						controlParameters: this.controlParameters,
						serverManagement: {
							processContentSourceRouteName: this.processContentSourceRouteName
						}
					},
					this.callback(this.saveStatusCallback));



					break;
				case 'setAccessFunction':
					if (!this[componentName]) {
						this[componentName] = {};
					}
					this[componentName].accessFunction = parameter;
					break;
			}
			//change dblclick mousedown mouseover mouseout dblclick
			//focusin focusout keydown keyup keypress select
		},

		//APPLICATION SPECIFIC =========================================================================================================

		initRefId: function() {
			var params = this.element.formParams();
			if (!params.refId) {
				this.element.append("<input type='hidden' name='refId' value='" + qtools.newGuid() + "'>");
			}
		},

		saveStatusCallback: function(status) {
			if (status.status==1){
			if (status.data.type=='insert'){
				this.statusDiv.text("Record added to database (id="+status.data.formParams.refId+")");
			}
			else{
				this.statusDiv.text("Existing record updated (id="+status.data.formParams.refId+")");
			}
			this.clearEntryFields();
			this.update('', {});
			}
			else{
				this.statusDiv.text("Problem: "+status.message);
			}
		},
		
		clearEntryFields:function(){
			var fields=this.element.find('input');
	for (var i=0, len=fields.length; i<len; i++){
		var element=fields[i];
		$(element).val('');
	}
			var fields=this.element.find('textarea');
	for (var i=0, len=fields.length; i<len; i++){
		var element=fields[i];
		$(element).val('');
	}
		}
	}) //end of method-containing object ===

});

