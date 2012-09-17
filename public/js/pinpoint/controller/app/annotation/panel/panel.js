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
			message: "<img id='hello' src='http://"+GLOBALS.serverDomain+"/media/accessButton.png'><p/>You can drag this."
		});

		$('#hello').draggable({drag:this.callback('tmpDrag')});
		$('#hello').click(this.callback('tmpClick'));
	},

	tmpDrag:function(){

		this.element.append('dragging ');
	},

	tmpClick:function(){
		this.element.append('click<br/>');
	}
})

});