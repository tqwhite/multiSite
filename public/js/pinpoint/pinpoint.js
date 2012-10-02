steal(
	'./models/models.js'
)
.then(
	'./stylesheets/base.css', 			// application CSS file
	'./controller/base/base.js'
)
.then(

	'./controller/session/dispatch/dispatch.js',
	'./controller/session/register/register.js',
	'./controller/session/login/login.js'
)
.then(
	'./resources/jqueryPlugins/jquery.rc4.js',
	'./resources/jqueryPlugins/jquery.cookie.js',
	'./resources/jqueryPlugins/jquery.md5.js',
	'./resources/jqueryPlugins/jquery.qprompt.js',
	'./resources/jqueryPlugins/spin.js',
	'./resources/jqueryPlugins/jquery-ui-1.8.23.custom.min.js', //this file only contains draggable/dropable


	'./controller/app/annotation/panel/panel.js',

	'./controller/app/signup/show_work_script/show_work_script.js',



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
		Pinpoint.Models.Session.initServerDomain(); //picks up global set by index.php script
		$('body').pinpoint_session_dispatch();
	}
)