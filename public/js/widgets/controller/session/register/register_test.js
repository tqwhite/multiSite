steal('funcunit').then(function(){

module("Widgets.Controller.Session.Register", { 
	setup: function(){
		S.open("//widgets/controller/session/register/register.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Session.Register Demo","demo text");
});


});