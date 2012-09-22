steal("funcunit/qunit", "pinpoint/fixtures", "pinpoint/models/account.js", function(){
	module("Model: Pinpoint.Models.Account")
	
	test("findAll", function(){
		expect(4);
		stop();
		Pinpoint.Models.Account.findAll({}, function(accounts){
			ok(accounts)
	        ok(accounts.length)
	        ok(accounts[0].name)
	        ok(accounts[0].description)
			start();
		});
		
	})
	
	test("create", function(){
		expect(3)
		stop();
		new Pinpoint.Models.Account({name: "dry cleaning", description: "take to street corner"}).save(function(account){
			ok(account);
	        ok(account.id);
	        equals(account.name,"dry cleaning")
	        account.destroy()
			start();
		})
	})
	test("update" , function(){
		expect(2);
		stop();
		new Pinpoint.Models.Account({name: "cook dinner", description: "chicken"}).
	            save(function(account){
	            	equals(account.description,"chicken");
	        		account.update({description: "steak"},function(account){
	        			equals(account.description,"steak");
	        			account.destroy();
						start();
	        		})
	            })
	
	});
	test("destroy", function(){
		expect(1);
		stop();
		new Pinpoint.Models.Account({name: "mow grass", description: "use riding mower"}).
	            destroy(function(account){
	            	ok( true ,"Destroy called" )
					start();
	            })
	})
})