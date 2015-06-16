define(['can/control', 'can/view/mustache', 'can/model'], function(Control, can) {


return can.Control.extend('Fidgets.ServerData', {
init:function(){
	console.dir(this.getControllerStartupList());
	console.dir(this.getServerData());
},


getControllerStartupList:function(){
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
		
		
		
		return controllerStartupList;
},


getServerData:function(){
	var serverDataDomObj=$('.serverData'),
		serverData={};

	var list=serverDataDomObj;
		this.serverData={};

	for (var i=0, len=list.length; i<len; i++){
		var element=$(list[i]);
		serverData[element.attr('id')]=JSON.parse(element.text());
	}

	return serverData;
}



})
});


