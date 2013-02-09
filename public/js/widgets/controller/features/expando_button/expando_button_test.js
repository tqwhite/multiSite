steal('funcunit').then(function(){

module("Widgets.Controller.Features.ExpandoButton", { 
	setup: function(){
		S.open("//widgets/controller/features/expando_button/expando_button.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Features.ExpandoButton Demo","demo text");
});


});