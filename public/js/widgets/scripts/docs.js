//js widgets/scripts/doc.js

load('steal/rhino/rhino.js');
steal("documentjs").then(function(){
	DocumentJS('widgets/widgets.html', {
		markdown : ['widgets']
	});
});