/**
 * model to hold the state of the controls
 */
define(['script.config'], function(config){
	var ListControlModel = {
		attributes: {
			employeeName: '',
			branch: null,
			cashAdvanceType: null,
			date: null,
		},
		set: function(options){
			$.extend(this.attributes, options);
			$(this).trigger('change');
		},
		getAttributes: function(){
			return {
				employeeName: this.attributes.employeeName,
				branch: this.attributes.branch,
				cashAdvanceType: this.attributes.cashAdvanceType,
				date: this.attributes.date,
			};
		},
		validate: function(){
			var valid = true;
			if(!this.attributes.date){
				valid = "Please enter date.";
			}
			
			return valid;
		},
	};
	return ListControlModel;
});