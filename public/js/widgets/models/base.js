steal('jquery/model', function(){

/**
 * @class Widgets.Models.base
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend base services.
 */
$.Model('Widgets.Models.Base',
/* @Static */
{

defaultError:function(){
	var modelName=this._fullName
	return function(){
		if (console) {console.log('Model Error ('+modelName+')');}
	}
},

getStorage:function(){
	var self=qtools.getDottedPath(window, this.fullName);
	if (!self.storage){self.storage={};}
	return self.storage;
},

keep:function(name, inData){
	this.getStorage()[name]=inData;
},

get:function(name){
	return this.getStorage()[name];
},

wrapDataForReturn:function(args, passByReference){
	var outObj, fieldName;

	outObj={};

	fieldName='data';
		if (qtools.isNotEmpty(args[fieldName])){
			outObj[fieldName]=args[fieldName];

			if (passByReference=='passByReference'){
				outObj[fieldName]=args[fieldName];
				outObj['comment']='passByReference==true';
			}
			else{
				outObj[fieldName]=qtools.passByValue(args[fieldName]);
				outObj['comment']='passByReference==false; passing by value';
			}

			outObj['status']=this.goodData;

		}
		else{
			outObj.status=this.noObject;
		}
	fieldName='container';
		if (qtools.isNotEmpty(args[fieldName])){
			outObj[fieldName]=args[fieldName];
		}
	fieldName='status';
		if (qtools.isNotEmpty(args[fieldName])){
			outObj[fieldName]=args[fieldName];
		}
	return outObj;
},

cleanJson:function(inData){
	var tmp=this.executeClean(inData),
		data=JSON.parse(tmp);
	return data.data;
},

executeClean:function(inData){

				return $.ajax({
				async:true,
				url: 'test/utility',
				type: 'post',
				dataType: 'json',
				data: {data:inData.toString()}
			});
}
},
/* @Prototype */
{});

})