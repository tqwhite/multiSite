steal("funcunit", function(){
	module("widgets test", { 
		setup: function(){
			S.open("//widgets/widgets.html");
		}
	});
	
	test("Copy Test", function(){
		equals(S("h1").text(), "Welcome to JavaScriptMVC 3.2!","welcome text");
	});
})