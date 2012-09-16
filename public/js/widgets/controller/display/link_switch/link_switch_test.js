steal('funcunit').then(function(){

module("Widgets.Controller.Display.LinkSwitch", { 
	setup: function(){
		S.open("//widgets/controller/display/link_switch/link_switch.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Display.LinkSwitch Demo","demo text");
});


});