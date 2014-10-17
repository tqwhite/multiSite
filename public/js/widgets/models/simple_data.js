steal('jquery/model', function(){

/**
 * @class Widgets.Models.SimpleData
 * @parent index
 * @inherits jQuery.Model
 * Wraps backend simple_data services.  
 */
Widgets.Models.Base.extend('Widgets.Models.SimpleData',
	/* @Static */
	{

		save: function(data, success, error) {

			success = success ? success : function() {
					alert('success');
				};
			error = error ? error : this.defaultError;

			// 		if (typeof(data.formParams.internalEmailAdr)=='string'){
			// 			data.formParams.internalEmailAdr=data.formParams.internalEmailAdr.replace(/\{noSpam\}/, '@');
			// 		}

			var errors = this.validate(data.formParams);
			if (errors.length > 0) {
				success({
					status: -1,
					messages: errors,
					data: {}
				});
				return;
			}


var toServer=data;

console.dir(data);
			$.ajax({
				url: '/simpledata/save',
				type: 'post',
				dataType: 'json',
				data: {
					data: toServer
				},
				success: success,
				error: error,
				fixture: false
			});

		},

		sendFiles: function(formDomObj, success, error) {


			var checkForFiles = formDomObj.find('[type="file"]');
			
			if (checkForFiles.length===0){
			this.sendFilesResult={status:0};
			return false; 
			}
			
			var a = formDomObj.find('form');
			var formName = a.attr('name');

			var formData = new FormData(document.forms.namedItem(formName));
			var self=this;


			$.ajax({
				url: '/upload/file',
				type: 'post',
				dataType: 'json',
				data: formData,
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function(inData){ 
				self.sendFilesResult=inData;
				},
				error: error,
				async: false //NOTE: ASYNC:FALSE
			});
		},

		send: function(data, success, error) {

			success = success ? success : function() {
					alert('success');
				};
			error = error ? error : this.defaultError;

			// 		if (typeof(data.formParams.internalEmailAdr)=='string'){
			// 			data.formParams.internalEmailAdr=data.formParams.internalEmailAdr.replace(/\{noSpam\}/, '@');
			// 		}

			var errors = this.validate(data.formParams);
			if (errors.length > 0) {
				success({
					status: -1,
					messages: errors,
					data: {}
				});
				return;
			}


			var toServer = data;

			$.ajax({
				url: '/email/form',
				type: 'post',
				dataType: 'json',
				data: {
					data: toServer
				},
				success: success,
				error: error,
				fixture: false
			});

		},

		contactFormHasEntry: function(inData) {

			for (var inx in inData) {
				if (inData[inx] != '' && inx != 'buttonId') {
					return true
				}
			}

			return false;

		},

		validate: function(inData) {
			var name, datum,
				errors = [];

			// 	name='internalEmailAdr';
			// 	datum=inData[name];
			// 	var emailRegexTest = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; //thanks: http://www.marketingtechblog.com/javascript-regex-emailaddress/
			// 	if (!datum || !emailRegexTest.test(datum) || datum.toLowerCase()=='required')
			// 	{errors.push([name, "Internal email address invalid or missing - permanently broken"]);}

			if (!this.contactFormHasEntry(inData)) {
				{errors.push([name, "Please enter something."]);}
			}
			return errors;
		}
	},
	/* @Prototype */
	{});

})
