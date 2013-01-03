steal("funcunit/qunit", "pinpoint/fixtures", "pinpoint/models/local_storage.js", function(){
	module("Model: Pinpoint.Models.LocalStorage")
	
	test("findAll", function(){
		expect(4);
		stop();
		Pinpoint.Models.LocalStorage.findAll({}, function(local_storages){
			ok(local_storages)
	        ok(local_storages.length)
	        ok(local_storages[0].name)
	        ok(local_storages[0].description)
			start();
		});
		
	})
	
	test("create", function(){
		expect(3)
		stop();
		new Pinpoint.Models.LocalStorage({name: "dry cleaning", description: "take to street corner"}).save(function(local_storage){
			ok(local_storage);
	        ok(local_storage.id);
	        equals(local_storage.name,"dry cleaning")
	        local_storage.destroy()
			start();
		})
	})
	test("update" , function(){
		expect(2);
		stop();
		new Pinpoint.Models.LocalStorage({name: "cook dinner", description: "chicken"}).
	            save(function(local_storage){
	            	equals(local_storage.description,"chicken");
	        		local_storage.update({description: "steak"},function(local_storage){
	        			equals(local_storage.description,"steak");
	        			local_storage.destroy();
						start();
	        		})
	            })
	
	});
	test("destroy", function(){
		expect(1);
		stop();
		new Pinpoint.Models.LocalStorage({name: "mow grass", description: "use riding mower"}).
	            destroy(function(local_storage){
	            	ok( true ,"Destroy called" )
					start();
	            })
	})
})