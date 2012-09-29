//js PinpointAtomic/scripts/build.js

load("steal/rhino/rhino.js");
steal('steal/build').then('steal/build/scripts','steal/build/styles',function(){
	steal.build('PinpointAtomic/scripts/build.html',{to: 'PinpointAtomic'});
});
