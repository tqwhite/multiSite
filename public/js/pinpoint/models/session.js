steal('jquery/model', './base.js', function(){

/**
 * @class Pinpoint.Models.Session
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend session services.
 */
Pinpoint.Models.Base.extend('Pinpoint.Models.Session',
/* @Static */
{

	start:function(placeholder, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:function(){alert('error');};

		$.ajax({
				url: 'http://'+GLOBALS.serverDomain+'/session/start',
				type: 'post',
				dataType: 'jsonp',
				data: {data:{hello:'hello from UI'}},
				success: success,
				error: error,
				fixture: false,
				crossDomain:true
			});
	},

	login:function(data, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:function(){alert('error');};

		$.ajax({
				url: 'http://'+GLOBALS.serverDomain+'/session/login',
				type: 'post',
				dataType: 'jsonp',
				data: {data:data},
				success: success,
				error: error,
				fixture: false,
				crossDomain:true
			});
	},

	logout:function(data, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:function(){alert('error');};

		$.ajax({
				url: 'http://'+GLOBALS.serverDomain+'/session/logout',
				type: 'post',
				dataType: 'json',
				data: {data:data},
				success: success,
				error: error,
				fixture: false
			});
	},

	saveReferenceLocation:function(){
		this.referenceLocation=qtools.passByValue(window.location);
	},

	getControllerNameFromReference:function(){
		var hash, hash;
		if (this.referenceLocation){
			hash=this.referenceLocation.hashCode;
		}
		else{
			hash=window.location.hash;
		}

		switch (hash){
			default:
				controllerName='register';
				break;
			case 'new':
				controllerName='register';
				break;
			case 'login':
				controllerName='login';
				break;
		}

		return controllerName;

	},

//ENVIRONMENT ====================================================================

	initServerDomain: function(){

		if (typeof(serverDomain)!='undefined'){
			GLOBALS.serverDomain=serverDomain;
			delete (window.serverDomain); //un-pollute the global name space
		}
		else{
			GLOBALS.serverDomain="work.pinpointatomic.com";
		}

	}

},
/* @Prototype */
{});

})