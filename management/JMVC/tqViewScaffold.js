<%
qtools.validateProperties({
	targetObject:this,
	propList:[
		{name:'displayParameters'},
		{name:'viewHelper'},
		{name:'formData'}
	],
 source:this.formData.source+"_views_init.js" });

qtools.validateProperties({
	targetObject:this.displayParameters,
	propList:[
		{name:'saveButton'}
	],
 source:this.formData.source+"_views_init.js" });

qtools.validateProperties({
	targetObject:this.formData,
	propList:[
		{name:'message'}
	],
 source:this.formData.source+"_views_init.js" });

%>
<%== this.message %><br/>
This button is for skeleton purposes only.<br/>
<div class='smallButton' style='float:right;width:40px;' id='<%=this.displayParameters.saveButton.divId%>'></div>