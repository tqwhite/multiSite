steal('funcunit').then(function(){

module("Widgets.Controller.Display.Anythingslider", { 
	setup: function(){
		S.open("//widgets/controller/display/anythingslider/anythingslider.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Display.Anythingslider Demo","demo text");
});


});