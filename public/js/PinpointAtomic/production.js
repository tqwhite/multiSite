steal.loading('PinpointAtomic/PinpointAtomic.js','PinpointAtomic/demo.js');
steal({src: 'PinpointAtomic/production.css', has: ['']});
steal("./demo.js",function(){console.log("getting started")});steal.loaded("PinpointAtomic/PinpointAtomic.js");console.log("hello from demo"+window.serverDomain);steal.loaded("PinpointAtomic/demo.js");
