steal('funcunit').then(function(){

module("Widgets.Controller.Session.Dispatch", { 
	setup: function(){
		S.open("//widgets/controller/session/dispatch/dispatch.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Session.Dispatch Demo","demo text");
});


});