steal('funcunit').then(function(){

module("Widgets.Controller.Features.ContactForm", { 
	setup: function(){
		S.open("//widgets/controller/features/contact_form/contact_form.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Features.ContactForm Demo","demo text");
});


});