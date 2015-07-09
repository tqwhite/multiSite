define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/widgets/resources/services/qtools.js',
	'/js/fidgets/controls/base/control.js',
	'/js/vendor/foundation/foundation.min.js',
	'can/component', 'can/view', 'can/view/stache',

	'/js/vendor/jquery_downloads/jquery-bullseye-1-0b/jquery.bullseye-1.0.js'

], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.VerticalStructure';

	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {
			this.directory = '/js/fidgets/controls/verticalStructure/';
			this._super(); //execute base.init(), only works in method 'init'
			//		this.loadCssFile('/js/vendor/jquery_downloads/pagePiling.js-master/jquery.pagepiling.css', this.startThisController.bind(this, element, options));
			this.startThisController(element, options);
		},

		startThisController: function(element, options) {

			qtools.validateProperties({
				targetObject: options,
				targetScope: this, //will add listed items to targetScope
				propList: [
					{
						name: 'defaultPanelId', //go to this if bookmark happens that's not in me
						optional: true
					}
				],
				source: this.constructor._fullName
			});

			this.controllerName = controllerName;

			this.initControlProperties(options);
			this.initDisplayParameters();
			this.establishViewModel();

			this.initDisplay();
			this.updateDom();

		},

		update: function(options) {
			this.startThisController(this.element, options);
		},

		initControlProperties: function(options) {},

		initDisplayParameters: function() {
			this.createPanelLookupLists();
		},

		updateDom: function() {

			this.executeBookmark(this.getInxFromLocation());

			 			this.setupAnchorClickSupport();
			this.setupHashUpdateSupport();
		},

		establishViewModel: function() {},

		initDisplay: function() {},

		setupAnchorClickSupport: function() {
			$('body').bind('click.' + this.eventNameSpace, function(event) {
				this.isAnchor(event.target) && this.executeBookmark(this.getInxFromTarget(event.target))
			}.bind(this));
		},

		setupHashUpdateSupport: function() {

			for (var i = 0, len = this.panelDomObjList.length; i < len; i++) {
				var element = this.panelDomObjList[i];
				var height = $(element).height()
				$(element).on('enterviewport.' + this.eventNameSpace, function(event) {
						this.updateHash(event)
					}.bind(this))
					.bullseye({
						offsetTop: Math.min(200, Math.floor(height / 2))
					});
				/*
					note: this routine works correctly for small panels when scrolling down.
					When scrolling up, whichever small panels are visible at the bottom of the 
					screen don't get their hash set on the way back up. I anticipate that panels
					will tend to be large enough so this won't be a problem. If I'm wrong, I'll 
					do something more complicated to make it work better.
				*/
			}

		},

		createPanelLookupLists: function() {
			var panels = this.element.children();
			this.panelIdList = {};
			this.panelInxList = [];
			this.panelDomObjList = [];

			panels.each(function(inx, item, all) {
				var id = $(item).attr('id');
				this.panelIdList[id] = inx;

				this.panelInxList.push(id);
				this.panelDomObjList.push(item);

			}.bind(this));

		},

		executeBookmark: function(inx) {
			if (typeof(inx)=='undefined') {
				return false;
			}

			else{

				var target = this.panelDomObjList[inx];
				if (target) {
					target = $(target);

					var transparent = {
							zoom: '1',
							filter: 'alpha(opacity=20)',
							opacity: '0.2'
						},
						opaque = {
							zoom: '1',
							filter: 'alpha(opacity=100)',
							opacity: '1',
							scrollTop: target.offset().top
						};

					$("html, body")
					//.css(transparent)
					.animate(opaque, 1000, function() {
						this.updateHash(inx);
					}.bind(this));
				return true;
				}

			}

			return false;
		},

		getInxFromLocation: function(href) {
			var sourceString = href || window.location.hash || window.location.search,
				hashMatch = sourceString.match(/\#id=(.*)(\W*)/),
				searchMatch = sourceString.match(/\?id=(.*)(\W*)/),
				match = hashMatch?hashMatch:searchMatch,
				id = match ? match[1] : '',
				inx = this.panelIdList[id];
				
				inx=inx?inx:this.panelIdList[this.defaultPanelId]
			return inx;

		},

		isAnchor: function(target) {
			return $(target).prop('tagName') == 'A';
		},

		getInxFromTarget: function(target) {
			target = $(target),
			href = target.attr('href');
			return this.getInxFromLocation(href);
		},

		updateHash: function(arg) {
			if (!arg) {
				return;
			}
			if (typeof (arg) == 'object') {
				var item = $(arg.target),
					id = $(item).attr('id');
			} else {

				var id = this.panelInxList[arg];
			}

			window.location.hash = "#id=" + id;

		}
	});

});


