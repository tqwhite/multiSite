steal('funcunit').then(function(){

module("Pinpoint.Controller.Session.Register", { 
	setup: function(){
		S.open("//pinpoint/controller/session/register/register.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.Session.Register Demo","demo text");
});


});