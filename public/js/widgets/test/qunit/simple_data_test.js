steal("funcunit/qunit", "widgets/fixtures", "widgets/models/simple_data.js", function(){
	module("Model: Widgets.Models.SimpleData")
	
	test("findAll", function(){
		expect(4);
		stop();
		Widgets.Models.SimpleData.findAll({}, function(simple_datas){
			ok(simple_datas)
	        ok(simple_datas.length)
	        ok(simple_datas[0].name)
	        ok(simple_datas[0].description)
			start();
		});
		
	})
	
	test("create", function(){
		expect(3)
		stop();
		new Widgets.Models.SimpleData({name: "dry cleaning", description: "take to street corner"}).save(function(simple_data){
			ok(simple_data);
	        ok(simple_data.id);
	        equals(simple_data.name,"dry cleaning")
	        simple_data.destroy()
			start();
		})
	})
	test("update" , function(){
		expect(2);
		stop();
		new Widgets.Models.SimpleData({name: "cook dinner", description: "chicken"}).
	            save(function(simple_data){
	            	equals(simple_data.description,"chicken");
	        		simple_data.update({description: "steak"},function(simple_data){
	        			equals(simple_data.description,"steak");
	        			simple_data.destroy();
						start();
	        		})
	            })
	
	});
	test("destroy", function(){
		expect(1);
		stop();
		new Widgets.Models.SimpleData({name: "mow grass", description: "use riding mower"}).
	            destroy(function(simple_data){
	            	ok( true ,"Destroy called" )
					start();
	            })
	})
})