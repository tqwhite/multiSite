//js widgets/scripts/build.js

load("steal/rhino/rhino.js");
steal('steal/build').then('steal/build/scripts','steal/build/styles',function(){
	steal.build('widgets/scripts/build.html',{to: 'widgets'});
});
