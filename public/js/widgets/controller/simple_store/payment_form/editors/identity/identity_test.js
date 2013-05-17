steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/payment_form/editors/identity/identity.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.PaymentForm.Editors.Identity Demo","demo text");
});


});