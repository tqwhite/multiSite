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
	var name, datum, sectionName,
		errors=[];

	if (!inData.usePurchaseOrder){
	sectionName='creditCardPanel';
		name='name';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Card Name is required"]);}

		name='street';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Card Street is required"]);}

		name='city';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Card City is required"]);}

		name='state';
		datum=inData[sectionName][name];
		if (datum.length!=2)
		{errors.push([name, "Card State code must be two characters"]);}

		name='zip';
		datum=inData[sectionName][name];
		if (!datum || datum=='00000')
		{errors.push([name, "Card Zip Code is required"]);}
			else if (datum.length!=5)
			{errors.push([name, "Card Zip must be five digits (00000)"]);}
			else if (!datum.match(/\d{5}/))
			{errors.push([name, "Card Zip must be five digits (00000)"]);}


		name='number';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Card Number is required"]);}
			else if (datum.replace(/ /g, '', datum).length<15)
			{errors.push([name, "Card number is invalid"]);}
			else if (datum.match(/[^0-9 ]/))
			{errors.push([name, "Numbers and spaces only in card number"]);}

		name='expMonth';
		datum=inData[sectionName][name];
		if (!datum || datum=='MM')
		{errors.push([name, "Card Expiration Date is required"]);}
			else if (datum<1 || datum>12)
			{errors.push([name, "Month must be 1 to 12"]);}

		name='expYear';
		datum=inData[sectionName][name];
		if (!datum || datum=='YY')
		{errors.push([name, "Card Expiration Year is required"]);}
			else if (!datum.match(/^\d{2}$/))
			{errors.push([name, "Year must be two digits"]);}
	}
	else{
	sectionName='purchaseOrderPanel';

		name='number';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Purchase Order Number is required is required"]);}
		
		name='name';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Authorized Person Name is required"]);}

		name='phoneNumber';
		datum=inData[sectionName][name];
		if (!datum || datum=='000-000-0000')
		{errors.push([name, "Phone Number is required"]);}
			else if (datum.length!=12)
			{errors.push([name, "Phone Number must be 000-000-0000"]);}
			else if (!datum.match(/\d{3}[ -]\d{3}[- ]\d{4}/))
			{errors.push([name, "Phone Number must be 000-000-0000"]);}

		name='street';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "PO Street is required"]);}

		name='city';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "PO City is required"]);}

		name='state';
		datum=inData[sectionName][name];
		if (datum.length!=2)
		{errors.push([name, "PO State code must be two characters"]);}

		name='zip';
		datum=inData[sectionName][name];
		if (!datum || datum=='00000')
		{errors.push([name, "PO Zip Code is required"]);}
			else if (datum.length!=5)
			{errors.push([name, "Zip must be five digits (00000)"]);}
			else if (!datum.match(/\d{5}/))
			{errors.push([name, "Zip must be five digits (00000)"]);}

			
	}
//start shipping


	if (inData.usePurchaseOrder){
	sectionName='shippingPanel';
		name='name';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Shipping Name is required"]);}

		name='street';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Shipping Street is required"]);}

		name='city';
		datum=inData[sectionName][name];
		if (!datum)
		{errors.push([name, "Shipping City is required"]);}

		name='state';
		datum=inData[sectionName][name];
		if (datum.length!=2)
		{errors.push([name, "Shipping State code must be two characters"]);}

		name='zip';
		datum=inData[sectionName][name];
		if (!datum || datum=='00000')
		{errors.push([name, "Shipping Zip Code is required"]);}
			else if (datum.length!=5)
			{errors.push([name, "Zip must be five digits (00000)"]);}
			else if (!datum.match(/\d{5}/))
			{errors.push([name, "Zip must be five digits (00000)"]);}

	}
	
//start identity

	sectionName='identityPanel';
	name='name';
	datum=inData[sectionName][name];
	if (!datum)
	{errors.push([name, "Name is required"]);}

	name='phoneNumber';
	datum=inData[sectionName][name];
	if (!datum || datum=='000-000-0000')
	{errors.push([name, "Phone Number is required"]);}
		else if (datum.length!=12)
		{errors.push([name, "Phone Number must be 000-000-0000"]);}
		else if (!datum.match(/\d{3}[ -]\d{3}[- ]\d{4}/))
		{errors.push([name, "Phone Number must be 000-000-0000"]);}

	name='emailAdr';
	datum=inData[sectionName][name];
	var emailRegexTest = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //thanks: http://www.marketingtechblog.com/javascript-regex-emailaddress/
	if (!datum || !emailRegexTest.test(datum) || datum.toLowerCase()=='required')
	{errors.push([name, "Invalid email address"]);}

	name='emailAdr';
	datum=inData[sectionName][name];
	if (inData[sectionName]['confirmEmail'] && !datum || datum.toLowerCase()=='required')
	{errors.push([name, "Confirmation email address is required"]);}

	if (inData[sectionName]['emailAdr'] && inData[sectionName]['confirmEmail'] && inData[sectionName]['emailAdr']!=inData[sectionName]['confirmEmail'])
	{errors.push([name, "Confirmation email address does not match email address"]);}
	
	//end of identity

	return errors;
}


},
/* @Prototype */
{});

})