steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/modal_screen.ejs', function($){

/**
 * @class Widgets.Controller.Tools.Ui.ModalScreen
 */
Widgets.Controller.Base.extend('Widgets.Controller.Tools.Ui.ModalScreen',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init: function(el, options) {
	
		this.options=options;

		this.mainViewName=this.constructor._shortName
		this.divPrefix=controllerHelper.divPrefix(this.constructor._fullName);

		this.initDataStructures(options);

		if (typeof(options.controls)!='undefined' && options.controls.version===2){
			this.initDisplay();
		}
		else{
			this.draw();
		}
	},

update:function(control, parameter){
	if (typeof(control)=='object'){$.extend(this.options, control);}
	if (typeof(parameter)=='object'){ this.options=$.extend(this.options, parameter);}
	this.init(this.element, this.options);
},

initDataStructures:function(options){
	var options=options?options:{};

	this.employerSender=options.employerSender;
	this.employerInit(); //especially, send the accessFunction to it


	this.progressMessageId=this.divPrefix+'progressMessage'
	this.progressMessage=options.progressMessage?options.progressMessage:'WORKING ...';

	this.screenId=this.divPrefix+'modalScreen';
},

draw:function(){
	var viewName, cleanUpObject, html;

	viewName=this.mainViewName;


	var cleanUpObject=controllerHelper.newCleanUpObject(this.element, this);
	html=$.View('//widgets/controller/tools/ui/modal_screen/views/modal_screen.ejs', {
		scope:this,
		cleanUpObject:cleanUpObject,
		divPrefix:this.divPrefix,

		screenId:this.screenId,
		progressMessageId:this.progressMessageId,
		progressMessage:this.progressMessage
		}
	);

	this.element.append(html);
	cleanUpObject.executeCleanupFunction();

	this.modalObj=$('#'+this.screenId);
	this.progressMessageObj=$('#'+this.progressMessageId);

	this.show();
	return true;
},

show:function(){
	var height=this.element.height();
	var width=this.element.width();
	this.modalObj.height(height);
	this.modalObj.width(width);
	this.modalObj.show();
	var msgHeight=this.progressMessageObj.height();
	var msgWidth=this.progressMessageObj.width();

	var msgTop=this.progressMessageObj.css('top');
	var msgLeft=this.progressMessageObj.css('left');

	this.modalObj.css('padding-top', (height/2)-msgHeight/2);
	this.modalObj.css('padding-left', (width/2)-msgWidth/2);
/*
	this.modalObj.css('top', msgTop);
	this.modalObj.css('left', msgLeft);
*/

	if (qtools.isNotEmpty(this.appearance)){this.applyCustomAppearance();}
	return;
},

applyCustomAppearance:function(){
	if (qtools.isNotEmpty(this.appearance.attributes)){qtools.applyAttributesFromList(this.modalObj, this.appearance.attributes);}
	if (qtools.isNotEmpty(this.appearance.styles)){qtools.applyStylesFromList(this.modalObj, this.appearance.styles);}

},

hide:function(){
	this.modalObj.hide();
},

employerInit:function(){
	this.employerSender('setAccessFunction', this.callback('employerReceiver'));
},

employerReceiver:function(control, parameter){
	if (!this.element){return;}

	switch(control){
		case 'setMessage':
			this.progressMessageObj.html(parameter);
		break;
		case 'clear':
			this.hide();
			this.employerSender('allDone');
			this.destroy();
		break;
	}

},

//VERSION TWO ===============================================

initDisplay:function(){

	var html=$.View('//widgets/controller/tools/ui/modal_screen/views/versionTwo.ejs', {
		divPrefix:this.divPrefix,

		screenId:this.screenId,
		progressMessageId:this.progressMessageId,
		progressMessage:this.options.controls.message
		}
	);

	this.element.prepend(html);
	
	
	this.modalObj=$('#'+this.screenId);

// 	this.progressMessageObj=$('#'+this.progressMessageId).children();
// 	this.progressMessageObj=$(this.progressMessageObj[0]);
	
	this.progressMessageObj=$('#'+this.progressMessageId);
	var progressChildren=this.progressMessageObj.children();
	if(progressChildren.length>0){
		this.progressMessageObj=$(progressChildren[0]);
	}
	
	var height=this.element.height();
	this.modalObj.height(height);
	
	if (this.options.controls.backgroundClickFunction){
		this.modalObj.click(this.options.controls.backgroundClickFunction);
	}
	
	switch(this.options.controls.messagePosition){
		case 'window':

			var size=Widgets.Models.Browser.windowSize(),
				windowHeight=size.height,
				windowWidth=size.width,
				windowCenterH=(windowHeight/2).toFixed(0),
				windowCenterW=(windowWidth/2).toFixed(0),
			
				panelHeight=this.progressMessageObj.css('height').replace(/px/, ''),
				panelWidth=this.progressMessageObj.css('width').replace(/px/, ''),
				panelCenterH=(panelHeight/2).toFixed(0),
				panelCenterW=(panelWidth/2).toFixed(0),
				
				toppH=windowCenterH-panelCenterH,
				toppW=windowCenterW-panelCenterW,
				
				setPanelHeight,
				setPanelWidth
				;

				if (toppW<0){toppW=0}
				if (toppH<0){toppH=0}
				
				if (windowHeight<panelHeight){
					setPanelHeight=windowHeight; 
					this.progressMessageObj.css({
						height:setPanelHeight+'px'
					});
					
					}
				else if (windowWidth<panelWidth){
					setPanelWidth=windowWidth;
					
					this.progressMessageObj.css({
						width:setPanelWidth+'px'
					});
					}
				
				
// 			var emptySpaceWidth=windowWidth-panelWidth,
// 				emptySpaceHeight=windowHeight-panelHeight,
// 				
// 				marginWidth=(emptySpaceWidth/2).toFixed(0),
// 				marginHeight=(emptySpaceHeight/2).toFixed(0);
				


			this.progressMessageObj.css({
				position:'fixed',
				top:toppH+'px',
				left:toppW+'px'
			});
	
	
			break;
	
	}
	
	if (this.options.controls.fadeParms){
		this.modalObj.fadeOut(this.options.controls.fadeParms.duration);
	}
}

})

});