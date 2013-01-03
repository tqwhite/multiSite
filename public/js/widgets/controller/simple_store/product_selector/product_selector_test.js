steal('funcunit').then(function(){

module("Widgets.Controller.SimpleStore.ProductSelector", { 
	setup: function(){
		S.open("//widgets/controller/simple_store/product_selector/product_selector.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.SimpleStore.ProductSelector Demo","demo text");
});


});