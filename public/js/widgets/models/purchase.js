steal('jquery/model', function(){

/**
 * @class Widgets.Models.Purchase
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend purchase services.
 */
Widgets.Models.Base.extend('Widgets.Models.Purchase',
/* @Static */
{

	process:function(data, success, error){

		success=success?success:function(){alert('success');};
		error=error?error:this.defaultError;

console.dir({serverData:data});
		var errors=this.validate(data.cardData);
		if (errors.length>0){
			success({status:-1, messages:errors, data:{}});
			return;
		}


var toServer=data;

		$.ajax({
				url: data.paymentServerUrl,
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
	name='cardName';
	datum=inData[name];
	if (!datum)
	{errors.push([name, "Card Name is required"]);}

	name='street';
	datum=inData[name];
	if (!datum)
	{errors.push([name, "Street is required"]);}

	name='city';
	datum=inData[name];
	if (!datum)
	{errors.push([name, "City is required"]);}

	name='state';
	datum=inData[name];
	if (datum.length!=2)
	{errors.push([name, "State code must be two characters"]);}

	name='zip';
	datum=inData[name];
	if (!datum || datum=='00000')
	{errors.push([name, "Zip Code is required"]);}
		else if (datum.length!=5)
		{errors.push([name, "Zip must be five digits (00000)"]);}
		else if (!datum.match(/\d{5}/))
		{errors.push([name, "Zip must be five digits (00000)"]);}

	name='phoneNumber';
	datum=inData[name];
	if (!datum || datum=='000-000-0000')
	{errors.push([name, "Phone Number is required"]);}
		else if (datum.length!=12)
		{errors.push([name, "Phone Number must be 000-000-0000"]);}
		else if (!datum.match(/\d{3}[ -]\d{3}[- ]\d{4}/))
		{errors.push([name, "Phone Number must be 000-000-0000"]);}


	name='cardNumber';
	datum=inData[name];
	if (!datum)
	{errors.push([name, "Card Number is required"]);}
		else if (datum.replace(/ /g, '', datum).length<15)
		{errors.push([name, "Card number is invalid"]);}
		else if (datum.match(/[^0-9 ]/))
		{errors.push([name, "Numbers and spaces only in card number"]);}

	name='expMonth';
	datum=inData[name];
	if (!datum || datum=='MM')
	{errors.push([name, "Expiration Date is required"]);}
		else if (!datum.match(/^\d{2}$/))
		{errors.push([name, "Month must be two digits"]);}
		else if (datum<1 || datum>12)
		{errors.push([name, "Month must be 1 to 12"]);}

	name='expYear';
	datum=inData[name];
	if (!datum || datum=='YY')
	{errors.push([name, "year is required"]);}
		else if (!datum.match(/^\d{2}$/))
		{errors.push([name, "Year must be two digits"]);}

	name='emailAdr';
	datum=inData[name];
	var emailRegexTest = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //thanks: http://www.marketingtechblog.com/javascript-regex-emailaddress/
	if (!datum || !emailRegexTest.test(datum) || datum.toLowerCase()=='required')
	{errors.push([name, "Invalid email address"]);}

	name='emailAdr';
	datum=inData[name];
	if (inData['confirmEmail'] && !datum || datum.toLowerCase()=='required')
	{errors.push([name, "Confirmation email address is required"]);}

	if (inData['emailAdr'] && inData['confirmEmail'] && inData['emailAdr']!=inData['confirmEmail'])
	{errors.push([name, "Confirmation email address does not match email address"]);}

	name='name';
	datum=inData[name];
	if (!datum)
	{errors.push([name, "Name is required"]);}

	return errors;
}


},
/* @Prototype */
{});

})