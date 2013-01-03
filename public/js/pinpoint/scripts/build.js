//js pinpoint/scripts/build.js

load("steal/rhino/rhino.js");
steal('steal/build').then('steal/build/scripts','steal/build/styles',function(){
	steal.build('pinpoint/scripts/build.html',{to: 'pinpoint'});
});
