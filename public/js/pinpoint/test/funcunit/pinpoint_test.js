steal("funcunit", function(){
	module("pinpoint test", { 
		setup: function(){
			S.open("//pinpoint/pinpoint.html");
		}
	});
	
	test("Copy Test", function(){
		equals(S("h1").text(), "Welcome to JavaScriptMVC 3.2!","welcome text");
	});
})