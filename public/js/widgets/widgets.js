steal(
	'./widgets.css', 			// application CSS file
	'./models/models.js',		// steals all your models
	'./fixtures/fixtures.js'	// sets up fixtures for your models
)
.then(

	'./controller/base/base.js'
)
.then(
	'./resources/jqueryPlugins/jquery.rc4.js',
	'./resources/jqueryPlugins/jquery.cookie.js',
	'./resources/jqueryPlugins/jquery.md5.js',
	'./resources/jqueryPlugins/jquery.qprompt.js',
	'./resources/jqueryPlugins/spin.js',

	'./resources/jqueryPlugins/qtip/jquery.qtip.min.css',
	'./resources/jqueryPlugins/qtip/jquery.qtip.min.js',

	'./controller/session/register/register.js',
	'./controller/session/dispatch/dispatch.js',
	'./controller/session/login/login.js',

	'./controller/display/anythingslider/anythingslider.js',
	'./controller/display/link_switch/link_switch.js',
	'./controller/display/tab_panel/tab_panel.js',

	'./controller/simple_store/main/main.js',
	'./controller/simple_store/product_selector/product_selector.js',
	'./controller/simple_store/payment_form/payment_form.js',

	'./resources/other/json2.js',
	'./resources/other/mousetrap.js',

	'jquery/dom/form_params',


	'./controller/tools/ui/button2/button2.js',
	'./controller/tools/ui/modal_screen/modal_screen.js',

	'./resources/services/qtools.js',
	'./resources/services/viewHelper2.js',
	'./resources/services/controllerHelper2.js',
	'./resources/services/static.js',

	function(){					// configure your application
		$('body').widgets_session_dispatch();
	})