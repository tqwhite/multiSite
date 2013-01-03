steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.PaymentForm", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/payment_form/payment_form.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.PaymentForm Demo","demo text");
});


});