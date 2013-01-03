steal('funcunit').then(function(){

module("Pinpoint.Controller.App.Annotation.Panel", { 
	setup: function(){
		S.open("//pinpoint/controller/app/annotation/panel/panel.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.App.Annotation.Panel Demo","demo text");
});


});