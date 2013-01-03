window.pinpoint={
	init : function(){
		$('body').prepend("<div id='demoSpace' style='position:absolute;top:0px;right:0px;'><img id='hello' style='z-index:1000;' src='http://"+window.serverDomain+"/media/accessButton.png'><p/><div style='background:white;width:200px;'></div>");

		$('#hello').draggable({drag:window.pinpoint.tmpDrag});
		$('#hello').click(window.pinpoint.tmpClick);
	},

	tmpDrag:function(){

//		$('#demoSpace').append('<span style="color:#bbbbbb;font-size:10pt;">dragging </span>');
	},

	tmpClick:function(){
		var top=$('#hello').css('top').replace('px', '')*1+55,
			right=$('#hello').css('right').replace('px', '')*1+50;

		$('#demoSpace')
			.append("<div class='simpleInputPanel' style='background:white;position:absolute;top:"+top+"px;right:"+right+"px;height:1px;width:1px;border:3pt solid green;padding:20px;'></div>")
		$('.simpleInputPanel').animate(
			{
				width:'+=200',
				height:'+=100'
			},
			1000,
			function(){
				$('.simpleInputPanel').html("<textarea style='height:95px;width:205px;'></textarea><br/>submit");
			}
			);

	}
}