steal('funcunit').then(function(){

module("Pinpoint.Controller.Session.Dispatch", { 
	setup: function(){
		S.open("//pinpoint/controller/session/dispatch/dispatch.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.Session.Dispatch Demo","demo text");
});


});