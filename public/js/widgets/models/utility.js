steal('jquery/model', function(){

/**
 * @class Widgets.Models.Utility
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend Utility services.
 */
Widgets.Models.Base.extend('Widgets.Models.Utility',
/* @Static */
{

	md5:function(inString, success, error){

		if (typeof(success)=='string' /*&& success=='return'*/){

			return $.ajax({
					url: '/utility/md5',
					type: 'post',
					dataType: 'json',
					data: {data:inString},
					async:false,
					fixture: false
				});
				
		}
		else{
			success=success?success:qtools.consoleMessage;
			error=error?error:qtools.consoleMessage;

			$.ajax({
					url: '/utility/md5',
					type: 'post',
					dataType: 'json',
					data: {data:inString},
					success: success,
					error: error,
					fixture: false
				});
		}

	}

},
/* @Prototype */
{});

})