steal('funcunit').then(function(){

module("Widgets.Controller.Apps.SimpleDataEntry", { 
	setup: function(){
		S.open("//widgets/controller/apps/simple_data_entry/simple_data_entry.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Apps.SimpleDataEntry Demo","demo text");
});


});