steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.PaymentForm.Editors.Card", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/payment_form/editors/card/card.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.PaymentForm.Editors.Card Demo","demo text");
});


});