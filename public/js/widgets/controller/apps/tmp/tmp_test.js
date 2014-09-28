steal('funcunit').then(function(){

module("Widgets.Controller.Apps.Tmp", { 
	setup: function(){
		S.open("//widgets/controller/apps/tmp/tmp.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Apps.Tmp Demo","demo text");
});


});