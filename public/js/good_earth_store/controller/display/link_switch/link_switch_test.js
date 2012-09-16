steal('funcunit').then(function(){

module("GoodEarthStore.Controller.Display.LinkSwitch", { 
	setup: function(){
		S.open("//good_earth_store/controller/display/link_switch/link_switch.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "GoodEarthStore.Controller.Display.LinkSwitch Demo","demo text");
});


});