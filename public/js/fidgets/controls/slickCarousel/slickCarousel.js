define([
	'/js/fidgets/core/start/setNameSpace.js',
	'/js/widgets/resources/services/qtools.js',
	'/js/fidgets/controls/base/control.js',
	'/js/vendor/foundation/foundation.min.js',

	'/js/vendor/node_modules/slick-carousel/slick/slick.js'], function(nameSpace) {
	var scopeObject = window[nameSpace],
		controllerName = nameSpace + '.Control.SlickCarousel';

	scopeObject.Control.Base.extend(controllerName, {
	}, {
		init: function(element, options) {
			
		qtools.validateProperties({
			targetObject:options,
			targetScope: this, //will add listed items to targetScope
			propList:[
				{name:'slickParms', optional:false}
			],
			source:this.constructor._fullName
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
			if (options == 'kill') {
				this.killSlick();
				return;
			}
			this.init(this.element, options);
		},

		initControlProperties: function(options) {
			this.createPanelLookupLists();
		},

		initDisplayParameters: function() {
			this.slickParms=qtools.extendToNew({
				dots: true,
				infinite: true,
				speed: 300,
				autoplay: false,
				arrows: true,
				appendArrows: $('#scroll1NavContainer'),
				fade: true,
				mobileFirst: true
			}, this.slickParms);
		
		},

		updateDom: function() {
			this.executeBookmark(this.getInxFromLocation());

			this.setupAnchorClickSupport();
			this.setupHashUpdateSupport();
		},

		establishViewModel: function() {},

		initDisplay: function() {
			this.element.slick(this.slickParms);

		},

		setupAnchorClickSupport: function() {
			$('body').on('click.' + this.eventNameSpace, function(event) {
				this.isAnchor(event.target) && this.executeBookmark(this.getInxFromTarget(event.target))
			}.bind(this));
		},
		
		setupHashUpdateSupport: function() {
			this.element.on('afterChange.'+this.eventNameSpace, function(element, slick, inx) {
				this.updateHash(inx)
			}.bind(this));
		},
		
		createPanelLookupLists: function() {
			var panels = this.element.children();
			this.panelIdList = {};
			this.panelInxList = [];

			panels.each(function(inx, item, all) {
				var id = $(item).attr('id');
				this.panelIdList[id] = inx;
				this.panelInxList.push(id);


			}.bind(this));
		},
		
		executeBookmark: function(inx) {
			if (inx) {
				setTimeout(function() {
					this.element.slick('slickGoTo', inx, true);
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

		killSlick: function() {
			this.element.slick('unslick');
			$('body').off('click.' + this.eventNameSpace);
			this.element.off('afterChange.'+this.eventNameSpace);
		},

		updateHash: function(inx) {
			var id = this.panelInxList[inx];
			window.location.hash = '#id=' + id;
		}
	});

});






