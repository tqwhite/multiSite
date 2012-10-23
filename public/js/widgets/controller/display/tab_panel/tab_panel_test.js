steal('funcunit').then(function(){

module("Widgets.Controller.Display.TabPanel", { 
	setup: function(){
		S.open("//widgets/controller/display/tab_panel/tab_panel.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Display.TabPanel Demo","demo text");
});


});