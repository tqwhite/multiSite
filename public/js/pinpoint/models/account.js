steal('jquery/model', './base.js', function(){

/**
 * @class Pinpoint.Models.Account
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend account services.
 */
Pinpoint.Models.Base.extend('Pinpoint.Models.Account',
/* @Static */
{

	register:function(data, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:this.defaultError;

		var errors=this.validate(data.data);
		if (errors.length>0){
			success({status:-1, messages:errors, data:{}});
			return;
		}

		success({status:1, messages:'welcome', data:{}});

		$.ajax({
				url: '/account/register',
				type: 'post',
				dataType: 'json',
				data: {data:data},
				success: success,
				error: error,
				fixture: false
			});

},

validate:function(inData){
	var name, datum,
		errors=[];

// 	name='lastName';
// 	datum=inData[name];
// 	if (!datum || datum.toLowerCase()=='required')
// 	{errors.push([name, "Last name is required"]);}
//
// 	name='firstName';
// 	datum=inData[name];
// 	if (!datum || datum.toLowerCase()=='required')
// 	{errors.push([name, "First name is required"]);}
//
// 	name='userName';
// 	datum=inData[name];
// 	if (!datum || datum.length<6 || datum.toLowerCase()=='required')
// 	{errors.push([name, "User Name must be six or more characters"]);}
//
// 	name='password';
// 	datum=inData[name];
// 	if (!datum || datum.length<6)
// 	{errors.push([name, "Password must be six or more characters"]);}

	name='emailAdr';
	datum=inData[name];
	var emailRegexTest = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //thanks: http://www.marketingtechblog.com/javascript-regex-emailaddress/
	if (!datum || !emailRegexTest.test(datum) || datum.toLowerCase()=='required')
	{errors.push([name, "Invalid email address"]);}

// 	name='emailAdr';
// 	datum=inData[name];
// 	if (inData['confirmEmail'] && !datum || datum.toLowerCase()=='required')
// 	{errors.push([name, "Confirmation email address is required"]);}
//
// 	if (inData['emailAdr'] && inData['confirmEmail'] && inData['emailAdr']!=inData['confirmEmail'])
// 	{errors.push([name, "Confirmation email address does not match email address"]);}
//
// 	name='phoneNumber';
// 	datum=inData[name];
// 	if (!datum || datum.toLowerCase()=='000-000-0000')
// 	{errors.push([name, "Phone Number is required"]);}
// 		else if (datum.length!=12)
// 		{errors.push([name, "Phone Number must be 000-000-0000"]);}
// 		else if (!datum.match(/\d{3}[ -]\d{3}[- ]\d{4}/))
// 		{errors.push([name, "Phone Number must be 000-000-0000"]);}


	return errors;
}

},
/* @Prototype */
{});

})