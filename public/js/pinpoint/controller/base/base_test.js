steal('funcunit').then(function(){

module("Pinpoint.Controller.Base", {
	setup: function(){
		S.open("//pinpoint/controller/base/base.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Pinpoint.Controller.Base Demo","demo text");
});


});