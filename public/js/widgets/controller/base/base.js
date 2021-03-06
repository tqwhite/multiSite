steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Widgets.Controller.Base
 */
$.Controller('Widgets.Controller.Base',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{

baseInits: function(params) {
	this.options=params;
	this.divPrefix=qtools.divPrefix(this.constructor._fullName);
	this.acceptClicks=true;
},

assembleComponentDivIdObject:function(nameArray){
	var componentDivIds={}
		divPrefix=this.divPrefix;
	for (var i=0, len=nameArray.length; i<len; i++){
		if (typeof(nameArray[i])=='string'){ //the use of the simple string is DEPRECATED, 11/8/11
			componentDivIds[nameArray[i]]=divPrefix+nameArray[i];
		}
		else{
			componentDivIds[nameArray[i].name]={
				name:nameArray[i].name,
				divId:divPrefix+nameArray[i].name,
				handler:this.callback(nameArray[i].handlerName),
				targetDivId:divPrefix+nameArray[i].targetDivId
			};

			if (nameArray[i].handlerName){
				componentDivIds[nameArray[i].name].handler=this.callback(nameArray[i].handlerName);
			}

			if (nameArray[i].targetDivId){
				componentDivIds[nameArray[i].name].targetDivId=divPrefix+nameArray[i].targetDivId;
			}
		}
	}
	return componentDivIds;
},
// CLICK MANAGEMENT ========================================================================

turnOffClicksForAwhile: function(milliseconds, callbacks) {
			
// 		if (this.isAcceptingClicks()){this.turnOffClicksForAwhile();} //turn off clicks for awhile and continue, default is 500ms
// 		else{return;}
			
	milliseconds=milliseconds?milliseconds:2000;
	callbacks=callbacks?callbacks:{};

	this.setNotAcceptClicks();

    var setAcceptClicks = function(scope, args) {
        scope.setAcceptClicks();
    }
	if (typeof(callbacks.beforeFunction)=='function'){ callbacks.beforeFunction(callbacks.beforeArgs);}

	var setAcceptClicks=this.callback('setAcceptClicks');
	var postFunc=(typeof(callbacks.afterFunction)=='function'?callbacks.afterFunction:function(){return;});
	var afterArgs=callbacks.postArgs;
	var timeFunction=function(){
		setAcceptClicks();
		postFunc();
	};

    qtools.timeoutProxy(timeFunction, milliseconds);

},

setNotAcceptClicks:function(){
	this.acceptClicks=false;
},

setAcceptClicks:function(){
	this.acceptClicks=true;
},

isAcceptingClicks:function(){
	return this.acceptClicks;
},

listMessages:function(messageArray, separator, itemIndex){
	separator=separator?separator:'<br/>';
	itemIndex=itemIndex?itemIndex:1;
	var outString='';

	if (messageArray){
		for (var i=0, len=messageArray.length; i<len; i++){
			outString+=messageArray[i][itemIndex]+separator;
		}
	}

	return outString;
},

setupEnterKey:function(handler){
	$('input', this.element).addClass('mousetrap');
	Mousetrap.bind("enter", handler);
},

startProgressIndicator:function(args){
		if (typeof(args)!='object'){args={};}
		domObj=args.domObj?args.domObj:this.element;
		styleString=args.styleString?"style='"+args.styleString+"'":'';
		classString=args.classString?"class='"+args.classString+"'":'';
		divPrefix=this.divPrefix?this.divPrefix:'';
		divId=args.divId?args.divId:divPrefix+'_progressIndicator';

		if (!styleString && !classString){
			styleString="style='margin-left:100px;margin-top:100px;'";
		}

		domObj.html("<div "+classString+" "+styleString+" id='"+divId+"'></div>");

	var opts={
	  lines: 7, // The number of lines to draw
	  length: 20, // The length of each line
	  width: 4, // The line thickness
	  radius: 10, // The radius of the inner circle
	  color: '#436235', // #rbg or #rrggbb
	  speed: 1, // Rounds per second
	  trail: 60, // Afterglow percentage
	  shadow: true // Whether to render a shadow
	};


	var spinner = new Spinner(opts).spin();
	$('#'+divId).append(spinner.el);
},

modalReceiver:function(control, parameter){
	var componentName='modalSender';
	switch(control){
		case 'setAccessFunction':
			if (!this[componentName]){this[componentName]={};}
			this[componentName].accessFunction=parameter; //eg, this.hScroll.accessFunction()
		break;
	}
},

assertModalScreen:function(targetObject, message){
	
	var controls={};
	
	if (typeof(message)=='object'){
		controls=message;
		message=(typeof(controls.message)=='string')?controls.message:'';
	}

	targetObject.widgets_tools_ui_modal_screen({
		employerSender:this.callback('modalReceiver'),
		controls:controls
	});

	this.modalSender.accessFunction('setMessage', message);
},

clearModalScreen:function(){
	if (typeof(this.modalSender)=='undefined' || typeof(this.modalSender.accessFunction)=='undefined'){return;} //during debugging, I don't always turn on the modal screen
	this.modalSender.accessFunction('clear');

}

})

});