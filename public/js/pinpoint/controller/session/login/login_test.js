steal('funcunit').then(function(){

module("Pinpoint.Controller.Session.Login", { 
	setup: function(){
		S.open("//pinpoint/controller/session/login/login.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.Session.Login Demo","demo text");
});


});