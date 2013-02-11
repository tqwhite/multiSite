steal('funcunit').then(function(){

module("Widgets.Controller.Apps.Bookmark.Create", { 
	setup: function(){
		S.open("//widgets/controller/apps/bookmark/create/create.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Widgets.Controller.Apps.Bookmark.Create Demo","demo text");
});


});