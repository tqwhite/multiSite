steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.PaymentForm.Editors.Shipping", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/payment_form/editors/shipping/shipping.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.PaymentForm.Editors.Shipping Demo","demo text");
});


});