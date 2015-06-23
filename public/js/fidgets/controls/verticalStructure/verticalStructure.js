define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/widgets/resources/services/qtools.js',
	'/js/fidgets/controls/base/control.js',
	'/js/vendor/foundation/foundation.min.js',
	'can/component', 'can/view', 'can/view/stache',

	'/js/vendor/jquery_downloads/jquery-bullseye-1-0b/jquery.bullseye-1.0.js',
	'/js/vendor/jquery_downloads/pagePiling.js-master/jquery.pagepiling.min.js'

], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.VerticalStructure';

	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {
			this.directory = '/js/fidgets/controls/verticalStructure/';

			qtools.validateProperties({
				targetObject: options,
				targetScope: this, //will add listed items to targetScope
				propList: [
					{
						name: 'placeholder',
						optional: true
					}
				],
				source: this.constructor._fullName
			});

			this._super(); //execute base.init()


			this.controllerName = controllerName;

			this.initControlProperties(options);
			this.initDisplayParameters();
			this.establishViewModel();

			this.initDisplay();

			this.updateDom();

		},

		update: function(options) {
			this.init(this.element, options);
		},

		initControlProperties: function(options) {},

		initDisplayParameters: function() {
			this.createPanelLookupLists();
		},

		updateDom: function() {
		
			this.addEnterViewpointBehavior();
			//			this.executeBookmark(this.getInxFromLocation());

			// 			this.setupAnchorClickSupport();
			// 			this.setupHashUpdateSupport();
		},

		establishViewModel: function() {},

		initDisplay: function() {
			/*
			Thought the docs (http://canjs.com/docs/can.view.html) clearly say, "To render to a string",
			it doesn't. I've tried a lot of things to make it work. I copied and pasted the actual 
			css into style.stach, which used to be
				<style>
					{{styleDefinitions}}
				</style>
			I have to move on. Sorry, tqii

					var styleDefinitions=can.view.render('/js/vendor/jquery_downloads/pagePiling.js-master/jquery.pagepiling.css');
			*/
			
			this.injectCss();
			this.addPagePileingSectionClass();
			this.element.pagepiling();


		},

		injectCss: function() {
			var renderer = can.view(this.directory + 'style.stache');
			var html = renderer(
			{
				//	styleDefinitions:styleDefinitions
			});
			$('body').prepend(html);
		},

		setupAnchorClickSupport: function() {
			$('body').on('click.' + this.eventNameSpace, function(event) {
				this.isAnchor(event.target) && this.executeBookmark(this.getInxFromTarget(event.target))
			}.bind(this));
		},

		setupHashUpdateSupport: function() {
			this.element.on('afterChange.' + this.eventNameSpace, function(element, slick, inx) {
				this.updateHash(inx)
			}.bind(this));
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
			console.dir({
				"this.panelDomObjList": this.panelDomObjList
			});


		},

		addPagePileingSectionClass: function() {

			for (var i = 0, len = this.panelDomObjList.length; i < len; i++) {
				var element = this.panelDomObjList[i];
				$(element).addClass('section');;

			}

		},

		executeBookmark: function(inx) {
			if (inx) {
				setTimeout(function() {

					console.log('what tool for this');
					//			this.element.slick('slickGoTo', inx, true);
				}.bind(this), 2000);
				return true;
			}

			return false;
		},

		getInxFromLocation: function(href) {
			var sourceString = href || window.location.hash || window.location.search,
				match = sourceString.match(/id=(.*)(\W*)/),
				id = match ? match[1] : '',
				inx = this.panelIdList[id];
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

		updateHash: function(inx) {
			var id = this.panelInxList[inx];
			window.location.hash = '#id=' + id;
		},

		addEnterViewpointBehavior: function() {

return;

			for (var i = 0, len = this.panelDomObjList.length; i < len; i++) {
				var element = this.panelDomObjList[i];
				$(element)
					// 				.css({
					// 						zoom: ' 1',
					// 						filter: ' alpha(opacity=50)',
					// 						opacity: ' 0.5'
					// 					})
					.on('enterviewport', function(event) {
						setTimeout(this.updateHash.bind(this, event), 500);
					}.bind(this))
					// 					.on('leaveviewport', function() {
					// 						setTimeout(function(){ $(this).css({ zoom: ' 1', filter: ' alpha(opacity=50)', opacity: '.5' });}.bind(this), 500);
					// 					})
					// 			.on('leaveviewport', function(){$(this).css({zoom:' 1',
					// filter:' alpha(opacity=50)',
					// opacity:' 0.5'});})
					.bullseye();

			}

		},

		updateHash: function(event) {
			var item = $(event.target),
				id = $(item).attr('id');

			window.location.hash = "#id=" + id;

		}
	});

});












