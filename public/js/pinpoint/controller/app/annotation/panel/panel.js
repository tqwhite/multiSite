steal( 'jquery/controller','jquery/view/ejs' )
	.then( './views/init.ejs', function($){

/**
 * @class Pinpoint.Controller.App.Annotation.Panel
 */
Pinpoint.Controller.Base.extend('Pinpoint.Controller.App.Annotation.Panel',
/** @Static */
{
	defaults : {}
},
/** @Prototype */
{
	init : function(){
		this.element.html("//pinpoint/controller/app/annotation/panel/views/init.ejs",{
			message: "<div id='demoSpace' style='color:red;font-size:24pt;width:900px;height:600px;background:url(http://"+GLOBALS.serverDomain+"/media/pushPinAtomBiggerPin.png);'><img id='hello' src='http://"+GLOBALS.serverDomain+"/media/accessButton.png'><p/><div style='background:white;width:200px;'>You can drag the button.</div></div>"
		});

		$('#hello').draggable({drag:this.callback('tmpDrag')});
		$('#hello').click(this.callback('tmpClick'));
	},

	tmpDrag:function(){

		$('#demoSpace').append('<span style="color:#bbbbbb;font-size:10pt;">dragging </span>');
	},

	tmpClick:function(){
		$('#demoSpace').append('click<br/>');
	}
})

});