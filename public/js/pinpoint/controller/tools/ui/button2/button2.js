steal( 'jquery/controller','jquery/view/ejs' )	.then( './views/button2.ejs', function($){/** * @class Pinpoint.Controller.Tools.Ui.Button2 */Pinpoint.Controller.Base.extend('Pinpoint.Controller.Tools.Ui.Button2',/** @Static */{	defaults : {}},/** @Prototype */{	init: function(el, options) {		this.baseInits();		this.mainViewName='//pinpoint/controller/tools/ui/button2/views/button2.ejs';		this.signalId=options.componentSignalId;		this.divPrefix=controllerHelper.divPrefix(this.constructor._fullName);		this.initAppearanceParameters(options, ['initial', 'ready', 'hover', 'clicked', 'unavailable']);		this.tmpRand=options.tmpRand;		this.clickDelayTime=options.clickDelayTime?options.clickDelayTime:null;		this.initializeState();		this.drawButton(options);	},initAppearanceParameters:function(options, parameterNameArray){		var scope=this;		if (!this.appearance){this.appearance={};}		$.each(parameterNameArray, function(inx, parmName){			var self, target, source;			self=scope;			target=self.appearance[parmName];			if (!target){self.appearance[parmName]={}; target=self.appearance[parmName];}			if (options[parmName]){				source=options[parmName];				target.classs=(source.classs) ? source.classs : options.ready.classs;			}			else{				target.classs=options.ready.classs;			}		});		if (options.label && options.label.toLowerCase()=='nolabel'){			this.appearance.label=this.element.html();		}		else{			this.appearance.label=(options.label) ? options.label : '&nbsp;';		}		this.appearance.accessFunction=options.accessFunction;		this.appearance.title=options.title;		this.appearance.accessFunction('setAccessFunction', this.callback('accessFunction'));		this.appearance.initialControl=(options.initialControl)? options.initialControl:'setToReady';},drawButton:function(targetPanel, args){	var cleanUpObject, html;	cleanUpObject=controllerHelper.newCleanUpObject(this.element, this);	html=$.View(this.mainViewName, {		scope:this,		appearance:this.appearance,		cleanUpObject:cleanUpObject,		divPrefix:this.divPrefix		}	);	this.element.html(html);	cleanUpObject.executeCleanupFunction();	return true;},initializeState:function(args){	this.initialImgClass=this.appearance.initial.classs;	this.readyImgClass=this.appearance.ready.classs;	this.state1ImgClass=this.appearance.clicked.classs;	this.rolloverImgClass=this.appearance.hover.classs;	this.unvailableImgClass=this.appearance.unavailable.classs;	this.incumbentImgClass=this.initialImgClass;	this.showAfterHover=this.initialImgClass;	this.emButtonNextState='1';	this.firstPass=true;	this.accessFunction(this.appearance.initialControl);	this.firstPass=false;},dblclick:function(thisDomObj, thisEventObj){	this.click(thisDomObj, thisEventObj);},click:function(thisDomObj, thisEventObj){	if (!this.isAcceptingClicks()){		return;	}	if (!this.alive){return;}	if (this.clickDelayTime!='none'){this.turnOffClicksForAwhile(this.clickDelayTime);}	switch (this.emButtonNextState){		default:		case '1': //set selected			thisDomObj.removeClass(this.incumbentImgClass);			thisDomObj.addClass(this.state1ImgClass);			this.incumbentImgClass=this.state1ImgClass;			this.showAfterHover=this.state1ImgClass; //so emMouseOff() knows what to show			this.emButtonNextState='2';			break;		case '2': //set not selected			thisDomObj.removeClass(this.incumbentImgClass);			thisDomObj.addClass(this.readyImgClass);			this.incumbentImgClass=this.readyImgClass;			this.showAfterHover=this.readyImgClass; //so emMouseOff() knows what to show			this.emButtonNextState='1';			break;	}	this.appearance.accessFunction('click', {thisDomObj:thisDomObj, thisEventObj:thisEventObj, buttonAccessFunction:this.callback('accessFunction')});},mouseover:function(thisDomObj, thisEventObj){	if (!this.alive){return;}	thisDomObj.removeClass(this.incumbentImgClass);	thisDomObj.addClass(this.rolloverImgClass);	this.incumbentImgClass=this.rolloverImgClass;	this.appearance.accessFunction('mouseover');},mouseout:function(thisDomObj, thisEventObj){	if (!this.alive){return;}	var incumbentImgClass, showAfterHover;	thisDomObj.removeClass(this.incumbentImgClass);	thisDomObj.addClass(this.showAfterHover);	this.incumbentImgClass=this.showAfterHover;	this.appearance.accessFunction('mouseout');},changeClass:function(stateName, className){	this[stateName+'ImgClass']=className;},accessFunction:function(control, parms){	switch(control){	/* The comment buttons require the case of 'setToReady' to be run each time they are used.  This is because the class of the indicator is changed each time the comment box is opened */	case 'setFirstPass':	//This is a special case, by calling this case the 'setToReady' case will be allowed to run instead of returning right away		this.firstPass=parms.firstPass;		break;	case 'changeClass':		this.changeClass(parms.stateName, parms.className);		break;	case 'setToReady':			if (this.alive && !this.firstPass){ return;} //don't want to engage the whole rigamarole if it's not a change	case 'forceReady':			$(this.element).removeClass(this.incumbentImgClass);			$(this.element).addClass(this.readyImgClass);			this.incumbentImgClass=this.readyImgClass;			this.showAfterHover=this.readyImgClass; //so emMouseOff() knows what to show			this.emButtonNextState='1';			this.alive=true;			this.accessFunction('show');	break;	case 'setUnavailable':			if (!this.alive && !this.firstPass){ return;} //don't want to engage the whole rigamarole if it's not a change			$(this.element).removeClass(this.incumbentImgClass);			$(this.element).addClass(this.unvailableImgClass);			this.incumbentImgClass=this.unvailableImgClass;			this.showAfterHover=this.unvailableImgClass; //so emMouseOff() knows what to show			this.emButtonNextState='1';			this.alive=false;	break;	case 'hide':		$(this.element).hide();	break;	case 'show':		$(this.element).show();	break;	case 'setLabel':		this.element.text(parms);		break;	}}})});