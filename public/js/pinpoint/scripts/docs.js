//js pinpoint/scripts/doc.js

load('steal/rhino/rhino.js');
steal("documentjs").then(function(){
	DocumentJS('pinpoint/pinpoint.html', {
		markdown : ['pinpoint']
	});
});