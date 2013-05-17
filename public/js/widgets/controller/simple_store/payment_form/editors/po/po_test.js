steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.PaymentForm.Editors.Po", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/payment_form/editors/po/po.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.PaymentForm.Editors.Po Demo","demo text");
});


});