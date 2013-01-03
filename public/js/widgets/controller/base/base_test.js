steal('funcunit').then(function(){

module("Widgets.Controller.Base", {
	setup: function(){
		S.open("//widgets/controller/base/base.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Base Demo","demo text");
});


});