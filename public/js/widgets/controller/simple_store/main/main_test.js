steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.Main", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/main/main.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.Main Demo","demo text");
});


});