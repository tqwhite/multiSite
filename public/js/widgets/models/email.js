steal('jquery/model', function(){

/**
 * @class Widgets.Models.Email
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend email services.
 */
Widgets.Models.Base.extend('Widgets.Models.Email',
/* @Static */
{

	send:function(data, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:this.defaultError;

		data.formParams.internalEmailAdr=data.formParams.internalEmailAdr.replace(/\{noSpam\}/, '@');

		var errors=this.validate(data.formParams);
		if (errors.length>0){
			success({status:-1, messages:errors, data:{}});
			return;
		}


var toServer=data;

		$.ajax({
				url: '/email/form',
				type: 'post',
				dataType: 'json',
				data: {data:toServer},
				success: success,
				error: error,
				fixture: false
			});

},

validate:function(inData){
	var name, datum,
		errors=[];

	name='internalEmailAdr';
	datum=inData[name];
	var emailRegexTest = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //thanks: http://www.marketingtechblog.com/javascript-regex-emailaddress/
	if (!datum || !emailRegexTest.test(datum) || datum.toLowerCase()=='required')
	{errors.push([name, "Invalid email address"]);}

	return errors;
}


},
/* @Prototype */
{});

})