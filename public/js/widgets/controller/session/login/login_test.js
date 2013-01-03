steal('funcunit').then(function(){

module("Widgets.Controller.Session.Login", { 
	setup: function(){
		S.open("//widgets/controller/session/login/login.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Session.Login Demo","demo text");
});


});