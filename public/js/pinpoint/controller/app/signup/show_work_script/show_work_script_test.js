steal('funcunit').then(function(){

module("Pinpoint.Controller.App.Signup.ShowWorkScript", { 
	setup: function(){
		S.open("//pinpoint/controller/app/signup/show_work_script/show_work_script.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.App.Signup.ShowWorkScript Demo","demo text");
});


});