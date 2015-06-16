define(['/js/fidgets/core/start/setNameSpace.js'], function(nameSpace) {
var scopeObject=window[nameSpace];

var accessLib={
serverControl:function(){
	if (typeof(this.controllerStartupListCache)!=='undefined'){
		return this.controllerStartupListCache;
	}
	var serverControlDomObj=$('#serverControl'), //'#serverData' is defined in Q_Controller_Action_Helper_WriteServerCommDiv()
		formParams, controllerStartupList;

		if (serverControlDomObj.length>0){
			formParams=serverControlDomObj.formParams();
			this.serverControlDomObj=serverControlDomObj;
		}
		else{
			formParams={};
		}

		controllerStartupList=(typeof(formParams.controller_startup_list)!='undefined')?formParams.controller_startup_list:[];

		for (var i in controllerStartupList){
			if (typeof(controllerStartupList[i].parameters)!='undefined'){
				controllerStartupList[i].parameters=$.parseJSON(controllerStartupList[i].parameters);
			}
			else{
				controllerStartupList[i].parameters={};
			}
		}
		
		
		
	this.controllerStartupListCache=controllerStartupList;
		return controllerStartupList;
},


serverData:function(){
	if (typeof(this.serverDataCache)!=='undefined'){
		return this.serverDataCache;
	}

	var serverDataDomObj=$('.serverData'),
		serverData={};

	var list=serverDataDomObj;

	for (var i=0, len=list.length; i<len; i++){
		var element=$(list[i]);
		serverData[element.attr('id')]=JSON.parse(element.text());
	}
	this.serverDataCache=serverData;
	return serverData;
}



};

if (typeof(scopeObject.serverInterface)){
scopeObject.serverInterface=accessLib;
}
return scopeObject.serverInterface;
});


