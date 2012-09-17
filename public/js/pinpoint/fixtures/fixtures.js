// map fixtures for this application

steal("jquery/dom/fixture", function(){
	
	$.fixture.make("base", 5, function(i, base){
		var descriptions = ["grill fish", "make ice", "cut onions"]
		return {
			name: "base "+i,
			description: $.fixture.rand( descriptions , 1)[0]
		}
	})
	$.fixture.make("local_storage", 5, function(i, local_storage){
		var descriptions = ["grill fish", "make ice", "cut onions"]
		return {
			name: "local_storage "+i,
			description: $.fixture.rand( descriptions , 1)[0]
		}
	})
	$.fixture.make("session", 5, function(i, session){
		var descriptions = ["grill fish", "make ice", "cut onions"]
		return {
			name: "session "+i,
			description: $.fixture.rand( descriptions , 1)[0]
		}
	})
})